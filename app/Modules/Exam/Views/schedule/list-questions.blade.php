@section('content')

<?php
$accessMode = ACL::getAccsessRight('Scheduling');
if (!ACL::isAllowed($accessMode, 'V'))
    die('no access right!');
?>

{!! Form::open(array('url' => '/exam/schedule/add-questions','method' => 'post', 'class' => 'form-horizontal','id' => 'ScheduleQuestionForm',
'role' => 'form','enctype' =>'multipart/form-date')) !!}

<div class=" modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title" id="myModalLabel"> <b> Add Questions From List</b> </h4>
</div>

<div class="modal-body">

    <div class="errorMsg alert alert-danger alert-dismissible hidden">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button></div>
    <div class="successMsg alert alert-success alert-dismissible hidden">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button></div>

    <div class="panel panel-info">
        <div class="panel-heading"> <b> List of Questions</b></div> <!-- /.panel-heading -->

        <div class="panel-body">
            <div class="table-responsive">
                <table id="list" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                    <thead class="">
                        <tr>
                            <th width="5%">#</th>
                            <th>Question</th>
                            <th width="15%">Question type</th>
                            <th width="20%">Last Updated</th>
                            <th width="7%">Add</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div> <!-- /.table-responsive -->

        </div>

    </div>

</div>

<div  class="modal-footer">
    <div class="row">
        <div class="col-md-12">
            {{-- @if(ACL::getAccsessRight('exam','E')) --}}
            <input type="hidden" name="schedule_id" value="{{ $_id }}">
            <button type="submit" class="btn btn-info pull-right submit_btn" id="submit_btn">
                <i class="fa fa-plus-square"></i> Add</button>
            {{-- @endif --}} {{-- Checking ACL --}}
        </div>
    </div>
</div>

{!! Form::close() !!}<!-- /.form end -->

<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>

<script type="text/javascript">
    $(function () {
        $('#list').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{url("/exam/schedule/get-question-list/".$_id)}}',
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
                {data: 'add', name: 'add', orderable: false}
            ],
            "aaSorting": []
        });
    });

    $(document).ready(function(){
        $('.submit_btn').on('click',function(){
            var selected = false;
            $('.questions:checked').each(function(i,checkbox){
                selected = true;
            });
            if(selected){
                return true;
            }

            var x = $('.questions').each(function(i,checkbox){});
            var questionOnList = x.length;

            if(questionOnList>0){
                alert("Please select questions");
            }else{
                alert("No question available in list to select");
            }
            return false;
        })
    });

</script>

