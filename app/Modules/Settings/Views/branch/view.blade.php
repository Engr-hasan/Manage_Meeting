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
                            <b>{!! trans('messages.branch_view') !!} {{(!empty($data->branch_name)?$data->branch_name:'')}} </b>
                        </div><!-- /.panel-heading -->

                        <div class="panel-body">
                            <div class="col-md-12 {{$errors->has('bank_name') ? 'has-error' : ''}}">
                                {!! Form::label('branch_name','Bank Name: ',['class'=>'col-md-2']) !!}
                                <div class="col-md-6"> {{ (!empty($data->bank_name)?$data->bank_name:'') }}</div>
                            </div>
                            <div class="col-md-12 {{$errors->has('branch_name') ? 'has-error' : ''}}">
                                {!! Form::label('branch_name','Branch Name: ',['class'=>'col-md-2']) !!}
                                <div class="col-md-6"> {{ (!empty($data->branch_name)?$data->branch_name:'') }}</div>
                            </div>

                            <div class="col-md-12 {{$errors->has('branch_code') ? 'has-error' : ''}}">
                                {!! Form::label('branch_code','Branch Code: ',['class'=>'col-md-2']) !!}
                                <div class="col-md-6">{{ (!empty($data->branch_code)?$data->branch_code:'') }}</div>
                            </div>

                            <div class="col-md-12 {{$errors->has('address') ? 'has-error' : ''}}">
                                {!! Form::label('address','Address: ',['class'=>'col-md-2']) !!}
                                <div class="col-md-6">{{ (!empty($data->address)?$data->address:'') }}</div>
                            </div>

                            <div class="col-md-12 {{$errors->has('manager_info') ? 'has-error' : ''}}">
                                {!! Form::label('manager_info','Manager Info: ',['class'=>'col-md-2']) !!}
                                <div class="col-md-6">{{ (!empty($data->manager_info)?$data->manager_info:'') }}</div>
                            </div>

                            <div class="col-md-12 {{$errors->has('is_active') ? 'has-error' : ''}}">
                                {!! Form::label('location','Activation Status: ',['class'=>'col-md-2']) !!}
                                <div class="col-md-6">{{ (($data->is_active == 1)?'Active':'Inactive') }}</div>
                            </div>

                            <div class="col-md-12 {{$errors->has('address') ? 'has-error' : ''}}">
                                {!! Form::label('address','Address: ',['class'=>'col-md-2']) !!}
                                <div class="col-md-6">{{ (!empty($data->address)?$data->address:'') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-footer">
                            <a href="{{ url('/settings/branch-list') }}">
                                {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                            </a>
                                @if(ACL::getAccsessRight('settings','E'))
                                <a href="{{ url('/settings/edit-branch/'.$id) }}" class="pull-right">
                                    {!! Form::button('<i class="fa fa-edit"></i><b> Edit Branch</b>', array('type' => 'button', 'class' => 'btn btn-primary')) !!}
                                </a>
                                @endif
                        
                    </div>

                    {!! Form::close() !!}<!-- /.form end -->

            </div>


    </section>
</div>

@endsection