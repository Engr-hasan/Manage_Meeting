@extends('layouts.front')

@section("content")
    <style type="text/css">
        body {
            background: url('/assets/images/top_bg_green.jpg') no-repeat scroll 0 0 !important;
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

    <header style="width: 100%; height: auto; opacity:0.7;">
        <div class="col-md-12 text-center">
            <div class="col-md-2"></div>
            <div class="col-md-8"  style="margin-top:5px;">
                {!! Html::image(Session::get('logo'), 'logo', array( 'width' => 70))!!}
                <h3 class="less-padding"><strong>{{Session::get('title')}}</strong></h3>
                {{--<hr class="hr" />--}}
                <h4>{{Session::get('manage_by')}}</h4>
            </div>
            <div class="col-md-2"></div>
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

    <div class="col-md-12">
        <div class="row">
            <div class="col-md-10 col-md-offset-1 box-div">
                <div class="row">
                    <div class="q-support">

                        <div class="item-s">
                            <h2 id="">To whom should I contact for technical support?</h2>
                            <p class="panel-body">
                                <strong>Business Automation Ltd.</strong> provides technical support for this project.
                                You can contact with the respective officer for your necessary technical support during office hour. <br>
                                <span>Phone: +88-02-9587353, Ext.1802.</span><br>
                                <span>Fax: +880-2-914-3656</span><br>
                                <span>Mobile: +8801755676725</span><br>
                                <span>Email: support@batworld.com </span><br>
                                Online Support portal: <b><a href="http://support.batworld.com" target="_blank">http://support.batworld.com</a></b>
                            </p>
                        </div>

                        <div class="col-md-12">
                            <a href="/login"><input type="button" class="btn btn-lg btn-success" value="Go Back to Login"/></a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
    <div id="footer">
        <div>
            <p class="company-info">
                <em>Managed by Business Automation Ltd.</em>
            </p>
        </div>
    </div>
@endsection

@section ('footer-script')
    <script src="{{ asset("assets/scripts/Chart.min.js") }}" src="" type="text/javascript"></script>
    <script src="{{ asset("assets/scripts/chart-data.js") }}" src="" type="text/javascript"></script>
@endsection