@extends('layouts.admin')

@section('content')
    <?php $accessMode=ACL::getAccsessRight('settings');
    if(!ACL::isAllowed($accessMode,'E')) die('no access right!');
    ?>
    <div class="col-lg-12">

        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

        <div class="panel panel-primary">
            <div class="panel-heading">
                <strong>{!!trans('messages.faq_cat_edit')!!}</strong>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                {!! Form::open(array('url' => '/faq/update-faq-cat/'.$encrypted_id,'method' => 'patch', 'class' => 'form-horizontal smart-form', 'id' => 'faq_cat-info',
                'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}


                <div class="form-group col-md-8 {{$errors->has('name') ? 'has-error' : ''}}">
                    {!! Form::label('name','Name: ',['class'=>'col-md-3  required-star']) !!}
                    <div class="col-md-7">
                        {!! Form::text('name', $data->name, ['class'=>'form-control required', 'size' => "10x5"]) !!}
                        {!! $errors->first('name','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-2">
                        <a href="{{ url('/faq/faq-cat') }}">
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
            $("#faq_cat-info").validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });
    </script>
    @endsection <!--- footer script--->