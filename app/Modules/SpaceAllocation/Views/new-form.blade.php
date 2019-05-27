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
                            <section class="content-header">
                                <ol class="breadcrumb">
                                    <li><strong>Tracking no. : </strong>SA091020170000</li>
                                    <li><strong> Date of Submission: </strong> 09.10.2017 </li>
                                    <li><strong>Current Status : </strong>
                                        Completed
                                    </li>
                                    <li>
                                        <strong>Current Desk :</strong> Applicant
                                    </li>

                                    <li>
                                        <a href="http://localhost:8000/uploads/2017/10/BHTP_10_59db17d7d2c1f6.82791394.pdf" class="btn show-in-view btn-xs btn-info" title="Download Approval Letter" target="_blank"> <i class="fa  fa-file-pdf-o"></i> <b>Download Certificate</b></a>
                                        <a onclick="return confirm('Are you sure ?')" href="/space-allocation/discard-certificate/xjcIiwR_uIlCg6TUOwGXok1zD7C5LkHDz0dRng-QFE0" class="btn show-in-view btn-xs btn-danger" title="Download Approval Letter"> <i class="fa  fa-trash"></i> <b>Discard Certificate</b></a>
                                        <a href="/space-allocation/project-cer-re-gen/xjcIiwR_uIlCg6TUOwGXok1zD7C5LkHDz0dRng-QFE0" class="btn show-in-view btn-xs btn-warning" title="Download Approval Letter" target="_self"> <i class="fa  fa-file-pdf-o"></i> <b>Re-generate certificate</b></a>
                                    </li>
                                </ol>
                            </section>


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
                                        <td><label for="company_name">Name of Organization/Company</label></td>
                                        <td colspan="5">
                                            {!! Form::text('company_name', '', ['class' => 'form-control input-sm', 'id' => 'company_name']) !!}
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
                                        <td><label for="tracking_id">Tracking ID</label></td>
                                        <td colspan="2">
                                            {!! Form::text('tracking_id', '', ['class' => 'form-control input-sm', 'id' => 'tracking_id']) !!}
                                        </td>
                                        <td><label for="company_reg_no">Company Registration No</label></td>
                                        <td colspan="2">
                                            {!! Form::text('company_reg_no', '', ['class' => 'form-control input-sm', 'id' => 'company_reg_no']) !!}
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
                                        <td><label for="office_district">District</label></td>
                                        <td>
                                            {!! Form::select('office_district',['' => 'Select One'],'', ['class' => 'form-control input-sm ','placeholder'=>'Select One','id'=>'office_district']) !!}
                                        </td>
                                        <td><label for="officce_police_station">Police Station</label></td>
                                        <td>
                                            {!! Form::text('officce_police_station', '', ['class' => 'form-control input-sm', 'id' => 'officce_police_station']) !!}
                                        </td>
                                        <td><label for="office_post_office">Post Office</label></td>
                                        <td>
                                            {!! Form::text('office_post_office', '', ['class' => 'form-control input-sm', 'id' => 'office_post_office']) !!}
                                        </td>
                                        <td><label for="office_post_code">Post Code</label></td>
                                        <td>
                                            {!! Form::text('office_post_code', '', ['class' => 'form-control input-sm', 'id' => 'office_post_code']) !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label for="office_house_flat_road">House,Flat/Apartment,Road</label></td>
                                        <td colspan="7">
                                            {!! Form::text('office_house_flat_road', '', ['class' => 'form-control input-sm', 'id' => 'office_house_flat_road']) !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label for="office_telephone">Telephone No</label></td>
                                        <td>
                                            {!! Form::text('office_telephone', '', ['class' => 'form-control input-sm', 'id' => 'office_telephone']) !!}
                                        </td>
                                        <td><label for="office_mobile">Mobile No</label></td>
                                        <td>
                                            {!! Form::text('office_mobile', '', ['class' => 'form-control input-sm', 'id' => 'office_mobile']) !!}
                                        </td>
                                        <td><label for="office_fax">Fax</label></td>
                                        <td>
                                            {!! Form::text('office_fax', '', ['class' => 'form-control input-sm', 'id' => 'office_fax']) !!}
                                        </td>
                                        <td><label for="office_email">Email</label></td>
                                        <td>
                                            {!! Form::text('office_email', '', ['class' => 'form-control input-sm', 'id' => 'office_email']) !!}
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
                                            {!! Form::select('factory_district',['' => 'Select One'],'', ['class' => 'form-control input-sm ','placeholder'=>'Select One','id'=>'factory_district']) !!}
                                        </td>
                                        <td><label for="factory_police_statuion">Police Station</label></td>
                                        <td>
                                            {!! Form::text('factory_police_statuion', '', ['class' => 'form-control input-sm', 'id' => 'factory_police_statuion']) !!}
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
                                            {!! Form::text('factory_email', '', ['class' => 'form-control input-sm', 'id' => 'factory_email']) !!}
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
                                            {!! Form::select('chairman_country',['' => 'Select One'],'', ['class' => 'form-control input-sm ','placeholder'=>'Select One','id'=>'chairman_country']) !!}
                                        </td>
                                        <td><label for="chairman_district">District/City/State</label></td>
                                        <td>
                                            {!! Form::select('chairman_district',['' => 'Select One'],'', ['class' => 'form-control input-sm ','placeholder'=>'Select One','id'=>'chairman_district']) !!}
                                        </td>
                                        <td><label for="chairman_police_station">Police Station/Town</label></td>
                                        <td>
                                            {!! Form::text('chairman_police_station', '', ['class' => 'form-control input-sm', 'id' => 'chairman_police_station']) !!}
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
                                            {!! Form::text('chairman_email', '', ['class' => 'form-control input-sm', 'id' => 'chairman_email']) !!}
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
                                            {!! Form::select('industry_type',['' => 'Select One'],'', ['class' => 'form-control input-sm ','placeholder'=>'Select One','id'=>'industry_type']) !!}
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
                                        <td><label for="incorporation_certificate">1. Certificate of Incorporation</label> </td>
                                        <td>
                                            {!! Form::file('incorporation_certificate', ['class'=>'form-control input-sm', 'id' => 'incorporation_certificate','onchange'=>'function(this)'])!!}
                                        </td>
                                        <td><button class="btn btn-sm btn-success"><b><i class="fa fa-folder-open-o"></i> Open</b></button> </td>
                                        <td><button class="btn btn-sm btn-primary"><b><i class="fa fa-download"></i> Download</b></button> </td>
                                        <td>Maximum 1 MB</td>
                                    </tr>
                                    <tr>
                                        <td><label for="memorandum_articles">2. Memorandum & Articales of Association</label> </td>
                                        <td>
                                            {!! Form::file('memorandum_articles', ['class'=>'form-control input-sm', 'id' => 'memorandum_articles','onchange'=>'function(this)'])!!}
                                        </td>
                                        <td><button class="btn btn-sm btn-success"><b><i class="fa fa-folder-open-o"></i> Open</b></button> </td>
                                        <td><button class="btn btn-sm btn-primary"><b><i class="fa fa-download"></i> Download</b></button> </td>
                                        <td>Maximum 1 MB</td>
                                    </tr>
                                    <tr>
                                        <td><label for="joint_agreement">3. Joint venture agreement dully signed by the both parties</label> </td>
                                        <td>
                                            {!! Form::file('joint_agreement', ['class'=>'form-control input-sm', 'id' => 'joint_agreement','onchange'=>'function(this)'])!!}
                                        </td>
                                        <td><button class="btn btn-sm btn-success"><b><i class="fa fa-folder-open-o"></i> Open</b></button> </td>
                                        <td><button class="btn btn-sm btn-primary"><b><i class="fa fa-download"></i> Download</b></button> </td>
                                        <td>Maximum 1 MB</td>
                                    </tr>
                                    <tr>
                                        <td><label for="shareholder_list">4. List of Shareholder/Directors</label> </td>
                                        <td>
                                            {!! Form::file('shareholder_list', ['class'=>'form-control input-sm', 'id' => 'shareholder_list','onchange'=>'function(this)'])!!}
                                        </td>
                                        <td><button class="btn btn-sm btn-success"><b><i class="fa fa-folder-open-o"></i> Open</b></button> </td>
                                        <td><button class="btn btn-sm btn-primary"><b><i class="fa fa-download"></i> Download</b></button> </td>
                                        <td>Maximum 10 MB</td>
                                    </tr>
                                    <tr>
                                        <td><label for="trade_license">5. Trade License</label> </td>
                                        <td>
                                            {!! Form::file('trade_license', ['class'=>'form-control input-sm', 'id' => 'trade_license','onchange'=>'function(this)'])!!}
                                        </td>
                                        <td><button class="btn btn-sm btn-success"><b><i class="fa fa-folder-open-o"></i> Open</b></button> </td>
                                        <td><button class="btn btn-sm btn-primary"><b><i class="fa fa-download"></i> Download</b></button> </td>
                                        <td>Maximum 1 MB</td>
                                    </tr>
                                    <tr>
                                        <td><label for="tin_certificate">6. TIN Certificate</label> </td>
                                        <td>
                                            {!! Form::file('tin_certificate', ['class'=>'form-control input-sm', 'id' => 'tin_certificate','onchange'=>'function(this)'])!!}
                                        </td>
                                        <td><button class="btn btn-sm btn-success"><b><i class="fa fa-folder-open-o"></i> Open</b></button> </td>
                                        <td><button class="btn btn-sm btn-primary"><b><i class="fa fa-download"></i> Download</b></button> </td>
                                        <td>Maximum 1 MB</td>
                                    </tr>
                                    <tr>
                                        <td><label for="project_profile">7. Project profile</label> </td>
                                        <td>
                                            {!! Form::file('project_profile', ['class'=>'form-control input-sm', 'id' => 'project_profile','onchange'=>'function(this)'])!!}
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
                                            {!! Form::text('authorized_email', '', ['class' => 'form-control input-sm', 'id' => 'authorized_email']) !!}
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
                                        <td>
                                            {!! Form::file('letter_of_authorization', ['class'=>'form-control input-sm', 'id' => 'letter_of_authorization','onchange'=>'function(this)'])!!}
                                        </td>
                                        <td><button class="btn btn-sm btn-success"><b><i class="fa fa-folder-open-o"></i> Open</b></button> </td>
                                        <td><button class="btn btn-sm btn-primary"><b><i class="fa fa-download"></i> Download</b></button> </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>


                            <div class="pull-left">
                                <button class="btn btn-warning btn-block"><b>Save as Draft</b></button>
                            </div>
                            <div class="pull-right">
                                <button class="btn btn-warning btn-block"><b>Submit</b></button>
                            </div>
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
            var today = new Date();
            var yyyy = today.getFullYear();
            $('.datepicker').datetimepicker({
                viewMode: 'years',
                format: 'DD-MMM-YYYY',
                maxDate: (new Date()),
                minDate: '01/01/' + (yyyy - 60)
            });
        });
    </script>

@endsection