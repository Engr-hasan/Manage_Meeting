@extends('layouts.admin')



@section('content')

    <style>
        .ex-page-content .text-error {
            color: #252932;
            font-size: 98px;
            font-weight: 700;
            line-height: 150px;
        }
        .ex-page-content .text-error i {
            font-size: 90px;
            padding: 0px 10px;
        }
        .text-pink {
            color: #fb6d9d;
        }
        .text-primary {
            color: #5d9cec;
        }.text-info {
             color: #34d3eb;
         }

    </style>

    <div class="col-sm-12">
        <div class="ex-page-content text-center">
            <div class="text-error">
                <span class="text-primary">4</span><i class="fa fa-frown-o text-pink" aria-hidden="true"></i><span class="text-info">1</span>
            </div>
            <h2>{{ $exception->getMessage() }}</h2><br>
            <p class="text-muted">You don't have permission to access on this Page.Please Contact with System Admin</p>
            <br>
            <a class="btn btn-info" href="{{URL::to('dashboard')}}"> Return Home</a>
        </div>
    </div>
@endsection