@extends('layouts.plain')
@section('content')

<div class="col-sm-12">
    <div class="panel panel-danger">
        <div class="panel-heading">
            <h3 class="panel-title"> You are delegated to the following user <i class="fa fa-rocket"></i><i class="fa fa-rocket"></i></h3>
        </div>
        <div class="panel-body">
            <div style="text-align: center;">
                <h4>Delegation Information:</h4> <br/>
                <b>Name : </b> {{ $info->user_full_name }}<br/>
                <b>Designation : </b>{{ $info->desk_name }}<br/>
                <b>Email : </b>{{ $info->user_email }}<br/>
                <b>Mobile : </b>{{ $info->user_phone }}<br/><br/>
                <a class="remove-delegation btn btn-primary" href="{{ url('/users/remove-deligation') }}">
                    <i class="fa fa-share-square-o"></i> Remove Delegation</a>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer-script')
@endsection