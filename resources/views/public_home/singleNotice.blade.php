@extends('layouts.front')

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style type="text/css">
    .identity_hover, .identity_type{
        cursor: pointer;
    }
</style>

@section("content")
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

    <div class="container">

        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <hr class="top-border"/>
            </div>
        </div>

        <div class="row">


        <!-- <div class="col-md-10 col-md-offset-1" >
                <div class="panel panel-page">
                    <div class="panel-heading" style="background: #4B8DF8;
    padding: 2px 10px;
    border-radius: 0;
    box-sizing: border-box;
    border: 1px solid #4B8DF8;
    border-bottom: 0;">
                        <h4 id=""><i class="fa fa-bell" aria-hidden="true"></i> <strong>বিজ্ঞপ্তি: {{$nData->heading}}</strong></h4>
                    </div>
                    <div class="panel-body" style="background: #CCEEF1;border: 1px solid #4B8DF8; border-top: 0;">
                        <p><b>Publication Date :</b> {{ \Carbon\Carbon::parse($nData->updated_at)->format('jS  F Y h:i A')}} </p>
                        <p><b>Category :</b>  {{ $nData->importance }} </p>
                                            <fieldset>
                            <legend>Details: </legend>
                            <p>{{ $nData->details }}</p>

                          </fieldset>

                    </div>
                </div>
            </div>
 -->
            <div class="col-md-10 col-md-offset-1" >
                <div class="panel panel-page">
                    <div class="panel-heading" style="background: #4B8DF8;
    padding: 2px 15px;
    border-radius: 0;
    box-sizing: border-box;
    border: 1px solid #4B8DF8;
    border-bottom: 0;">
                        <div class="row">
                            <span style="font-size: 18px;"><i class="fa fa-bell" aria-hidden="true" style="color: white"></i> <strong style="color: white">Notice</strong></span>
                            <span ><a href="/"  style="background: #89C4F4;border-color: #72b8f2;font-size: 18px" class="btn btn-info btn-sm pull-right">Home</a></span>
                        </div>

                    </div>
                    <div class="panel-body" style="background: #ffffff;border: 1px solid #4B8DF8; border-top: 0;">




                        <div class="panel panel-default">
                            <div class="panel-heading" style="padding: 5px">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-8"><span class="pull-left"> <h4><b>Title: </b>{{$nData->heading}}</h4></span></div>
                                        <div class="col-md-4"><span class="pull-right"> <h4><b>Date :</b> {{$nData->updated_at}}</h4></span></div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel-body">
                                <p><b style="font-size: 18px">Notice Details: </b>{{$nData->details}}</p>
                            </div>

                        </div>


                        <div id="footer" class="text-center">

                        </div>
                    </div>
                    <div style="text-align: center;"> <b> <span  style="color: #2E4053">Developed by Online Content Provider Ltd. (OCPL) in Associate with Business Automation Ltd.</span></b>
                    </div>
@endsection
@section ('footer-script')
@endsection