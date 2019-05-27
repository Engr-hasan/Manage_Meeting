<?php
use App\Libraries\CommonFunction;
use \App\Modules\ProcessHmis\Models\Replacement;
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="panel-title">
            Replacement Request <br/>
            Reference No : {!! $json_object->reference_no !!}
        </div>
    </div>
    <div class="panel-body">
        <div class="col-md-6">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">
                        Replaced for :
                    </div>
                </div>
                <?php
                    $profile_pic = CommonFunction::getPicture('pilgrim',$json_object->user_ref_pilgrim_id);
                ?>
                <div class="panel-body">
                    <div class="col-md-12">
                        <div class="panel-thumbnail">
                            <img class="img-responsive profile-user-img"
                                 src="{{ ($profile_pic == '')?url('assets/images/default_profile.jpg'):$profile_pic }}">
                        </div>
                    </div>
                    <div class="col-md-12 text-center">
                        <span class="lead">{!!$json_object->user_name_bengali !!}</span>
                        <p class=""> {!! $json_object->user_per_police_station!!},
                            {!! $json_object->user_per_district !!}-{!! $json_object->user_per_post_code !!}</p>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('user_name','Name:',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">
                            {!! $json_object->user_name_english !!}
                        </div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('tracking_no','Tracking No:',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">
                            {!! $json_object->user_track_no !!}
                        </div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('user_pid','Pid:',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">
                            {!! $json_object->user_pid !!}
                        </div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('reason','Replacement reason',['class'=>'col-md-5']) !!}
                        <div class="col-md-7 text-danger">
                            {!! $json_object->user_replacement_reason !!}
                        </div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('user_pass_no','Passport No:',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">
                            {!! $json_object->user_pass_no !!}
                        </div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('user_pass_issue_date','Passport issue date',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">
                            {!! CommonFunction::changeDateFormat($json_object->user_pass_issue_date) !!}
                        </div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('user_pass_exp_date','Passport expire date',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">
                            {!! CommonFunction::changeDateFormat($json_object->user_pass_exp_date) !!}
                        </div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('user_pass_type','Passport type',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">
                            {!! $json_object->user_pass_type !!}
                        </div>
                    </div>

                    <div class="col-md-12">
                        {!! Form::label('user_pass_district','Passport district:',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">
                            {!! $json_object->user_pass_district !!}
                        </div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('user_pass_thana','Passport thana',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">
                            {!! $json_object->user_pass_thana !!}
                        </div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('user_pass_village','Passport village',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">
                            {!! $json_object->user_pass_village !!}
                        </div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('user_pass_post_code','Passport code',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">
                            {!! $json_object->user_pass_post_code !!}
                        </div>
                    </div>

                    <div class="col-md-12">
                        {!! Form::label('user_mobile_no','Mobile no',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">
                            {!! $json_object->user_mobile_no !!}
                        </div>
                    </div>
                    @if($json_object->user_national_id != '')
                        <div class="col-md-12 text-center">
                            <a href="javascript:void(0);"
                               url="{!! url('pilgrim-profile/view-nid-source') !!}"
                               nid="{!! Encryption::encode($json_object->user_national_id) !!}"
                               birthdate="{!! Encryption::encode($json_object->user_birth_date) !!}"
                               class="btn btn-xs btn-default nidinfo">
                                <i class="fa fa-eye"></i>
                                View NID
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">
                        Requested Pilgrim Information :
                    </div>
                </div>
                <?php
                    $requested_pilgrim_info = Replacement::getRequestedPilgrimInfo($json_object->req_user_pilgrim_id);
                    $req_user_profile_pic = CommonFunction::getPicture('pilgrim',$requested_pilgrim_info->id);
                ?>
                <div class="panel-body">
                    <div class="col-md-12">
                        <div class="panel-thumbnail">
                            <img class="img-responsive profile-user-img"
                                 src="{{ ($req_user_profile_pic == '')?url('assets/images/default_profile.jpg'):$req_user_profile_pic }}">
                        </div>
                    </div>
                    <div class="col-md-12 text-center">
                        <span class="lead">{!! $requested_pilgrim_info->full_name_bangla !!}</span>
                        <p class=""> {!! $requested_pilgrim_info->per_police_station!!},
                            {!! $requested_pilgrim_info->per_district !!}-{!! $requested_pilgrim_info->per_post_code !!}</p>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('user_name','Name:',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">
                            {!! $requested_pilgrim_info->full_name_english !!}
                        </div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('tracking_no','Tracking No:',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">
                            {!! $json_object->req_user_track_no !!}
                        </div>
                    </div>
                    @if($requested_pilgrim_info->national_id != '')
                        <div class="col-md-12 text-center">
                            <a href="javascript:void(0);"
                               url="{!! url('pilgrim-profile/view-nid-source') !!}"
                               nid="{!! Encryption::encode($requested_pilgrim_info->national_id) !!}"
                               birthdate="{!! Encryption::encode($requested_pilgrim_info->birth_date) !!}"
                               class="btn btn-xs btn-default nidinfo">
                                <i class="fa fa-eye"></i>
                                View NID
                            </a>
                        </div>
                    @endif
                    <div class="col-md-12">
                        {!! Form::label('req_user_pass_no','Passport No:',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">
                            {!! $json_object->req_user_pass_no !!}
                        </div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('user_pass_issue_date','Passport issue date',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">
                            {!! $json_object->req_pass_issue_date !!}
                        </div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('user_pass_exp_date','Passport expire date',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">
                            {!! $json_object->req_pass_exp_date !!}
                        </div>
                    </div>

                    <div class="col-md-12">
                        {!! Form::label('req_pass_issue_place','Passport issue place',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">
                            {!! $json_object->req_pass_issue_place !!}
                        </div>
                    </div>

                    <div class="col-md-12">
                        {!! Form::label('user_pass_type','Passport district',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">
                            {!! $json_object->req_pass_district !!}
                        </div>
                    </div>

                    <div class="col-md-12">
                        {!! Form::label('user_pass_thana','Passport thana',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">
                            {!! $json_object->req_pass_thana !!}
                        </div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('req_pass_village','Passport village',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">
                            {!! $json_object->req_pass_village !!}
                        </div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('user_pass_post_code','Passport code',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">
                            {!! $json_object->req_pass_post_code !!}
                        </div>
                    </div>
                    <hr/>
                    <?php
                        $agencyInfo = Replacement::getAgencyInfo($requested_pilgrim_info->user_sub_type);
                    ?>
                    <div class="col-md-12">
                        <blockquote>
                            <h4>
                                Agency Name: {!! $agencyInfo->name !!}
                            </h4>
                            <p>License No : {!! $agencyInfo->license_no !!}</p>
                        </blockquote>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
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
