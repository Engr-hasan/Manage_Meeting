@extends('layouts.front')
@section ('body')
    <style type="text/css">
        .container {
            width: 900px;
            margin: 50px auto;
            /*border: 1px solid red;*/
            overflow: hidden;
        }
        .button {
            float: right;
            margin-bottom: 20px;
        }
        .myButton {
        background-color:#44c767;
        -moz-border-radius:6px;
        -webkit-border-radius:6px;
        border-radius:6px;
        border:1px solid #18ab29;
        display:inline-block;
        cursor:pointer;
        color:#ffffff;
        font-family:Arial;
        font-size:17px;
        padding:7px 30px;
        text-decoration:none;
        text-shadow:0px 1px 0px #2f6627;
        }
        .myButton:hover {
            background-color:#5cbf2a;
            text-decoration: none;
        }
        .myButton:active {
            position:relative;
            top:1px;
            text-decoration: none;
            color: #fff;
        }
        .myButton:visited {
            text-decoration: none;
            color: #fff;
        }
        textarea {
            resize: none;
        }
        .note {
            margin-top: 20px;
        }
        .note p {
            margin-left: 25px;
            color: gray;
            font-weight: bold;
            text-decoration: underline;
        }
        a:hover {
            text-decoration: none;
            color: #fff;
        }
        a:visited {
            text-decoration: none;
            color: #fff;
        }
        .area p {
            font-weight: bold;
            font-style: italic;
        }
        .downloadlink a:link{text-decoration:none;color: #fff}
        .downloadlink a:active{text-decoration:none;color: #fff}
        .downloadlink a:visited{text-decoration:none;color: #fff}
        .downloadlink a:hover{text-decoration:none;color: #fff}
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
<header style="width: 100%; height: auto; opacity:0.7;">
    <div class="col-md-12 text-center">
        <div class="col-md-4"></div>
        <div class="col-md-4"  style="margin-top:5px;">
            <img width="70" alt="Logo" src="/assets/images/govt_logo.png"/>
            <h3 class="less-padding">{!!trans('messages.prp_owner')!!}</h3>
            <hr class="hr" />
            <h4><strong>{{$title_txt}}</strong></h4>
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
            <!-- <div class="container"> -->


            <div class="row">
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="area">
                            <p>Download URL:</p>
                            <textarea id="txtarea" style="height:50px;resize:none;width: 100% !important;" onClick="SelectAll('txtarea');" >{!! $downloadLink !!}</textarea>
                        </div>
                        <div>&nbsp;</div>
                        <div class="button downloadlink" style="float:left;">
                            <a href="{!! $downloadLink !!}" class="myButton" download>Download</a>
                        </div>
                    </div>
                </div>
            </div>


            <!-- </div> -->
        </div>
    </div>

    <div class="row">
        <div class="note">
            <p>Note</p>
            <ul>
                <li>To download the PDF, click "Download PDF" button</li>
                <li>Or you copy the link from the text area &amp; then paste it to the browser</li>
            </ul>
        </div>
    </div>

</div>




@endsection

<script type="text/javascript">
function SelectAll(id) {
    document.getElementById(id).focus();
    document.getElementById(id).select();
}
</script>