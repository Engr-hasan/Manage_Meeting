@extends('layouts.admin')

@section('content')
<?php
$accessMode = ACL::getAccsessRight('settings');
if (!ACL::isAllowed($accessMode, 'E'))
    die('no access right!');
?>
<div class="col-lg-12">

    {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
    {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

    <div class="panel panel-primary">
        <div class="panel-heading" style="font-size: large">
            {!! trans('messages.notice_edit') !!}
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            {!! Form::open(array('url' => '/settings/update-notice/'.$encrypted_id,'method' => 'patch', 'class' => 'form-horizontal smart-form', 'id' => 'notice-info',
            'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}

            <div class="form-group col-md-8 {{$errors->has('heading') ? 'has-error' : ''}}">
                {!! Form::label('heading','Heading: ',['class'=>'col-md-3  required-star']) !!}
                <div class="col-md-7">
                    {!! Form::text('heading',  $data->heading, ['class'=>'form-control bnEng required', 'size' => "10x5"]) !!}
                    {!! $errors->first('heading','<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="form-group col-md-8 {{$errors->has('details') ? 'has-error' : ''}}">
                {!! Form::label('details','Details: ',['class'=>'col-md-3  required-star']) !!}
                <div class="col-md-7">
                    {!! Form::textarea('details',  $data->details, ['class'=>'form-control bnEng required', 'size' => "10x5"]) !!}
                    {!! $errors->first('details','<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <?php
            $importance_arr = array(
                '' => 'Select One',
                'danger' => 'Danger',
                'info' => 'Info',
                'top' => 'Top',
                'warning' => 'Warning',
            );
            ?>
            <div class="form-group col-md-8 {{$errors->has('importance') ? 'has-error' : ''}}">
                {!! Form::label('importance','Importance: ',['class'=>'col-md-3  required-star']) !!}
                <div class="col-md-7">
                    {!! Form::select('importance',$importance_arr,  $data->importance, array('class'=>'form-control required')) !!}
                    {!! $errors->first('importance','<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="form-group col-md-12 {{$errors->has('status') ? 'has-error' : ''}}">
                {!! Form::label('status','Status: ',['class'=>'col-md-2 required-star']) !!}
                <div class="col-md-7">
                    @if($data->status == 'draft')
                    <label>{!! Form::radio('status', 'draft', $data->status  == 'draft', ['class'=>' required']) !!} Draft</label>
                    @endif
                    
                    @if(ACL::getAccsessRight('settings','E'))
                    &nbsp;&nbsp;
                    <label>{!! Form::radio('status', 'private', $data->status  == 'private', ['class'=>' required']) !!} Private</label>
                    &nbsp;&nbsp;
                    <label>{!! Form::radio('status', 'unpublished', $data->status  == 'unpublished', ['class'=>'required']) !!} Unpublished</label>
                            @if(Auth::user()->user_type=='1x101' OR Auth::user()->user_type=='2x202')
                            &nbsp;&nbsp;
                            <label>{!! Form::radio('status', 'public', $data->status  == 'public', ['class'=>'required']) !!} Public</label>
                            @endif
                    @endif
                    {!! $errors->first('status','<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="col-md-12">
                <div class="col-md-2">
                    <a href="{{ url('/settings/notice') }}">
                        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                    </a>
                </div>
                <div class="col-md-6 col-md-offset-1">
                    {!! CommonFunction::showAuditLog($data->updated_at, $data->updated_by) !!}
                </div>
                <div class="col-md-2">
                    @if(ACL::getAccsessRight('settings','E'))
                    <button type="submit" class="btn btn-primary pull-right">
                        <i class="fa fa-chevron-circle-right"></i> Save</button>
                    @endif
                </div>
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
        $("#notice-info").validate({
            errorPlacement: function () {
                return false;
            }
        });
    });
</script>
@endsection <!--- footer script--->