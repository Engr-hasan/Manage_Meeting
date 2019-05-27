
{{-- For Pay order submit and resubmit--}}
{{--
    15 == Proceed for payment
    18 == Challan Declined
--}}
@if($viewMode == "on"  && Auth::user()->user_sub_type == $appInfo->company_id && in_array(Auth::user()->user_type,['5x505']) && in_array($appInfo->status_id, [15, 18]))

    {!! Form::open(array('url' => 'space-allocation/challan-store/'.Encryption::encodeId($appInfo->id),'method' => 'post',
                                'id' => 'challanForm','files' => true, 'role'=>'form')) !!}
    <div class="panel panel-primary">
        <div class="panel-heading"><strong>Pay order related information</strong></div>
        <div class="panel-body">
            <div class="col-md-12">
                <div class="col-md-6">

                    <div class="form-group col-md-12 {{$errors->has('payorder_no') ? 'has-error' : ''}}">
                        {!! Form::label('Pay Order No :','Pay Order No : ',['class'=>'col-md-5 font-ok required-star']) !!}
                        <div class="col-md-7">
                            {!! Form::text('payorder_no', (isset($appInfo->po_no) ? $appInfo->po_no : $challanReg->value),['class'=>'form-control bnEng required input-sm',
                            'placeholder'=>'e.g. 1103', 'data-rule-maxlength'=>'40']) !!}
                            {!! $errors->first('payorder_no','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group col-md-12 {{$errors->has('bank_name') ? 'has-error' : ''}}">
                        {!! Form::label('bank_name','Bank Name :',['class'=>'col-md-5 font-ok required-star']) !!}
                        <div class="col-md-7">
                            {!! Form::select('bank_name', $banks, (isset($appInfo->po_bank_id) ? $appInfo->po_bank_id : ''), ['class' => 'form-control input-sm required',
                            'id'=>'bank_name_form']) !!}
                            {!! $errors->first('bank_name','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group col-md-12 {{$errors->has('amount') ? 'has-error' : ''}}">
                        {!! Form::label('amount','Amount :',['class'=>'col-md-5 font-ok required-star']) !!}
                        <div class="col-md-7">

                            {!! Form::text('amount', (isset($appInfo->po_ammount) && $appInfo->po_ammount != 0 ? $appInfo->po_ammount : $challanReg->value2), ['class'=>'form-control bnEng required input-sm','placeholder'=>'e.g. 5000',
                            'data-rule-maxlength'=>'40']) !!}
                            {!! $errors->first('amount','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>


                </div>
                <div class="col-md-6">

                    <div class="form-group col-md-12 {{$errors->has('date') ? 'has-error' : ''}}">
                        {!! Form::label('payorder_date','Date :',['class'=>'col-md-5 font-ok required-star']) !!}



                        <div class=" col-md-7">
                            <div class="datepicker input-group date" data-date-format="yyyy-mm-dd">
                                {!! Form::text('payorder_date', (isset($appInfo->po_date) ? date('d-M-Y',strtotime($appInfo->po_date)) : date('d-M-Y', strtotime(\Carbon\Carbon::now()))), ['class'=>'form-control input-sm required datepicker', 'id' => 'datepicker', 'placeholder'=>'Pick from datepicker']) !!}
                                <label for="datepicker" class="input-group-addon input-sm datepicker">
                                    <span class="glyphicon glyphicon-calendar user_DOB" id="datepicker"></span></label>
                            </div>
                        </div>
                        {!! $errors->first('payorder_date','<span class="help-block">:message</span>') !!}


                    </div>
                    <div class="form-group col-md-12 {{$errors->has('branch') ? 'has-error' : ''}}">
                        {!! Form::label('branch','Branch Name :',['class'=>'col-md-5 font-ok required-star']) !!}
                        <div class="col-md-7">
                            {!! Form::text('branch',(isset($appInfo->po_bank_branch_id) ? $appInfo->po_bank_branch_id : ''), ['class'=>'form-control required input-sm','placeholder'=>'Branch Name',
                            'data-rule-maxlength'=>'40']) !!}
                            {!! $errors->first('branch','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group col-md-12 {{$errors->has('payorder_file') ? 'has-error' : ''}}">
                        {!! Form::label('payorder_file','Pay order copy :',['class'=>'col-md-5 font-ok required-star']) !!}
                        <div class="col-md-7">
                            <input type="file" name="payorder_file" class="required"/>
                            {{--{!! Form::file('payorder_file','', ['class'=>'required input-sm required', 'required']) !!}--}}
                            {!! $errors->first('payorder_file','<span class="help-block">:message</span>') !!}
                            <span class="text-danger" style="font-size: 9px; font-weight: bold">[File Format: *.pdf | Maximum File size 3MB]</span>

                            <div class="save_file" style="margin-top: 5px">
                                <?php if(!empty($appInfo->po_file)){ ?>
                                <a target="_blank" class="btn btn-xs btn-primary" title=""
                                   href="{{URL::to($appInfo->po_file)}}"><i
                                            class="fa fa-file-pdf-o"></i> Open Challan File
                                </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    {{--@if(ACL::getAccsessRight('spaceAllocation','E'))--}}
                    <button type="submit" class="btn btn-primary pull-right next">
                        <i class="fa fa-chevron-circle-right"></i> Save
                    </button>
                    {{--@endif--}}
                </div>
            </div>
        </div>
    </div> <!--End of Panel Group-->
    {!! Form::close() !!}<!-- /.form end -->

@endif



{{-- For Pay order view--}}
@if($viewMode == "on" && isset($appInfo) && isset($appInfo->po_no) && in_array($appInfo->status_id, [16,17,25]))

    <div class="panel panel-primary" id="challanFormView">
        <div class="panel-heading"><strong>Pay order related information</strong></div>
        <div class="panel-body">
            <div class="col-md-12">
                <div class="col-md-6">

                    <div class="form-group col-md-12 {{$errors->has('payorder_no') ? 'has-error' : ''}}">
                        {!! Form::label('Pay Order No :','Pay Order No : ',['class'=>'col-md-5 font-ok required-star']) !!}
                        <div class="col-md-7">
                            {!! Form::text('payorder_no', $appInfo->po_no,['class'=>'form-control bnEng required input-sm',
                            'placeholder'=>'e.g. 1103', 'data-rule-maxlength'=>'40']) !!}
                            {!! $errors->first('payorder_no','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group col-md-12 {{$errors->has('bank_name') ? 'has-error' : ''}}">
                        {!! Form::label('bank_name','Bank Name :',['class'=>'col-md-5 font-ok required-star']) !!}
                        <div class="col-md-7">
                            {!! Form::select('bank_name', $banks, $appInfo->po_bank_id, ['class' => 'form-control input-sm required',
                            'id'=>'bank_name_form']) !!}
                            {!! $errors->first('bank_name','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group col-md-12 {{$errors->has('amount') ? 'has-error' : ''}}">
                        {!! Form::label('amount','Amount :',['class'=>'col-md-5 font-ok required-star']) !!}
                        <div class="col-md-7">
                            {!! Form::text('amount',$appInfo->po_ammount, ['class'=>'form-control bnEng required input-sm','placeholder'=>'e.g. 5000',
                            'data-rule-maxlength'=>'40']) !!}
                            {!! $errors->first('amount','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>


                </div>
                <div class="col-md-6">
                    {{ $appInfo->po_date }}
                    <div class="form-group col-md-12 {{$errors->has('date') ? 'has-error' : ''}}">
                        {!! Form::label('payorder_date','Date :',['class'=>'col-md-5 font-ok required-star']) !!}
                        <div class="col-md-7">
                            <div class="datepicker input-group date" data-date-format="dd-mm-yyyy">
                                <?php $po_date = ($appInfo->po_date == '0000-00-00' ? '' : date('d-M-Y', strtotime($appInfo->po_date))) ?>
                                {!! Form::text('payorder_date', $po_date, ['class'=>'form-control input-sm required datepicker', 'id' => 'datepicker', 'placeholder'=>'Pick from datepicker']) !!}
                                <label for="datepicker" class="input-group-addon input-sm datepicker">
                                    <span class="glyphicon glyphicon-calendar datepicker" id="datepicker"></span></label>
                            </div>
                        </div>
                        {!! $errors->first('payorder_date','<span class="help-block">:message</span>') !!}
                    </div>
                    <div class="form-group col-md-12 {{$errors->has('branch') ? 'has-error' : ''}}">
                        {!! Form::label('branch','Branch Name :',['class'=>'col-md-5 font-ok required-star']) !!}
                        <div class="col-md-7">
                            {!! Form::text('branch',$appInfo->po_bank_branch_id, ['class'=>'form-control required input-sm','placeholder'=>'Branch Name',
                            'data-rule-maxlength'=>'40']) !!}
                            {!! $errors->first('branch','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group col-md-12 {{$errors->has('payorder_file') ? 'has-error' : ''}}">
                        {!! Form::label('payorder_file','Pay order copy :',['class'=>'col-md-5 font-ok required-star']) !!}
                        <div class="col-md-7">
                            <a href="{{url($appInfo->po_file)}}" target="_blank"
                               class="btn show-in-view btn-xs btn-success" title="Download Space Allocation Challan">
                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>  Space Allocation Challan</a>
                        </div>
                    </div>
                </div>
                <div class="form-group col-lg-12">
                    <a href="http://www.cga.gov.bd/index.php?option=com_wrapper&Itemid=497" target="_blank" class="btn btn-xs btn-info show-in-view">
                        Challan Verification Link </a>
                </div>
            </div>
        </div>
    </div> <!--End of Panel Group-->
@endif