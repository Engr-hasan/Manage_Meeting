@extends('layouts.admin')

@section('content')

    <?php
    $accessMode = ACL::getAccsessRight('BoardMeting');
    if (!ACL::isAllowed($accessMode, 'A')) {
        die('You have no access right! Please contact with system admin for more information.');
    }
    ?>
    @include('partials.messages')
    <div class="col-lg-12">


        <div class="panel panel-info">
            <div class="panel-heading">
                <b>{!! trans('messages.new_document') !!}</b>
            </div>
        <!-- /.panel-heading -->
            <div class="panel-body">
                {!! Form::open(array('url' => '/board-meting/share-document/store-document','method' => 'post', 'class' => 'form-horizontal smart-form', 'id' => 'entry-form',
                'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}

                <div class="col-md-12">

                    <div class="row">
                        <div class="col-md-12 form-group">
                            {!! Form::label('name','Name of document', ['class'=>'col-md-3 required-star']) !!}
                            <div class="col-md-5 {{$errors->has('doc_name') ? 'has-error': ''}}">
                                {!! Form::text('doc_name', null, ['class' => 'col-md-12 form-control input-sm required']) !!}
                                {!! $errors->first('doc_name','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-12 form-group">
                            {!! Form::label('attachment','Attachment', ['class'=>'col-md-3 required-star']) !!}
                            <div class="col-md-5 {{$errors->has('agenda_file') ? 'has-error': ''}}">
                                <input type="file" name="attachment" class="required form-control">
                                <span style="font-size: 11px;color: #8e8989;">[File Type Must be pdf,xls,xlsx,ppt,pptx,docx,doc Max size: 3MP ]</span>
                            </div>
                        </div>
                        <div class="col-md-12 form-group">
                            {!! Form::label('tag','Tag', ['class'=>'col-md-3 required-star']) !!}
                            <div class="col-md-5 {{$errors->has('tag') ? 'has-error': ''}}">
                                {!! Form::select('tag', [''=>'Select One', '1'=>'Normal', '2'=>'Moderate', '3'=>'High'],'', ['class' => 'col-md-12 form-control input-sm required']) !!}
                                {!! $errors->first('tag','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                    <div>
                        <a href="{{ url('/board-meting/lists') }}">
                            {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                        </a>
                        @if(ACL::getAccsessRight('BoardMeting','A'))
                            <button type="submit" class="btn btn-primary tostar pull-right">
                                <i class="fa fa-chevron-circle-right"></i> Save</button>
                        @endif
                    </div>

                </div><!--/col-md-12-->

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
            $("#entry-form").validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });
    </script>
@endsection <!--- footer script--->