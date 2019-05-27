<style>.err_msg_hide{display:none;}</style>
<div class="col-md-12">
    <div class="panel panel-primary" style="border:0px;">
        <div class="panel-heading text-left text-info">
            Trainee Registration Form {!! $training_info->title !!}
        </div>
        <div class="panel-body" style="background: azure;">

            <div class="col-md-10 col-md-offset-1" id="public-training">
                {!! Form::open(['url'=>'apply','id'=>'training-form','class'=>'form-horizontal', 'role'=>'form'])!!}
                <input type="hidden" name="schedule_id" value="{{ \App\Libraries\Encryption::encodeId($schedule_id) }}" />
                <div class="row">

                    <div class="form-group">


                        {!! Form::label('User Type','User Type',['class'=>'col-md-4 control-label text-left']) !!}
                        <div class="col-md-8">
                            <div class="clearfix" style="margin-bottom:6px;"></div>
                            {!! $training_info->public_user_types !!}
                        </div>
                        <div class="clearfix"></div>



                        {!! Form::label('Location','Location',['class'=>'col-md-4 control-label text-left']) !!}
                        <div class="col-md-8">
                            <div class="clearfix" style="margin-bottom:6px;"></div>
                            {!! $training_info->location !!}
                        </div>
                        <div class="clearfix"></div>


                        {!! Form::label('Date & Time','Date & Time',['class'=>'col-md-4 control-label text-left']) !!}
                        <div class="col-md-8">
                            <div class="clearfix" style="margin-bottom:6px;"></div>
                            {!! date('jS M Y', strtotime($training_info->start_time)) !!}, <strong>{!! date('h:i a', strtotime($training_info->start_time)) !!} - {!! date('h:i a', strtotime($training_info->end_time)) !!}</strong>
                        </div>
                        <div class="clearfix"></div>

                        {!! Form::label('Seat Capacity','Seat Capacity',['class'=>'col-md-4 control-label text-left']) !!}
                        <div class="col-md-8">
                            <div class="clearfix" style="margin-bottom:6px;"></div>
                            {!! $training_info->total_seats !!}
                        </div>
                        <div class="clearfix"></div>


                        {!! Form::label('Number of applicant','Number of applicant',['class'=>'col-md-4 control-label text-left']) !!}
                        <div class="col-md-8">
                            <div class="clearfix" style="margin-bottom:6px;"></div>
                            {!! $info['total_applied'] !!}
                        </div>
                        <div class="clearfix"></div>

                        {!! Form::label('Number of verified','Number of verified',['class'=>'col-md-4 control-label text-left']) !!}
                        <div class="col-md-8">
                            <div class="clearfix" style="margin-bottom:6px;"></div>
                            {!! $info['total_verified'] !!}
                        </div>
                        <div class="clearfix"></div>

                    </div>

                    <div class="alert alert-danger alert-dismissible err_msg_hide"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><span id="err_msg"></span></div>

                    <div class="form-group">
                        &nbsp;
                    </div>


                    <div class="form-group">
                        {!! Form::label('name','Name',['class'=>'col-md-4 control-label required-star text-left']) !!}
                        <div class="col-md-8">
                            {!! Form::text('name',null,['class'=>'form-control bnEng required textOnly','placeholder'=>'Name', 'data-rule-maxlength'=>'20', 'required'=>'' ]) !!}
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('email','Email',['class'=>'col-md-4 control-label required-star text-left']) !!}
                        <div class="col-md-8">
                            {!! Form::text('email',null,['class'=>'form-control bnEng required','placeholder'=>'Email Address', 'data-rule-maxlength'=>'20', 'required'=>'', 'type'=>'email']) !!}
                            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('phone','Mobile No',['class'=>'col-md-4 control-label required-star text-left']) !!}
                        <div class="col-md-8">
                            {!! Form::text('phone',null,['class'=>'form-control required number','placeholder'=>'Phone No', 'data-rule-maxlength'=>'20', 'required'=>'']) !!}
                            <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('district','District (বর্তমান কর্মস্থল)',['class'=>'col-md-4 control-label required-star text-left', 'style' => 'font-size:12px']) !!}
                        <div class="col-md-8">
                            {!! Form::select('district', $districts, '', ['class'=>'form-control required', 'placeholder'=>'Select one', 'id'=>"district"]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('trainee_nid','NID No',['class'=>'col-md-4 control-label required-star text-left']) !!}
                        <div class="col-md-8">
                            {!! Form::text('trainee_nid',null,['class'=>'form-control bnEng required','placeholder'=>'10, 13 or 17 digits NID number', 'data-rule-maxlength'=>'20', 'required'=>'']) !!}
                            <span class="glyphicon glyphicon-flag form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('dob','Date of Birth',['class'=>'col-md-4 control-label required-star text-left']) !!}
                        <div class="col-md-8">
                            <div class="datepicker input-group date col-md-12">
                                {!! Form::text('dob', '', ['class'=>'form-control datepicker required']) !!}
                                <span class="input-group-addon">
                                        <span class="fa fa-calendar"></span>
                                    </span>
                            </div>
                        </div>
                    </div>
                    @if(array_intersect(\App\Libraries\CommonFunction::bankUser(), $user_type))
                    <div class="form-group">
                        {!! Form::label('bank','Bank : ',['class'=>'col-md-4 control-label required-star text-left']) !!}
                        <div class="col-md-8">
                            <!-- {!! Form::text('bank',null,['class'=>'form-control bnEng required','placeholder'=>'Bank Name', 'data-rule-maxlength'=>'20', 'id' => 'bank', 'required'=>'']) !!} -->
                            {!! Form::select('bank', $bank, '', ['class'=>'form-control required', 'placeholder'=>' Select One ', 'id'=>"bank"]) !!}
                        </div>
                    </div>
                    @endif
                    @if(array_intersect(\App\Libraries\CommonFunction::agencyUser(), $user_type))
                    <div class="form-group">
                        {!! Form::label('agency_license','Licence: ',['class'=>'col-md-4 control-label required-star text-left']) !!}
                        <div class="col-md-8">
                            {!! Form::text('agency_license',null,['class'=>'form-control bnEng required', 'id' => 'agency', 'placeholder'=>'4 digit Licence No', 'data-rule-maxlength'=>'20', 'required'=>'']) !!}
                        </div>
                    </div>
                    @endif
                    <div class="form-group pull-right  {{$errors->has('g-recaptcha-response') ? 'has-error' : ''}}">
                        <div class="col-md-12">
                            {!! Recaptcha::render() !!}
                            {!! $errors->first('g-recaptcha-response','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class='clearfix'></div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <div class='pull-left col-md-6'>
                                <button type="reset" class="btn btn-default btn-sm form-control">Reset</button>
                            </div>
                            <div class="col-md-6"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-6"></div>
                            <div class='pull-right col-md-6' style="padding-right: 0px;">
                                <button type="button" class="btn btn-primary btn-sm form-control trainingFormSubmit">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>