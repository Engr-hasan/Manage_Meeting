@extends('layouts.admin')


@section('content')

    <div class="col-lg-12">

        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

        <section class="col-md-12">
            <div class="col-md-12">

                <div class="panel panel-primary">
                    <div class="panel-group panel-primary">
                        <div class="panel-heading">
                            <div class="pull-left">
                                <b> {{$training->title}} </b>
                            </div>
                            @if(in_array(Auth::user()->user_type,CommonFunction::trainingAdmin()))
                                <div class="pull-right">
                                    <a href="{{ url('Training/resource/'. Encryption::encodeId($training->id)) }}" class="btn btn-default"><i class="fa fa-arrow-circle-right"></i>&nbsp;Add Training Resource</a>
                                </div>
                            @endif
                            <div class="clearfix"></div>
                        </div><!-- /.panel-heading -->

                        <div class="panel-body">
                            <div class="col-md-12 {{$errors->has('name') ? 'has-error' : ''}}">
                                {!! Form::label('user_types','User Type: ',['class'=>'col-md-2']) !!}
                                <div class="col-md-6"> {{ $type_name }}</div>
                            </div>

                            <div class="col-md-12 {{$errors->has('title') ? 'has-error' : ''}}">
                                {!! Form::label('title','Training Title: ',['class'=>'col-md-2']) !!}
                                <div class="col-md-6">{{ $training->title }}</div>
                            </div>

                            <div class="col-md-12 {{$errors->has('description') ? 'has-error' : ''}}">
                                {!! Form::label('description','Description: ',['class'=>'col-md-2']) !!}
                                <div class="col-md-6">{{ $training->description }}</div>
                            </div>

                            <div class="col-md-12 {{$errors->has('status') ? 'has-error' : ''}}">
                                {!! Form::label('status','Status: ',['class'=>'col-md-2']) !!}
                                <div class="col-md-6">{{ $training->status }}</div>
                            </div>

                            <div class="panel-footer">
                                <div class="col-md-12">
                                    <div class="col-md-2">
                                        <?php
                                        $clz_url = 'Training/material-list';
                                        if(in_array(Auth::user()->user_type, CommonFunction::trainingAdmin()))
                                        {
                                            $clz_url = 'Training';
                                        }
                                        ?>
                                        <a href="{{ url ($clz_url) }}">
                                            {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                                        </a>
                                    </div>
                                    <div class="col-md-6 col-md-offset-1">
                                        {!! CommonFunction::showAuditLog($training->updated_at, $training->updated_by) !!}
                                    </div>
                                    <div class="col-md-2">
                                        @if(in_array(Auth::user()->user_type, CommonFunction::trainingAdmin()))

                                        <a href="{{ url('Training/edit/'. Encryption::encodeId($training->id)) }}">
                                            {!! Form::button('<i class="fa fa-edit"></i>&nbsp; Edit Training', array('type' => 'button', 'class' => 'btn btn-primary pull-right')) !!}
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

        </section>

        @if(!in_array(Auth::user()->user_type,CommonFunction::trainingAdmin()))
            <section class="col-md-12">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <b>Resource List :</b>
                        </div>
                        <div class="panel-body">
                            <div class="tab-content">
                                <div class="table-responsive">
                                    <table id="training_resource" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                        <thead class="alert alert-info">
                                        <tr>
                                            <th>Resource Title</th>
                                            <th>Types</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div><!-- /.table-responsive -->
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <section class="col-md-12">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <b> {!!trans('messages.training_schedule')!!} </b>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="table-responsive">
                                <table id="training_list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                    <thead class="alert alert-info">
                                    <tr>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Venue</th>
                                        <th>Location</th>
                                        <th>Trainer</th>
                                        <th>Total Seat</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div><!-- /.table-responsive -->
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('footer-script')
@include('partials.datatable-scripts')
<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
<input type="hidden" name="training_id" value="<?php echo Encryption::encodeId($training->id); ?>">
<script>

        <?php
            $training_schedule_url = 'training/get-training-material-schedule-data';
            if (in_array(Auth::user()->user_type, CommonFunction::trainingAdmin())){
                $$training_schedule_url = 'training/get-training-schedule-data';
            }
        ?>
        $(function () {
        $('#training_list').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{url( $training_schedule_url )}}',
                method: 'post',
                data: function (d) {
                    d._token = $('input[name="_token"]').val();
                    d.training_id = $('input[name="training_id"]').val();
                }
            },
            columns: [
                {data: 'start_date', name: 'start_date'},
                {data: 'time', name: 'time'},
                {data: 'venue_name', name: 'venue_name'},
                {data: 'location', name: 'location'},
                {data: 'trainer_name', name: 'trainer_name'},
                {data: 'total_seats', name: 'total_seats'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            "aaSorting": []
        });

    });

    $(function () {
        $('#training_resource').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{url( 'training/participant-training-resource-data' )}}',
                method: 'post',
                data: function (d) {
                    d._token = $('input[name="_token"]').val();
                    d.training_id = $('input[name="training_id"]').val();
                }
            },
            columns: [
                {data: 'resource_title', name: 'resource_title'},
                {data: 'resource_type', name: 'resource_type'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            "aaSorting": []
        });

    });

    $(document).on('click','.applyfortraining',function(e){
        btn = $(this);
        btn_content = btn.html();
        btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;'+btn_content);
        schedule_id = btn.attr('id');
        training_id = btn.attr('tid');


        $.ajax({
            url: '{{url("Training/apply-for-training")}}',
            type: 'post',
            data: {
                _token: $('input[name="_token"]').val(),
                training_id: training_id,
                schedule_id: schedule_id
            },
            dataType: 'json',
            success: function (response) {
                if(response.responseCode == 1)
                {
                    btn.removeClass('btn-info');
                    btn.removeClass('applyfortraining');
                    btn.addClass('btn-success');
                    btn.html('Applied');
                }
                else if(response.responseCode == 0 || response.responseCode == 2 || response.responseCode == 3 || response.responseCode == 4)
                {
                    btn.html(btn_content);
                    alert(response.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);

            },
            beforeSend: function (xhr) {
                console.log('before send');
            },
            complete: function () {
                //completed
            }
        });
    });
</script>
@endsection