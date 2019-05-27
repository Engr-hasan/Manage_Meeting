<style>
    .hr-label {
        position: relative;
        margin-bottom: 10px;
        text-align: center;
        clear: both;
        overflow: hidden;
    }
    .hr-label::before,
    .hr-label::after {
        content: '';
        position: relative;
        width: 43%;
        background-color: rgba(0,0,0,0.2);
        display: inline-block;
        height: 1px;
        vertical-align: middle;
    }
    .hr-label__text {
        font-size: 13px;
        line-height: 24px;
        padding: 0 8px;
        vertical-align: top;
    }

</style>
{!! Form::open(array('url' => '', 'method' => '', 'id' => 'credential-login-form')) !!}
<div class="row">
    <div class="form-group col-md-12">
        <div style="display: none" class="alert alert-danger error-message alert-dismissible">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <span class="error-message"></span></div>
        <div class="col-md-12">
            {!! link_to('auth/google', trans('messages.google_btn_text'), array('class' => 'form-control btn btn-primary btn-danger btn-block btn-google')) !!}
            <span class="form-control-feedback"><span class="fa fa-google"></span></span>
        </div>
        {{--<div class="col-md-12">--}}
            {{--<div class="form-group">--}}
                {{--{!! link_to('auth/facebook', 'Login with Facebook', array('class' => 'form-control btn btn-success btn-block btn-facebook')) !!}--}}
                {{--<span class="form-control-feedback"><span class="fa fa-facebook"></span></span>--}}
                {{--<div class="hr-label"><span class="hr-label__text">or</span></div>--}}
            {{--</div>--}}
        {{--</div>--}}
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::button(trans('messages.otp_btn_text'), array('type' => 'button',  'class' => 'form-control btn btn-info btn-block otp-login-btn')) !!}
{{--                {!! Form::button('Login with OTP', array('type' => 'button',  'class' => 'form-control btn btn-info btn-block otp-login-btn')) !!}--}}

            </div>
        </div>
        <div class="col-md-12">
            <div class="hr-label"><span class="hr-label__text">{!! trans('messages.devider_text_login_modal') !!}</span></div>
        </div>

        <div class="col-md-12">
            <span class="email-error-message"></span>
            {!! Form::text('email', '', array('class' => 'form-control', 'placeholder' => 'Email', 'id' => 'user_email', 'required' => '1', 'type' => 'email')) !!}
        </div>
    </div>
    <div class="form-group col-md-12">
        <div class="col-md-12">
            <span class="password-error-message"></span>
            {!! Form::password('password', array('class' => 'form-control', 'id' => 'user_password', 'placeholder' => 'Password','required' => '1')) !!}
        </div>
    </div>
    <div id="myDiv">

    </div>

    <?php if (Session::get('hit') >= 3) { ?>
    <div class="form-group col-md-12 captchaCheck">
        <span class="captcha-error-message" style="color: red"></span>
        <div class="form-group col-md-12">
            <span id="rowCaptcha"><?php echo Captcha::img(); ?></span> <img onclick="changeCaptcha();"
                                                                            src="assets/images/refresh.png"
                                                                            class="reload" alt="Reload"/>
        </div>
        <div class="form-group" style="margin-top: 15px;">
            <input class="form-control required" required placeholder="Enter captcha code" name="captcha" type="text"
                   id="captcha">
        </div>
    </div>
    <?php } ?>

    <div class="form-group col-md-12">
        <div class="col-md-12">
            <button type="button" id="loginbtn" class="btn btn-primary pupup-login pull-right credential-login-form">Log
                in
            </button>
        </div>
    </div>
</div>

{!! Form::close() !!}

<div class="row">
    <div class="form-group col-md-12">
        <div class="login-need-help col-md-12">

            <a href="{{ url('forget-password') }}">{{trans('messages.forget_password')}}</a>
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


<script>
    $('.otp-login-btn').click(function () {

        btn = $(this);
        btn_content = btn.html();
        $('#user_login_modal').modal('hide')
        btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;' + btn_content);

        $.ajax({
            url: '/login/load-login-otp-form',
            type: 'post',
            data: {
                _token: $('input[name="_token"]').val()
            },
            success: function (response) {
                btn.html(btn_content);
                $(".login-otp").html(response);
                $('#otp_modal').modal();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);

            },
            beforeSend: function (xhr) {
                console.log('before send');
            },
            complete: function () {
                //completed
            }
        });

    });
</script>


<script>
    function changeCaptcha() {
        $.ajax({
            type: "GET",
            url: '<?php echo url(); ?>/re-captcha',
            success: function(data) {
                $("#rowCaptcha").html(data);
            }
        });
    }
</script>