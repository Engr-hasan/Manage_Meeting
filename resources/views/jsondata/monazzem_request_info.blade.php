<?php
use App\Libraries\UtilFunction;
//dd($json_object);
?>
<style>
    .form-group{
        margin-bottom: 5px !important;
    }
</style>

<section class="col-md-12">
    <div class="row">
        <!-- Horizontal Form -->
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="pull-left">
                    Hajj Agency Profile with Monazzem Information
                </div>
                <div class="clearfix"></div>
            </div><!-- /.panel-heading -->

            <div class="panel-body">

                {{--NID source modal for Monazzem--}}
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


                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="panel panel-green">
                            <div class="panel-heading">
                                <h3 class="panel-title">{!! trans('messages.information_of_agency') !!}</h3>
                            </div>
                            <div class="panel-body">

                                <div class="form-group col-md-12">
                                    {!! Form::label('agency_name',trans('messages.agency_name').': ',['class'=>'col-md-6 font-ok']) !!}
                                    <div class="col-md-6">
                                        {!! $json_object->Agency_Info->Agency_Name !!}
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    {!! Form::label('agency_license',' Agency HL: ',['class'=>'col-md-6 font-ok']) !!}
                                    <div class="col-md-6">
                                        {!! $json_object->Agency_Info->Agency_HL !!}
                                    </div>
                                </div>


                                <div class="form-group col-md-12">
                                    {!! Form::label('agency_name_arabic',trans('messages.agency_name_arabic').': ',['class'=>'col-md-6 font-ok']) !!}
                                    <div class="col-md-6">
                                        {!! $json_object->Agency_Info->Agency_Name_Arabic !!}
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    {!! Form::label('monazzem_no',trans('messages.monazzem_no'),['class'=>'col-md-6 font-ok ']) !!}
                                    <div class="col-md-6">
                                        {!! $json_object->Agency_Info->Monazzem_No !!}
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    {!! Form::label('hajj_licence_no',trans('messages.hajj_licence_no'),['class'=>'col-md-6 font-ok']) !!}
                                    <div class="col-md-6">
                                        {!! $json_object->Agency_Info->Hajj_License_No !!}
                                    </div>
                                </div>


                                <div class="form-group col-md-12">
                                    {!! Form::label('licence_issue_date',trans('messages.licence_issue_date'),['class'=>'col-md-6 font-ok ']) !!}
                                    <div class="col-md-6">
                                        {!! $json_object->Agency_Info->License_Issue_Date !!}
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    {!! Form::label('licence_expire_date',trans('messages.licence_expire_date'),['class'=>'col-md-6 font-ok ']) !!}
                                    <div class="col-md-6">
                                        {!! $json_object->Agency_Info->License_Expire_Date !!}
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    {!! Form::label('iata_no',trans('messages.iata_no'),['class'=>'col-md-6 font-ok']) !!}
                                    <div class="col-md-6">
                                        {!! $json_object->Agency_Info->Iata_No !!}
                                    </div>
                                </div>


                                <div class="form-group col-md-12">
                                    {!! Form::label('iata_issue_date',trans('messages.iata_issue_date'),['class'=>'col-md-6 font-ok']) !!}
                                    <div class="col-md-6">
                                        {!! $json_object->Agency_Info->Iata_Issue_Date !!}
                                    </div>
                                </div>



                                <div class="form-group col-md-12">
                                    {!! Form::label('iata_expired_date',trans('messages.iata_expired_date'),['class'=>'col-md-6 font-ok']) !!}
                                    <div class="col-md-6">
                                        {!! $json_object->Agency_Info->Iata_Expire_Date !!}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-yellow">
                            <div class="panel-heading">
                                <h3 class="panel-title">Picture</h3>
                            </div>
                            <div class="panel-body" style="min-height: 350px;">
                                <div class="row text-center">
                                    <div class="progress hidden pull-right" id="upload_progress"
                                         style="width: 50%;">
                                        <div class="progress-bar progress-bar-striped active" role="progressbar"
                                             aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"
                                             style="width: 100%">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php $img = CommonFunction::getPicture('monazzem_request', $applicationInfo->ref_id);?>
                                    {!! '<img src="'.$img.'" class="profile-user-img img-responsive"  alt="Profile Picture" id="uploaded_pic"  width="200"/>' !!}
                                </div>

                                <div class="form-group"><br/>
                                    <div class="row text-center">
                                        <a href="javascript:void(0);"
                                           url="{!! url('pilgrim-profile/view-nid-source') !!}"
                                           nid="{!! Encryption::encode($json_object->Monazzem_Info->Monazzem_Nid) !!}"
                                           birthdate="{!! Encryption::encode($json_object->Monazzem_Info->Monazzem_DOB) !!}"
                                           class="btn btn-xs btn-default nidinfo">
                                            <i class="fa fa-eye"></i>
                                            View NID
                                        </a>
                                    </div>
                                </div>

                                {{--<div class="form-group">--}}
                                    {{--<div class="col-md-12">--}}
                                        {{--<div class="col-md-4"></div>--}}
                                        {{--<div class="col-md-8">--}}
                                            {{--&nbsp;--}}
                                        {{--</div>--}}
                                    {{--</div>--}}

                                    {{--<div class="col-md-12">--}}
                                        {{--<br/>--}}
                                            {{--<span style="font-size: 12px;">--}}
                                                {{--&nbsp;--}}
                                            {{--</span>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="panel panel-green">
                            <div class="panel-heading">
                                <h3 class="panel-title">{!! trans('messages.agency_address_information') !!}</h3>
                            </div>
                            <div class="panel-body" style="min-height: 285px;">

                                <div class="form-group col-md-12">
                                    {!! Form::label('agency_road_no',trans('messages.agency_road_no'),['class'=>'col-md-6 font-ok ']) !!}
                                    <div class="col-md-6">
                                        {!! $json_object->Agency_Address->Agency_Road !!}
                                    </div>
                                </div>


                                <div class="form-group col-md-12">
                                    {!! Form::label('agency_post_box',trans('messages.agency_post_box'),['class'=>'col-md-6 font-ok']) !!}
                                    <div class="col-md-6">
                                        {!! $json_object->Agency_Address->Post_Box !!}
                                    </div>
                                </div>



                                <div class="form-group col-md-12">
                                    {!! Form::label('agency_post_code',trans('messages.agency_post_code'),['class'=>'col-md-6 font-ok']) !!}
                                    <div class="col-md-6">
                                        {!! $json_object->Agency_Address->Post_Code !!}
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    {!! Form::label('agency_telephone_no',trans('messages.agency_telephone_no'),['class'=>'col-md-6 font-ok ']) !!}
                                    <div class="col-md-6">
                                        {!! $json_object->Agency_Address->Agency_Phone !!}
                                    </div>
                                </div>


                                <div class="form-group col-md-12">
                                    {!! Form::label('agency_fax',trans('messages.agency_fax'),['class'=>'col-md-6 font-ok']) !!}
                                    <div class="col-md-6">
                                        {!! $json_object->Agency_Address->Agency_Fax !!}
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    {!! Form::label('agency_email_no',trans('messages.agency_email_no'),['class'=>'col-md-6 font-ok ']) !!}
                                    <div class="col-md-6">
                                        {!! $json_object->Agency_Address->Agency_Email !!}
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    {!! Form::label('agency_website',trans('messages.agency_website'),['class'=>'col-md-6 font-ok']) !!}
                                    <div class="col-md-6">
                                        {!! $json_object->Agency_Address->Agency_Website !!}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-green">
                            <div class="panel-heading">
                                <h3 class="panel-title">{!! trans('messages.monazzem_address_related_info') !!}</h3>
                            </div>
                            <div class="panel-body">

                                <div class="form-group col-md-12">
                                    {!! Form::label('mona_building',trans('messages.mona_building'),['class'=>'col-md-7 font-ok ']) !!}
                                    <div class="col-md-5">
                                        {!! $json_object->Monazzem_Address->Monazzem_Building !!}
                                    </div>
                                </div>


                                <div class="form-group col-md-12">
                                    {!! Form::label('mona_road',trans('messages.mona_road'),['class'=>'col-md-7 font-ok ']) !!}
                                    <div class="col-md-5">
                                        {!! $json_object->Monazzem_Address->Monazzem_Road !!}
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    {!! Form::label('mona_post_box',trans('messages.mona_post_box'),['class'=>'col-md-7 font-ok']) !!}
                                    <div class="col-md-5">
                                        {!! $json_object->Monazzem_Address->Monazzem_Post_Box !!}
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    {!! Form::label('mona_post_code',trans('messages.mona_post_code'),['class'=>'col-md-6 font-ok']) !!}
                                    <div class="col-md-6">
                                        {!! $json_object->Monazzem_Address->Monazzem_Post_Code !!}
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    {!! Form::label('mona_phone',trans('messages.mona_phone'),['class'=>'col-md-7 font-ok']) !!}
                                    <div class="col-md-5">
                                        {!! $json_object->Monazzem_Address->Monazzem_Phone !!}
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    {!! Form::label('mona_fax',trans('messages.mona_fax'),['class'=>'col-md-7 font-ok']) !!}
                                    <div class="col-md-5">
                                        {!! $json_object->Monazzem_Address->Monazzem_Fax !!}
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    {!! Form::label('monazzem_mobile_bd',trans('messages.monazzem_mobile_bd'),['class'=>'col-md-7 font-ok ']) !!}
                                    <div class="col-md-5">
                                        {!! $json_object->Monazzem_Address->Monazzem_BD_Mobile !!}
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    {!! Form::label('monazzem_mobile_sa',trans('messages.monazzem_mobile_sa'),['class'=>'col-md-7 font-ok ']) !!}
                                    <div class="col-md-5">
                                        {!! $json_object->Monazzem_Address->Monazzem_SA_Mobile !!}
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="panel-title">{!! trans('messages.mozzem_related_info') !!}</h3>
                            </div>
                            <div class="panel-body">


                                <div class="form-group col-md-12">
                                    {!! Form::label('monazzem_name_nid_english',trans('messages.monazzem_name_nid_english'),['class'=>'col-md-7 font-ok ']) !!}
                                    <div class="col-md-5">
                                        {!! $json_object->Monazzem_Info->Monazzem_Name_NID !!}
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    {!! Form::label('monazzem_father',trans('messages.monazzem_father'),['class'=>'col-md-7 font-ok ']) !!}
                                    <div class="col-md-5">
                                        {!! $json_object->Monazzem_Info->Monazzem_Father !!}
                                    </div>
                                </div>


                                <div class="form-group col-md-12">
                                    {!! Form::label('monazzem_father_name_english',trans('messages.monazzem_father_name_english'),['class'=>'col-md-7 font-ok ']) !!}
                                    <div class="col-md-5">
                                        {!! $json_object->Monazzem_Info->Monazzem_Father_Name_English !!}
                                    </div>
                                </div>


                                <div class="form-group col-md-12">
                                    {!! Form::label('mona_mother_name_bangla',trans('messages.mona_mother_name_bangla'),['class'=>'col-md-7 font-ok ']) !!}
                                    <div class="col-md-5">
                                        {!! $json_object->Monazzem_Info->Monazzem_Mother_Name_Bangla !!}
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    {!! Form::label('monazzem_mother_name_english',trans('messages.monazzem_mother_name_english'),['class'=>'col-md-7 font-ok ']) !!}
                                    <div class="col-md-5">
                                        {!! $json_object->Monazzem_Info->Monazzem_Mother_Name_English !!}
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    {!! Form::label('mona_spouse_name',trans('messages.mona_spouse_name'),['class'=>'col-md-7 font-ok ']) !!}
                                    <div class="col-md-5">
                                        {!! $json_object->Monazzem_Info->Monazzem_Spouse_Name !!}
                                    </div>
                                </div>


                                <div class="form-group col-md-12">
                                    {!! Form::label('monazzem_grandpa',trans('messages.monazzem_grandpa'),['class'=>'col-md-7 font-ok']) !!}
                                    <div class="col-md-5">
                                        {!! $json_object->Monazzem_Info->Monazzem_Grandpa !!}
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    {!! Form::label('monazzem_ancestry',trans('messages.monazzem_ancestry'),['class'=>'col-md-7 font-ok']) !!}
                                    <div class="col-md-5">
                                        {!! $json_object->Monazzem_Info->Monazzem_Ancestry !!}
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    {!! Form::label('monazzem_passport',trans('messages.monazzem_passport'),['class'=>'col-md-7 font-ok ']) !!}
                                    <div class="col-md-5">
                                        {!! $json_object->Monazzem_Info->Monazzem_Passport !!}
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    {!! Form::label('monazzem_nationality',trans('messages.monazzem_nationality'),['class'=>'col-md-7 font-ok ']) !!}
                                    <div class="col-md-5">
                                        {!! $json_object->Monazzem_Info->Monazzem_Nationality !!}
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    {!! Form::label('monazzem_nid',trans('messages.monazzem_nid'),['class'=>'col-md-7 font-ok ']) !!}
                                    <div class="col-md-5">
                                        {!! $json_object->Monazzem_Info->Monazzem_Nid !!}
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    {!! Form::label('monazzem_dob',trans('messages.monazzem_dob'),['class'=>'col-md-7 font-ok ']) !!}
                                    <div class="col-md-5">
                                        {!! $json_object->Monazzem_Info->Monazzem_DOB !!}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <div class="panel-title">{!! trans('messages.monazzem_passport_information') !!}</div>
                            </div>
                            <div class="panel-body">
                                <div class="form-group col-md-12">
                                    {!! Form::label('pass_type',trans('messages.monazzem_pass_type'),['class'=>'col-md-7 font-ok']) !!}
                                    <div class="col-md-5">
                                        {!! ucfirst($json_object->Monazzem_Passport->Monazzem_PP_Type) !!}
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    {!! Form::label('monazzem_passport',trans('messages.monazzem_passport'),['class'=>'col-md-7']) !!}
                                    <div class="col-md-5">
                                        {!! $json_object->Monazzem_Info->Monazzem_Passport !!}
                                    </div>
                                </div>
                                <?php
                                    $pass_expire_date = ($json_object->Monazzem_Info->Monazzem_Passport_Expire != '') ? date('d-M-y',strtotime($json_object->Monazzem_Info->Monazzem_Passport_Expire)) : '';
                                    $pass_issue_date = ($json_object->Monazzem_Passport->Monazzem_Passport_Issue_Date != '') ? date('d-M-y',strtotime($json_object->Monazzem_Passport->Monazzem_Passport_Issue_Date)) : '';
                                ?>
                                <div class="form-group col-md-12">
                                    {!! Form::label('monazzem_pp_expire_date',trans('messages.monazzem_pp_expire_date'),['class'=>'col-md-7 font-ok']) !!}
                                    <div class="col-md-5">
                                        {!! $pass_expire_date !!}
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    {!! Form::label('monazzem_pass_issue_date',trans('messages.monazzem_pass_issue_date'),['class'=>'col-md-7 font-ok']) !!}
                                    <div class="col-md-5">
                                        {!! $pass_issue_date !!}
                                    </div>
                                </div>
                                <?php
                                 $pass_issue_place = UtilFunction::placeName('PassportPlace',$json_object->Monazzem_Passport->Monazzem_Pass_Issue_Place_Id);
                                 $district = UtilFunction::placeName('AreaInfo',$json_object->Monazzem_Passport->Monazzem_Pass_District_Id);
                                 $thana = UtilFunction::placeName('AreaInfo',$json_object->Monazzem_Passport->Monazzem_Pass_Thana_Id);
                                ?>
                                <div class="form-group col-md-12">
                                    {!! Form::label('pass_issue_place_id',trans('messages.monazzem_pass_delivery_authority'),['class'=>'col-md-7 font-ok']) !!}
                                    <div class="col-md-5">
                                        {!! $pass_issue_place !!}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <br/>
                                    <h5><b>{!! trans('messages.monazzem_pass_parmanent_addr') !!}</b></h5>
                                    <hr/>
                                </div>

                                <div class="form-group col-md-12">
                                    {!! Form::label('monazzem_pass_district_id',trans('messages.monazzem_pass_district'),['class'=>'col-md-7 font-ok']) !!}
                                    <div class="col-md-5">
                                        {!! $district !!}
                                    </div>
                                </div>


                                <div class="form-group col-md-12">
                                    {!! Form::label('monazzem_pass_thana_id',trans('messages.monazzem_pass_thana'),['class'=>'col-md-7 font-ok']) !!}
                                    <div class="col-md-5">
                                        {!! $thana !!}
                                    </div>
                                </div>


                                <div class="form-group col-md-12">
                                    {!! Form::label('monazzem_pass_address',trans('messages.monazzem_pass_address'),['class'=>'col-md-7 font-ok']) !!}
                                    <div class="col-md-5">
                                        {!! $json_object->Monazzem_Passport->Monazzem_Pass_Address !!}
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    {!! Form::label('monazzem_pass_post_code',trans('messages.monazzem_pass_post_code'),['class'=>'col-md-7 font-ok']) !!}
                                    <div class="col-md-5">
                                        {!! $json_object->Monazzem_Passport->Monazzem_Pass_Post_Code !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
    </div>
    </div>
</section>