@extends('layouts.admin')
@section('content')
    <?php
    use App\Libraries\CommonFunction;$accessMode = ACL::getAccsessRight('spaceAllocation');
    if (!ACL::isAllowed($accessMode, $mode)) {
        die('You have no access right! Please contact with system admin if you have any query.');
    }
    ?>
    <style>
        /*.wizard > .actions a, .wizard > .actions a:hover, .wizard > .actions a:active{*/
        /*background: #449d44 !important;*/
        /*}*/
        input.error[type="radio"] {
            outline: 2px solid red;
        }
    </style>

    <style>

        .alert-primary {
            /*color: #ffffff;*/
            /*background-color: #2779B2;*/
            /*border-color: #2779b1;*/
            color: #ffffff;
            background-color: #238000;
            border-color: #238000;
        }

        .panel-form {
        }

        .panel-form .panel-heading {
            background: #B2DDE7;
        }

        .panel-form .panel-body {
            background: #8ac8e6;
            padding: 10px;
        }

        .panel-form table {
            background: #fff;
            margin-bottom: 10px;

        }

        .panel-form label {
            margin-bottom: 0;
            font-size: 14px;
            font-weight: normal;
        }

        .table > tbody > tr > td {
            vertical-align: middle;
        }

        .panel-form .table > tbody > tr:first-child > td:first-child {
            width: 50px;
            text-align: center;
        }

        .panel-form .table > tbody > tr:nth-child(2) {

        }

        .panel-form.table-bordered > thead > tr > th, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td {
            /*border: 1px solid #2779b1;*/
            border: 1px solid #FFD5B6;
        }

        .panel-form .btn-warning {
            background: #ec971f;
        }
        .btn-breadcrumb .btn.btn-info:not(:last-child):before {
            border-left: 20px solid #e8e3e3;
        }
        .btn-breadcrumb .btn.btn-info:hover:not(:last-child):before {
            border-left: 20px solid #e8e3e3;
        }
        .btn-breadcrumb .btn.btn-danger:not(:last-child):before {
            border-left: 20px solid #e8e3e3;
        }

        .btn-breadcrumb .btn.btn-danger:hover:not(:last-child):before {
            border-left: 20px solid #e8e3e3;
        }

        .btn-breadcrumb .btn.btn-warning:not(:last-child):before {
            border-left: 20px solid #e8e3e3;
        }

        .btn-breadcrumb .btn.btn-warning:hover:not(:last-child):before {
            border-left: 20px solid #e8e3e3;
        }

        .btn-breadcrumb .btn.btn-danger:not(:last-child):after {
            border-left: 10px solid #363231;
        }
        .btn-breadcrumb .btn.btn-danger:hover:not(:last-child):after {
            border-left: 10px solid #363231;
        }

    </style>

    <section class="content" id="applicationForm1">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    {{--start application form with wizard--}}
                    {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
                    {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}


                    <div class="panel panel-info" >
                        <div class="panel-heading">
                            {{--<h5><strong>  Application for Space Allocation </strong></h5>--}}
                            <h5><strong> Application for Registration </strong></h5>
                        </div>

                        @if(ACL::isAllowed($accessMode, '-UP-') && $viewMode == 'on' && $hasDeskParkWisePermission)
                            @if(Request::segment(4) == ''){{-- 4= meeting module --}}
                            @include('ProcessPath::batch-process')
                            @endif
                        @endif
                        @if($viewMode == 'on')
                            @if(Request::segment(4) == ''){{-- 4= meeting module --}}
                            <br>
                            <div class="container">
                                <div class="row">
                                    <div class="btn-group btn-breadcrumb steps">
                                        <?php
                                        $x = 0;

                                        ?>
                                        @foreach($statusName as $status)
                                            <?php
                                            if($status->id == $appInfo->status_id){
                                                $class = 'warning';
                                                $color = '#';
                                                $disable = 'disable';
                                            }elseif($status->id > $appInfo->status_id){
                                                $class = 'info';
                                                $color = '#';
                                                $disable = 'disaabled';
                                            }elseif($status->id ==''){

                                            }
                                            else{
//                                              $current_status = 'Shorfall';
                                            $class = 'success';
                                            $color = '#358e35';
                                            $disable = 'disable';

                                            ?>
                                            <?php
                                            }
                                            $x++;
                                            ?>
                                            <a href="#" class="btn btn-<?php echo $class;?>" style="background-color:{{$color}};">{{$status->status_name}}</a>

                                            {{--<a href="#" class="btn btn-danger">{{$status->status_name}}</a>--}}
                                            {{--<a href="#" class="btn btn-warning">{{$status->status_name}}</a>--}}
                                        @endforeach

                                    </div>
                                </div>

                            </div>
                            @endif
                        @endif
                        <div class="panel-body  panel-form" id="inputForm">
                            @if($viewMode != 'on')
                                {!! Form::open(array('url' => '/general-apps/add','method' => 'post','id' => 'ApplicationForm','role'=>'form','enctype'=>'multipart/form-data','files'=>'true')) !!}
                                <input type="hidden" name="selected_file" id="selected_file"/>
                                <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                                <input type="hidden" name="isRequired" id="isRequired"/>
                                {{--hidden field for show identification type radio box in preview page--}}
                                <input type="hidden" name="identificationValue" id="identificationValue"/>
                                {{--hidden field for show EIA certificate radio box in preview page--}}
                                <input type="hidden" name="eiaCertValue" id="eiaCertValue" value=""/>
                                <input type="hidden" name="eia_cer_fileValue" id="eia_cer_fileValue" value=""/>
                            @endif

                            <h3 class="text-center stepHeader"> General Information</h3>
                            <fieldset>
                                <fieldset>
                                    <div class="panel panel-info preview">

                                        <div class="panel-body">
                                            @if ($viewMode != 'on')
                                            <div class="panel-heading">
                                                <h4 class="text-center"><b>{!!$dynamicSection->title!!}</b></h4>
                                            </div>
                                            @endif
                                            @if ($viewMode == 'on')
                                            <section class="content-header">
                                                <ol class="breadcrumb">
                                                    <li><strong>Tracking no. : </strong>{{$appInfo->tracking_no}}</li>
                                                    <li><strong> Date of Submission: </strong> {{ \App\Libraries\CommonFunction::formateDate($appInfo->application_date)  }}  </li>
                                                    <li><strong>Current Status : </strong>
                                                        @if(isset($appInfo) && $appInfo->status_id == -1) Draft
                                                        @else {!! $statusArray[$appInfo->status_id] !!}
                                                        @endif
                                                    </li>
                                                    <li>
                                                        @if($appInfo->desk_id != 0) <strong>Current Desk :</strong>
                                                        {{ \App\Libraries\CommonFunction::getDeskName($appInfo->desk_id)  }}
                                                        @else
                                                            <strong>Current Desk :</strong> Applicant
                                                        @endif
                                                    </li>

                                                    <li>
                                                        <?php if (isset($appInfo) && $appInfo->status_id == 25 && isset($appInfo->certificate) && $appInfo->certificate != '') { ?>
                                                        <a href="{{ url($appInfo->certificate) }}" class="btn show-in-view btn-xs btn-info"
                                                           title="Download Approval Letter" target="_blank"> <i class="fa  fa-file-pdf-o"></i> <b>Download Certificate</b></a>
                                                        @if(Auth::user()->user_type == '1x101')
                                                            <a onclick="return confirm('Are you sure ?')" href="/space-allocation/discard-certificate/{{ Encryption::encodeId($appInfo->id)}}" class="btn show-in-view btn-xs btn-danger"
                                                               title="Download Approval Letter"> <i class="fa  fa-trash"></i> <b>Discard Certificate</b></a>
                                                        @endif
                                                        @if(Auth::user()->user_type != '13x131')
                                                            <a href="/space-allocation/project-cer-re-gen/{{ Encryption::encodeId($appInfo->id)}}" class="btn show-in-view btn-xs btn-warning"
                                                               title="Download Approval Letter" target="_self"> <i class="fa  fa-file-pdf-o"></i> <b>Re-generate certificate</b></a>
                                                        @endif
                                                        <?php } ?>
                                                        @if((env('server_type') == 'local' OR env('server_type') == 'dev') && isset($appInfo) && $appInfo->status_id == 23)
                                                            <a target="_blank" href="/land-requisition/open-certificate/{{ Encryption::encodeId($appInfo->id)}}" class="btn show-in-view btn-xs btn-success"
                                                               title="Download Approval Letter" target="_self"> <i class="fa  fa-file-pdf-o"></i> <b>Open Certificate</b></a>
                                                        @endif
                                                    </li>
                                                </ol>
                                            </section>
                                            @endif

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="  ">
                                                        <table class="table table-bordered">
                                                            <tbody>
                                                            <tr>
                                                            </tr>
                                                            @if ($viewMode != 'on')
                                                            <tr class=" ">
                                                                <td colspan="8"><label><Br>
                                                                        {!!$dynamicSection->description!!}

                                                                    </label>
                                                                    <input {{ ($appInfo->acceptTerms == 1)?'checked':'' }} id="acceptTerms-2"
                                                                           name="acceptTerms" type="checkbox"
                                                                           class="required col-md-1 text-left no_remove"
                                                                           style="width:3%;">
                                                                    <label for="acceptTerms-2"
                                                                           class="col-md-11 text-left required-star">{{$dynamicSection->terms_and_conditions}}</label>
                                                                </td>
                                                            </tr>
                                                                @endif


                                                            </tbody>
                                                        </table>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </fieldset>
                            <h3 class="text-center stepHeader">Application Form</h3>
                            <fieldset>
                                <div class="panel panel-info">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel-heading"><b>1.Organization/Company Information</b>
                                                </div>
                                                <div class="  ">
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                        <tr class=" ">
                                                            <td colspan="6"></td>
                                                        </tr>
                                                        <tr>
                                                            <td><label for="company_name" class="required-star">Name of
                                                                    Organization/Company</label></td>
                                                            <td colspan="5">
                                                                {!! Form::text('company_name', $appInfo->company_name, ['class' => 'form-control input-sm required', 'id' => 'company_name']) !!}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Name of Service</label></td>
                                                            <td>Local industrial permission for investor</td>
                                                            <td><label for="date_of_submission">Date of
                                                                    Submission</label></td>
                                                            <td>
                                                                <div class="datepicker input-group date"
                                                                     data-date="12-03-2015"
                                                                     data-date-format="dd-mm-yyyy">
                                                                    {!! Form::text('date_of_submission', date('d-M-Y', strtotime($appInfo->date_of_submission)), ['class'=>'form-control input-sm', 'id' => 'date_of_submission','placeholder' => 'Pick from Calendar']) !!}
                                                                    <span class="input-group-addon"><span
                                                                                class="fa fa-calendar"></span></span>
                                                                </div>
                                                            </td>
                                                            <td><label for="date_of_approval">Date of Approval</label>
                                                            </td>
                                                            <td>
                                                                <div class="datepicker input-group date"
                                                                     data-date="12-03-2015"
                                                                     data-date-format="dd-mm-yyyy">
                                                                    {!! Form::text('date_of_approval', date('d-M-Y', strtotime($appInfo->date_of_approval)), ['class'=>'form-control input-sm', 'id' => 'date_of_approval','placeholder' => 'Pick from Calendar']) !!}
                                                                    <span class="input-group-addon"><span
                                                                                class="fa fa-calendar"></span></span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>

                                                            <td><label for="company_reg_no" class="required-star">Company
                                                                    Registration No</label></td>
                                                            <td colspan="2">
                                                                {!! Form::text('company_reg_no', $appInfo->company_reg_no, ['class' => 'form-control input-sm required', 'id' => 'company_reg_no']) !!}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="8">
                                                                <label></label>
                                                            </td>
                                                        </tr>

                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="panel-heading"><b>2.Office Address</b></div>
                                                <div class=" ">
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                        {{--<tr>--}}
                                                        {{--<td rowspan="5"><label>2</label></td>--}}
                                                        {{--</tr>--}}
                                                        <tr class=" ">
                                                            <td colspan="8"></td>
                                                        </tr>
                                                        <tr>
                                                            <td><label for="office_district" class="required-star">District</label>
                                                            </td>
                                                            <td>
                                                                {!! Form::select('office_district', $districtList, $appInfo->office_district, ['class' => 'form-control input-sm required','placeholder'=>'Select One','id'=>'office_district']) !!}
                                                            </td>
                                                            <td><label for="officce_police_station"
                                                                       class="required-star">Police Station</label></td>
                                                            <td>
                                                                {!! Form::select('officce_police_station', $policeStation, $appInfo->officce_police_station, $attributes = array('class'=>'form-control input-sm required',
                                                                'placeholder' => 'Select One', 'id'=>"officce_police_station")) !!}
                                                            </td>
                                                            <td><label for="office_post_office" class="required-star">Post
                                                                    Office</label></td>
                                                            <td>
                                                                {!! Form::text('office_post_office', $appInfo->office_post_office, ['class' => 'form-control input-sm required', 'id' => 'office_post_office']) !!}
                                                            </td>
                                                            <td><label for="office_post_code" class="required-star">Post
                                                                    Code</label></td>
                                                            <td>
                                                                {!! Form::text('office_post_code', $appInfo->office_post_code, ['class' => 'form-control input-sm required', 'id' => 'office_post_code']) !!}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><label for="office_house_flat_road"
                                                                       class="required-star">House,Flat/Apartment,Road</label>
                                                            </td>
                                                            <td colspan="7">
                                                                {!! Form::text('office_house_flat_road', $appInfo->office_house_flat_road, ['class' => 'form-control input-sm required', 'id' => 'office_house_flat_road']) !!}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><label for="office_telephone">Telephone No</label></td>
                                                            <td>
                                                                {!! Form::text('office_telephone', $appInfo->office_telephone, ['class' => 'form-control input-sm', 'id' => 'office_telephone']) !!}
                                                            </td>
                                                            <td><label for="office_mobile" class="required-star">Mobile
                                                                    No</label></td>
                                                            <td>
                                                                {!! Form::text('office_mobile', $appInfo->office_mobile, ['class' => 'form-control input-sm required mobile_number_validation', 'id' => 'office_mobile']) !!}
                                                            </td>
                                                            <td><label for="office_fax">Fax</label></td>
                                                            <td>
                                                                {!! Form::text('office_fax', $appInfo->office_fax, ['class' => 'form-control input-sm', 'id' => 'office_fax']) !!}
                                                            </td>
                                                            <td><label for="office_email"
                                                                       class="required-star">Email</label></td>
                                                            <td>
                                                                {!! Form::text('office_email', $appInfo->office_email, ['class' => 'form-control input-sm required email', 'id' => 'office_email']) !!}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="8">
                                                                <label></label>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>


                                                <div class="panel-heading"><b>3.Factory Address</b></div>
                                                <div class="  ">
                                                    <table class="table table-bordered">
                                                        <tbody>

                                                        <tr class=" ">
                                                            <td colspan="8"><label></label></td>
                                                        </tr>
                                                        <tr>
                                                            <td><label for="factory_district">District</label></td>
                                                            <td>
                                                                {!! Form::select('factory_district', $districtList, $appInfo->factory_district, ['class' => 'form-control input-sm ','placeholder'=>'Select One','id'=>'factory_district']) !!}
                                                            </td>
                                                            <td><label for="factory_police_statuion">Police
                                                                    Station</label></td>
                                                            <td>
                                                                {!! Form::select('factory_police_statuion', $policeStation, $appInfo->factory_police_statuion, $attributes = array('class'=>'form-control input-sm',
                                                                    'placeholder' => 'Select One', 'id'=>"factory_police_statuion")) !!}
                                                            </td>
                                                            <td><label for="factory_post_office">Post Office</label>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('factory_post_office', $appInfo->factory_post_office, ['class' => 'form-control input-sm', 'id' => 'factory_post_office']) !!}
                                                            </td>
                                                            <td><label for="factory_post_code">Post Code</label></td>
                                                            <td>
                                                                {!! Form::text('factory_post_code', $appInfo->factory_post_code, ['class' => 'form-control input-sm', 'id' => 'factory_post_code']) !!}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><label for="factory_mouza_no">Mouza No</label></td>
                                                            <td colspan="3">
                                                                {!! Form::text('factory_mouza_no', $appInfo->factory_mouza_no, ['class' => 'form-control input-sm', 'id' => 'factory_mouza_no']) !!}
                                                            </td>
                                                            <td><label for="factory_house_flat_road">House,Flat/Apartment,Road</label>
                                                            </td>
                                                            <td colspan="3">
                                                                {!! Form::text('factory_house_flat_road', $appInfo->factory_house_flat_road, ['class' => 'form-control input-sm', 'id' => 'factory_house_flat_road']) !!}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><label for="factory_telephone">Telephone No</label></td>
                                                            <td>
                                                                {!! Form::text('factory_telephone', $appInfo->factory_telephone, ['class' => 'form-control input-sm', 'id' => 'factory_telephone']) !!}
                                                            </td>
                                                            <td><label for="factory_mobile">Mobile No</label></td>
                                                            <td>
                                                                {!! Form::text('factory_mobile', $appInfo->factory_mobile, ['class' => 'form-control input-sm', 'id' => 'factory_mobile']) !!}
                                                            </td>
                                                            <td><label for="factory_fax">Fax</label></td>
                                                            <td>
                                                                {!! Form::text('factory_fax', $appInfo->factory_fax, ['class' => 'form-control input-sm', 'id' => 'factory_fax']) !!}
                                                            </td>
                                                            <td><label for="factory_email">Email</label></td>
                                                            <td>
                                                                {!! Form::text('factory_email', $appInfo->factory_email, ['class' => 'form-control input-sm email', 'id' => 'factory_email']) !!}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="8">
                                                                <label></label>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>


                                                <div class="panel-heading"><b>4.Name of Principal Promoter</b></div>
                                                <div class="  ">
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                        <tr class=" "></tr>
                                                        <tr>
                                                            <td colspan="1"><label for="chairman_name">Name of Principal
                                                                    Promoter/Chairman/Managing Director/CEO</label></td>
                                                            <td>
                                                                {!! Form::text('chairman_name', $appInfo->chairman_name, ['class' => 'form-control input-sm', 'id' => 'chairman_name']) !!}
                                                            </td>
                                                            <td><label for="chairman_designation">Designation</label>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('chairman_designation', $appInfo->chairman_designation, ['class' => 'form-control input-sm', 'id' => 'chairman_designation']) !!}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="8">
                                                                <label></label>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>


                                                <div class="panel-heading"><b>5.Address of Principal
                                                        Promoter/Chairman/Managing Director/CEO</b></div>
                                                <div class="  ">
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                        <tr class=" ">
                                                            <td colspan="8"><label></label></td>
                                                        </tr>
                                                        <tr>
                                                            <td><label for="chairman_country">Country</label></td>
                                                            <td>
                                                                {!! Form::select('chairman_country', $countries, $appInfo->chairman_country, ['class' => 'form-control input-sm ','placeholder'=>'Select One','id'=>'chairman_country']) !!}
                                                            </td>
                                                            <td>
                                                                <label class="hidden" for="chairman_district"
                                                                       id="chairman_district_label">District</label>
                                                                <label class="" for="chairman_state"
                                                                       id="chairman_state_label">City/State</label>
                                                            </td>
                                                            <td>
                                                                <div class="hidden" id="district_div">
                                                                    {!! Form::select('chairman_district',$districtList, $appInfo->chairman_district, ['class' => 'form-control input-sm ','placeholder'=>'Select One','id'=>'chairman_district']) !!}
                                                                </div>

                                                                <div class="" id="state_div">
                                                                    {!! Form::text('chairman_state',  $appInfo->chairman_state, $attributes = array('class'=>'form-control input-sm','id'=>"chairman_state")) !!}
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <label class="hidden" for="chairman_police_station"
                                                                       id="police_station_div_label">Police
                                                                    Station</label>
                                                                <label for="chairman_province" id="province_div_label">Province</label>
                                                            </td>
                                                            <td>
                                                                <div class="hidden" id="police_station_div">
                                                                    {!! Form::select('chairman_police_station', $policeStation, $appInfo->chairman_police_station, $attributes = array('class'=>'form-control input-sm',
                                                                    'placeholder' => 'Select One', 'id'=>"chairman_police_station")) !!}
                                                                </div>

                                                                <div class="" id="province_div">
                                                                    {!! Form::text('chairman_province', $appInfo->chairman_province, ['class' => 'form-control input-sm', 'id' => 'chairman_province']) !!}
                                                                </div>
                                                            </td>
                                                            <td><label for="chairman_post_code">Post/Zip Code</label>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('chairman_post_code', $appInfo->chairman_post_code, ['class' => 'form-control input-sm', 'id' => 'chairman_post_code']) !!}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><label for="chairman_house_flat_road">House,Flat/Apartment,Road</label>
                                                            </td>
                                                            <td colspan="7">
                                                                {!! Form::text('chairman_house_flat_road', $appInfo->chairman_house_flat_road, ['class' => 'form-control input-sm', 'id' => 'chairman_house_flat_road']) !!}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><label for="chairman_telephone">Telephone No</label>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('chairman_telephone', $appInfo->chairman_telephone, ['class' => 'form-control input-sm', 'id' => 'chairman_telephone']) !!}
                                                            </td>
                                                            <td><label for="chairman_mobile">Mobile No</label></td>
                                                            <td>
                                                                {!! Form::text('chairman_mobile', $appInfo->chairman_mobile, ['class' => 'form-control input-sm', 'id' => 'chairman_mobile']) !!}
                                                            </td>
                                                            <td><label for="chairman_fax">Fax</label></td>
                                                            <td>
                                                                {!! Form::text('chairman_fax', $appInfo->chairman_fax, ['class' => 'form-control input-sm', 'id' => 'chairman_fax']) !!}
                                                            </td>
                                                            <td><label for="chairman_email">Email</label></td>
                                                            <td>
                                                                {!! Form::text('chairman_email', $appInfo->chairman_email, ['class' => 'form-control input-sm email', 'id' => 'chairman_email']) !!}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="8">
                                                                <label></label>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>


                                                <div class="panel-heading"><b>6.Type of Industry (following
                                                        International Standard of Industrial Classification)</b></div>
                                                <div class="  ">
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                        <tr>
                                                        <tr></tr>
                                                        <td><label for="industry_type">Type of Industry</label></td>
                                                        <td>
                                                            {!! Form::select('industry_type', $typeofIndustry, $appInfo->industry_type, ['class' => 'form-control input-sm ','placeholder'=>'Select One','id'=>'industry_type']) !!}
                                                        </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="8">
                                                                <label></label>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>


                                                <div class="panel-heading"><b>7.Manpower of the organization</b></div>
                                                <div class="  ">
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                        <tr class=" ">
                                                            <td colspan="8"><label></label></td>
                                                        </tr>
                                                        <tr class="text-center">
                                                            <td colspan="3"><label>Local (Bangladesh Only)</label></td>
                                                            <td colspan="3"><label>Foreign (Abroad Country)</label></td>
                                                            <td colspan="2"><label>Ratio</label></td>
                                                        </tr>
                                                        <tr class="text-center">
                                                            <td><label for="local_executive">Executive</label></td>
                                                            <td><label for="local_supporting_staff">Supporting
                                                                    Staff</label></td>
                                                            <td><label for="local_total">Total</label></td>
                                                            <td><label for="foreign_executive">Executive</label></td>
                                                            <td><label for="foreign_supporting_staff">Supporting
                                                                    Staff</label></td>
                                                            <td><label for="foreign_total">Total</label></td>
                                                            <td><label for="ratio_local">Local</label></td>
                                                            <td><label for="ratio_foreign">Foreign</label></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                {!! Form::text('local_executive', $appInfo->local_executive, ['class' => 'form-control input-sm', 'id' => 'local_executive']) !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('local_supporting_staff', $appInfo->local_supporting_staff, ['class' => 'form-control input-sm', 'id' => 'local_supporting_staff']) !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('local_total', $appInfo->local_total, ['class' => 'form-control input-sm', 'id' => 'local_total']) !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('foreign_executive', $appInfo->foreign_executive, ['class' => 'form-control input-sm', 'id' => 'foreign_executive']) !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('foreign_supporting_staff', $appInfo->foreign_supporting_staff, ['class' => 'form-control input-sm', 'id' => 'foreign_supporting_staff']) !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('foreign_total', $appInfo->foreign_total, ['class' => 'form-control input-sm', 'id' => 'foreign_total']) !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('ratio_local', $appInfo->ratio_local, ['class' => 'form-control input-sm', 'id' => 'ratio_local']) !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('ratio_foreign', $appInfo->ratio_foreign, ['class' => 'form-control input-sm', 'id' => 'ratio_foreign']) !!}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="8">
                                                                <label></label>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="panel-heading"><b>8.Public Utility Service Required</b>
                                                </div>
                                                <div class="  ">
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                        <tr class=" ">
                                                            <td colspan="9"><label></label></td>
                                                        </tr>
                                                        <tr class="text-center">
                                                            <td><label for="electricity">Electricity</label></td>
                                                            <td><label for="gas">Gas</label></td>
                                                            <td><label for="telephone">Telephone</label></td>
                                                            <td><label for="road">Road</label></td>
                                                            <td><label for="water">Water</label></td>
                                                            <td><label for="drainage">Drainage</label></td>
                                                            <td rowspan="2" width="120px"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                {!! Form::text('electricity', $appInfo->electricity, ['class' => 'form-control input-sm', 'id' => 'electricity']) !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('gas', $appInfo->gas, ['class' => 'form-control input-sm', 'id' => 'gas']) !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('telephone', $appInfo->telephone, ['class' => 'form-control input-sm', 'id' => 'telephone']) !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('road', $appInfo->road, ['class' => 'form-control input-sm', 'id' => 'road']) !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('water', $appInfo->water, ['class' => 'form-control input-sm', 'id' => 'water']) !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('drainage', $appInfo->drainage, ['class' => 'form-control input-sm', 'id' => 'drainage']) !!}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="8">
                                                                <label></label>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="panel-heading"><b>9.Tax Identification number of the
                                                        Company</b></div>
                                                <div class="  ">
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                        <tr></tr>
                                                        <tr>
                                                            <td><label for="tin_no"> Tax Identification number of the
                                                                    Company</label></td>
                                                            <td>
                                                                {!! Form::text('tin_no', $appInfo->tin_no, ['class' => 'form-control input-sm', 'id' => 'tin_no']) !!}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="8">
                                                                <label></label>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </fieldset>

                            <h3 class="text-center stepHeader">Attachment</h3>
                            <fieldset>
                                <div class="panel panel-info">
                                    <!-- /.panel-heading -->
                                    <div class="panel-body">
                                        <div class="panel-heading"><b>10. Necessary Documents to be attached here (Only
                                                PDF file to be attach here)</b></div>
                                        <div class="">
                                            <table class="table table-bordered">
                                                <tbody>
                                                <?php $i = 1;?>
                                                @foreach($document as $docRow)
                                                    <tr>
                                                        <td>
                                                            <div align="center">{!! $i !!}<?php echo $docRow->doc_priority == "1" ? "<span class='required-star'></span>" : ""; ?></div>
                                                        </td>
                                                        <td>{!!  $docRow->doc_name !!}</td>
                                                        <td>
                                                            <input name="document_id_<?php echo $docRow->doc_id; ?>"
                                                                   type="hidden"
                                                                   value="{{(!empty($clrDocuments[$docRow->doc_id]['doucument_id']) ? $clrDocuments[$docRow->doc_id]['doucument_id'] : '')}}">
                                                            <input type="hidden" value="{!!  $docRow->doc_name !!}"
                                                                   id="doc_name_<?php echo $docRow->doc_id; ?>"
                                                                   name="doc_name_<?php echo $docRow->doc_id; ?>"/>
                                                            <input name="file<?php echo $docRow->doc_id; ?>"
                                                                   <?php if (empty($clrDocuments[$docRow->doc_id]['file']) && empty($allRequestVal["file$docRow->doc_id"]) && $docRow->doc_priority == "1") {
                                                                       echo "class='required'";
                                                                   } ?>
                                                                   id="file<?php echo $docRow->doc_id; ?>" type="file"
                                                                   size="20"
                                                                   onchange="uploadDocument('preview_<?php echo $docRow->doc_id; ?>', this.id, 'validate_field_<?php echo $docRow->doc_id; ?>', '<?php echo $docRow->doc_priority; ?>')"/>

                                                            @if($docRow->additional_field == 1)
                                                                <table>
                                                                    <tr>
                                                                        <td>Other file Name :</td>
                                                                        <td><input maxlength="64"
                                                                                   class="form-control input-sm <?php if ($docRow->doc_priority == "1") {
                                                                                       echo 'required';
                                                                                   } ?>"
                                                                                   name="other_doc_name_<?php echo $docRow->doc_id; ?>"
                                                                                   type="text"
                                                                                   value="{{(!empty($clrDocuments[$docRow->doc_id]['doc_name']) ? $clrDocuments[$docRow->doc_id]['doc_name'] : '')}}">
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            @endif


                                                            <div id="preview_<?php echo $docRow->doc_id; ?>">
                                                                <input type="hidden"
                                                                       value="<?php echo !empty($clrDocuments[$docRow->doc_id]['file']) ?
                                                                           $clrDocuments[$docRow->doc_id]['file'] : ''?>"
                                                                       id="validate_field_<?php echo $docRow->doc_id; ?>"
                                                                       name="validate_field_<?php echo $docRow->doc_id; ?>"
                                                                       class="<?php echo $docRow->doc_priority == "1" ? "required" : '';  ?>"/>
                                                            </div>


                                                            @if(!empty($allRequestVal["file$docRow->doc_id"]))
                                                                <label id="label_file{{$docRow->doc_id}}"><b>File: {{$allRequestVal["file$docRow->doc_id"]}}</b></label>
                                                                <input type="hidden" class="required"
                                                                       value="{{$allRequestVal["validate_field_".$docRow->doc_id]}}"
                                                                       id="validate_field_{{$docRow->doc_id}}"
                                                                       name="validate_field_{{$docRow->doc_id}}">
                                                            @endif

                                                        </td>
                                                        <td>
                                                            @if(!empty($clrDocuments[$docRow->doc_id]['file']))
                                                                <div class="save_file saved_file_{{$docRow->doc_id}}">
                                                                    <a target="_blank"
                                                                       class="documentUrl btn btn-success btn-xs" href="{{URL::to('/uploads/'.(!empty($clrDocuments[$docRow->doc_id]['file']) ?
                                                                    $clrDocuments[$docRow->doc_id]['file'] : ''))}}"
                                                                       title="{{$docRow->doc_name}}">
                                                                        <b><i class="fa fa-download"></i> Download</b>
                                                                    </a>

                                                                    <?php if(!empty($appInfo) && Auth::user()->id == $appInfo->created_by && $viewMode != 'on') {?>
                                                                    <a href="javascript:void(0)"
                                                                       onclick="ConfirmDeleteFile({{ $docRow->doc_id }})">
                                                                    <span class="btn btn-xs btn-danger"><i
                                                                                class="fa fa-times"></i></span>
                                                                    </a>
                                                                    <?php } ?>
                                                                </div>
                                                            @else
                                                                <span>No Attachment</span>
                                                            @endif
                                                        </td>
                                                        @if ($viewMode != 'on')
                                                        <td colspan="3"><span class="text-danger" id="alert_10"
                                                                              style="font-size: 9px; font-weight: bold;display:block; color: green; ">[File Format: *.pdf | File size within 3 MB]</span>
                                                        </td>
                                                        @endif


                                                    </tr>
                                                    <?php $i++; ?>
                                                @endforeach
                                                <tr>
                                                    <td colspan="9">
                                                        @if($viewMode != 'off')
                                                            @include('GeneralApps::doc-tab')
                                                        @endif
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </fieldset>
                                <h3 class="text-center stepHeader">Payment</h3>
                                <fieldset>
                                    <div class="panel panel-info">
                                        <!-- /.panel-heading -->
                                        <div class="panel-body">
                                            <div class="panel-heading"><b>11. Payment</b></div>
                                            <div class="">
                                                <table class="table table-bordered">
                                                    <tbody>
                                                    <tr>
                                                        <td>
                                                            <div align="center">1</div>
                                                        </td>
                                                        <td colspan="4">Do you want to pay?</td>
                                                        <td colspan="3">
                                                            <input type="radio" class="no_remove" name="check">Yes

                                                        </td>
                                                        <td colspan="3">
                                                            <input type="radio" class="no_remove" checked name="check">No
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </fieldset>

                            <h3 class="stepHeader">Submit</h3>
                            <fieldset>
                                <div class="panel panel-info">
                                    <!-- /.panel-heading -->
                                    <div class="panel-body">
                                        <div class="panel-heading"><b>12.Declaration</b></div>
                                        <div class="  ">
                                            <table class="table table-bordered">
                                                <tbody>

                                                <tr></tr>
                                                <tr class="text-center">
                                                    <td colspan="4">
                                                        <b style="color: #e20505;">
                                                            <small>I do here by declare that the information given above
                                                                is true to the best of my knowledge and I shall be
                                                                liable for any false information/system is given.
                                                            </small>
                                                        </b>
                                                    </td>
                                                </tr>
                                                <tr class="text-center">
                                                    <td colspan="4">
                                                        <label>Authorized Personnel of the Orginazation </label>
                                                    </td>
                                                </tr>

                                                <tr class="text-center">
                                                    <td class="text-right"><label for="authorized_name">Full
                                                            Name </label></td>
                                                    <td colspan="3">
                                                        {!! Form::text('authorized_name', $appInfo->authorized_name, ['class' => 'form-control input-sm', 'readonly', 'id' => 'authorized_name']) !!}
                                                    </td>
                                                </tr>
                                                <tr class="text-center">
                                                    <td class="text-right"><label
                                                                for="authorized_address">Address </label></td>
                                                    <td colspan="3">
                                                        {!! Form::text('authorized_address', $appInfo->authorized_address, ['class' => 'form-control input-sm', 'readonly', 'id' => 'authorized_address']) !!}
                                                    </td>
                                                </tr>
                                                <tr class="text-center">
                                                    <td class="text-right"><label for="authorized_email">E-mail </label>
                                                    </td>
                                                    <td colspan="3">
                                                        {!! Form::text('authorized_email',$appInfo->authorized_email, ['class' => 'form-control input-sm email', 'readonly', 'id' => 'authorized_email']) !!}
                                                    </td>
                                                </tr>
                                                <tr class="text-center">
                                                    <td class="text-right"><label for="authorized_mobile">Mobile
                                                            No </label></td>
                                                    <td colspan="3">
                                                        {!! Form::text('authorized_mobile', $appInfo->authorized_mobile, ['class' => 'form-control input-sm', 'readonly', 'id' => 'authorized_mobile']) !!}
                                                    </td>
                                                </tr>
                                                <tr class="text-center">
                                                    <td class="text-right"><label for="letter_of_authorization">Letter of Authorization </label></td>
                                                    <td class="text-left">
                                                        {{--{!! Form::file('letter_of_authorization', ['class'=>'form-control input-sm', 'id' => 'letter_of_authorization','onchange'=>'function(this)'])!!}--}}
                                                        <input name="file80"
                                                               id="letter_of_authorization" type="file"
                                                               size="20"
                                                               class="input-sm <?php if (empty($appInfo->letter_of_authorization)) {
                                                                   echo 'reqauired';
                                                               } ?>"
                                                               onchange="uploadDocument('preview_80', this.id, 'letter_of_authorization', '0')"/>
                                                        <div id="preview_80">
                                                            <input type="hidden" class=""
                                                                   id="letter_of_authorization"
                                                                   name="letter_of_authorization"/>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <?php if (!empty($appInfo->letter_of_authorization)) { ?>
                                                        <div class="save_file">
                                                            <a target="_blank" class="btn btn-xs btn-success documentUrl" title=""
                                                               href="{{URL::to('/uploads/'.$appInfo->letter_of_authorization)}}"
                                                               style="margin-left: 10px">
                                                                <b><i class="fa fa-download"></i> Download</b>
                                                            </a>
                                                            <?php if(!empty($appInfo) && Auth::user()->id == $appInfo->created_by && $viewMode != 'on') {?>
                                                            <a href="javascript:void(0)"
                                                               onclick="ConfirmDeleteFile({{ $docRow->doc_id }})">
                                                                    <span class="btn btn-xs btn-danger"><i
                                                                                class="fa fa-times"></i></span>
                                                            </a>
                                                            <?php } ?>
                                                        </div>
                                                    <?php } ?>
                                                    @if ($viewMode != 'on')
                                                    <td colspan="2"><span class="text-danger" id="alert_10"
                                                                          style="font-size: 9px; font-weight: bold;display:block; color: green; ">[File Format: *.pdf | File size within 3 MB]</span>
                                                    </td>
                                                    @endif
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </fieldset>

                            @if(ACL::getAccsessRight('spaceAllocation','-E-'))
                                <button style="background: #ec971f" type="submit" class="btn btn-warning btn-md cancel "
                                        value="draft" name="actionBtn">Save as Draft
                                </button>
                            @endif
                            {!! Form::close() !!}

                        </div>
                    </div>
                    {{--End application form with wizard--}}
                    {{--@if(isset($viewMode) && $viewMode == "on" && (in_array(Auth::user()->user_type, array('1x101','3x303','4x404')) || in_array($appInfo->desk_id, $desk_id_array) ) && $appInfo->status_id != -1)--}}
                    @if(in_array(Auth::user()->user_type, array('1x101','3x303','4x404')))
                        @include('ProcessPath::application-history')
                    @endif
                    {{--application history panel end--}}
                </div>
            </div>
        </div>


    </section>




@endsection

@section('footer-script')


    <link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
    <script src="{{ asset('assets/scripts/jquery.steps.js') }}"></script>
    <script>


        $(document).ready(function () {
            //--------Step Form init+validation Start----------//
            var form = $("#ApplicationForm").show();
            form.steps({
                headerTag: "h3",
                bodyTag: "fieldset",
                transitionEffect: "slideLeft",
                onStepChanging: function (event, currentIndex, newIndex) {
                    // Allways allow previous action even if the current form is not valid!
                    if (newIndex == 2) {

                    }

                    if (currentIndex > newIndex) {
                        return true;
                    }
                    // Forbid next action on "Warning" step if the user is to young
                    if (newIndex === 3 && Number($("#age-2").val()) < 18) {
                        return false;
                    }
                    // Needed in some cases if the user went back (clean up)
                    if (currentIndex < newIndex) {
                        // To remove error styles
//                    form.find(".body:eq(" + newIndex + ") label.error").remove();
//                    form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                    }
                    form.validate().settings.ignore = ":disabled,:hidden";
//                    return true;
                    return form.valid();
                },
                onStepChanged: function (event, currentIndex, priorIndex) {

                    // Used to skip the "Warning" step if the user is old enough.
                    /*if (currentIndex === 2 && Number($("#age-2").val()) >= 18){
                     form.steps("next");
                     }
                     // Used to skip the "Warning" step if the user is old enough and wants to the previous step.
                     if (currentIndex === 2 && priorIndex === 3){
                     //form.steps("previous");
                     }*/
                },
                onFinishing: function (event, currentIndex) {
                    form.validate().settings.ignore = ":disabled";
//                    return true;
                    return form.valid();
                },
                onFinished: function (event, currentIndex) {
                    //alert("Submitted!");
                }
            });
            //--------Step Form init+validation End----------//
            var popupWindow = null;
            $('.finish').on('click', function (e) {

                if ($('#acceptTerms-2').is(":checked")) {
                    $('#acceptTerms-2').removeClass('error');
                    $('#acceptTerms-2').next('label').css('color', 'black');
                    $('body').css({"display": "none"});
                    popupWindow = window.open('<?php echo URL::to('/general-apps/preview'); ?>', 'Sample', '');
                } else {
                    $('#acceptTerms-2').addClass('error');
                    return false;
                }
            });


            // get thana list by district id for Office Address
            $("#office_district").change(function () {
                var self = $(this);
                var districtId = $('#office_district').val();
                if (districtId !== '') {
                    $(this).after('<span class="loading_data">Loading...</span>');
                    $("#loaderImg").html("<img style='margin-top: -15px;' src='<?php echo url(); ?>/public/assets/images/ajax-loader.gif' alt='loading' />");
                    $.ajax({
                        type: "GET",
                        url: "<?php echo url(); ?>/users/get-thana-by-district-id",
                        data: {
                            districtId: districtId
                        },
                        success: function (response) {
                            var option = '<option value="">Select One</option>';
                            if (response.responseCode == 1) {
                                $.each(response.data, function (id, value) {
                                    // for edit list , applicant thana
                                    {{--if (id == '{{$users->thana}}'){--}}
                                    {{--option += '<option value="'+ id + '" selected>' + value + '</option>';--}}
                                    {{--}--}}
                                    //                                    else {
                                    option += '<option value="' + id + '">' + value + '</option>';
//                                    }
                                });
                            }
                            $("#officce_police_station").html(option);
                            self.next().hide();
                        }
                    });
                }
            });
            //$("#office_district").trigger('change');


            // get thana list by district id for Factory Address
            $("#factory_district").change(function () {
                var self = $(this);
                var districtId = $('#factory_district').val();
                if (districtId !== '') {
                    $(this).after('<span class="loading_data">Loading...</span>');
                    $("#loaderImg").html("<img style='margin-top: -15px;' src='<?php echo url(); ?>/public/assets/images/ajax-loader.gif' alt='loading' />");
                    $.ajax({
                        type: "GET",
                        url: "<?php echo url(); ?>/users/get-thana-by-district-id",
                        data: {
                            districtId: districtId
                        },
                        success: function (response) {
                            var option = '<option value="">Select One</option>';
                            if (response.responseCode == 1) {
                                $.each(response.data, function (id, value) {
                                    // for edit list , applicant thana
                                    {{--if (id == '{{$users->thana}}'){--}}
                                    {{--option += '<option value="'+ id + '" selected>' + value + '</option>';--}}
                                    {{--}--}}
                                    //                                    else {
                                    option += '<option value="' + id + '">' + value + '</option>';
//                                    }
                                });
                            }
                            $("#factory_police_statuion").html(option);
                            self.next().hide();
                        }
                    });
                }
            });
            //$("#factory_district").trigger('change');


            // get thana list by district id for Chairman address
            $("#chairman_district").change(function () {
                var self = $(this);
                var districtId = $('#chairman_district').val();
                if (districtId !== '') {
                    $(this).after('<span class="loading_data">Loading...</span>');
                    $("#loaderImg").html("<img style='margin-top: -15px;' src='<?php echo url(); ?>/public/assets/images/ajax-loader.gif' alt='loading' />");
                    $.ajax({
                        type: "GET",
                        url: "<?php echo url(); ?>/users/get-thana-by-district-id",
                        data: {
                            districtId: districtId
                        },
                        success: function (response) {
                            var option = '<option value="">Select One</option>';
                            if (response.responseCode == 1) {
                                $.each(response.data, function (id, value) {
                                    // for edit list , applicant thana
                                    {{--if (id == '{{$users->thana}}'){--}}
                                    {{--option += '<option value="'+ id + '" selected>' + value + '</option>';--}}
                                    {{--}--}}
                                    //                                    else {
                                    option += '<option value="' + id + '">' + value + '</option>';
//                                    }
                                });
                            }
                            $("#chairman_police_station").html(option);
                            self.next().hide();
                        }
                    });
                }
            });
            //$("#chairman_district").trigger('change');

            // on change country show district and thana div
            $('#chairman_country').change(function (e) {
                if (this.value == 'BD') {
                    // for district or state
                    $('#chairman_district_label').removeClass('hidden');
                    $('#chairman_state_label').addClass('hidden');
                    $('#district_div').removeClass('hidden');
                    $('#state_div').addClass('hidden');

                    // for thana or province
                    $('#police_station_div_label').removeClass('hidden');
                    $('#province_div_label').addClass('hidden');
                    $('#police_station_div').removeClass('hidden');
                    $('#province_div').addClass('hidden');

                } else {
                    // for district or state
                    $('#chairman_district_label').addClass('hidden');
                    $('#chairman_state_label').removeClass('hidden');
                    $('#district_div').addClass('hidden');
                    $('#state_div').removeClass('hidden');

                    // for thana or province
                    $('#police_station_div_label').addClass('hidden');
                    $('#province_div_label').removeClass('hidden');
                    $('#police_station_div').addClass('hidden');
                    $('#province_div').removeClass('hidden');
                }
            });
            $('#chairman_country').trigger('change');

            // Datepicker initialize
            var today = new Date();
            var yyyy = today.getFullYear();
            $('.datepicker').datetimepicker({
                viewMode: 'years',
                format: 'DD-MMM-YYYY',
                maxDate: (new Date()),
                minDate: '01/01/' + (yyyy - 60)
            });



            @if ($viewMode == 'on')
//            $('#inputForm .btn').not('.show-in-view,.documentUrl').each(function () {
//                $(this).replaceWith("");
//            });
//            $('#inputForm :input').attr('disabled', true);
//            $('#existing_challan_form :input').attr('disabled', true);
//            $('#inputForm :input[type=file]').hide();
//            //        $('#appClearenceForm :input[type=file]').next().hide();
//            $('.addTableRows').attr('disabled', 'true');
//
//            $('#challanFormView :input').attr('disabled', true);
//            $('#challanFormView :input[type=file]').hide();





            $('#inputForm select').each(function (index) {
                var text = $(this).find('option:selected').text();
                var id = $(this).attr("id");
                var val = $(this).val();
                $('#' + id + ' option:selected').replaceWith("<option value='" + val + "' selected>" + text + "</option>");
            });
            $("#inputForm :input[type=text]").each(function (index) {


                $(this).attr("value", $(this).val());

            });
            $("#inputForm textarea").each(function (index) {
                $(this).text($(this).val());
            });
            $("#inputForm select").css({
                "border": "none",
                "background": "#fff",
                "pointer-events": "none",
                "box-shadow": "none",
                "-webkit-appearance": "none",
                "-moz-appearance": "none",
                "appearance": "none"
            });
            $("#inputForm fieldset").css({"display": "block"});
            $("#inputForm #full_same_as_authorized").css({"display": "none"});
            $("#inputForm .actions").css({"display": "none"});
            $("#inputForm .steps").css({"display": "none"});
            $("#inputForm .draft").css({"display": "none"});
            $("#inputForm .title ").css({"display": "none"});
            //document.getElementById("previewDiv").innerHTML = document.getElementById("projectClearanceForm").innerHTML;
            $('#inputForm #showPreview').remove();
            $('#inputForm #save_btn').remove();
            $('#inputForm #save_draft_btn').remove();
            $('#inputForm .stepHeader, #inputForm .calender-icon,#inputForm .hiddenDiv').remove();
            $('#inputForm .required-star').removeClass('required-star');
            $('#inputForm input[type=hidden], #inputForm input[type=file]').remove();
            $('#inputForm .panel-orange > .panel-heading').css('margin-bottom', '10px');
            $('#invalidInst').html('');

            $('#inputForm').find('input[type=checkbox]').each(function () {
                jQuery(this).attr('disabled', 'disabled');
            });

            $('#inputForm').find('input:not(:checkbox),textarea').each(function () {
                if (this.value != '') {
                    var displayOp = ''; //display:block
                } else {
                    var displayOp = 'display:none';
                }

                if ($(this).hasClass("onlyNumber") && !$(this).hasClass("nocomma")) {
                    var thisVal = commaSeparateNumber(this.value);
                    $(this).replaceWith("<span class='onlyNumber " + this.className + "' style='background-color:#ddd !important; height:auto; margin-bottom:2px; padding:6px;"
                        + displayOp + "'>" + thisVal + "</span>");
                } else {
                    if (!$(this).hasClass("sub_category")) {
                        $(this).replaceWith("<span class='" + this.className + "' style='background-color:#ddd; height:auto; margin-bottom:2px; padding:6px;"
                            + displayOp + "'>" + this.value + "</span>");
                    }
                }
            });


            $('#inputForm .btn').not('.show-in-view,.documentUrl').each(function () {
                $(this).replaceWith("");
            });

            $('#acceptTerms-2').attr("onclick", 'return false').prop("checked", true).css('margin-left', '5px');
            $('#inputForm').find('input[type=radio]').each(function () {
                jQuery(this).attr('disabled', 'disabled');
            });


            $("#inputForm select").replaceWith(function () {

                var selectedText = $(this).find('option:selected').text().trim();
                var displayOp = '';
                if (selectedText != '' && selectedText != 'Select One') {
                    displayOp = ''; //display:block
                } else {
                    displayOp = 'display:none';
                }

                return "<span class='" + this.className + "' style='background-color:#ddd;  height:auto; margin-bottom:2px; " +
                    displayOp + "'>" + selectedText + "</span>";
            });
            @endif {{-- viewMode is on --}}




        });

        //--------File Upload Script Start----------//
        function uploadDocument(targets, id, vField, isRequired) {
            var inputFile = $("#" + id).val();
            if (inputFile == '') {
                $("#" + id).html('');
                document.getElementById("isRequired").value = '';
                document.getElementById("selected_file").value = '';
                document.getElementById("validateFieldName").value = '';
                document.getElementById(targets).innerHTML = '<input type="hidden" class="required" value="" id="' + vField + '" name="' + vField + '">';
                if ($('#label_' + id).length)
                    $('#label_' + id).remove();
                return false;
            }

            try {
                document.getElementById("isRequired").value = isRequired;
                document.getElementById("selected_file").value = id;
                document.getElementById("validateFieldName").value = vField;
                document.getElementById(targets).style.color = "red";
                var action = "{{url('general-apps/upload-document')}}";
                $("#" + targets).html('Uploading....');
                var file_data = $("#" + id).prop('files')[0];
                var form_data = new FormData();
                form_data.append('selected_file', id);
                form_data.append('isRequired', isRequired);
                form_data.append('validateFieldName', vField);
                form_data.append('_token', "{{ csrf_token() }}");
                form_data.append(id, file_data);
                $.ajax({
                    target: '#' + targets,
                    url: action,
                    dataType: 'text', // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    type: 'post',
                    success: function (response) {
                        $('#' + targets).html(response);
                        var fileNameArr = inputFile.split("\\");
                        var l = fileNameArr.length;
                        if ($('#label_' + id).length)
                            $('#label_' + id).remove();
                        var doc_id = parseInt(id.substring(4));
                        var newInput = $('<label class="saved_file_' + doc_id + '" id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + ' <a href="javascript:void(0)" onclick="EmptyFile(' + doc_id + ')"><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a></b></label>');
//                        var newInput = $('<label id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + '</b></label>');
                        $("#" + id).after(newInput);
                        //check valid data
                        var validate_field = $('#' + vField).val();
                        if (validate_field == '') {
                            document.getElementById(id).value = '';
                        }
                    }
                });
            } catch (err) {
                document.getElementById(targets).innerHTML = "Sorry! Something Wrong.";
            }
        }

        //--------File Upload Script End----------//


    </script>
    <style>
        .wizard > .actions a, .wizard > .actions a:hover, .wizard > .actions a:active{
            background: #ec971f;
        }
        .wizard > .steps > ul > li:first-child, .wizard > .steps > ul > li:last-child{
            width: 22%;
        }
        .wizard > .steps > ul > li:nth-child(2), .wizard > .steps > ul > li:nth-child(3){
            width: 20%;
        }
        .wizard > .steps > ul > li{
            width: 16%;
        }

    </style>
@endsection