@extends('layouts.admin')
@section('page_heading')
    {!! $pageTitle !!}
@endsection
@section('content')
    <link rel="stylesheet" href="{{ asset("morris.css") }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="{{ asset("morris.js") }}"></script>
    <div class="col-sm-12">
        @include('message.message')
    </div>
    @if(Auth::user()->is_approved != 1 or Auth::user()->is_approved !=true)
        @include('message.un-approved')
    @else
        <div class="col-md-12">
            {{--@include('message.mode')--}}
            @include('Dashboard::dashboard')
        </div>
    @endif

    @include('navigation.footer')
@endsection

@section('footer-script')
@endsection
