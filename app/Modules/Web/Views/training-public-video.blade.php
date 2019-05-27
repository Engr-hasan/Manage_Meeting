@extends('layouts.front')

@section ('body')

    <style type="text/css">
        body {
            background: url('/assets/images/top_bg.jpg') no-repeat scroll 0 0 !important;
        }
        a{text-decoration:none; color:#000;}
        p{color:#000; font-size:13px;}
        .q-support {
            padding:20px 20px;
            text-align: left;
            height: 500px;
        }
        .item-s p{
            font-size:12px;
            line-height:20px;
            padding-bottom:20px;
            text-align: justify;
        }
        .q-support p a{
            font-size:17px;
            color:#1953a1;
            line-height:30px;
        }
        .q-support p a:hover{
            text-decoration:underline;
            color:#039;
        }
        .q-support h4{
            color:#0a6829;
            padding-bottom:3px;
            margin-bottom:6px;
            border-bottom:1px solid #e1dede;
            text-shadow:0px 1px 0px #999;
        }
        .item-s p span{
            font-size:15px;
            color:#05326e;
        }
        .company-info {
            color: #333;
            font-size: 12px;
            font-weight: normal;
            padding-bottom: 3px;
            padding-top: 2px;
            text-align: center;
        }
        .hr{
            border-top: 1px solid #d3d3d3;
            box-shadow: 0 1px #fff inset;
            margin: 0px;
            padding: 0px;
        }
        .less-padding {
            padding: 1px !important;
            margin: 0px !important;
        }
        .top-border{
            border-top: 3px steelblue solid !important;
            padding-bottom: 5px !important;
            margin-bottom: 5px !important;
            margin-top: 0px !important;
        }
    </style>
    <?php
    $dev_app_mode = 80;
    $uat_app_mode = 50;
    $training_app_mode = 60;
    $live_app_mode = 80;
    $live_prps_app_mode = 90;

    $title_txt = trans('messages.prp_home_title_u');
    if(env('APP_MODE') == $uat_app_mode)
    {
        $title_txt = trans('messages.prp_home_title_u');
    }
    else if(env('APP_MODE') == $training_app_mode)
    {
        $title_txt = trans('messages.prp_home_title_t');
    }
    ?>
    <header style="width: 100%; height: auto; background: #fff; opacity:0.7;">
        <div class="col-md-12 text-center">
            <div class="col-md-4"></div>
            <div class="col-md-4"  style="margin-top:5px;">
                {!! Html::image(Session::get('logo'), 'logo', array( 'width' => 70 ))!!}
                <h3 class="less-padding"> {{Session::get('title')}}</h3>
                <hr class="hr " />
                <h4><strong> {{Session::get('manage_by')}}</strong></h4>
            </div>
            <div class="col-md-4"></div>
        </div>
        <div class="clearfix"> <br></div>
    </header>

    <div class="col-md-12">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <hr class="top-border"/>
        </div>
        <div class="col-md-1"></div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading panel-title">
                        {{ $resourceDetail->resource_title }}
                    </div>
                    <div class="panel-body">
                        <div class="col-md-7 col-md-offset-3">
                            <iframe style="border: 3px solid #EEE;" width="560" height="315" src="https://www.youtube.com/embed/{{ $resourceDetail->resource_link }}" frameborder="0" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div id="footer">
        <div>
            <br/>
            <hr class=" less-padding"/>
            <p class="company-info">
                <em>Managed by Business Automation Ltd. on behalf of OCPL OSS Framework, Bangladesh.</em>
            </p>
        </div>
    </div>
@endsection

@section ('footer-script')
    <script src="{{ asset("assets/scripts/Chart.min.js") }}" src="" type="text/javascript"></script>
    <script src="{{ asset("assets/scripts/chart-data.js") }}" src="" type="text/javascript"></script>
@endsection