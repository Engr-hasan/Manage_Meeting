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
                        <div class="col-sm-12">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    Agency Information
                                </div>
                                <div class="panel-body">
                                    <div class="col-md-6">
                                        <h5>Pre-Registration Agency</h5>
                                        {{$json_object->Transfer_Request->agency_name_from}}
                                        ({{$json_object->Transfer_Request->agency_license_from}})
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Transfer Agency To</h5>
                                        {{$json_object->Transfer_Request->agency_name_to}}
                                        ({{$json_object->Transfer_Request->agency_license_to}})

                                    </div>

                                </div>
                                <div class="panel-footer">
                                    <code>
                                        Submitted by:
                                        {{$json_object->Transfer_Request->bank_name}},
                                        {{$json_object->Transfer_Request->bank_location}},
                                        Phone: {{$json_object->Transfer_Request->bank_phone}}
                                        at
                                        {!! $json_object->Transfer_Request->job_submit_date !!}
                                    </code>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="panel panel-info">
                                <br/>
                                <div class="col-md-12">



                                </div>
                                <div class="panel-body  text-center">
                                    <p class="lead">{!!$json_object->Transfer_Request->full_name_english!!}</p>
                                    <p class="lead">{!!$json_object->Transfer_Request->tracking_no!!}</p>
                                    <span>
                                        <a href="javascript:void(0);"
                                           url="{!! url('pilgrim-profile/view-nid-source') !!}"
                                           nid="{!! Encryption::encode($json_object->Transfer_Request->national_id) !!}"
                                           birthdate="{!! Encryption::encode($json_object->Transfer_Request->birth_date) !!}"
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
                                                        {!!$json_object->Transfer_Request->full_name_bangla!!}
                                                        <br/>
                                                        <strong> Father's Name:</strong>
                                                        {!!$json_object->Transfer_Request->father_name!!}
                                                        <br/>
                                                        <strong>Mother Name:</strong>
                                                        {!!$json_object->Transfer_Request->mother_name!!}
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Date of Birth:</strong>
                                                        @if(isset($json_object->Transfer_Request->birth_date))
                                                            {!!CommonFunction::changeDateFormat($json_object->Transfer_Request->birth_date)!!}
                                                        @endif
                                                        <br/>

                                                        <strong>Gender:</strong>
                                                        {!! ucfirst($json_object->Transfer_Request->gender) !!}
                                                        <br/>
                                                        <strong>Management:</strong>
                                                        {!! ucfirst($json_object->Transfer_Request->is_govt) !!}
                                                        <br/>



                                                    </div>

                                                </div>

                                                <div class="col-md-12"><br/></div>

                                                {{--contact information--}}
                                                <div class="col-md-6">
                                                    <label>Contact Information</label>

                                                    <div class="col-md-10">
                                                        <strong>Mobile:</strong>
                                                        {!!$json_object->Transfer_Request->mobile!!}

                                                        {{--SB Report--}}
                                                        <br/><br/>
                                                    </div>

                                                </div>

                                                    <div class="col-md-6">
                                                        @if($json_object->Transfer_Request->is_registrable==1)
                                                            <br/>
                                                            {{--passport info--}}
                                                            <strong>Passport No:</strong>
                                                            {!!$json_object->Transfer_Request->passport_no!!}
                                                            <br/>
                                                            <strong>Passport Type:</strong>
                                                            {!! ucfirst($json_object->Transfer_Request->pass_type) !!}
                                                            <br/>

                                                            @if($json_object->Transfer_Request->pass_exp_date)
                                                                <strong>Passport Expire Date:</strong>
                                                                @if(isset($json_object->Transfer_Request->pass_exp_date))
                                                                    {!!CommonFunction::changeDateFormat($json_object->Transfer_Request->pass_exp_date)!!}
                                                                @endif
                                                            @endif
                                                        @endif
                                                        @if($json_object->Transfer_Request->nationality2)
                                                            <br>
                                                            <strong>Second Nationality:</strong>
                                                            {!!$json_object->Transfer_Request->nationality2!!}
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
                                                        {!!$json_object->Transfer_Request->village_ward!!}, {!! $json_object->Transfer_Request->police_station!!},
                                                        {!! $json_object->Transfer_Request->district !!}-{!! $json_object->Transfer_Request->post_code !!}
                                                    </div>

                                                    <div class="col-md-12">
                                                        <strong>Permanent Address:</strong>
                                                        {!!$json_object->Transfer_Request->per_village_ward!!}
                                                        , {!! $json_object->Transfer_Request->per_police_station!!},
                                                        {!! $json_object->Transfer_Request->per_district !!}-{!! $json_object->Transfer_Request->per_post_code !!}
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </span>

                <!-- .panel-body -->
            </div>
        </div>
    </div><!-- /.panel -->

</div><!-- /.full col-sm-12 -->