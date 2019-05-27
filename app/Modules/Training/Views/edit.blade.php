@extends('layouts.admin')


@section('content')
    <?php
    $accessMode = ACL::getAccsessRight('Training');
    if (!ACL::isAllowed($accessMode, 'A'))
        die('no access right!');
    ?>
    <section class="col-md-12">
        <div class="row">

            <!-- Horizontal Form -->
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <b style="font-size: large">{{trans('messages.training_edit')}} : {{$training->title}}</b>
                </div><!-- /.panel-heading -->

                <div class="panel-body">
                    <div class="col-md-10">
                        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
                        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

                        <!-- form start -->
                        {!! Form::open(array('url' => 'training/update/'.\App\Libraries\Encryption::encodeId($training->id),'method' => 'patch', 'class' => 'form-horizontal', 'id' => '',
                        'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
                        <div class="box-body">

                            <div class="form-group col-md-12 {{$errors->has('user_type') ? 'has-error' : ''}}">
                                {!! Form::label('user_types','User Type: ',['class'=>'col-md-4  required-star']) !!}
                                <div class="col-md-6">
                                    {!! Form::select('user_types[]', $userTypes, $select, ['class' => 'form-control input-sm limitedNumbSelect2', 'id'=>'user_type','multiple'=>'true']) !!}
                                    {!! $errors->first('user_types','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="form-group col-md-12 {{$errors->has('training_title') ? 'has-error' : ''}}">
                                {!! Form::label('title','Training Title: ',['class'=>'col-md-4  required-star']) !!}
                                <div class="col-md-6">
                                    {!! Form::text('title', $training->title, ['class'=>'form-control bnEng required', 'data-rule-maxlength'=>'150', 'id'=>'training_title']) !!}
                                    {!! $errors->first('title','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="form-group col-md-12 {{$errors->has('short_description') ? 'has-error' : ''}}">
                                {!! Form::label('description','Short Description: ',['class'=>'col-md-4  required-star']) !!}
                                <div class="col-md-6">
                                    {!! Form::textarea('description', $training->description, ['class'=>'form-control required']) !!}
                                    {!! $errors->first('description','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="form-group col-md-12 {{$errors->has('user_type') ? 'has-error' : ''}}">
                                {!! Form::label('status','Status: ',['class'=>'col-md-4  required-star']) !!}
                                <div class="col-md-6">
                                    {!! Form::select('status', $status, $training->status, ['class' => 'form-control input-sm', 'id'=>'status']) !!}
                                    {!! $errors->first('status','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="box-footer">
                                <div class="col-md-12">
                                    <div class="col-md-2">
                                        <a href="{{ url('/Training/') }}">
                                            {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                                        </a>
                                    </div>
                                    <div class="col-md-7 col-md-offset-1">
                                        {!! CommonFunction::showAuditLog($training->updated_at, $training->updated_by) !!}
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-save"></i> Save</button>
                                    </div>
                                </div>
                            </div>

                        </div><!-- /.box -->

                        {!! Form::close() !!}<!-- /.form end -->

                    </div>
                </div>
            </div>
        </div>
    </section>
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