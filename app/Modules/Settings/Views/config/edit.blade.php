@extends('layouts.admin')

@section('page_heading',trans('messages.edit_config'))

@section('content')
    <?php $accessMode=ACL::getAccsessRight('settings');
    if(!ACL::isAllowed($accessMode,'E')) die('no access right!');
    ?>
<div class="col-lg-12">


    @include('partials.messages')

    <div class="panel panel-primary">
        <div class="panel-heading">
            <b> {!!trans('messages.edit_config')!!} of {{ $data->caption }} </b>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            {!! Form::open(array('url' => '/settings/update-config/'.$id,'method' => 'patch', 'class' => 'form-horizontal', 'id' => 'bank-info',
            'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}

            <div class="form-group col-md-12 {{$errors->has('caption') ? 'has-error' : ''}}">
                {!! Form::label('caption','Caption: ',['class'=>'col-md-2']) !!}
                <div class="col-md-6">
                    {!! Form::text('caption', $data->caption, ['class'=>'form-control textOnly','disabled'=>'disabled']) !!}
                </div>
            </div>

            <div class="form-group col-md-12 {{$errors->has('value') ? 'has-error' : ''}}">
                {!! Form::label('value','Value: ',['class'=>'col-md-2  required-star']) !!}
                <div class="col-md-6">
                    {!! Form::text('value', $data->value, ['class'=>'form-control required']) !!}
                </div>
            </div>

            <div class="form-group col-md-12 {{$errors->has('value2') ? 'has-error' : ''}}">
                {!! Form::label('value2','Value2: ',['class'=>'col-md-2']) !!}
                <div class="col-md-6">
                    {!! Form::text('value2', $data->value2, ['class'=>'form-control']) !!}
                </div>
            </div>

            <div class="form-group col-md-12 {{$errors->has('value3') ? 'has-error' : ''}}">
                {!! Form::label('value3','Value3: ',['class'=>'col-md-2']) !!}
                <div class="col-md-6">
                    {!! Form::text('value3', $data->value3, ['class'=>'form-control']) !!}
                </div>
            </div>

            <div class="form-group col-md-12 {{$errors->has('details') ? 'has-error' : ''}}">
                {!! Form::label('address','Address: ',['class'=>'col-md-2']) !!}
                <div class="col-md-6">
                    {!! Form::text('details', $data->details, ['class'=>'form-control']) !!}
                </div>
            </div>

            <div class="col-md-12">
                <div class="col-md-2">
                    <a href="{{ url('/settings/configuration') }}">
                        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                    </a>
                </div>
                <div class="col-md-6 col-md-offset-1">
                    @if($data->updated_at  &&  $data->updated_at> '0')
                        {!! CommonFunction::showAuditLog($data->updated_at, $data->updated_by) !!}
                    @endif
                </div>
                @if(ACL::getAccsessRight('settings','E'))
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary pull-right">
                        <i class="fa fa-chevron-circle-right"></i> Save</button>
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