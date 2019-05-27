@extends('layouts.front')

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

    <div class="col-md-12">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <hr class="top-border"/>
        </div>
        <div class="col-md-1"></div>
    </div>

    <div class="col-md-12">
        <div class="row">

            <div class="col-md-4"></div>
            <div class="col-md-4 box-div">
                <br /><br />
               <center><h4 style="font-size:17px;">Change or get your new password</h4></center>
                <br />
                {!!session()->has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'.session('success') .'</div>' : '' !!}
                {!!session()->has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. session('error') .'</div>' : '' !!}


                {!! Form::open(array('url' => 'users/reset-forgotten-password','method' => 'patch', 'class' => '', 'id' => 'forgetPassForm')) !!}

                <fieldset>
                    <div class="form-group">
                        <div class="col-md-12">

                            <label>Email Address:</label>
                            {!! Form::email('user_email', $value = null, $attributes = array('class'=>'form-control required email',
                            'placeholder'=>'Enter your Email Address','id'=>"user_email")) !!}
                        </div>
                    </div>

                    <div class="clearfix">&nbsp;</div>

                    <div class="form-group">
                        <div class="col-md-12">
                            {!! Recaptcha::render() !!}
                            {!! $errors->first('g-recaptcha-response','<span class="help-block">:message</span>') !!}
                        </div>
                        <div class="clearfix">&nbsp;</div>
                        <div class="col-md-4">
                            <button type="submit" value="forgentpassword" name="forgentpassword"  class="btn btn-block btn-primary"><b>Submit</b></button><br/>
                        </div>
                        <div class="clearfix">&nbsp;</div>

                        <span class="col-md-12">
                            <b>Go back to login page {!! link_to('/', 'Login', array("class" => " ")) !!}</b>
                        </span>
                        <span class="col-md-12">
                            <b>Don't have an account? {!! link_to('signup', 'Sign Up', array("class" => " ")) !!}</b>
                        </span>
                    </div>
                </fieldset>
                {!! Form::close() !!}

            </div>
            <div class="col-md-4"></div>
        </div>
    </div>
@endsection
<script src="{{ asset("assets/scripts/jquery.min.js") }}" src="" type="text/javascript"></script>
<script src="{{ asset("assets/scripts/jquery.validate.js") }}"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ready(function () {
        $("#forgetPassForm").validate({
            errorPlacement: function () {
                return false;
            }
        });
    });
</script>