@section('footer-script')
    <script src="{{ asset("assets/scripts/datatable/jquery.dataTables.min.js") }}"></script>
    <script src="{{ asset("assets/scripts/datatable/dataTables.bootstrap.min.js") }}"></script>
    <script src="{{ asset("assets/scripts/datatable/dataTables.responsive.min.js") }}"></script>
    <script src="{{ asset("assets/scripts/datatable/responsive.bootstrap.min.js") }}"></script>
    <script src="{{ asset("assets/newsTicker/jquery.ticker.js") }}"></script>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <script>
        //Marquee Ticker
        $(function(){
            var timer = !1;
            _Ticker = $(".TickerNews").newsTicker({});
            _Ticker.on("mouseenter",function(){
                var __self = this;
                timer = setTimeout(function(){
                    __self.pauseTicker();
                },200);
            });
            _Ticker.on("mouseleave",function(){
                clearTimeout(timer);
                if(!timer) return !1;
                this.startTicker();
            });
        });

        $(document).ready(function(){
            count = 0;
        });

        $(function () {
            $('.notice_heading').click(function () {
                $(this).parent().parent().find('.details').show();
                return false;
            });

            $('#object_report').click(function () {
                $('#object_report_content').load('{{URL::to("/web/get-report-object/REG_HALNAGAT")}}');

            });



            $('.login-cred-btn').click(function () {

                btn = $(this);
                btn_content = btn.html();
                btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;'+btn_content);

                $.ajax({
                    url: '/login/load-login-form',
                    type: 'post',
                    data: {
                        _token: $('input[name="_token"]').val()
                    },
                    success: function (response) {
                        btn.html(btn_content);
                        $(".login-info-box").html(response);
                        $('#user_login_modal').modal();
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


            $('.pupup-login').click(function () {
                $('#loginbtn').click();
            });

            $(document).on('keypress','#user_email,#user_password,#captcha',function(e){
                if(e.which == 13) {
                    $('#loginbtn').click();
                }
            });

            $(document).on('keypress','#otp_email,#sms_opt,#email_opt',function(e){
                if(e.which == 13) {
                    $('#otpnext1').click();
                }
            });

            $(document).on('keypress','#login_token',function(e){
                if(e.which == 13) {
                    $('#otpnext2').click();
                }
            });


//            var buttinNext1 = false;
//            var buttinNext2 = false;

            $('.otp-login-btn').click(function () {
//                alert(1);
//
//                if(buttinNext1)
//                {
//                    return false;
//                }
//                buttinNext1 = true;

                btn = $(this);
                btn_content = btn.html();
                btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;'+btn_content);

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
            function isValidEmailAddress(emailAddress) {
                var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
                return pattern.test(emailAddress);
            };

            $(document).on('click','.Next1',function(e){

//              alert(11);
//                if(buttinNext2)
//                {
//                    return false;
//                }
//                buttinNext2 = true;

                var email = $('#otp_email').val();
                if( email == '' || !isValidEmailAddress(email))
                {
                    $("#otp_email").addClass('error');
                    $(".email-error-message").text("Please enter your email");
                    return false;
                }
                else {
                    $("#otp_email").removeClass('error');
                    $(".email-error-message").text("");
                }
                if(!$('input[name=otp]').is(':checked'))
                {
                    alert('Please select option for receiving OTP');
                    return false;
                }
                btn = $(this);
                btn_content = btn.html();
                btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;'+btn_content);
                btn.prop('disabled', true);
                $.ajax({
                    url: '/login/otp-login-email-validation-with-token-provide',
                    type: 'post',
                    data: {
                        _token: $('input[name="_token"]').val(),
                        'email': email,
                        'otp' : $('#otpForm').find('input[name=otp]:checked').val()
                    },
                    success: function (response) {
                        btn.prop('disabled', false);
                        btn.html(btn_content);

                        if(response.responseCode == 1)
                        {
                            $('#otp_step_1').css("display", "none");
                            $('#otp_step_2').css("display", "block");
                            $('#otpnext1').css("display", "none");
                            $('#otpnext2').css("display", "block");
                        }
                        else
                        {
                            alert('Invalid Credentials');
                            return false;
                        }
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
            $(document).on('click','.Next2',function(e){
                btn.prop('disabled', true);
                var login_token = $('#login_token').val();
                var email = $('#otp_email').val();
                if( email == '' || !isValidEmailAddress(email))
                {
                    $("#otp_email").addClass('error');
                    $(".email-error-message").text("Please enter your email");
                    return false;
                }
                else {
                    $("#otp_email").removeClass('error');
                    $(".email-error-message").text("");
                }
                if(!login_token)
                {
                    alert('OTP should be given');
                    return false;
                }

                if(!email)
                {
                    alert('Data has mismatch');
                    return false;
                }


                btn = $(this);
                btn_content = btn.html();
                btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;'+btn_content);

                $.ajax({
                    url: '/login/otp-login-check',
                    type: 'post',
                    data: {
                        _token: $('input[name="_token"]').val(),
                        'email': email,
                        'login_token' : login_token
                    },
                    success: function (response) {

                        btn.html(btn_content);
                        btn.prop('disabled', false);

                        //console.log(response);
                        //alert(response);


                        if(response.responseCode == 1)
                        {
                            //console.log(response.data.redirect_to);
                            window.location.href = response.redirect_to;
                        }
                        else
                        {

                            $(".error-message").show();
                            $(".error-message").text(response.msg);
                        }
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



            $(document).on('click','#loginbtn',function(e){





//                $('#loginbtn').prop('disabled', true);
                var email = $('#user_email').val();
                var password = $('#user_password').val();
                var captcha = $('#captcha').val();
                if( email == '' || !isValidEmailAddress(email))
                {
                    $("#user_email").addClass('error');
                    $(".email-error-message").text("Please enter your email");
                    return false;
                }
                else {
                    $("#user_email").removeClass('error');
                    $(".email-error-message").text("");
                }

                if( password == '')
                {
                    $("#password").addClass('error');
                    $(".password-error-message").text("Please enter your password");
                    return false;
                }
                else {
                    $("#password").removeClass('error');
                    $(".password-error-message").text("");
                }
                if ('<?php echo Session::get('hit'); ?>' >= 3) {

                    if( captcha == '')
                    {
                        $("#captcha").addClass('error');
                        $(".captcha-error-message").text("Please enter your recaptcha code");
                        return false;
                    }
                }




                btn = $(this);
                btn_content = btn.html();
                btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;'+btn_content);

                $.ajax({
                    url: '/login/check',
                    type: 'post',
                    data: {
                        _token: $('input[name="_token"]').val(),
                        'email': email,
                        'password' : password,
                        'captcha' : captcha
                    },
                    success: function (response) {
                        btn.html(btn_content);

                        //console.log(response);
                        //alert(response.msg);


                        if(response.responseCode == 1)
                        {
                            //console.log(response.data.redirect_to);
                            window.location.href = response.redirect_to;
                        }
                        else
                        {
//                            alert("hi");
                            count += 1;
//                            alert(count);
                            if (count >= 3) {
                                var button = '<div class="form-group col-md-12"><span  style="color: red" class="captcha-error-message"></span>'
                                        +'<div class="form-group col-md-12"><span id="rowCaptcha"> <?php echo Captcha::img(); ?></span> <img onclick="changeCaptcha();" src="assets/images/refresh.png" class="reload" alt="Reload" />'
                                        +'</div><div class="form-group" style="margin-top: 15px;">'
                                        +'<input class="form-control required" required placeholder="Enter captcha code" name="captcha" type="text" id="captcha">'
                                        +'</div></div>';

                                $(".captchaCheck").remove();
                                $("#myDiv").html(button);
                            }

                            $(".error-message").show();
                            $(".error-message").text(response.msg);
                        }
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


        });
    </script>
    <script>
        /*
        login validation
         */
        $(document).on('click','.otp-login-form',function(e){
            /*
             email validation function
             */
            function isValidEmailAddress(emailAddress) {
                var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
                return pattern.test(emailAddress);
            };

            if($("input[name=email]").val() == '' || !isValidEmailAddress( $("input[name=email]").val() ))
            {
                $("input[name=email]").addClass('error');
                $(".email-error-message").text("Please enter your email");
                return false;
            }
            else {
                $("input[name=email]").removeClass('error');
                $(".email-error-message").text("");
            }
            if($("input[name=code]").val() == ''){
                $("input[name=code]").addClass('error');
                $(".password-error-message").text("Please enter your password");
                return false;
            }
            else {
                $("input[name=code]").removeClass('error');
                $(".password-error-message").text("");
            }
        });
        $(document).on('click','.credential-login-form2',function(e){

            alert(1);
            /*
             email validation function
             */
            function isValidEmailAddress(emailAddress) {
                var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
                return pattern.test(emailAddress);
            };
            if($("#user_email").val() == '' || !isValidEmailAddress( $("#user_email").val() ))
            {
                $("#user_email").addClass('error');
                $(".email-error-message").text("Please enter your email");
                return false;
            }
            else {
                $("#user_email").removeClass('error');
                $(".email-error-message").text("");
            }
            if($("#user_password").val() == ''){
                $("#user_password").addClass('error');
                $(".password-error-message").text("Please enter your password");
                return false;
            }
            else {
                $("#user_password").removeClass('error');
                $(".password-error-message").text("");
            }

        });

        //            #### training details information
        $('.training_heading').click(function () {
            $(this).hide();
            $(this).parent().parent().find('.training_details').show();
            return false;
        });

        //        Training Schedule for public users
        $(document).on('click','.scheduleDetails',function(e){
            btn = $(this);
            btn_content = btn.html();
            btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;'+btn_content);
            var training_id = btn.attr('id');
            $.ajax({
                url: '/training-public/get-training-public-schedule',
                type: 'GET',
                data: {
                    training_id: training_id
                },
                success: function (response) {
                    btn.html(btn_content);
                    $(".scheduleInfo").html(response);
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

        //        Training application form for public users
        $(document).on('click','.applyForTraining',function(e){

            var schedule_id = $(this).attr('id');
            btn = $(this);
            btn_content = btn.html();
            btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;'+btn_content);
            btn.prop('disabled', true);

            $.ajax({
                url: '/training-public/application-form',
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: $('input[name="_token"]').val(),
                    schedule_id: schedule_id
                },
                success: function (response) {
                    btn.html(btn_content);
                    if(response.responseCode == 1)
                    {
                        $(".scheduleInfo").html(response.public_html);
                        $(".scheduleInfo").load();

                        // Triggering on datepicker on-success
                        $('.datepicker').datetimepicker({
                            viewMode: 'years',
                            format: 'DD-MMM-YYYY',
                            maxDate: (new Date()),
                            minDate: '01/01/1905'
                        });
                    }
                    else
                    {
                        btn.prop('disabled', false);
                        alert(response.msg);
                        return false;
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);

                },
                beforeSend: function (xhr) {
                    console.log('before send'.xhr);
                },
                complete: function () {
                    //completed
                }
            });
        });
    </script>


@endsection