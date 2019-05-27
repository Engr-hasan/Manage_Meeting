@extends('layouts.plane')
@section ('body')
    @include('public_home.header')
    @include('public_home.style')
    <style type="text/css">

        /*a {*/
            /*background-color: white;*/
        /*}*/
        .top-border {
            border-top: 1px #1E398C solid !important;
            padding-bottom: 5px !important;
            margin-bottom: 5px !important;
            margin-top: 34px !important;}
    </style>
    <div class="container">
        <div class="row">
            <div class="col-lg-12" style="width: 100.67%">
                <div class="alert alert-info ticker-section">
                    <div class="col-md-1">
                        <h5 class="newsTicker-title"><b> {!! trans('messages.latest_notice_title') !!}</b></h5>
                    </div>
                    <div class="col-md-11">
                        @if($notice)
                            <?php
                            echo '<div class="TickerNews">';
                            echo '<div class="ti_wrapper">';
                            echo '<div class="ti_slide">';
                            echo '<div class="ti_content">';

                            $arr = $notice;
                            for($i = 0; $i < count($arr); $i++){
                                if($i == 6){
                                    break;
                                }
                                echo '<div class="ti_news"><a href="/single-notice/'.\App\Libraries\Encryption::encodeId($arr[$i]->id).'">'. $arr[$i]->heading .'</a></div>';

                            }
                            //                            foreach ($arr as $value) {
                            //                                echo '<div class="ti_news"><a href="viewNotice/'.\App\Libraries\Encryption::encodeId($value->id).'" target="_blank">'. $value->heading .'</a> </div>';
                            //                            }
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            ?>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div style="margin-bottom: 11px;">
                    @include('public_home.home_slider')
                </div>

                @include('public_home.report')
            </div>
            @include('public_home.login_panel')
        </div>
    </div>
    @include('public_home.footer')
@stop
@include('public_home.footer_script')