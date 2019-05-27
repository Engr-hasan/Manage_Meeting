@extends('layouts.admin')


<?php $accessMode=ACL::getAccsessRight('settings');
if(!ACL::isAllowed($accessMode,'V')) die('no access right!');
?>
@section('content')
<section class="col-md-12">
    <div class="panel panel-primary">
        <div class="panel-heading"> 
            <h5>
                <strong>{!! trans('messages.create_security_profile') !!}</strong>
            </h5>
        </div>
        <div class="panel-body">
            {!! Form::open(array('url' => 'settings/store-security','method' => 'patch', 'class' => 'form-horizontal', 'id' => 'securityForm')) !!}

            <div class="form-group col-md-8 {{$errors->has('profile_name') ? 'has-error' : ''}}">
                {!! Form::label('profile_name','Profile Name: ',['class'=>'col-md-4 control-label required-star']) !!}
                <div class="col-md-6">
                    {!! Form::text('profile_name',null,['class'=>'form-control required','placeholder'=>'Enter Profile Name']) !!}
                    {!! $errors->first('profile_name','<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="form-group col-md-8 {{$errors->has('allowed_remote_ip') ? 'has-error' : ''}}">
                {!! Form::label('allowed_remote_ip','Ip Address: ',['class'=>'col-md-4 control-label required-star']) !!}
                <div class="col-md-6">
                    {!! Form::text('allowed_remote_ip',null,['class'=>'form-control required','placeholder'=>'Enter Ip Address']) !!}
                    {!! $errors->first('allowed_remote_ip','<span class="help-block">:message</span>') !!}
                </div>
            </div>
                        <div class="form-group col-md-8 {{$errors->has('user_email') ? 'has-error' : ''}}">
                {!! Form::label('user_email','Email Address: ',['class'=>'col-md-4 control-label']) !!}
                <div class="col-md-6">
                    {!! Form::text('user_email',null,['class'=>'form-control','placeholder'=>'Enter Email Address']) !!}
                    {!! $errors->first('user_email','<span class="help-block">:message</span>') !!}
                </div>
            </div>
            {{--<div class="form-group col-md-8 {{$errors->has('user_type') ? 'has-error' : ''}}" id="user_type">--}}
                {{--{!! Form::label('user_type','User Type: ',['class'=>'col-md-4 control-label']) !!}--}}
                {{--<div class="col-md-6">--}}
                    {{--{!! Form::select('user_type', $user_types,null, ['class' => 'form-control']) !!}--}}
                    {{--{!! $errors->first('user_type','<span class="help-block">:message</span>') !!}--}}
                {{--</div>--}}
            {{--</div>--}}
            <div class="form-group col-md-8 {{$errors->has('week_off_days') ? 'has-error' : ''}}">
                {!! Form::label('week_off_days','Weekly Off Days: ',['class'=>'col-md-4 control-label required-star']) !!}
                <div class="col-md-6">
                    {!! Form::text('week_off_days',null,['class'=>'form-control required','placeholder'=>'Enter Weekly Off Days']) !!}
                    {!! $errors->first('week_off_days','<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="form-group col-md-8 {{$errors->has('work_hour_start') ? 'has-error' : ''}}">
                {!! Form::label('work_hour_start','Working Hour Start Time: ',['class'=>'col-md-4 control-label required-star']) !!}
                <div class="col-md-6">
                    {!! Form::text('work_hour_start',null,['class'=>'form-control datepicker required','placeholder'=>'Enter Working Hour Start Time']) !!}
                    {!! $errors->first('work_hour_start','<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="form-group col-md-8 {{$errors->has('work_hour_end') ? 'has-error' : ''}}">
                {!! Form::label('work_hour_end','Working Hour End Time: ',['class'=>'col-md-4 control-label required-star']) !!}
                <div class="col-md-6">
                    {!! Form::text('work_hour_end',null,['class'=>'form-control datepicker required','placeholder'=>'Enter Working Hour End Time']) !!}
                    {!! $errors->first('work_hour_end','<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="form-group col-md-8 {{$errors->has('active_status') ? 'has-error' : ''}}">
                {!! Form::label('active_status','Status: ',['class'=>'col-md-4  control-label required-star']) !!}
                <div class="col-md-6">
                    {!! Form::radio('active_status', 'yes', ['class'=>'form-control']) !!} Active
                    &nbsp;&nbsp;
                    {!! Form::radio('active_status', 'no', ['class'=>'form-control']) !!} In-Active
                    {!! $errors->first('active_status','<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="form-group col-md-8 {{$errors->has('caption') ? 'has-error' : ''}}">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary pull-right next">
                        <i class="fa fa-chevron-circle-right"></i> Save Information</button>
                </div>
            </div>

            {!! Form::close() !!}<!-- /.form end -->
        </div>
    </div>


     <div class="panel panel-primary">
        <div class="panel-heading">
            <h5>
                <strong>{{ trans('messages.security_list') }}</strong>
            </h5>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            @include('partials.messages')
    <div class="table-responsive" style="overflow:visible;">
                <table id="list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" >
                    <thead>
                        <tr>
                            <th>Profile Name</th>
                            <th>Ip Address</th>
                            <th>Weekly Off Days</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div> <!-- /.table-responsive -->
        </div> <!-- /.panel-body -->
    </div><!-- /.panel -->

</section>

@endsection

@section('footer-script')

@include('partials.datatable-scripts')

<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

<script>
    $(function() {
        $("#securityForm").validate({
            errorPlacement: function() {
                return false;
            }
        });
        $('.datepicker').datetimepicker({
            format: 'HH:mm'
        });
        $('#list').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{url("settings/get-security-data")}}',
                method: 'post',
                data: function(d) {
                    d._token = $('input[name="_token"]').val();
                }
            },
            columns: [
                {data: 'profile_name', name: 'profile_name'},
                {data: 'allowed_remote_ip', name: 'allowed_remote_ip'},
                {data: 'week_off_days', name: 'week_off_days'},
                {data: 'work_hour_start', name: 'work_hour_start'},
                {data: 'work_hour_end', name: 'work_hour_end'},
                {data: 'active_status', name: 'active_status'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            "aaSorting": []
        });

    });
</script>
@endsection
