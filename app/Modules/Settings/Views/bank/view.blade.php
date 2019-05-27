@extends('layouts.admin')

@section('page_heading',trans('messages.bank_view'))

@section('content')
<?php $accessMode=ACL::getAccsessRight('settings');
if(!ACL::isAllowed($accessMode,'V')) die('no access right!');
?>
<div class="col-lg-12">

    {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
    {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

    <section class="col-md-12">
        <div class="col-md-12">

            <div class="panel panel-primary">
                    <div class="panel-group panel-primary">
                        <div class="panel-heading">
                            <b> Details of {{$data->name}} </b>
                        </div><!-- /.panel-heading -->

                        <div class="panel-body">
                            <div class="col-md-12 {{$errors->has('name') ? 'has-error' : ''}}">
                                {!! Form::label('name','Name: ',['class'=>'col-md-2']) !!}
                                <div class="col-md-6"> {{ $data->name }}</div>
                            </div>

                            <div class="col-md-12 {{$errors->has('code') ? 'has-error' : ''}}">
                                {!! Form::label('code','Code: ',['class'=>'col-md-2']) !!}
                                <div class="col-md-6">{{ $data->bank_code }}</div>
                            </div>

                            <div class="col-md-12 {{$errors->has('email') ? 'has-error' : ''}}">
                                {!! Form::label('email','Email: ',['class'=>'col-md-2']) !!}
                                <div class="col-md-6">{{ $data->email }}</div>
                            </div>

                            <div class="col-md-12 {{$errors->has('phone') ? 'has-error' : ''}}">
                                {!! Form::label('phone','Phone: ',['class'=>'col-md-2']) !!}
                                <div class="col-md-6">{{ $data->phone }}</div>
                            </div>

                            <div class="col-md-12 {{$errors->has('website') ? 'has-error' : ''}}">
                                {!! Form::label('website','Website: ',['class'=>'col-md-2']) !!}
                                <div class="col-md-6">{{ $data->website }}</div>
                            </div>

                            <div class="col-md-12 {{$errors->has('location') ? 'has-error' : ''}}">
                                {!! Form::label('location','Location: ',['class'=>'col-md-2']) !!}
                                <div class="col-md-6">{{ $data->location }}</div>
                            </div>

                            <div class="col-md-12 {{$errors->has('address') ? 'has-error' : ''}}">
                                {!! Form::label('address','Address: ',['class'=>'col-md-2']) !!}
                                <div class="col-md-6">{{ $data->address }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-footer">
                            <a href="{{ url('/settings/bank-list') }}">
                                {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                            </a>
                                                        @if(ACL::getAccsessRight('settings','E'))
                                <a href="{{ url('/settings/edit-bank/'.$id) }}" class="pull-right">
                                    {!! Form::button('<i class="fa fa-edit"></i><b> Edit Bank</b>', array('type' => 'button', 'class' => 'btn btn-primary')) !!}
                                </a>
                                @endif
                        
                    </div>

                    {!! Form::close() !!}<!-- /.form end -->

            </div>


    </section>
</div>

@endsection