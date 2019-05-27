@extends('layouts.admin')

@section('page_heading')

@section('content')
    <?php
    $accessMode = ACL::getAccsessRight('search');
    if (!ACL::isAllowed($accessMode, 'V'))
        die('no access right!');
    ?>

    <div class="col-lg-12">

        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

        <div class="panel panel-primary">
            <div class="panel-heading">
                <strong>{!! trans('messages.faq_form') !!}</strong>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                {!! Form::open(array('url' => '/faq/store-faq-article','method' => 'patch', 'class' => 'form-horizontal smart-form', 'id' => 'faq-info',
                'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}

                <div class="col-md-8">
                    <div class="form-group col-md-12 {{$errors->has('question') ? 'has-error' : ''}}">
                        {!! Form::label('question','Question: ',['class'=>'col-md-3  required-star']) !!}
                        <div class="col-md-9">
                            {!! Form::text('question', '', ['class'=>'form-control bnEng required', 'size' => "10x5"]) !!}
                            {!! $errors->first('question','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group col-md-12 {{$errors->has('answer') ? 'has-error' : ''}}">
                        {!! Form::label('answer','Answer: ',['class'=>'col-md-3  required-star']) !!}
                        <div class="col-md-9">
                            {!! Form::textarea('answer', '', ['class'=>'wysihtml5-editor bnEng form-control required', 'size' => "10x5"]) !!}
                            {!! $errors->first('answer','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group col-md-12 {{$errors->has('type') ? 'has-error' : ''}}">
                        {!! Form::label('type','Type: ',['class'=>'col-md-3  required-star']) !!}
                        <div class="col-md-7">
                            {!! Form::select('type[]',$faq_types, null, array('multiple'=>true,'class'=>'form-control required')) !!}
                            {!! $errors->first('type','<span class="help-block">:message</span>') !!}
                        </div>
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
                    <a href="{{ url('/faq/index') }}">
                        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                    </a>
                    @if(ACL::getAccsessRight('search','A'))
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
    <link rel="stylesheet" href="{{ asset("assets/stylesheets/bootstrap3-wysihtml5.min.css") }}">
    <script src="{{ asset("assets/scripts/bootstrap3-wysihtml5.all.min.js") }}" type="text/javascript"></script>

    <script>
        var _token = $('input[name="_token"]').val();

        var age = -1;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function () {
            $("#faq-info").validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });

        $(function () {
            $(".wysihtml5-editor").wysihtml5();
        });
    </script>
    <style>
        ul, ol {
            list-style-type: none;
        }
    </style>
    @endsection <!--- footer script--->