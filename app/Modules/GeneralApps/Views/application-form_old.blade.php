@extends('layouts.admin')
@section('content')

    <style>


        .alert-primary{
            /*color: #ffffff;*/
            /*background-color: #2779B2;*/
            /*border-color: #2779b1;*/
            color: #ffffff;
            background-color: #238000;
            border-color: #238000;
        }
        .panel-form{}
        .panel-form .panel-heading{
            background: #B2DDE7;
        }
        .panel-form .panel-body{
            background: #4B8DD1;
            padding: 10px;
        }
        .panel-form table{
            background: #fff;
            margin-bottom: 10px;
        }
        .panel-form label {
            margin-bottom: 0;
            font-size: 13px;
        }
        .table > tbody > tr > td {
            vertical-align: middle;
        }
        .panel-form .table > tbody > tr:first-child > td:first-child {
            width: 50px;
            text-align: center;
        }
        .panel-form .table > tbody > tr:nth-child(2){

        }
        .panel-form.table-bordered > thead > tr > th, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td {
            /*border: 1px solid #2779b1;*/
            border: 1px solid #FFD5B6;
        }
        .panel-form .btn-warning{
            color: #000;
            font-weight: bold;
        }

    </style>



    <section class="content" id="applicationForm">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    {{--start application form with wizard--}}
                    {!! Session::has('success') ? '<div class="alert alert-info alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
                    {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

                    <div class="panel panel-form" id="inputForm">
                        <div class="panel-heading">
                            <h4 class="text-center"><b>Online application for local industrial permission</b></h4>
                        </div>

                        <div class="panel-body">
                            {!! Form::open(array('url' => '/general-apps/add','method' => 'post','id' => 'ApplicationForm','role'=>'form','enctype'=>'multipart/form-data')) !!}

                            {{--File upload related input field--}}
                            <input type="hidden" name="selected_file" id="selected_file"/>
                            <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                            <input type="hidden" name="isRequired" id="isRequired"/>


                            <div class="  ">
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        <td rowspan="5"><label>1</label></td>
                                    </tr>
                                    <tr class=" ">
                                        <td colspan="6"><label>Organization/Company Information</label></td>
                                    </tr>
                                    <tr>
                                        <td><label for="company_name" class="required-star">Name of Organization/Company</label></td>
                                        <td colspan="5">
                                            {!! Form::text('company_name', '', ['class' => 'form-control input-sm required', 'id' => 'company_name']) !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label>Name of Service</label></td>
                                        <td>Local industrial permission for investor</td>
                                        <td><label for="date_of_submission">Date of Submission</label></td>
                                        <td>
                                            <div class="datepicker input-group date" data-date="12-03-2015" data-date-format="dd-mm-yyyy">
                                                {!! Form::text('date_of_submission', '', ['class'=>'form-control input-sm', 'id' => 'date_of_submission','placeholder' => 'Pick from Calendar']) !!}
                                                <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                            </div>
                                        </td>
                                        <td><label for="date_of_approval">Date of Approval</label></td>
                                        <td>
                                            <div class="datepicker input-group date" data-date="12-03-2015" data-date-format="dd-mm-yyyy">
                                                {!! Form::text('date_of_approval', '', ['class'=>'form-control input-sm', 'id' => 'date_of_approval','placeholder' => 'Pick from Calendar']) !!}
                                                <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label for="tracking_id" class="required-star">Tracking ID</label></td>
                                        <td colspan="2">
                                            {!! Form::text('tracking_id', '', ['class' => 'form-control input-sm required', 'id' => 'tracking_id']) !!}
                                        </td>
                                        <td><label for="company_reg_no" class="required-star">Company Registration No</label></td>
                                        <td colspan="2">
                                            {!! Form::text('company_reg_no', '', ['class' => 'form-control input-sm required', 'id' => 'company_reg_no']) !!}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="  ">
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        <td rowspan="5"><label>2</label></td>
                                    </tr>
                                    <tr class=" ">
                                        <td colspan="8"><label>Office Address</label></td>
                                    </tr>
                                    <tr>
                                        <td><label for="office_district" class="required-star">District</label></td>
                                        <td>
                                            {!! Form::select('office_district', $districtList,'', ['class' => 'form-control input-sm required','placeholder'=>'Select One','id'=>'office_district']) !!}
                                        </td>
                                        <td><label for="officce_police_station" class="required-star">Police Station</label></td>
                                        <td>
                                            {!! Form::select('officce_police_station', [], '', $attributes = array('class'=>'form-control input-sm required',
                                                'placeholder' => 'Select One', 'id'=>"officce_police_station")) !!}
                                        </td>
                                        <td><label for="office_post_office" class="required-star">Post Office</label></td>
                                        <td>
                                            {!! Form::text('office_post_office', '', ['class' => 'form-control input-sm required', 'id' => 'office_post_office']) !!}
                                        </td>
                                        <td><label for="office_post_code" class="required-star">Post Code</label></td>
                                        <td>
                                            {!! Form::text('office_post_code', '', ['class' => 'form-control input-sm required', 'id' => 'office_post_code']) !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label for="office_house_flat_road" class="required-star">House,Flat/Apartment,Road</label></td>
                                        <td colspan="7">
                                            {!! Form::text('office_house_flat_road', '', ['class' => 'form-control input-sm required', 'id' => 'office_house_flat_road']) !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label for="office_telephone">Telephone No</label></td>
                                        <td>
                                            {!! Form::text('office_telephone', '', ['class' => 'form-control input-sm', 'id' => 'office_telephone']) !!}
                                        </td>
                                        <td><label for="office_mobile" class="required-star">Mobile No</label></td>
                                        <td>
                                            {!! Form::text('office_mobile', '', ['class' => 'form-control input-sm required mobile_number_validation', 'id' => 'office_mobile']) !!}
                                        </td>
                                        <td><label for="office_fax">Fax</label></td>
                                        <td>
                                            {!! Form::text('office_fax', '', ['class' => 'form-control input-sm', 'id' => 'office_fax']) !!}
                                        </td>
                                        <td><label for="office_email" class="required-star">Email</label></td>
                                        <td>
                                            {!! Form::text('office_email', '', ['class' => 'form-control input-sm required email', 'id' => 'office_email']) !!}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>


                            <div class="  ">
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        <td rowspan="5"><label>3</label></td>
                                    </tr>
                                    <tr class=" ">
                                        <td colspan="8"><label>Factory Address</label></td>
                                    </tr>
                                    <tr>
                                        <td><label for="factory_district">District</label></td>
                                        <td>
                                            {!! Form::select('factory_district', $districtList,'', ['class' => 'form-control input-sm ','placeholder'=>'Select One','id'=>'factory_district']) !!}
                                        </td>
                                        <td><label for="factory_police_statuion">Police Station</label></td>
                                        <td>
                                            {!! Form::select('factory_police_statuion', [], '', $attributes = array('class'=>'form-control input-sm',
                                                'placeholder' => 'Select One', 'id'=>"factory_police_statuion")) !!}
                                        </td>
                                        <td><label for="factory_post_office">Post Office</label></td>
                                        <td>
                                            {!! Form::text('factory_post_office', '', ['class' => 'form-control input-sm', 'id' => 'factory_post_office']) !!}
                                        </td>
                                        <td><label for="factory_post_code">Post Code</label></td>
                                        <td>
                                            {!! Form::text('factory_post_code', '', ['class' => 'form-control input-sm', 'id' => 'factory_post_code']) !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label for="factory_mouza_no">Mouza No</label></td>
                                        <td colspan="3">
                                            {!! Form::text('factory_mouza_no', '', ['class' => 'form-control input-sm', 'id' => 'factory_mouza_no']) !!}
                                        </td>
                                        <td><label for="factory_house_flat_road">House,Flat/Apartment,Road</label></td>
                                        <td colspan="3">
                                            {!! Form::text('factory_house_flat_road', '', ['class' => 'form-control input-sm', 'id' => 'factory_house_flat_road']) !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label for="factory_telephone">Telephone No</label></td>
                                        <td>
                                            {!! Form::text('factory_telephone', '', ['class' => 'form-control input-sm', 'id' => 'factory_telephone']) !!}
                                        </td>
                                        <td><label for="factory_mobile">Mobile No</label></td>
                                        <td>
                                            {!! Form::text('factory_mobile', '', ['class' => 'form-control input-sm', 'id' => 'factory_mobile']) !!}
                                        </td>
                                        <td><label for="factory_fax">Fax</label></td>
                                        <td>
                                            {!! Form::text('factory_fax', '', ['class' => 'form-control input-sm', 'id' => 'factory_fax']) !!}
                                        </td>
                                        <td><label for="factory_email">Email</label></td>
                                        <td>
                                            {!! Form::text('factory_email', '', ['class' => 'form-control input-sm email', 'id' => 'factory_email']) !!}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="  ">
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        <td><label>4</label></td>
                                        <td><label for="chairman_name">Name of Principal Promoter/Chairman/Managing Director/CEO</label></td>
                                        <td>
                                            {!! Form::text('chairman_name', '', ['class' => 'form-control input-sm', 'id' => 'chairman_name']) !!}
                                        </td>
                                        <td><label for="chairman_designation">Designation</label></td>
                                        <td>
                                            {!! Form::text('chairman_designation', '', ['class' => 'form-control input-sm', 'id' => 'chairman_designation']) !!}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>


                            <div class="  ">
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        <td rowspan="5"><label>5</label></td>
                                    </tr>
                                    <tr class=" ">
                                        <td colspan="8"><label>Address of Principal Promoter/Chairman/Managing Director/CEO</label></td>
                                    </tr>
                                    <tr>
                                        <td><label for="chairman_country">Country</label></td>
                                        <td>
                                            {!! Form::select('chairman_country', $countries,'', ['class' => 'form-control input-sm ','placeholder'=>'Select One','id'=>'chairman_country']) !!}
                                        </td>
                                        <td>
                                            <label class="hidden" for="chairman_district" id="chairman_district_label">District</label>
                                            <label class="" for="chairman_state" id="chairman_state_label">City/State</label>
                                        </td>
                                        <td>
                                            <div class="hidden" id="district_div">
                                                {!! Form::select('chairman_district',$districtList,'', ['class' => 'form-control input-sm ','placeholder'=>'Select One','id'=>'chairman_district']) !!}
                                            </div>

                                            <div class="" id="state_div">
                                                {!! Form::text('chairman_state', '', $attributes = array('class'=>'form-control input-sm','id'=>"chairman_state")) !!}
                                            </div>
                                        </td>
                                        <td>
                                            <label class="hidden" for="chairman_police_station" id="police_station_div_label">Police Station</label>
                                            <label for="chairman_province" id="province_div_label">Province</label>
                                        </td>
                                        <td>
                                            <div class="hidden" id="police_station_div">
                                                {!! Form::select('chairman_police_station', [], '', $attributes = array('class'=>'form-control input-sm',
                                                'placeholder' => 'Select One', 'id'=>"chairman_police_station")) !!}
                                            </div>

                                            <div class="" id="province_div">
                                                {!! Form::text('chairman_province', '', ['class' => 'form-control input-sm', 'id' => 'chairman_province']) !!}
                                            </div>
                                        </td>
                                        <td><label for="chairman_post_code">Post/Zip Code</label></td>
                                        <td>
                                            {!! Form::text('chairman_post_code', '', ['class' => 'form-control input-sm', 'id' => 'chairman_post_code']) !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label for="chairman_house_flat_road">House,Flat/Apartment,Road</label></td>
                                        <td colspan="7">
                                            {!! Form::text('chairman_house_flat_road', '', ['class' => 'form-control input-sm', 'id' => 'chairman_house_flat_road']) !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label for="chairman_telephone">Telephone No</label></td>
                                        <td>
                                            {!! Form::text('chairman_telephone', '', ['class' => 'form-control input-sm', 'id' => 'chairman_telephone']) !!}
                                        </td>
                                        <td><label for="chairman_mobile">Mobile No</label></td>
                                        <td>
                                            {!! Form::text('chairman_mobile', '', ['class' => 'form-control input-sm', 'id' => 'chairman_mobile']) !!}
                                        </td>
                                        <td><label for="chairman_fax">Fax</label></td>
                                        <td>
                                            {!! Form::text('chairman_fax', '', ['class' => 'form-control input-sm', 'id' => 'chairman_fax']) !!}
                                        </td>
                                        <td><label for="chairman_email">Email</label></td>
                                        <td>
                                            {!! Form::text('chairman_email', '', ['class' => 'form-control input-sm email', 'id' => 'chairman_email']) !!}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="  ">
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        <td><label>6</label></td>
                                        <td><label for="industry_type">Type of Industry (following International Standard of Industrial Classification)</label></td>
                                        <td>
                                            {!! Form::select('industry_type', $typeofIndustry,'', ['class' => 'form-control input-sm ','placeholder'=>'Select One','id'=>'industry_type']) !!}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="  ">
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        <td rowspan="5"><label>7</label></td>
                                    </tr>
                                    <tr class=" ">
                                        <td colspan="8"><label>Manpower of the organization</label></td>
                                    </tr>
                                    <tr class="text-center">
                                        <td colspan="3"><label>Local (Bangladesh Only)</label></td>
                                        <td colspan="3"><label>Foreign (Abroad Country)</label></td>
                                        <td colspan="2"><label>Ratio</label></td>
                                    </tr>
                                    <tr class="text-center">
                                        <td><label for="local_executive">Executive</label></td>
                                        <td><label for="local_supporting_staff">Supporting Staff</label></td>
                                        <td><label for="local_total">Total</label></td>
                                        <td><label for="foreign_executive">Executive</label></td>
                                        <td><label for="foreign_supporting_staff">Supporting Staff</label></td>
                                        <td><label for="foreign_total">Total</label></td>
                                        <td><label for="ratio_local">Local</label></td>
                                        <td><label for="ratio_foreign">Foreign</label></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {!! Form::text('local_executive', '', ['class' => 'form-control input-sm', 'id' => 'local_executive']) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('local_supporting_staff', '', ['class' => 'form-control input-sm', 'id' => 'local_supporting_staff']) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('local_total', '', ['class' => 'form-control input-sm', 'id' => 'local_total']) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('foreign_executive', '', ['class' => 'form-control input-sm', 'id' => 'foreign_executive']) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('foreign_supporting_staff', '', ['class' => 'form-control input-sm', 'id' => 'foreign_supporting_staff']) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('foreign_total', '', ['class' => 'form-control input-sm', 'id' => 'foreign_total']) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('ratio_local', '', ['class' => 'form-control input-sm', 'id' => 'ratio_local']) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('ratio_foreign', '', ['class' => 'form-control input-sm', 'id' => 'ratio_foreign']) !!}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="  ">
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        <td rowspan="5"><label>8</label></td>
                                    </tr>
                                    <tr class=" ">
                                        <td colspan="9"><label>Public Utility Service Required</label></td>
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
                                            {!! Form::text('electricity', '', ['class' => 'form-control input-sm', 'id' => 'electricity']) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('gas', '', ['class' => 'form-control input-sm', 'id' => 'gas']) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('telephone', '', ['class' => 'form-control input-sm', 'id' => 'telephone']) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('road', '', ['class' => 'form-control input-sm', 'id' => 'road']) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('water', '', ['class' => 'form-control input-sm', 'id' => 'water']) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('drainage', '', ['class' => 'form-control input-sm', 'id' => 'drainage']) !!}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>


                            <div class="  ">
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        <td><label>9</label></td>
                                        <td><label for="tin_no">	Tax Identification number of the Company</label></td>
                                        <td>
                                            {!! Form::text('tin_no', '', ['class' => 'form-control input-sm', 'id' => 'tin_no']) !!}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="  ">
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        <td rowspan="10"><label>10</label></td>
                                    </tr>
                                    <tr class=" ">
                                        <td colspan="6"><label>Necessary Documents to be attached here (Only PDF file to be attach here)</label></td>
                                    </tr>
                                    <tr>
                                        <td><label for="incorporation_certificate" class="required-star">1. Certificate of Incorporation</label> </td>
                                        <td>
                                            {{--{!! Form::file('incorporation_certificate', ['class'=>'form-control input-sm', 'id' => 'incorporation_certificate','onchange'=>'function(this)'])!!}--}}
                                            <input name="file10"
                                                   id="incorporation_certificate" type="file"
                                                   size="20" class="input-sm required"
                                                   onchange="uploadDocument('preview_10', this.id, 'incorporation_certificate', '0')"/>
                                            <div id="preview_10">
                                                <input type="hidden" class="required"
                                                       id="incorporation_certificate"
                                                       name="incorporation_certificate"/>
                                            </div>

                                        </td>
                                        <td><button class="btn btn-sm btn-success"><b><i class="fa fa-folder-open-o"></i> Open</b></button> </td>
                                        <td><button class="btn btn-sm btn-primary"><b><i class="fa fa-download"></i> Download</b></button> </td>
                                        <td><span class="text-danger" id="alert_10"
                                                  style="font-size: 9px; font-weight: bold;display:block; color: green; ">[File Format: *.pdf | File size within 3 MB]</span>Maximum 1 MB</td>
                                    </tr>
                                    <tr>
                                        <td><label for="memorandum_articles">2. Memorandum & Articales of Association</label> </td>
                                        <td>
{{--                                            {!! Form::file('memorandum_articles', ['class'=>'form-control input-sm', 'id' => 'memorandum_articles','onchange'=>'function(this)'])!!}--}}
                                            <input name="file20"
                                                   id="memorandum_articles" type="file"
                                                   size="20" class="input-sm"
                                                   onchange="uploadDocument('preview_20', this.id, 'memorandum_articles', '0')"/>
                                            <div id="preview_20">
                                                <input type="hidden" class="required"
                                                       id="memorandum_articles"
                                                       name="memorandum_articles"/>
                                            </div>
                                        </td>
                                        <td><button class="btn btn-sm btn-success"><b><i class="fa fa-folder-open-o"></i> Open</b></button> </td>
                                        <td><button class="btn btn-sm btn-primary"><b><i class="fa fa-download"></i> Download</b></button> </td>
                                        <td>Maximum 1 MB</td>
                                    </tr>
                                    <tr>
                                        <td><label for="joint_agreement">3. Joint venture agreement dully signed by the both parties</label> </td>
                                        <td>
                                            {{--{!! Form::file('joint_agreement', ['class'=>'form-control input-sm', 'id' => 'joint_agreement','onchange'=>'function(this)'])!!}--}}
                                            <input name="file30"
                                                   id="joint_agreement" type="file"
                                                   size="20" class="input-sm"
                                                   onchange="uploadDocument('preview_30', this.id, 'joint_agreement', '0')"/>
                                            <div id="preview_30">
                                                <input type="hidden" class="required"
                                                       id="joint_agreement"
                                                       name="joint_agreement"/>
                                            </div>
                                        </td>
                                        <td><button class="btn btn-sm btn-success"><b><i class="fa fa-folder-open-o"></i> Open</b></button> </td>
                                        <td><button class="btn btn-sm btn-primary"><b><i class="fa fa-download"></i> Download</b></button> </td>
                                        <td>Maximum 1 MB</td>
                                    </tr>
                                    <tr>
                                        <td><label for="shareholder_list">4. List of Shareholder/Directors</label> </td>
                                        <td>
                                            {{--{!! Form::file('shareholder_list', ['class'=>'form-control input-sm', 'id' => 'shareholder_list','onchange'=>'function(this)'])!!}--}}
                                            <input name="file40"
                                                   id="shareholder_list" type="file"
                                                   size="20" class="input-sm"
                                                   onchange="uploadDocument('preview_40', this.id, 'shareholder_list', '0')"/>
                                            <div id="preview_40">
                                                <input type="hidden" class="required"
                                                       id="shareholder_list"
                                                       name="shareholder_list"/>
                                            </div>
                                        </td>
                                        <td><button class="btn btn-sm btn-success"><b><i class="fa fa-folder-open-o"></i> Open</b></button> </td>
                                        <td><button class="btn btn-sm btn-primary"><b><i class="fa fa-download"></i> Download</b></button> </td>
                                        <td>Maximum 10 MB</td>
                                    </tr>
                                    <tr>
                                        <td><label for="trade_license">5. Trade License</label> </td>
                                        <td>
                                            {{--{!! Form::file('trade_license', ['class'=>'form-control input-sm', 'id' => 'trade_license','onchange'=>'function(this)'])!!}--}}
                                            <input name="file50"
                                                   id="trade_license" type="file"
                                                   size="20" class="input-sm"
                                                   onchange="uploadDocument('preview_50', this.id, 'trade_license', '0')"/>
                                            <div id="preview_50">
                                                <input type="hidden" class="required"
                                                       id="trade_license"
                                                       name="trade_license"/>
                                            </div>
                                        </td>
                                        <td><button class="btn btn-sm btn-success"><b><i class="fa fa-folder-open-o"></i> Open</b></button> </td>
                                        <td><button class="btn btn-sm btn-primary"><b><i class="fa fa-download"></i> Download</b></button> </td>
                                        <td>Maximum 1 MB</td>
                                    </tr>
                                    <tr>
                                        <td><label for="tin_certificate">6. TIN Certificate</label> </td>
                                        <td>
                                            {{--{!! Form::file('tin_certificate', ['class'=>'form-control input-sm', 'id' => 'tin_certificate','onchange'=>'function(this)'])!!}--}}
                                            <input name="file60"
                                                   id="tin_certificate" type="file"
                                                   size="20" class="input-sm"
                                                   onchange="uploadDocument('preview_60', this.id, 'tin_certificate', '0')"/>
                                            <div id="preview_60">
                                                <input type="hidden" class="required"
                                                       id="tin_certificate"
                                                       name="tin_certificate"/>
                                            </div>
                                        </td>
                                        <td><button class="btn btn-sm btn-success"><b><i class="fa fa-folder-open-o"></i> Open</b></button> </td>
                                        <td><button class="btn btn-sm btn-primary"><b><i class="fa fa-download"></i> Download</b></button> </td>
                                        <td>Maximum 1 MB</td>
                                    </tr>
                                    <tr>
                                        <td><label for="project_profile">7. Project profile</label> </td>
                                        <td>
                                            {{--{!! Form::file('project_profile', ['class'=>'form-control input-sm', 'id' => 'project_profile','onchange'=>'function(this)'])!!}--}}
                                            <input name="file70"
                                                   id="project_profile" type="file"
                                                   size="20" class="input-sm"
                                                   onchange="uploadDocument('preview_70', this.id, 'project_profile', '0')"/>
                                            <div id="preview_70">
                                                <input type="hidden" class="required"
                                                       id="project_profile"
                                                       name="project_profile"/>
                                            </div>
                                        </td>
                                        <td><button class="btn btn-sm btn-success"><b><i class="fa fa-folder-open-o"></i> Open</b></button> </td>
                                        <td><button class="btn btn-sm btn-primary"><b><i class="fa fa-download"></i> Download</b></button> </td>
                                        <td>Maximum 15 MB</td>
                                    </tr>
                                    <tr class="text-center">
                                        <td colspan="6">
                                            <b style="color: #e20505;"><small>All submitted documents other then original must be attached by Chairman/Managing Director/Principle Promoter/Managing Partner of the company</small></b>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>


                            <div class="  ">
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        <td rowspan="9"><label>11</label></td>
                                    </tr>
                                    <tr class="text-center  ">
                                        <td colspan="4"><label>Declaration</label></td>
                                    </tr>
                                    <tr class="text-center">
                                        <td colspan="4">
                                            <b style="color: #e20505;"><small>I do here by declare that the information given above is true to the best of my knowledge and I shall be liable for any false information/system is given.</small> </b>
                                        </td>
                                    </tr>
                                    <tr class="text-center">
                                        <td colspan="4">
                                            <label>Authorized Personnel of the Orginazation  </label>
                                        </td>
                                    </tr>

                                    <tr class="text-center">
                                        <td class="text-right"><label for="authorized_name">Full Name  </label></td>
                                        <td colspan="3">
                                            {!! Form::text('authorized_name', '', ['class' => 'form-control input-sm', 'id' => 'authorized_name']) !!}
                                        </td>
                                    </tr>
                                    <tr class="text-center">
                                        <td class="text-right"><label for="authorized_address">Address  </label></td>
                                        <td colspan="3">
                                            {!! Form::text('authorized_address', '', ['class' => 'form-control input-sm', 'id' => 'authorized_address']) !!}
                                        </td>
                                    </tr>
                                    <tr class="text-center">
                                        <td class="text-right"><label for="authorized_email">E-mail	  </label></td>
                                        <td colspan="3">
                                            {!! Form::text('authorized_email', '', ['class' => 'form-control input-sm email', 'id' => 'authorized_email']) !!}
                                        </td>
                                    </tr>
                                    <tr class="text-center">
                                        <td class="text-right"><label for="authorized_mobile">Mobile No	  </label></td>
                                        <td colspan="3">
                                            {!! Form::text('authorized_mobile', '', ['class' => 'form-control input-sm', 'id' => 'authorized_mobile']) !!}
                                        </td>
                                    </tr>
                                    <tr class="text-center">
                                        <td class="text-right"><label for="letter_of_authorization">Letter of Authorization  </label></td>
                                        <td class="text-left">
                                            {{--{!! Form::file('letter_of_authorization', ['class'=>'form-control input-sm', 'id' => 'letter_of_authorization','onchange'=>'function(this)'])!!}--}}
                                            <input name="file80"
                                                   id="letter_of_authorization" type="file"
                                                   size="20" class="input-sm required"
                                                   onchange="uploadDocument('preview_80', this.id, 'letter_of_authorization', '0')"/>
                                            <div id="preview_80">
                                                <input type="hidden" class="required"
                                                       id="letter_of_authorization"
                                                       name="letter_of_authorization"/>
                                            </div>
                                        </td>
                                        <td><button class="btn btn-sm btn-success"><b><i class="fa fa-folder-open-o"></i> Open</b></button> </td>
                                        <td><button class="btn btn-sm btn-primary"><b><i class="fa fa-download"></i> Download</b></button> </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>


                            <div class="pull-left">
                                <button type="submit" class="btn btn-warning cancel" value="draft" name="actionBtn">Save as Draft</button>
                            </div>
                            <div class="pull-right">
                                <button type="submit" class="btn btn-warning btn-block" value="save" name="actionBtn">Submit</button>
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                    {{--End application form with wizard--}}
                </div>
            </div>
        </div>
        </div>
        </div>
    </section>

@endsection


@section('footer-script')

    <script>
        $(document).ready(function () {

            // Form Validate initialize
            $("#ApplicationForm").validate();


            // Datepicker initialize
            var today = new Date();
            var yyyy = today.getFullYear();
            $('.datepicker').datetimepicker({
                viewMode: 'years',
                format: 'DD-MMM-YYYY',
                maxDate: (new Date()),
                minDate: '01/01/' + (yyyy - 60)
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
            $("#office_district").trigger('change');



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
            $("#factory_district").trigger('change');



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
            $("#chairman_district").trigger('change');


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
                if ($('#label_' + id).length) $('#label_' + id).remove();
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
                        var newInput = $('<label style="padding: 5px" id="label_' + id + '"><b><i class="fa fa-file-pdf-o"></i> ' + fileNameArr[l - 1].substring(0, 20) + '... </b></label>');
                        $("#" + id).after(newInput);
                        console.log(id);

                        //====New Code===//
                        //var span = document.getElementById('text-danger');
                        //$(span).after(newInput);

                        //check valid data
                        var validate_field = $('#' + vField).val();
                        if (validate_field == '') {
                            document.getElementById(id).value = '';
                        }
                    }
                });
            } catch (err) {
                document.getElementById(targets).innerHTML = "Sorry! Something went wrong... Please try again.";
            }
        }
        //--------File Upload Script End----------//

    </script>

@endsection