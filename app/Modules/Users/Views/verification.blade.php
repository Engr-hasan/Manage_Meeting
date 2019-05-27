@extends('layouts.front')

@section("content")

    <div class="container" style="margin-top:30px;">
        <div class="row">
            <div class="col-md-10 col-md-offset-1" style="background: #ABD6AC; opacity:0.88; border-radius:8px;">
                <h3>&nbsp;</h3>
                @include('partials.messages')

                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading text-success">
                            <h3 class="text-center">Verification </h3>
                        </div>
                        <div class="panel-body">
                            {!! Form::open(array('url' => '/users/verification_store/'.$confirmationCode,'method' => 'patch', 'class' => 'form-horizontal',
                            'id'=> 'vreg_form')) !!}

                            <div class="col-md-12">
                                <h3>Terms of Usage of OSS Framework</h3>
                                Terms and conditions to use this system can be briefed as -
                                <ul>
                                    <li>You must follow any policies made available to you within the Services.</li>
                                    <li>You have to fill all the given fields with correct information and take responsibility if any wrong or misleading information has been given</li>
                                    <li>You are responsible for the activity that happens on or through your account. So, keep your password confidential.</li>
                                    <li>We may modify these terms or any additional terms that apply to a Service to, for example,
                                        reflect changes to the law or changes to our Services. You should look at the terms regularly.</li>
                                </ul>
                            </div>

                            <div class="col-md-12">
                                <fieldset>
                                    <div class="col-md-12"><br/></div>

                                    <div class="form-group has-feedback {{ $errors->has('details') ? 'has-error' : ''}}">
                                        <label  class="col-lg-6 text-left"> Additional information regarding this registration  (If any)</label>
                                        <div class="col-lg-6">
                                            {!! Form::textarea('details', $value = null, $attributes = array('size' => '30x4','class'=>'form-control',
                                            'placeholder'=>'Details Information','id'=>"details", 'data-rule-maxlength'=>'255')) !!}
                                            @if($errors->first('details'))
                                                <span  class="control-label">
                                            <em><i class="fa fa-times-circle-o"></i> {{ $errors->first('details','') }}</em>
                                        </span>
                                            @endif
                                        </div>
                                    </div>
                                </fieldset>
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
                            <!--</form>-->
                            {!! Form::close() !!}
                            <div class="clearfix"></div>
                        </div>
                    </div><!--/panel-->
                </div>
            </div>
        </div>
        @endsection

        @section('footer-script')
            <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.devbridge-autocomplete/1.2.24/jquery.autocomplete.min.js"></script>
            <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
            <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
            <script>
                $(function () {
                    var _token = $('input[name="_token"]').val();
                    $("#vreg_form").validate({
                        errorPlacement: function () {
                            return false;
                        }
                    });
                });

                $(document).ready(function () {
                    $("#district").change(function () {
                        var districtId = $('#district').val();
                        $(this).after('<span class="loading_data">Loading...</span>');
                        var self = $(this);
                        $.ajax({
                            type: "GET",
                            url: "<?php echo url(); ?>/users/get-thana-by-district-id",
                            data: {
                                districtId: districtId
                            },
                            success: function (response) {
                                var option = '<option value="">Select One</option>';
                                if (response.responseCode == 1) {
                                    $.each(response.data, function (id, value) {
                                        option += '<option value="' + id + '">' + value + '</option>';
                                    });
                                }
                                $("#thana").html(option);
                                $(self).next().hide();
                            }
                        });
                    });
                });
            </script>

            <style>
                input[type="checkbox"].error{
                    outline: 1px solid red !important;
                }
            </style>
@endsection