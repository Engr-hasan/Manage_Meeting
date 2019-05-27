@extends('layouts.admin')

@section('content')



    <style>
        .help-inline {
            color:red !important;
        }
        .limitedNumbSelect2{
            width: 100%;
        }
        textarea{ resize:none;}
    </style>

    <?php
    $accessMode = ACL::getAccsessRight('Training');
    if (!ACL::isAllowed($accessMode, 'A'))
        die('no access right!');
    ?>

    <div class="col-lg-12">

        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

        <div class="panel panel-primary">
            <div class="panel-heading">
                <b> {!!trans('messages.schedule_edit')!!} </b>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="col-lg-10">
                    {!! Form::open(array('url' => '/training-schedule/update/'.\App\Libraries\Encryption::encodeId($training_schedule->id),'method' => 'patch', 'class' => 'form-horizontal', 'id' => 'agency-info',
                    'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}

                    <div class="form-group col-md-12 {{$errors->has('training_id') ? 'has-error' : ''}}">
                        {!! Form::label('training_title','Select Training: ',['class'=>'col-md-4  required-star']) !!}
                        <div class="col-md-6">
                            {!! Form::select('training_id', $training_lists, $training_schedule->training_id, ['class' => 'form-control input-sm ', 'placeholder'=>'Select One', 'id'=>'training_id']) !!}
                            {!! $errors->first('training_id','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group col-md-12 {{$errors->has('trainer_name') ? 'has-error' : ''}}">
                        {!! Form::label('trainer_name','Trainer Name: ',['class'=>'col-md-4  required-star']) !!}
                        <div class="col-md-6">
                            {!! Form::text('trainer_name', $training_schedule->trainer_name, ['class' => 'form-control input-sm ', 'placeholder'=>'Trainer Name', 'id'=>'trainer_name']) !!}
                            {!! $errors->first('trainer_name','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group col-md-12 {{$errors->has('venue_name') ? 'has-error' : ''}}">
                        {!! Form::label('venue_name','Select Venue for Training: ',['class'=>'col-md-4  required-star']) !!}
                        <div class="col-md-6">
                            {!! Form::text('venue_name', $training_schedule->venue, ['class' => 'form-control input-sm ', 'placeholder'=>'Venue Name', 'id'=>'venue_id']) !!}
                            {!! $errors->first('venue_name','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group col-md-12 {{$errors->has('total_seats') ? 'has-error' : ''}}">
                        {!! Form::label('total_seats','Number of allocated seats: ',['class'=>'col-md-4  required-star']) !!}
                        <div class="col-md-6">
                            {!! Form::text('total_seats', $training_schedule->total_seats, ['class'=>'form-control required', 'type'=>'number', 'data-rule-maxlength'=>'150', 'id'=>'total_seats']) !!}
                            {!! $errors->first('total_seats','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group col-md-12 {{$errors->has('location') ? 'has-error' : ''}}">
                        {!! Form::label('location','Training Location: ',['class'=>'col-md-4  required-star']) !!}
                        <div class="col-md-6">
                            {!! Form::textarea('location', $training_schedule->location, ['class'=>'form-control required', 'id'=>'location', 'rows'=>'3']) !!}
                            {!! $errors->first('location','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group col-md-12 {{$errors->has('start_time') ? 'has-error' : ''}}">
                        {!! Form::label('start_time','Training Start Time: ',['class'=>'col-md-4 required-star']) !!}
                        <div class="col-md-6">
                            <?php $start_time = !empty($training_schedule->start_time) ? date('m-d-Y h:i A', strtotime($training_schedule->start_time)) : ''; ?>
                            <div class="input-group col-md-12">
                                {!! Form::text('start_time', $start_time,['class'=>'form-control input-sm required datetimepicker',
                                'placeholder'=>'MM/DD/YYYY HH:MM', 'data-rule-maxlength'=>'20']) !!}
                                <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                            </div>
                            {!! $errors->first('start_time','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group col-md-12 {{$errors->has('end_time') ? 'has-error' : ''}}">
                        {!! Form::label('end_time','Training End Time: ',['class'=>'col-md-4 required-star']) !!}
                        <div class="col-md-6">
                            <?php $end_time = !empty($training_schedule->end_time) ? date('m-d-Y h:i A', strtotime($training_schedule->end_time)) : ''; ?>
                            <div class="input-group col-md-12">
                                {!! Form::text('end_time', $end_time,['class'=>'form-control input-sm required datetimepicker',
                                'placeholder'=>'MM/DD/YYYY HH:MM', 'data-rule-maxlength'=>'20']) !!}
                                <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                            </div>
                            {!! $errors->first('end_time','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group col-md-12 {{$errors->has('status') ? 'has-error' : ''}}">
                        {!! Form::label('status','Status: ',['class'=>'col-md-4  required-star']) !!}
                        <div class="col-md-6">
                            {!! Form::select('status', $status, $training_schedule->status, ['class' => 'form-control input-sm', 'id'=>'status']) !!}
                            {!! $errors->first('status','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <div class="pull-left">
                            <a href="{{ url('training/schedule') }}" class="btn btn-success">Close</a>
                        </div>
                        <div class="pull-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-chevron-circle-right"></i> Save
                            </button>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    {!! Form::close() !!}<!-- /.form end -->

                    <div class="overlay" style="display: none;">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div><!-- /.box -->
            </div>
        </div>
    </div>

@endsection

@section('footer-script')
    <script>
        $(document).ready(function () {

            var today = new Date();
            var yyyy = today.getFullYear();
            var mm = today.getMonth();
            mm = parseInt(mm) + parseInt(1);
            var dd = today.getDate();

            $('.datetimepicker').datetimepicker({
                viewMode: 'months',
                sideBySide: true,
                minDate: (mm) + '/' + dd + '/' + (yyyy - 5),
                maxDate: (mm) + '/' + dd + '/' + (yyyy + 5),
            });
        });
    </script>
@endsection