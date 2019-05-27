@extends('layouts.admin')
@section('content')
<?php
$accessMode = ACL::getAccsessRight('settings');
if (!ACL::isAllowed($accessMode, 'A'))
    die('no access right!');
?>
<div class="col-lg-12">

    {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
    {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

    <div class="panel panel-primary">
        <div class="panel-heading" style="font-size: large;">
            <b> Notice Form </b>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            {!! Form::open(array('url' => '/settings/store-notice','method' => 'patch', 'class' => 'form-horizontal smart-form', 'id' => 'notice-info',
            'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}


            <div class="form-group col-md-8 {{$errors->has('heading') ? 'has-error' : ''}}">
                {!! Form::label('heading','Heading: ',['class'=>'col-md-3  required-star']) !!}
                <div class="col-md-7">
                    {!! Form::text('heading', '', ['class'=>'form-control bnEng required', 'size' => "10x5"]) !!}
                    {!! $errors->first('heading','<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="form-group col-md-8 {{$errors->has('details') ? 'has-error' : ''}}">
                {!! Form::label('details','Details: ',['class'=>'col-md-3  required-star']) !!}
                <div class="col-md-7">
                    {!! Form::textarea('details', '', ['class'=>'form-control bnEng required', 'size' => "10x5"]) !!}
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
                        {!! Form::select('importance',$importance_arr, null, array('class'=>'form-control required')) !!}
                        {!! $errors->first('importance','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

            <div class="form-group col-md-12 {{$errors->has('status') ? 'has-error' : ''}}">
                {!! Form::label('status','Status: ',['class'=>'col-md-2  required-star']) !!}
                <div class="col-md-10">
                    <label>{!! Form::radio('status', 'draft', ['class'=>'form-control required']) !!} Draft</label>
                    &nbsp;&nbsp;
                    <label>{!! Form::radio('status', 'private', ['class'=>'form-control required']) !!} Private</label>
                    &nbsp;&nbsp;
                    <label>{!! Form::radio('status', 'unpublished', ['class'=>'form-control required']) !!} Unpublished</label>
                    @if(Auth::user()->user_type=='1x101' OR Auth::user()->user_type=='2x202')
                    &nbsp;&nbsp;
                    <label>{!! Form::radio('status', 'public', ['class'=>'form-control required']) !!} Public</label>
                    @endif
                    {!! $errors->first('status','<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="col-md-12">
                <a href="{{ url('/settings/notice') }}">
                    {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                </a>
                @if(ACL::getAccsessRight('settings','A'))
                <button type="submit" class="btn btn-primary pull-right">
                    <i class="fa fa-chevron-circle-right"></i> Save</button>
                @endif
            </div><!-- /.box-footer -->

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