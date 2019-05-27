@extends('layouts.admin')

<?php
$accessMode = ACL::getAccsessRight('ResultProcess');
if (!ACL::isAllowed($accessMode, 'V'))
    die('no access right!');
?>


<style>
    .mini-stat {
        background: #fff;
        padding: 15px;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        margin-bottom: 20px;
        overflow: hidden;
    }
    .candidate {
        background: #337AB7 !important;
    }
    .participate {
        background: #250D3D !important;
    }
    .passed{
        background-color: #00a65a !important;
    }.fail{
        background-color: #C40D0D !important;
    }
     .percentage{
         background-color: #122b40 !important;
     }
    .mini-stat-icon {

         width: 60px;
         height: 60px;
         display: inline-block;
         line-height: 60px;
         text-align: center;
         font-size: 30px;
         background: #eee;
         -webkit-border-radius: 100%;
         -moz-border-radius: 100%;
         border-radius: 100%;
         float: left;
         margin-right: 10px;
         color: #fff;
     }
    .mini-stat-icon i {
        line-height: 60px;
    }
    .mini-stat-info {
          font-size: 12px;
          padding-top: 2px;
          color: #122b40;
      }.mini-stat-info span {
           display: block;
           font-size: 24px;
           font-weight: 600;
       }
    .exam-info tr td{
        /*border: none !important;*/
        color: #31708F;
        border-top: 1px solid #ddd !important;
        border-bottom: 1px solid #ddd !important;
    }

    .table > tbody > tr > td, .table > tfoot > tr > td {
        padding: 8px !important;
        line-height: 1.42857143 !important;
        vertical-align: top !important;
        border-top: 1px solid #ddd !important;
    }
</style>

@section('content')

    <div class="col-lg-12">

        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

        <div class="panel panel-primary">
            <div class="panel-heading" style="padding: 2px;">
                <span style=" font-size: 18px;">{{ $heading }}</span>
                <div class="clearfix"></div>
            </div>

            <!-- /.panel-heading -->
            <div class="panel-body">
                <fieldset>

                    <div class="panel profile" style="overflow: hidden;margin-bottom: 15px;border-radius: 0px;border-color: #cccccc">
                        <div class="panel-heading" style="overflow: hidden;background-color: #f2f2f2;;border-color: #cccccc">
                            <div class="row">
                                <div class="col-sm-12">
                                        {{--<h1 class="exam-icon" style="font-size: 90px;"><i class="fa fa-graduation-cap" aria-hidden="true"></i></h1>--}}
                                        <h2 style="color: #122b40;"><i class="fa fa-graduation-cap" aria-hidden="true"></i> Exam Name - {{ $examInfo->question_title }}</h2>
                                        <br/>


                                        <div class="table-responsive">
                                            <table class="table table-responsive exam-info">
                                                <tbody>
                                                <tr>
                                                    <td> <strong><i class="fa fa-file-o" aria-hidden="true"></i> Exam type:</strong></td>
                                                    <td>{{ $examInfo->exam_type }}</td>
                                                    <td><strong ><i class="fa fa-calendar" aria-hidden="true"></i> Exam available from:</strong></td>
                                                    <td>{{ date('d/m/Y',strtotime($examInfo->exam_available_from)) }}</td>
                                                    <td><strong ><i class="fa fa-calculator" aria-hidden="true"></i> Total question:</strong></td>
                                                    <td>{{ $examInfo->no_of_question_for_examinee }}</td>

                                                </tr>
                                                <tr>
                                                </tr>
                                                <tr>
                                                    <td><strong ><i class="fa fa-question-circle" aria-hidden="true"></i> Question Type:</strong></td>
                                                    <td>{{ $examInfo->exam_name }}</td>
                                                    <td><strong ><i class="fa fa-calendar" aria-hidden="true"></i> Exam disabled at:</strong></td>
                                                    <td>{{ date('d/m/Y',strtotime($examInfo->exam_disabled_at)) }}</td>
                                                    <td><strong ><i class="fa fa-check-square" aria-hidden="true"></i>  Mark per question:</strong></td>
                                                    <td>{{ $examInfo->mark_per_question }}</td>
                                                </tr>

                                                <tr>
                                                </tr>
                                                <tr>
                                                    <td> <strong><i class="fa fa-clock-o" aria-hidden="true"></i> Duration:</strong></td>
                                                    <td>{{ substr($examInfo->duration,0,-3) }} hour </td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                </div>

                            </div>

                            <div class="row" style="padding-top: 20px;">
                                <div class="col-md-4">
                                    <div class="mini-stat clearfix">
                                        <span class="mini-stat-icon candidate"><i class="fa fa-users" aria-hidden="true"></i></span>
                                        <div class="mini-stat-info">
                                            <span>{{ $examInfo->candidate }}</span>
                                            Candidate
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mini-stat clearfix">
                                        <span class="mini-stat-icon participate"><i class="fa fa-user" aria-hidden="true"></i></span>
                                        <div class="mini-stat-info">
                                            <span>{{ $examInfo->participate }}</span>
                                            Participate
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mini-stat clearfix">
                                        <span class="mini-stat-icon passed"><i class="fa fa-check" aria-hidden="true"></i></span>
                                        <div class="mini-stat-info">
                                            <span>{{ $examInfo->passed }}</span>
                                            Pass
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="mini-stat clearfix">
                                        <span class="mini-stat-icon fail"><i class="fa fa-times" aria-hidden="true"></i></span>
                                        <div class="mini-stat-info">
                                            <span>{{ $examInfo->failed }}</span>
                                            Fail
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mini-stat clearfix">
                                        <span class="mini-stat-icon percentage"><i class="fa fa-bar-chart" aria-hidden="true"></i></span>
                                        <div class="mini-stat-info">
                                            <span> {{ ($examInfo->passed>0)?round(($examInfo->passed/$examInfo->participate)*100,2):0 }}% </span>
                                            Pass Percentage
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="panel-body">
                            <table id="list" class="table table-striped table-hover table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead class="alert alert-info">
                                <tr>
                                    <th>#</th>
                                    <th>Examinee name</th>
                                    <th>Right answer</th>
                                    <th>Wrong answer</th>
                                    <th>Obtain marks</th>
                                    <th>Result</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>

                            <div class="row" style="padding-top: 15px;">
                                <div class="col-md-12">
                                    <a href="{{ url('/exam/result/list') }}">
                                        <button class="btn btn-default"><i class="fa fa-times"></i> Close</button>
                                    </a>
                                    @if($examInfo->schedule_status == 'Exam Taken')
                                    <a href="{{ url('/exam/result/publish/'.Encryption::encodeId($examInfo->schedule_id)) }}">
                                        <button class="btn btn-info pull-right"><i class="fa fa-chevron-circle-right"></i> Publish Result</button>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>



                </fieldset>
            </div><!-- /.box-body -->
        </div>
    </div>

@endsection


@section('footer-script')
    @include('partials.datatable-scripts')
    <script type="text/javascript">
        $(function () {
            $('#list').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{url("exam/result/examinee-list/".Encryption::encodeId($examInfo->schedule_id))}}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {data: 'serial', name: 'serial'},
                    {data: 'examinee_name', name: 'examinee_name'},
                    {data: 'right_answer', name: 'right_answer'},
                    {data: 'wrong_answer', name: 'wrong_answer'},
                    {data: 'mark_obtain', name: 'mark_obtain'},
                    {data: 'remarks', name: 'remarks'},
                    {data: 'action', name: 'action', orderable: false, searchable: true}
                ],
                "aaSorting": []
            });

        });


    </script>
@endsection <!--- footer script--->

