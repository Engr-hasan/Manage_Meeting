{!! Form::open(array('url' => '', 'method' => '' ,'id'=>'otpForm')) !!}
<div class="row">

    <div id="otp_step_1">

        <div class="form-group col-md-12">
            <div class="col-md-12">
                <span class="email-error-message"></span>
                {!! Form::text('email', '', array('class' => 'form-control', 'placeholder' => 'Enter Your Email','id'=>'otp_email')) !!}
            </div>
        </div>
        <div class="form-group col-md-12 otp_receiver">
            {!! Form::label('otp_by', 'OTP Receive through:', array('class' => 'col-md-8 text-left', 'id' => '')) !!}
            <div class="col-md-4">
                <input type="radio" id="sms_opt" name="otp" value="1"> SMS &nbsp;<input type="radio" id="email_opt" name="otp" value="2"> Email
            </div>
        </div>

    </div>


    <div id="otp_step_2" style="display:none;">
        <div class="form-group col-md-12">
            <div style="display: none" class="alert alert-danger error-message alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><span class="error-message"></span></div>

            {!! Form::label('login_token', 'OTP:', array('class' => 'col-md-4 text-right')) !!}
            <div class="col-md-6">
                {!! Form::text('login_token', '', array('class' => 'form-control', 'placeholder' => 'Enter Your OTP','id'=>'login_token')) !!}
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group col-md-12">
            <button type="button" class="btn btn-primary pull-right  Next1" id="otpnext1">Next</button>
            <button type="button" style="display:none;float:right;" class="btn btn-primary pull-right Next2" id="otpnext2">Log in</button>
        </div>
    </div>

</div>
{!! Form::close() !!}