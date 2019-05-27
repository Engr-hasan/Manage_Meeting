@extends('layouts.admin')

@section('content')



<style>
    .help-inline {
        color:red !important;
    }
    .limitedNumbSelect2{
        width: 100%;
    }
    textarea{ resize:none;}
</style>

    <?php
    $accessMode = ACL::getAccsessRight('Training');
    if (!ACL::isAllowed($accessMode, 'A'))
        die('no access right!');
    ?>

    <div class="col-lg-12">

        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

        <div class="panel panel-primary">
            <div class="panel-heading">
                <b> {!!trans('messages.new_training_material')!!} </b>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="col-lg-10">
                    {!! Form::open(array('url' => '/Training/store','method' => 'post', 'class' => 'form-horizontal', 'id' => 'agency-info',
                    'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}

                    <div class="form-group col-md-12 {{$errors->has('user_types') ? 'has-error' : ''}}">
                        {!! Form::label('user_types','Select User Type: ',['class'=>'col-md-4  required-star']) !!}
                        <div class="col-md-6">
                            {!! Form::select('user_types[]', $userTypes,'', ['class' => 'form-control input-sm limitedNumbSelect2', 'id'=>'user_types','multiple'=>'true']) !!}
                            {!! $errors->first('user_types','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group col-md-12 {{$errors->has('title') ? 'has-error' : ''}}">
                        {!! Form::label('title','Training Title: ',['class'=>'col-md-4  required-star']) !!}
                        <div class="col-md-6">
                            {!! Form::text('title', '', ['class'=>'form-control bnEng required', 'data-rule-maxlength'=>'150', 'id'=>'title']) !!}
                            {!! $errors->first('title','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group col-md-12 {{$errors->has('description') ? 'has-error' : ''}}">
                        {!! Form::label('description','Short Description: ',['class'=>'col-md-4  required-star']) !!}
                        <div class="col-md-6">
                            {!! Form::textarea('description', '', ['class'=>'form-control required']) !!}
                            {!! $errors->first('description','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group col-md-12 {{$errors->has('status') ? 'has-error' : ''}}">
                        {!! Form::label('status','Status: ',['class'=>'col-md-4  required-star']) !!}
                        <div class="col-md-6">
                            {!! Form::select('status', $status,'', ['class' => 'form-control input-sm', 'id'=>'status']) !!}
                            {!! $errors->first('status','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <button type="submit" class="btn btn-primary pull-right">
                            <i class="fa fa-chevron-circle-right"></i> Save
                        </button>
                    </div>

                    {!! Form::close() !!}<!-- /.form end -->

                    <div class="overlay" style="display: none;">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div><!-- /.box -->
            </div>
        </div>
    </div>

@endsection


@section('footer-script')

<link rel="stylesheet" href="{{ asset("assets/plugins/select2.min.css") }}">
<script src="{{ asset("assets/plugins/select2.min.js") }}"></script>

    <script>
        var _token = $('input[name="_token"]').val();
        $(document).ready(function(){
            //Select2
            $(".limitedNumbSelect2").select2({
                //maximumSelectionLength: 1
            });
        });
    </script>
    @endsection <!--- footer script--->