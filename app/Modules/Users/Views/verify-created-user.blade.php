@extends('layouts.front')

@section("content")

    <div class="container" style="margin-top:30px;">
        <div class="row">
            <div class="col-md-10 col-md-offset-1" style="background: #ABD6AC; opacity:0.88; border-radius:8px;">
                <h3 class="text-center">Verification Process</h3>
                @include('partials.messages')
                <hr/>
                <div class="col-md-12 col-sm-12">
                    {!! Form::open(array('url' => '/users/created-user-verification/'.$encrypted_token,'method' => 'patch', 'class' => 'form-horizontal',
                    'id'=> 'vreg_form')) !!}

                    <div class="col-md-12 col-sm-12">
                        <h3>Terms of Usage of OSS Framework</h3>
                        Terms and conditions to use this system can be briefed as -
                        <ol>
                            <li>You must follow any policies made available to you within the Services.</li>
                            <li>You have to fill all the given fields with correct information and take responsibility if any wrong or misleading information has been given</li>
                            <li>You are responsible for the activity that happens on or through your account. So, keep your password confidential.</li>
                            <li>We may modify these terms or any additional terms that apply to a Service to, for example,
                                reflect changes to the law or changes to our Services. You should look at the terms regularly.</li>
                        </ol>
                    </div>

                    <div class="col-md-8"><br/></div>

                    <div class="col-md-12">
                        <label>
                            {!! Form::checkbox('user_agreement', 1, null,  ['class'=>'required']) !!}
                            &nbsp;
                            I have read and agree to terms and conditions.
                        </label>
                    </div>

                    <div class="col-md-8"><br/></div>

                    <div class="col-md-8 col-md-offset-2">
                        <div class="form-group">
                            <div class="col-lg-5 col-lg-offset-3">
                                <button type="submit" class="btn btn-block btn-primary btn-large"><b>Save and Continue</b></button>
                            </div>
                        </div>
                        <div class="col-md-8"><br/></div>
                        <div class="form-group">
                            <div class="col-lg-12 col-lg-offset-3">
                                Already have an account? <b>{!! link_to('users/login', 'Login', array('class' => '')) !!}</b>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    {!! Form::close() !!}
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-script')

    <script>
        $(function () {
            var _token = $('input[name="_token"]').val();
            $("#vreg_form").validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });
    </script>

    <style>
        input[type="checkbox"].error{
            outline: 1px solid red
        }
    </style>
@endsection