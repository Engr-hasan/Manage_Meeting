<div class="col-md-4 box-div box-div-img">
    <div class="text-center">
        <h4>{!! trans('messages.login_panel_title') !!}</h4>

    </div>
   {{--<div class="form-group">--}}
    {{--{!! Form::button('Login with OTP', array('type' => 'button', 'class' => 'form-control btn btn-info btn-block otp-login-btn')) !!}--}}
    {{--<span class="form-control-feedback"><span class="fa fa-mobile-phone"></span></span>--}}
    {{--</div>--}}

    <div class="form-group">
        {!! Form::button('Login', array('type' => 'button', 'class' => 'form-control btn btn-primary btn-block login-cred-btn','style'=>'background:#0d563b')) !!}

    </div>
    <div class="pannel">
        @if (count($errors))
            <ul class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <li>{!!$error!!}</li>
                @endforeach
            </ul>
        @endif
        {!!session()->has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'.session('success') .'</div>' : '' !!}
        {!!session()->has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. session('error') .'</div>' : '' !!}
        {!! Form::open(array('url' => 'login/check','method' => 'post', 'class' => '')) !!}
        <fieldset>
        <!--
            <div class="form-group">
                <input class="form-control" placeholder="E-mail" required name="email" value="{{old('email')}}" type="email" autofocus>
            </div>
            <div class="form-group">
                <input class="form-control" placeholder="Password" required name="password" type="password">
            </div>
            <?php if (Session::get('hit') >= 3) { ?>
                <div class="form-group">
                    <span id="rowCaptcha"><?php echo Captcha::img(); ?></span> <img onclick="changeCaptcha();" src="assets/images/refresh.png" class="reload" alt="Reload" />
                    </div>
                    <div class="form-group" style="margin-top: 15px;">
                        <input class="form-control required" required placeholder="Enter captcha code" name="captcha" type="text">
                    </div>
            <?php } ?>

                <div class="col-md-12" style="padding: 0px !important; white-space: nowrap">
                    <button type="submit" class="btn btn-primary pull-right"><b>Login</b></button>
                </div>
 -->

            <div class="form-group">
                <div class="col-md-12 bold">
                    <span class="pull-right">
                     {!! link_to('forget-password',trans('messages.forget_password'), array("class" => "text-right color-class")) !!}
                    </span>
                    <br/>
                    <span class="pull-right">
                         {!! trans('messages.new_user?') !!} {!! link_to('signup',trans('messages.signup'), array("class" => "color-class")) !!}
                    </span>
                    <br/>
                    <div class="pull-right">
                        {{--{!! link_to('users/support', 'সাহায্য  প্রয়োজন?', array("class" => "text-right")) !!}--}}
                        {{--{!! link_to(Session::get('help_link'),--}}
                        {{--'সাহায্য  প্রয়োজন?', array("class" => "text-right","target"=>"-blank")) !!}--}}
                        {!! link_to('users/support',
                              trans('messages.need_help?'), array("class" => "color-class text-right","target"=>"-blank")) !!}
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>

            <br/>
            <div class="text-right">
                <span style="font-size: smaller">{{trans('messages.manage_by')}}</span>
                <br/>
                {!!  Html::image('assets/images/business_automation.png','BAT logo',['width'=>'100']) !!} <br/><br/>
            </div>
        </fieldset>
        {!! Form::close() !!}

    </div>
</div>


<div class="col-md-4" style="margin-top: 10px;">
    <div class="panel panel-green">
        <div class="panel-heading" style="height: 45px;padding-top: 13px;">
            <strong style="margin-left: 5px;">What's New?</strong>
        </div>
        <div class="panel-body" style="height: 210px; width: 100%">
            <div id="myCarousel1" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <?php for($j = 0; $j < count($whatsNew); $j++){
                    if($j == '0'){
                    ?>
                    <li data-target="#myCarousel1" data-slide-to="0" class="active"></li>
                    <?php }else{  ?>
                    <li data-target="#myCarousel1" data-slide-to="<?php echo $j; ?>"></li>
                    <?php } } ?>
                </ol>
                <div class="carousel-inner">
                    <?php
                    $i = 0;
                    ?>
                    @foreach($whatsNew as $whatsData)
                        @if($i == '0')
                            <div class="item active">
                                <img src="{{url($whatsData->image)}}" alt="Los Angeles" style="width:350px; height: 200px;">
                            </div>
                        @else
                            <div class="item">
                                <img src="{{url($whatsData->image)}}" alt="Los Angeles" style="width:350px; height: 200px;">
                            </div>
                        @endif
                        <?php $i++; ?>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div id="otp_modal" class="modal fade" role="dialog">
    <div class="modal-dialog user-login-modal-container">

        <!-- Modal content for OTP Login-->
        <div class="modal-content user-login-modal-body">
            <div class="modal-header user-login-modal-title">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-title">Login with OTP</div>
            </div>
            <div class="modal-body login-otp user-login-modal-content">
                ..................
            </div>
            <div class="modal-footer user-login-modal-footer">

            </div>
        </div>

    </div>
</div>

<div id="user_login_modal" class="modal fade" role="dialog">
    <div class="modal-dialog user-login-modal-container">

        <!-- Modal content for OTP Login-->
        <div class="modal-content user-login-modal-body">
            <div class="modal-header user-login-modal-title">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-title">{{trans('messages.login_modal_title')}}</div>
            </div>
            <div class="modal-body login-info-box user-login-modal-content">
                ......................
            </div>
            <div class="modal-footer user-login-modal-footer">
            </div>
        </div>

    </div>
</div>



<script>
    function createComplain() {
        $('#myModal .modal-title').html("অভিযোগ ও পরামর্শ");
        $("#myModal #body-content").load("{{URL::to('users/complain')}}");
        $('#myModal .modal-dialog').removeClass().addClass('modal-dialog' + " " + "modal-md" + " " + "success-modal");
        $('#myModal .modal-footer').empty().append('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button> <button type="submit" id="submitBtn" class="btn btn-primary">Submit</button>');
    }
</script>