@extends('layouts.admin')

<?php
$accessMode = ACL::getAccsessRight('QuestionBank');
if (!ACL::isAllowed($accessMode, 'V'))
    die('no access right!');
?>

@section('content')
<div class="col-lg-12">
    {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
    {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

    <div class="panel panel-primary">
        <div class="panel-heading"><strong>Uploading Questions</strong></div><!-- /.panel-heading -->

        <div class="panel-body">
            {!! Form::open(array('url' => 'exam/question-bank/save-question-excel/','method' => 'post', 'class' => 'form-horizontal', 'id' => 'inputForm',
            'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
            <input type="hidden" name="excelUrl" value="{{$excelUrl}}"/>
            <div class="table-responsive" style="clear:both">
                <table id="list" class="table table-bordered dt-responsive" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Question type</th>
                            <th>Question Name</th>
                            <th>Option 1</th>
                            <th>Option 2</th>
                            <th>Option 3</th>
                            <th>Option 4</th>
                            <th>Correct Answer</th>
                        </tr>
                    </thead>
                    <tbody>
                    <div class="errormsg"></div>
                    @foreach($excelData as $excelRow)
                    <tr>
                                <td>{{$excelRow->question_type}}</td>
                                <td>{{$excelRow->question_name}}</td>
                                <td>{{$excelRow->answer_option_1}}</td>
                                <td>{{$excelRow->answer_option_2}}</td>
                                <td>{{$excelRow->answer_option_3}}</td>
                                <td>{{$excelRow->answer_option_4}}</td>
                                <td>{{ $excelRow->correct_answer }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-md-12">
                <div class="pull-left">
                    <a href="{{URL::to('/exam/question-bank/list')}}" class="btn btn-default">Cancel</a>
                </div>
                <div class="pull-right">
                    <button type="submit" id="saveCsvData" class="btn btn-primary">Save</button>
                </div>
            </div>
            {!! Form::close() !!}<!-- /.form end -->

        </div>
        <!-- /.panel-body -->
    </div>
    <!-- /.panel -->
</div>
<!-- /.col-lg-12 -->

@endsection

@section('footer-script')

<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.devbridge-autocomplete/1.2.24/jquery.autocomplete.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

<script>
$(document).ready(function () {
$("#saveCsvData").on('click', function () {

});
</script>
@endsection
