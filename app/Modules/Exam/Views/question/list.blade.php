@extends('layouts.admin')

<?php
$accessMode = ACL::getAccsessRight('QuestionBank');
if (!ACL::isAllowed($accessMode, 'V'))
    die('no access right!');
?>

@section('content')
<div class="modal fade" id="question_upload" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        {!! Form::open(array('url' => 'exam/question-bank/upload-question-info/','method' => 'post', 'class' => 'form-horizontal', 
        'id' => 'questionUpload','enctype' =>'multipart/form-data', 'files' => 'true')) !!}
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Upload Question</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            {!! Form::label('import_question','Select Question File',['class'=>'col-sm-4 required-star']) !!}
                            <div class="col-sm-8">
                                {!! Form::file('import_question', '' ,['class'=>'form-control required','required']) !!}
                                {!! $errors->first('import_question','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div class="alert alert-warning">
                            <strong>Warning!</strong> Upload only csv, xls or xlsx file. Give proper heading following the sample. <br/>
                            You can see file sample <a href="/question_info_sample.xlsx" title="Sample file"><b> here</b></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<div class="col-md-12">
    {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
    {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}
    <div class="panel panel-primary">
        <div class="panel-heading" style="padding: 3px 15px !important;" >
            <div class="pull-left">
                <h6><span style="font-size: 18px;">{{ trans('messages.question_list') }}</span></h6>
            </div>
            {{--@if(ACL::getAccsessRight('exam','A'))--}}
            <div class="pull-right">
                <a  href="{{ url('exam/question-bank/download-question-info-excel/') }}" class="">
                    {!! Form::button('<i class="fa fa-cloud-download" aria-hidden="true"></i>'.' Download Format',array('type' => 'button',
                    'class' => 'btn btn-link','style="color:#fff;text-decoration:none;font-weight:bold;"')) !!}
                </a>
                <a href="" class="addProjectModal" data-toggle="modal" data-target="#question_upload">
                    {!! Form::button('<i class="fa fa-cloud-upload" aria-hidden="true"></i>'.' Upload Question',array('type' => 'button',
                    'class' => 'btn btn-link','style="color:#fff;text-decoration:none;font-weight:bold;"')) !!}
                </a>
                <a class="" href="{{ url('exam/question-bank/create') }}">
                    {!! Form::button('<i class="fa fa-plus"></i> '. trans('messages.create_question'), array('type' => 'button', 'class' => 'btn btn-default')) !!}
                </a>
            </div>
            {{--@endif --}}{{-- checking ACL --}}
            <div class="clearfix"></div>
        </div> <!-- /.panel-heading -->

        <div class="panel-body">
            <div class="table-responsive">
                <table id="list" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                    <thead class="">
                        <tr>
                            <th width="7%">#</th>
                            <th width="40%">Question</th>
                            <th width="15%">Question type</th>
                            <th width="15%">Updated</th>
                            <th width="20%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div> <!-- /.table-responsive -->
        </div> <!-- /.panel-body -->
    </div><!-- /.panel -->
</div><!-- /.col-lg-12 -->

@endsection

@section('footer-script')
@include('partials.datatable-scripts')

<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
<script>
    $(function () {
        $('#list').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{url("exam/question-bank/get-question-list")}}',
                method: 'post',
                data: function (d) {
                    d._token = $('input[name="_token"]').val();
                }
            },
            columns: [
                {data: 'serial', name: 'serial'},
                {data: 'question_name', name: 'question_name'},
                {data: 'exam_name', name: 'exam_name'},
                {data: 'last_update', name: 'last_update'},
                {data: 'action', name: 'action', orderable: false, searchable: true}
            ],
            "aaSorting": []
        });
    });

    function openMdoal(link, div) {
        $(link).on('click', function (e) {
            e.preventDefault();
            $('#' + div).html('<div style="text-align:center;"><h3 class="text-primary">Loading Form...</h3></div>');
            $(div).load(
                    $(this).attr('href'),
                    function (response, status, xhr) {
                        if (status === 'error') {
                            alert('error');
                            $(div).html('<p>Sorry, but there was an error:' + xhr.status + ' ' + xhr.statusText + '</p>');
                        }
                        return this;
                    });
        });
    }

    $(document).ready(function () {
        $("#questionUpload").validate({
            errorPlacement: function () {
                return false;
            }
        });
    });
</script>
@endsection
