<?php
use App\Libraries\UtilFunction;
use App\Libraries\CommonFunction;
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="panel-title">
            Passport Change Verification Form of Tracking No: {!! $json_object->tracking_no !!}
        </div>
    </div>
    <div class="panel-body">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">
                        Old Passport Information
                    </div>
                </div>
                <div class="panel-body">
                    <div class="col-md-12">
                        {!! Form::label('title','Passport No: ',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">{{ $json_object->old_pass_no }}</div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('title','Passport Issue Date: ',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">{{ CommonFunction::changeDateFormat($json_object->old_pass_issue_date) }}</div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('title','Passport Exp Date: ',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">{{ CommonFunction::changeDateFormat($json_object->old_pass_exp_date) }}</div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('title','Issue Place: ',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">{{ $json_object->old_pass_issue_place }}</div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('title','Passport Type: ',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">{{ $json_object->old_pass_type }}</div>
                    </div>
                    <div class="col-md-12">
                        <h5><b>স্থায়ী ঠিকানা (পাসপোর্ট অনুযায়ী):</b> </h5>
                        <hr/>
                    </div>

                    <div class="col-md-12">
                        {!! Form::label('title','Post Code: ',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">{{ $json_object->old_pass_post_code }}</div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('title','Passport Village: ',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">{{ $json_object->old_pass_village }}</div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('title','Passport Thana: ',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">{{ UtilFunction::areaIdToNameConvert($json_object->old_pass_thana) }}</div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('title','Passport District: ',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">{{ UtilFunction::areaIdToNameConvert($json_object->old_pass_district) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">
                        Requested Passport Information
                    </div>
                </div>
                <div class="panel-body">
                    <div class="col-md-12">
                        {!! Form::label('title','Passport No: ',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">{{ $json_object->new_passport_no }}</div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('title','Passport Issue Date: ',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">{{ CommonFunction::changeDateFormat($json_object->new_pass_issue_date) }}</div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('title','Passport Exp Date: ',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">{{ CommonFunction::changeDateFormat($json_object->new_pass_exp_date) }}</div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('title','Issue Place: ',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">{{ $json_object->new_pass_issue_place }}</div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('title','Passport Type: ',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">{{ $json_object->new_pass_type }}</div>
                    </div>
                    <div class="col-md-12">
                        <h5><b>স্থায়ী ঠিকানা (পাসপোর্ট অনুযায়ী):</b> </h5>
                        <hr/>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('title','Post Code: ',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">{{ $json_object->new_pass_post_code }}</div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('title','Passport Village: ',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">{{ $json_object->new_pass_village }}</div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('title','Passport Thana: ',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">{{ UtilFunction::areaIdToNameConvert($json_object->new_pass_thana) }}</div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('title','Passport District: ',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">{{ UtilFunction::areaIdToNameConvert($json_object->new_pass_district) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <?php
        $passport_copy = DB::table(env('HMIS_DB').'.img_passport_update_file')->where('pilgrim_id',$json_object->pilgrim_id)->pluck('details');
        ?>
        <div class="panel panel-green">
            <div class="panel-body">
                <div class="col-md-6 col-md-offset-3">
                    <div class="magnify">
                        <div class="large" style="background:url('{{ $passport_copy }}') no-repeat;"></div>
                        <img class="small img-responsive" src="{{ $passport_copy }}">
                    </div>
                    <br/>
                    <div class="text-center">
                        <a href="{{ $passport_copy }}" target="_blank" class="btn btn-sm btn-default"><i class="fa fa-file-text-o"></i> View Passport </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>