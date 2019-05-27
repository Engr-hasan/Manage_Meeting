@extends('layouts.admin')

@section('page_heading',trans('messages.view_notification'))

@section('content')
<?php $accessMode=ACL::getAccsessRight('settings');
if(!ACL::isAllowed($accessMode,'V')) die('no access right!');
?>
<div class="col-lg-12">

    @include('partials.messages')
    <section class="col-md-12">
        <div class="col-md-12">

            <div class="panel panel-primary">
                    <div class="panel-group panel-primary">
                        <div class="panel-heading">
                            <b> &nbsp; </b>
                        </div><!-- /.panel-heading -->

                        <div class="panel-body">
                            <div class="col-md-12 {{$errors->has('source') ? 'has-error' : ''}}" style="background:white;">
                                {!! Form::label('source','Source: ',['class'=>'col-md-2']) !!}
                                <div class="col-md-10"> <code>{{ $data->source }}</code></div>
                            </div>

                            <div class="col-md-12 {{$errors->has('ref_id') ? 'has-error' : ''}}">
                                {!! Form::label('ref_id','Reference id: ',['class'=>'col-md-2']) !!}
                                <div class="col-md-10"> {{ $data->ref_id }}</div>
                            </div>

                            <div class="col-md-12 {{$errors->has('destination') ? 'has-error' : ''}}">
                                {!! Form::label('ref_id','Destination: ',['class'=>'col-md-2']) !!}
                                <div class="col-md-10"> {{ $data->destination }}</div>
                            </div>

                            <div class="col-md-12 {{$errors->has('is_sent') ? 'has-error' : ''}}">
                                {!! Form::label('is_sent','Sent: ',['class'=>'col-md-2']) !!}
                                <div class="col-md-10"> {!! ($data->is_sent==1)?'<p class="text-success">Sent</p>':'<p class="text-danger">Not Sent</p>';  !!}</div>
                            </div>

                            <div class="col-md-12 {{$errors->has('sent_on') ? 'has-error' : ''}}">
                                {!! Form::label('sent_on','Sent on: ',['class'=>'col-md-2']) !!}
                                <div class="col-md-10"> {!! CommonFunction::showDate($data->sent_on) !!}</div>
                            </div>

                            <div class="col-md-12 {{$errors->has('msg_type') ? 'has-error' : ''}}">
                                {!! Form::label('msg_type','Msg_type: ',['class'=>'col-md-2']) !!}
                                <div class="col-md-10"> {{ $data->msg_type }}</div>
                            </div>

                            <div class="col-md-12 {{$errors->has('template_id') ? 'has-error' : ''}}">
                                {!! Form::label('template_id','Template id: ',['class'=>'col-md-2']) !!}
                                <div class="col-md-10"> {{ $data->template_id }}</div>
                            </div>

                            <div class="col-md-12 {{$errors->has('response') ? 'has-error' : ''}}">
                                {!! Form::label('response','Response: ',['class'=>'col-md-2']) !!}
                                <div class="col-md-10"> <code>{{ $data->response }}</code></div>
                            </div>

                            <div class="panel-footer">

                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                            <a href="{{ url('/settings/notification') }}">
                                {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                            </a>
                    </div>

                    {!! Form::close() !!}<!-- /.form end -->

            </div>


    </section>
</div>

@endsection