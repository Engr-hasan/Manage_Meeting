<div class="row">
    <div class="full col-sm-12">
        <div class="hidden">
            {!! Session::has('hiddenMsg') ? Session::get("hiddenMsg") : '' !!}
        </div>

        <div class="panel panel-primary">

            <!-- .panel-heading -->
            <div class="panel-heading">
                <div class="row text-center">
                    <b>Profile of Guide</b>
                </div>
            </div>

            {{--NID source modal--}}
            <div class="modal fade" id="nidinfoModal" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">
                                NID Source Information
                            </h4>
                        </div>
                        <div class="modal-body">
                            <div id="nid_pilgrim_info">

                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="clearfix">&nbsp;</div>
                        </div>
                        <div class="modal-footer">
                            &nbsp;
                        </div>
                    </div>
                </div>
            </div>
            {{--NID source modal end--}}

            <div class="panel-body">
                    <span class="full col-sm-12">

                        {{--left sided info--}}
                        <div class="col-sm-3">
                            <div class="panel panel-info">
                                <br/>
                                <div class="col-md-12">
                                    <div class="panel-thumbnail">
                                        <?php
                                        $profile_pic = CommonFunction::getPicture('guide',$json_object->guide_info->id);
                                        ?>
                                        <img class="img-responsive profile-user-img"
                                             src="{{ ($profile_pic =='' || $profile_pic==null)?url('assets/images/default_profile.jpg'):$profile_pic }}">
                                    </div>
                                </div>
                                <div class="panel-body  text-center">
                                    <p class="lead">{!!$json_object->guide_info->full_name_english!!}</p>
                                    <span>
                                        <a href="javascript:void(0);"
                                           url="{!! url('pilgrim-profile/view-nid-source') !!}"
                                           nid="{!! Encryption::encode($json_object->guide_info->national_id) !!}"
                                           birthdate="{!! Encryption::encode($json_object->guide_info->birth_date) !!}"
                                           class="btn btn-xs btn-default nidinfo">
                                                <i class="fa fa-eye"></i>
                                                View NID
                                            </a>
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{--Right sided info--}}
                        <div class="col-sm-9">
                            <div class="col-sm-12">
                                <div id="accordion" class="panel-group">

                                    {{--Basic information--}}
                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a href="#collapseOne" data-toggle="collapse" aria-expanded="true"
                                                   class="">Basic Information</a>
                                            </h4>
                                        </div>
                                        <div class="panel-collapse collapse in" id="collapseOne" aria-expanded="true"
                                             style="">
                                            <div class="panel-body">
                                                <div class="col-md-12">
                                                    <div class="col-md-6">
                                                        <strong> Name in Bengali:</strong>
                                                        {!!$json_object->guide_info->full_name_bangla!!}
                                                        <br/>
                                                        <strong> Father's Name:</strong>
                                                        {!!$json_object->guide_info->father_name!!}
                                                        <br/>
                                                        <strong>Mother Name:</strong>
                                                        {!!$json_object->guide_info->mother_name!!}
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Date of Birth:</strong>
                                                        @if(isset($json_object->guide_info->birth_date))
                                                            {!!CommonFunction::changeDateFormat($json_object->guide_info->birth_date)!!}
                                                        @endif
                                                        <br/>

                                                        <strong>Gender:</strong>
                                                        {!! ucfirst($json_object->guide_info->gender) !!}
                                                        <br/>
                                                        <strong>Management:</strong>
                                                        {!! ucfirst($json_object->guide_info->is_govt) !!}
                                                        <br/>



                                                    </div>

                                                </div>

                                                <div class="col-md-12"><br/></div>

                                                {{--contact information--}}
                                                <div class="col-md-6">
                                                    <label>Contact Information</label>

                                                    <div class="col-md-10">
                                                        <strong>Mobile:</strong>
                                                        {!!$json_object->guide_info->mobile!!}

                                                        {{--SB Report--}}
                                                        <br/><br/>
                                                    </div>

                                                </div>

                                                    <div class="col-md-6">
                                                        @if($json_object->guide_info->is_registrable==1)
                                                            <br/>
                                                            {{--passport info--}}
                                                            <strong>Passport No:</strong>
                                                            {!!$json_object->guide_info->passport_no!!}
                                                            <br/>
                                                            <strong>Passport Type:</strong>
                                                            {!! ucfirst($json_object->guide_info->pass_type) !!}
                                                            <br/>

                                                            @if($json_object->guide_info->pass_exp_date)
                                                                <strong>Passport Expire Date:</strong>
                                                                @if(isset($json_object->guide_info->pass_exp_date))
                                                                    {!!CommonFunction::changeDateFormat($json_object->guide_info->pass_exp_date)!!}
                                                                @endif
                                                            @endif
                                                        @endif
                                                        @if($json_object->guide_info->nationality2)
                                                            <br>
                                                            <strong>Second Nationality:</strong>
                                                            {!!$json_object->guide_info->nationality2!!}
                                                        @endif
                                                    </div>

                                                <div class="col-md-12">
                                                    <label>Address</label>
                                                </div>

                                                {{--Address--}}
                                                <div class="col-md-12">
                                                    <div class="col-md-12">
                                                        Contact Information
                                                    </div>
                                                    <div class="col-md-12">
                                                        <strong>Present Address:</strong>
                                                        {!!$json_object->guide_info->village_ward!!}, {!! $json_object->guide_info->police_station!!},
                                                        {!! $json_object->guide_info->district !!}-{!! $json_object->guide_info->post_code !!}
                                                    </div>

                                                    <div class="col-md-12">
                                                        <strong>Permanent Address:</strong>
                                                        {!!$json_object->guide_info->per_village_ward!!}
                                                        , {!! $json_object->guide_info->per_police_station!!},
                                                        {!! $json_object->guide_info->per_district !!}-{!! $json_object->guide_info->per_post_code !!}
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </span>

                <span class="col-sm-12">
                        <br/>
                        <div class="panel panel-success">

                            <!-- .panel-heading -->
                            <div class="panel-heading">
                                <div class="col-sm-4"><b>Voucher's List</b></div>
                                    &nbsp;
                                </div>

                            <div class="panel-body">
                                <div class="table-responsive">
                            <?php $sl = 0; ?>
                                    @if(count($json_object->guides_voucher)==0)
                                        <div class="text-center text-danger"> <h4>No Voucher found.</h4></div>
                                    @else
                                        <table class="table table-striped table-bordered dt-responsive nowrap"
                                               cellspacing="0" width="100%">


                                    <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th>Voucher Name</th>
                                        <th>Tracking No</th>
                                        <th>No of Pilgrims</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($json_object->guides_voucher as $voucher)
                                        <tr>
                                            <td>{!! ++$sl !!}</td>
                                            <td>{!! $voucher->voucher_name !!}</td>
                                            <td>{!! $voucher->voucher_tracking_no !!}</td>
                                            <td>{!! $voucher->no_of_pilgrims !!}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                    @endif
                        </div><!-- /.table-responsive -->
                            </div>
                        </div>


                    </span>
                <!-- .panel-body -->
            </div>
        </div>
    </div><!-- /.panel -->

</div><!-- /.full col-sm-12 -->