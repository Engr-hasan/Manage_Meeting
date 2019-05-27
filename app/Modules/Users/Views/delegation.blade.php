@extends('layouts.admin')

@section('page_heading','Delegated Information')

@section("content")
    <?php
    $accessMode = ACL::getAccsessRight('user');
    if (!ACL::isAllowed($accessMode, 'E'))
        die('no access right!');
    ?>
    <section class="content">

        <!-- Default box -->
        <div class="box box-success col-md-10">
            <div class="box-header with-border">
                {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
                {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

            </div>

            <div class="box-body">
                <div class="col-md-12">
                    {!! Form::open(array('url' => '/users/store-delegation','method' => 'patch', 'class' => 'form-horizontal', 'id' => 'delegation_form',
                    'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
                    <div class="panel">
                        <div class="panel-body">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <strong>Delegation Form</strong>
                                </div> <!-- /.panel-heading -->
                                <div class="panel-body">

                                    @if($isDelegate != 0)
                                        <div class="col-sm-12">
                                            <div class="panel panel-danger">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title"> This user is already delegated to the following user <i class="fa fa-rocket"></i><i class="fa fa-rocket"></i></h3>
                                                </div>
                                                <div class="panel-body">
                                                    <div style="text-align: center;">
                                                        <h4>Delegation Information:</h4> <br/>
                                                        <b>Name : </b> {{ $info->user_full_name }}<br/>
                                                        <b>Designation : </b>{{ $info->desk_name }}<br/>
                                                        <b>Email : </b>{{ $info->user_email }}<br/>
                                                        <b>Mobile : </b>{{ $info->user_phone }}<br/><br/>
                                                        <a class="remove-delegation btn btn-primary" onclick="return confirm('Are you sure?')" href="{{ url('/users/remove-deligation/'. \App\Libraries\Encryption::encodeId($delegate_to_user_id)) }}">
                                                            <i class="fa fa-share-square-o"></i> Remove Delegation</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-md-12">
                                            {!! Form::open(array('url' => '/users/store-delegation','method' => 'patch', 'class' => 'form-horizontal', 'id' => 'delegation_form',
                        'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
                                            <div class="form-group has-feedback {{ $errors->has('user_full_name') ? 'has-error' : ''}}">
                                                <label  class="col-md-3 text-right">User Name : </label>
                                                <div class="col-md-4">
                                                    {{ $info->user_full_name }}
                                                </div>
                                            </div>

                                            <div class="form-group has-feedback {{ $errors->has('user_phone') ? 'has-error' : ''}}">
                                                <label  class="col-md-3 text-right">Contact Number : </label>
                                                <div class="col-md-4">
                                                    {{ $info->user_phone }}
                                                </div>
                                            </div>

                                            <div class="form-group has-feedback {{ $errors->has('designation') ? 'has-error' : ''}}">
                                                <label  class="col-md-3 text-right">Designation : </label>
                                                <div class="col-md-4">
                                                    {{ $info->designation }}
                                                </div>
                                            </div>

                                            {{--<div class="form-group has-feedback {{ $errors->has('desk_name') ? 'has-error' : ''}}">--}}
                                            {{--<label  class="col-md-3 text-right">Designation From : </label>--}}
                                            {{--<div class="col-md-4">--}}
                                            {{--{{ $info->desk_name }}--}}
                                            {{--</div>--}}
                                            {{--</div>--}}
                                            <div class="form-group has-feedback {{ $errors->has('desk_id') ? 'has-error' : ''}}">
                                                <label  class="col-md-3 text-right">User Type : </label>
                                                <div class="col-md-4">
                                                    {{--{!! Form::hidden('user_id',$info->id) !!}--}}
                                                    {!! Form::hidden('user_id',$info->id, $attributes = array('class'=>'form-control',
                                                    'placeholder'=>'Enter your','id'=>"delegate_form_user_id")) !!}
                                                    {{ $info->type_name }}
                                                </div>
                                            </div>
                                            <div class="form-group has-feedback {{ $errors->has('designation') ? 'has-error' : ''}}">
                                                <label  class="col-md-3 text-right required-star">Delegate To User Type :</label>
                                                <div class="col-md-4">
                                                    {!! Form::select('designation', $designation, '', $attributes = array('class'=>'form-control required', 'onchange'=>'getUserDeligate()', 'placeholder' => 'Select one', 'id'=>"designation")) !!}
                                                </div>
                                            </div>
                                            <div class="form-group has-feedback {{ $errors->has('delegated_user') ? 'has-error' : ''}}">
                                                <label  class="col-md-3 text-right required-star">Delegate To User :</label>

                                                <div class="col-md-4">
                                                    {!! Form::select('delegated_user', [] , '', $attributes = array('class'=>'form-control required',
                                                    'placeholder' => 'Select user', 'id'=>"delegated_user")) !!}
                                                </div>
                                            </div>
                                            <div class="form-group has-feedback {{ $errors->has('remarks') ? 'has-error' : ''}}">
                                                <label  class="col-md-3 text-right required-star">Remarks :</label>

                                                <div class="col-md-4">
                                                    {!! Form::text('remarks', null, $attributes = array('class'=>'form-control required',
                                                    'rows'=>'5','placeholder'=>'Enter your Remarks','id'=>"remarks")) !!}

                                                </div>
                                            </div>
                                            <div class='clearfix'></div>
                                            <div class="col-md-3"></div>
                                            <div class="form-group col-md-4">
                                                @if(ACL::getAccsessRight('user','A'))
                                                    <button type="submit" class="btn btn-block btn-primary"><b>Deligate</b></button>
                                                @endif
                                            </div>

                                            {!! Form::close() !!}
                                        </div>
                                    @endif


                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endsection

            @section('footer-script')
                <script>
                    $(function() {
                        $("#delegation_form").validate({
                            errorPlacement: function() {
                                return false;
                            }
                        });
                    });
                    function getUserDeligate() {
                        var _token = $('input[name="_token"]').val();
                        var designation = $('#designation').val();
                        var delegate_form_user_id = $('#delegate_form_user_id').val();

                        $.ajax({
                            url: '{{url("users/get-delegate-userinfos")}}',
                            type: 'post',
                            data: {
                                _token: _token,
                                designation: designation,
                                delegate_form_user_id: delegate_form_user_id
                            },
                            dataType: 'json',
                            success: function(response) {
                                html = '<option value="">Select One</option>';

                                $.each(response, function(index, value) {
                                    html += '<option value="' + value.id + '" >' + value.user_full_name + '</option>';
                                });
                                $('#delegated_user').html(html);
                            },
                            beforeSend: function(xhr) {
                                console.log('before send');
                            },
                            complete: function() {
                                //completed
                            }
                        });
                    }
                </script>
        @endsection <!--- footer-script--->
