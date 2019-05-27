@extends('layouts.plane')
@section('body')
    <style>
        #footer {
            position: fixed;
            /*height: 30px;*/
            bottom: 0;
            width: 100%;
        }
        .help-button {
            display: inline-block;
            width: 35px;
            height: 35px;

            text-align: center;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            border: 2px solid #637282;
            border-radius: 50%;
            font-size: 16px;
            font-family: AtlasGrotesk, sans-serif;
            font-weight: 500;
            color: #637282;
            background-color: white;
            text-decoration: none;
        }
        .help-button:active, .help-button:hover, .help-button:focus {
            border-color: #0070E0;
            color: #0070E0;
            text-decoration: none;
        }

    </style>
    <div id="wrapper">

        @include ('navigation.nav')
        <div id="page-wrapper">
            <div class="row">
            {{--<div class="col-md-10 col-lg-10">--}}
            {{--<h3 class="page-header">@yield('page_heading')</h3>--}}
            {{--</div>--}}
            {{--<div class="col-md-2 col-lg-2 text-right">--}}
            {{--<a href="{{url('support/help/'.Request::segment(1))}}"><h5><span style="color: green">--}}
            {{--Need Help <i class="fa fa-question-circle"></i>--}}
            {{--</span></h5></a>--}}
            {{--</div>--}}
            <!-- /.col-lg-12 -->
            </div>
            <div class="row">
                @yield('content')
            </div>
            <!-- /#page-wrapper -->
        </div>
    </div>

    <div id="footer">
        <div class="col-sm-9"></div>
        <div class="col-sm-3">
            <?php
            $PendingYourApplication = \App\Libraries\CommonFunction::pendingApplication();
            ?>
            <div class="tooltip-demo pull-right">
                <a href="{{'/process/list'}}" class="btn  btn-info btn-xs" style="padding: 4px;background:#5ca99b">Pending process in your desk: ({{$PendingYourApplication }})</a>
                <a  href="{{url('support/help/'.Request::segment(1))}}" target="_blank"  data-toggle="tooltip" data-placement="top" title="Help" class="btn btn-default help-button">
                    <i class=" fa fa-question"></i>
                </a>
            </div>
        </div>
    </div>

@stop

