@extends('layouts.admin')


@section('content')
<?php
$accessMode = ACL::getAccsessRight('report');
if (!ACL::isAllowed($accessMode, 'V'))
    die('no access right!');
?>

<div class="col-lg-12">

    {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
    {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

    <div class="panel panel-primary">
        <div class="panel-heading">
           <b> {!!trans('messages.report_form')!!}</b>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            {!! Form::open(['url' => '/reports/store', 'method' => 'patch', 'class' => 'form report_form', 'role' => 'form', 'id' => 'report_form']) !!}
<div class="col-sm-8">
            <!-- text input -->
            <div class="form-group {{$errors->has('report_title') ? 'has-error' : ''}}">
                {!! Form::label('report_title','Report Title') !!}
                {!! Form::text('report_title','',['class'=>'form-control bnEng required report_title','placeholder'=>'Enter report name...']) !!}
                {!! $errors->first('report_title','<span class="help-block">:message</span>') !!}
            </div>

            <!-- radio -->
            <div class="form-group">
                <div class="radio">
                    <label>
                        {!! Form::radio('status', '1', true) !!}
                        Publish
                    </label>
                    <label>
                        {!! Form::radio('status', '0') !!}
                        Unpublished
                    </label>
                </div>
            </div>
</div>
<div class="col-sm-4">
    <!-- select -->
            <div class="form-group {{$errors->has('user_id') ? 'has-error' : ''}}">
                {!! Form::label('user_id','Who can view?') !!}
                {!! Form::select('user_id[]', $usersList, '',['class' => 'form-control required user_id','multiple']) !!}
                {!! $errors->first('user_id','<span class="help-block">:message</span>') !!}
            </div>
</div>

            {!! Form::hidden('redirect_to_new',0,['class'=>'form-control redirect_to_new']) !!}

<div class="col-sm-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#tab_1" aria-expanded="true"><i class="fa fa-code"></i> SQL</a></li>
                    <li class="results_tab"><a data-toggle="tab" href="#tab_2" aria-expanded="false"><i class="fa fa-table"></i> Results</a></li>
                    <li class="db_tables"><a data-toggle="tab" href="#tab_3" aria-expanded="false"><i class="fa fa-university"></i> Database objects</a></li>
                    <li class="help"><a data-toggle="tab" href="#tab_4" aria-expanded="false"><i class="fa fa-question-circle"></i> Help</a></li>
                </ul>
                <div class="tab-content">
                    <div id="tab_1" class="tab-pane active">
                        <!-- textarea -->
                        <div class="form-group {{$errors->has('report_para1') ? 'has-error' : ''}}">
                            {!! Form::textarea('report_para1', '', ['class'=>'sql required form-control well fa-code-fork']) !!}
                            {!! $errors->first('report_para1','<span class="help-block">:message</span>') !!}
                        </div>
                    </div><!-- /.tab-pane -->

                    <div id="tab_2" class="tab-pane">
                        <div class="results">
                            <br />
                            <br />
                            Please click on Verify button to run the SQL.
                            <br />
                            <br />
                            <br />
                        </div>
                    </div><!-- /.tab-pane -->
                    <div id="tab_3" class="tab-pane">
                        <div class="db_fields">
                            <br />
                            Please click on Show Tables button to run the SQL.
                            <br />
                            you can run SELECT statement to generate a report.
                            <br />
                            SysAdmin can execute SHOW, DESC, EXPLAIN statement!
                            <br />
                            <br />
                            <br />
                        </div>
                    </div><!-- /.tab-pane -->
                    <div id="tab_4" class="tab-pane">
                        <div class="help">
                            @include('Reports::help')
                        </div>
                    </div><!-- /.tab-pane -->
                </div><!-- /.tab-content -->
            </div>
</div>

            <div class="form-group">
                @if(ACL::getAccsessRight('report','A'))
                {!! Form::button('<i class="fa fa-save"></i> Save', array('type' => 'button', 'value'=> 'save', 'class' => 'btn btn-success save')) !!}
                {!! Form::button('<i class="fa fa-credit-card"></i> Save & Run', array('type' => 'button', 'value'=> 'save_new', 'class' => 'btn btn-warning save')) !!}
                @endif
                <a href="/reports">{!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-danger')) !!}</a>

                <span class="pull-right">
                    @if(ACL::getAccsessRight('report','E'))
                    <button class="btn btn-sm btn-primary" id="verifyBtn" type="button">
                        <i class="fa fa-check"></i>
                        Verify
                    </button>
                    <button class="btn btn-sm btn-primary" id="showTables" type="button">
                        <i class="fa fa-list"></i> Show Tables
                    </button>
                    @endif
                </span>
            </div>

            {!! Form::close() !!}
        </div><!-- /.box-body -->
    </div>
</div>

@endsection

@section('footer-script')
<script language="javascript">
    $(document).ready(
            function () {
                $('.save').click(function () {
                    switch ($(this).val()) {
                        case 'save_as_new' :
                            $('.report_title').val($('.report_title').val() + "-{{ Carbon\Carbon::now() }}");
                            $('.report_form').attr('action', "{!! URL::to('/reports/store') !!}");
                            break;
                        case 'save_new':
                            $('.redirect_to_new').val(1);
                            break;
                        default:
                    }
                    $('.report_form').submit();
                });

                $('#verifyBtn').click(function () {
                    var sql = $('.sql').val();
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url: '/reports/verify',
                        type: 'POST',
                        data: {sql: sql, _token: _token},
                        dataType: 'text',
                        success: function (data) {
                            $('.results').html(data);
                            $('.results_tab a').trigger('click');
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $('.results').html(jqXHR.responseText);
                            $('.results_tab a').trigger('click');
                        }
                    });
                });

                $('#showTables').click(function () {
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url: '/reports/tables',
                        type: 'GET',
                        data: {_token: _token},
                        dataType: 'text',
                        success: function (data) {
                            $('.db_fields').html(data);
                            $('.db_tables a').trigger('click');
                        }
                    });
                });
            });

    $(document).ready(
            function () {
                $("#report_form").validate({
                    errorPlacement: function () {
                        return false;
                    }
                });
            });
</script>
@endsection
