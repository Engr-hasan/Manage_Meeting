@extends('layouts.admin')

@section('page_heading',trans('messages.edit_user_type'))

@section('content')
    <?php $accessMode=ACL::getAccsessRight('settings');
    if(!ACL::isAllowed($accessMode,'E')) die('no access right!');
    ?>
    @include('partials.messages')
    <div class="col-lg-12">




        <div class="panel panel-primary">
            <div class="panel-heading">
                <b> {!! trans('messages.user_type_edit') !!} {{ $data->type_name }} </b>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                {!! Form::open(array('url' => '/settings/update-user-type/'.Encryption::encodeId($data->id),'method' => 'patch', 'class' => 'form-horizontal', 'id' => 'bank-info',
                'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}

                <div class="form-group col-md-12 {{$errors->has('caption') ? 'has-error' : ''}}">
                    {!! Form::label('type_name','Type Name: ',['class'=>'col-md-2']) !!}
                    <div class="col-md-6">
                        {!! Form::text('type_name', $data->type_name, ['class'=>'form-control']) !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('value') ? 'has-error' : ''}}">
                    {!! Form::label('security_profile','Security Profile: ',['class'=>'col-md-2  required-star']) !!}
                    <div class="col-md-6">
                        {!! Form::select('security_profile', $security_profiles, $data->security_profile_id, ['class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('value') ? 'has-error' : ''}}">
                    {!! Form::label('auth_token_type','Auth Token Type: ',['class'=>'col-md-2  required-star']) !!}
                    <div class="col-md-6">
                        {!! Form::select('auth_token_type', ['optional'=>'optional','mandatory'=>'mandatory'], $data->auth_token_type, ['class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('value2') ? 'has-error' : ''}}">
                    {!! Form::label('db_access_data','DB Access Data: ',['class'=>'col-md-2']) !!}
                    <div class="col-md-6">
                        {!! Form::text('db_access_data', Encryption::decode($data->db_access_data), ['class'=>'form-control']) !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('value2') ? 'has-error' : ''}}">
                    {!! Form::label('status','User Status: ',['class'=>'col-md-2']) !!}
                    <div class="col-md-6">
                        {!! Form::select('status', ['active'=>'active','inactive'=>'inactive'], $data->status, ['class' => 'form-control']) !!}
                    </div>
                </div>



                <div class="col-md-12">
                    <div class="col-md-6 col-md-offset-1">
                        @if($data->updated_at  &&  $data->updated_at> '0')
                            {!! CommonFunction::showAuditLog($data->updated_at, $data->updated_by) !!}
                        @endif
                    </div>
                    @if(ACL::getAccsessRight('settings','E'))
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary pull-right">
                                <i class="fa fa-chevron-circle-right"></i> Update</button>
                        </div>
                    @endif
                </div>

            {!! Form::close() !!}<!-- /.form end -->

                <div class="overlay" style="display: none;">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
            </div><!-- /.box -->
        </div>
    </div>

@endsection


@section('footer-script')

    <script>
        var _token = $('input[name="_token"]').val();

        var age = -1;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function () {
            $("#bank-info").validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });
    </script>
@endsection <!--- footer script--->