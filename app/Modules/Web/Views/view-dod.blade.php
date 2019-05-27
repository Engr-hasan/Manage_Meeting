@extends('layouts.front')
<link rel="stylesheet" href="{{ asset("assets/stylesheets/styles.css") }}" />
<link rel="stylesheet" href="{{ asset("assets/scripts/datatable/dataTables.bootstrap.min.css") }}" />
<link rel="stylesheet" href="{{ asset("assets/scripts/datatable/responsive.bootstrap.min.css") }}" />
<link rel="stylesheet" href="{{ asset("assets/stylesheets/bootstrap-datetimepicker.css") }}" />
@section('content')
            <div class="{{Request::segment(2)}}" id="{{Request::segment(2)}}">
                {!! $HtmlData !!}
            </div>
@endsection

@section('footer-script')
    <script src="{{ asset("assets/scripts/jquery.min.js") }}" type="text/javascript"></script>
    @include('partials.datatable-scripts-mobile')
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <script>
        $(document).ready(function () {
            var obj = $('.' + '{{$key}}');
            $('#' + obj.find('.dataTable').attr('id')).dataTable({
                "lengthChange": false,
                'displayLength': 15,
                "dom": '<"top">t<"bottom"ifp><"clear">'
            });
        });
    </script>
@endsection