@extends('layouts.admin')
@section('content')
    <section class="content" id="LoanLocator">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    {!! Form::open(array('url' => '/meeting-form/add','method' => 'post','id' => 'appClearenceForm','role'=>'form','enctype'=>'multipart/form-data')) !!}
                    <div class="panel panel-red" id="inputForm">
                        <div class="panel-heading">{{\App\Libraries\CommonFunction::getMonthCurrentPrevious(date('m'))}} পর্যন্ত লিডারশীপ কার্যক্রমের বিবরণী</div>
                        <div class="panel-body" style="margin:6px;">
                            <div class="panel panel-primary">
                                <div class="panel-heading"><strong>{{trans('messages.required_info')}}</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-8  {{$errors->has('team_name') ? 'has-error': ''}}">
                                                {!! Form::label('team_name',trans('টিমের নাম'),['class'=>'col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('team_name','', ['maxlength'=>'500',
                                                    'class' => 'form-control input-sm  bnEng required']) !!}
                                                    {!! $errors->first('team_name','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-8  {{$errors->has('team_leader_name') ? 'has-error': ''}}">
                                                {!! Form::label('team_leader_name','টিম লিডারের নাম ' ,['class'=>'col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('team_leader_name',Auth::user()->user_full_name, ['maxlength'=>'500',
                                                    'class' => 'form-control readonly input-sm  bnEng required','readonly' => 'true']) !!}
                                                    {!! $errors->first('team_leader_name','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-8  {{$errors->has('no_of_member') ? 'has-error': ''}}">
                                                {!! Form::label('no_of_member',trans('টিমের মোট সদস্য সংখ্যা'),['class'=>'col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('no_of_member','', ['maxlength'=>'500',
                                                    'class' => 'form-control input-sm bnEng required']) !!}
                                                    {!! $errors->first('no_of_member','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-8  {{$errors->has('women_member') ? 'has-error': ''}}">
                                                {!! Form::label('task_description',trans('মহিলা সদস্য'),['class'=>'col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('women_member','', ['maxlength'=>'1000',
                                                    'class' => 'form-control input-sm  bnEng']) !!}
                                                    {!! $errors->first('women_member','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-8  {{$errors->has('intern_member') ? 'has-error': ''}}">
                                                {!! Form::label('intern_member',trans('ইন্টার্ন সদস্য'),['class'=>'col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('intern_member','', ['maxlength'=>'500',
                                                    'class' => 'form-control input-sm bnEng']) !!}
                                                    {!! $errors->first('intern_member','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div><!--/col-md-12-->
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong >{{trans('বিগত মাসের উল্লেখযোগ্য কার্যক্রম-সংক্রান্ত তথ্যঃ')}}</strong>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-xs btn-info pull-right" id="addTableRows_previous_month_work" ><i class="fa fa-plus"></i></button>
                                            <a href="#" section-data="section1" class="btn btn-primary btn-xs pull-right previousmonthdata" data-toggle="modal" data-target="#largeModal">Click Here for Previous Month Data</a>
                                            <div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title" id="previousMonthHead"></h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <table id="preMonth" class="table table-bordered">
                                                                    <thead>

                                                                    </thead>
                                                                    <tbody>

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    <div class="panel-body" >
                                        <table class="table table-bordered" id="tablemainwork">
                                            <thead>
                                            <tr>
                                                 <th class="text-center">#</th>
                                                <th class="text-center">
                                                    <div class="tooltip-demo">
                                                        <a data-toggle="tooltip" data-placement="top" title="" class="btn btn-default help-button" data-original-title="বিগত মাসের পরিকল্পিত হলে টিক দিন" aria-describedby="tooltip260504">
                                                            <i class=" fa fa-question"></i>
                                                        </a>
                                                    </div>
                                                </th>
                                                <th class="text-center"> </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="width: 6%"  class="text-center">
                                                <span class="sNo">1</span></td>
                                                    <td style="width: 6%" class="text-center"><label>
                                                            {!! Form::checkbox('oldone[]',1,null, null) !!}
                                                        </label></td>
                                                    <td class="text-center" >
                                                        <div class="form-group clearfix">
                                                                <div class="col-md-11  {{$errors->has('previous_month_main_work_info') ? 'has-error': ''}}">
                                                                    <div class="col-md-12">
                                                                        {!! Form::textarea('previous_month_main_work_info[]','', ['maxlength'=>'500','rows'=>'4',
                                                                        'class' => 'form-control   bnEng required']) !!}
                                                                        {!! $errors->first('previous_month_main_work_info','<span class="help-block">:message</span>') !!}
                                                                    </div>
                                                                </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            <div class="panel panel-primary">
                                <div class="panel-heading"><strong>{{trans('বিগত মাসে টিমের গঠনমূলক কার্যক্রমঃ')}}</strong>
                                        <button type="button" class="btn btn-xs btn-info pull-right" id="addTableRows_previous_month_constructive_work" ><i class="fa fa-plus"></i></button>
                                        <a href="#" section-data="section2" class="btn btn-primary btn-xs pull-right previousmonthdata" data-toggle="modal" data-target="#largeModal">Click Here for Previous Month Data</a>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-bordered" id="tableconstructivework">
                                        <thead>
                                        <tr>
                                             <th class="text-center">#</th>
                                            <th class="text-center">
                                                <div class="tooltip-demo">
                                                    <a data-toggle="tooltip" data-placement="top" title="" class="btn btn-default help-button" data-original-title="বিগত মাসের পরিকল্পিত হলে টিক দিন" aria-describedby="tooltip260504">
                                                        <i class=" fa fa-question"></i>
                                                    </a>
                                                </div>
                                            </th>
                                            <th class="text-center"> </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td  class="text-center">    <span class="sNo">1</span></td>
                                            <td style="width: 6%" class="text-center"><label>
                                                    {!! Form::checkbox('oldtwo[]',1,null, null) !!}
                                                </label></td>
                                            <td class="text-center" >
                                                <div class="form-group clearfix">
                                                    <div class="col-md-11  {{$errors->has('previous_month_constructive_work') ? 'has-error': ''}}">
                                                        <div class="col-md-12">
                                                            {!! Form::textarea('previous_month_constructive_work[]','', ['maxlength'=>'500','rows'=>'4',
                                                            'class' => 'form-control   bnEng required']) !!}
                                                            {!! $errors->first('previous_month_constructive_work','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <strong>{{trans('বিগত মাসের টিমের দক্ষতা বৃদ্ধি প্রসঙ্গঃ')}}</strong>
                                    <button type="button" class="btn btn-xs btn-info pull-right" id="addTableRows_increase_capability" ><i class="fa fa-plus"></i></button>
                                    <a href="#" section-data="section3" class="btn btn-primary btn-xs pull-right previousmonthdata" data-toggle="modal" data-target="#largeModal">Click Here for Previous Month Data</a>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-bordered" id="tableincreasecapability">
                                        <thead>
                                        <tr>
                                             <th class="text-center">#</th>
                                            <th class="text-center">
                                                <div class="tooltip-demo">
                                                    <a data-toggle="tooltip" data-placement="top" title="" class="btn btn-default help-button" data-original-title="বিগত মাসের পরিকল্পিত হলে টিক দিন" aria-describedby="tooltip260504">
                                                        <i class=" fa fa-question"></i>
                                                    </a>
                                                </div>
                                            </th>
                                            <th class="text-center"> </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td style="width: 6%"  class="text-center">
                                                <span class="sNo">1</span></td>
                                            <td style="width: 6%" class="text-center"><label>
                                                    {!! Form::checkbox('oldthree[]',1,null, null) !!}
                                                </label></td>
                                            <td class="text-center" >
                                                <div class="form-group clearfix">
                                                    <div class="col-md-11  {{$errors->has('previous_month_main_work_info') ? 'has-error': ''}}">
                                                        <div class="col-md-12">
                                                            {!! Form::textarea('increase_capability[]','', ['maxlength'=>'500','rows'=>'4',
                                                            'class' => 'form-control   bnEng required']) !!}
                                                            {!! $errors->first('previous_month_main_work_info','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <strong>{{trans('বিগত মাসে মানব সম্পদের যথাযথ ব্যবহারঃ')}}</strong>
                                    <button type="button" class="btn btn-xs btn-info pull-right" id="addTableRows_manpower_uses" ><i class="fa fa-plus"></i></button>
                                    <a href="#" section-data="section4" class="btn btn-primary btn-xs pull-right previousmonthdata" data-toggle="modal" data-target="#largeModal">Click Here for Previous Month Data</a>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-bordered" id="tablemanpoweruses">
                                        <thead>
                                        <tr>
                                             <th class="text-center">#</th>
                                            <th class="text-center">
                                                <div class="tooltip-demo">
                                                    <a data-toggle="tooltip" data-placement="top" title="" class="btn btn-default help-button" data-original-title="বিগত মাসের পরিকল্পিত হলে টিক দিন" aria-describedby="tooltip260504">
                                                        <i class=" fa fa-question"></i>
                                                    </a>
                                                </div>
                                            </th>
                                            <th class="text-center"> </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td style="width: 6%"  class="text-center">
                                                <span class="sNo">1</span></td>
                                            <td style="width: 6%" class="text-center"><label>
                                                    {!! Form::checkbox('oldfour[]',1,null, null) !!}
                                                </label></td>
                                            <td class="text-center" >
                                                <div class="form-group clearfix">
                                                    <div class="col-md-11  {{$errors->has('manpower_uses') ? 'has-error': ''}}">
                                                        <div class="col-md-12">
                                                            {!! Form::textarea('manpower_uses[]','', ['maxlength'=>'500','rows'=>'4',
                                                            'class' => 'form-control   bnEng required']) !!}
                                                            {!! $errors->first('manpower_uses','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="panel panel-primary">
                                <div class="panel-heading"><strong>{{trans('বিগত মাসের নতুন সদস্য অন্তর্ভুক্তকরণঃ')}}</strong>
                                    <button type="button" class="btn btn-xs btn-info pull-right" id="addTableRows_new_member" ><i class="fa fa-plus"></i></button>
                                    <a href="#" section-data="section5" class="btn btn-primary btn-xs pull-right previousmonthdata" data-toggle="modal" data-target="#largeModal">Click Here for Previous Month Data</a>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-bordered" id="tablenewmember">
                                        <thead>
                                        <tr>
                                             <th class="text-center">#</th>
                                            <th class="text-center">
                                                <div class="tooltip-demo">
                                                    <a data-toggle="tooltip" data-placement="top" title="" class="btn btn-default help-button" data-original-title="বিগত মাসের পরিকল্পিত হলে টিক দিন" aria-describedby="tooltip260504">
                                                        <i class=" fa fa-question"></i>
                                                    </a>
                                                </div>
                                            </th>
                                            <th class="text-center"> </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td style="width: 6%"  class="text-center">
                                                <span class="sNo">1</span></td>
                                            <td style="width: 6%" class="text-center"><label>
                                                    {!! Form::checkbox('oldfive[]',1,null, null) !!}
                                                </label></td>
                                            <td class="text-center" >
                                                <div class="form-group clearfix">
                                                    <div class="col-md-11  {{$errors->has('new_member') ? 'has-error': ''}}">
                                                        <div class="col-md-12">
                                                            {!! Form::textarea('new_member[]','', ['maxlength'=>'500','rows'=>'4',
                                                            'class' => 'form-control   bnEng required']) !!}
                                                            {!! $errors->first('new_member','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <strong>{{trans('পরবর্তী মাসে পরিকল্পিত উদ্যোগসমূহঃ')}}</strong>
                                    <button type="button" class="btn btn-xs btn-info pull-right" id="addTableRows_next_month_Initiative" ><i class="fa fa-plus"></i></button>
                                    <a href="#" section-data="section6" class="btn btn-primary btn-xs pull-right previousmonthdata" data-toggle="modal" data-target="#largeModal">Click Here for Previous Month Data</a>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-bordered" id="tablenextmonthinitiatives">
                                        <thead>
                                        <tr>
                                             <th class="text-center">#</th>

                                            <th class="text-center"> </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td style="width: 6%"  class="text-center">
                                                <span class="sNo">1</span></td>

                                            <td  class="text-center" >
                                                <div class="form-group clearfix">
                                                    <div class="col-md-11  {{$errors->has('next_month_initiative') ? 'has-error': ''}}">
                                                        <div class="col-md-12">
                                                            {!! Form::textarea('next_month_initiative[]','', ['maxlength'=>'500','rows'=>'4',
                                                            'class' => 'form-control   bnEng required']) !!}
                                                            {!! $errors->first('next_month_initiative','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                                <!--/panel-body-->
                    </div> <!--/panel-->
                            <div style="margin:6px;">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <button type="submit" class="btn btn-primary btn-md cancel" value="draft"
                                                name="actionBtn">Save as Draft
                                        </button>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6 text-right">
                                        <button type="submit" class="btn btn-primary btn-md" value="save"
                                                name="actionBtn">Submit
                                        </button>
                                    </div>
                                    <!-- /.form end -->
                                </div>
                            </div>
                    {!! Form::close() !!}
                    <br/>
                    <br/>

                </div>
            </div>
        </div>
    </section>
@endsection
@section('footer-script')
    <script type="text/javascript">

        $(document).ready(function() {
           $('#addTableRows_previous_month_work').on('click',function () {
               var tableone ='tablemainwork';
               var inputname='previous_month_main_work_info';
               var deleteclass='deleteone';
               var cehckboxname='oldone';
               addTableRowmainwork(tableone,inputname,deleteclass,cehckboxname);
           });
            $('#addTableRows_previous_month_constructive_work').on('click',function () {
                var tableone ='tableconstructivework';
                var inputname='previous_month_constructive_work';
                var deleteclass='deletetwo';
                var cehckboxname='oldtwo';
                addTableRowmainwork(tableone,inputname,deleteclass,cehckboxname);
            });
            $('#addTableRows_increase_capability').on('click',function () {
                var tablethree ='tableincreasecapability';
                var inputname='increase_capability';
                var deleteclass='deletethree';
                var cehckboxname='oldthree';
                addTableRowmainwork(tablethree,inputname,deleteclass,cehckboxname);
            });
            $('#addTableRows_manpower_uses').on('click',function () {
                var tablethree ='tablemanpoweruses';
                var inputname='manpower_uses';
                var deleteclass='deletefour';
                var cehckboxname='oldfour';
                addTableRowmainwork(tablethree,inputname,deleteclass,cehckboxname);
            });

            $('#addTableRows_new_member').on('click',function () {
                var tablethree ='tablenewmember';
                var inputname='new_member';
                var deleteclass='deletefive';
                var cehckboxname='oldfive';
                addTableRowmainwork(tablethree,inputname,deleteclass,cehckboxname);
            });
            $('#addTableRows_next_month_Initiative').on('click',function () {
                var tablethree ='tablenextmonthinitiatives';
                var inputname='next_month_initiative';
                var deleteclass='deletesix';
                var cehckboxname='';
                addTableRowmainwork(tablethree,inputname,deleteclass,cehckboxname);
            });

        });

        function addTableRowmainwork (table,inputname,deleteclass,cehckboxname){

            var count=$('#'+table+' tr').length;

            var html_code = "<tr id='row" + count + "'>";
            html_code += '<td  class="text-center">';
            html_code += '<span class="sNo">' + count + '</span>';
            html_code += '</td>';
            if(cehckboxname !=""){
                html_code += '<td style="width: 6%;"  class="text-center">';
                html_code += '<input type ="checkbox" name="'+cehckboxname+'['+count+']"/>';
                html_code += '</td>';
            }
            html_code += '<td><div class="form-group clearfix"> <div class="col-md-11"><div class="col-md-12">';
            html_code +='<textarea maxlength="500" rows="4" class="form-control   bnEng required" name="'+inputname+'['+count+'] cols="50"></textarea>';
            html_code += '{!! $errors->first('','<span class="help-block">:message</span>') !!}';
            html_code += '</div></div><div class="col-md-1"><a href="javascript:void(0)"  class="btn btn-xs btn-danger '+deleteclass+'"> <i class="fa fa-minus"></i> </a></div> </div> ';
            html_code += '</td>';
            $('#'+table +' tbody').append(html_code);
        }

        $(document).on('click', '.deleteone', function () {
            var countwork = $('#tablemainwork tr').length;
            if (countwork > 1) {
                jQuery(this).parents("tr").remove();
                var tableone='tablemainwork';
                arrangeSno(tableone);
            }
        });

        $(document).on('click', '.deletetwo', function () {
            var countwork = $('#tableconstructivework tr').length;
            if (countwork > 1) {
                jQuery(this).parents("tr").remove();
                var tabletwo='tableconstructivework';
                arrangeSno(tabletwo);
            }
        });
        $(document).on('click', '.deletethree', function () {
            var countwork = $('#tableincreasecapability tr').length;
            if (countwork > 1) {
                jQuery(this).parents("tr").remove();
                var tabletwo='tableincreasecapability';
                arrangeSno(tabletwo);
            }
        });

        $(document).on('click', '.deletefour', function () {
            var countwork = $('#tablemanpoweruses tr').length;
            if (countwork > 1) {
                jQuery(this).parents("tr").remove();
                var tabletwo='tablemanpoweruses';
                arrangeSno(tabletwo);
            }
        });
        $(document).on('click', '.deletefive', function () {
            var countwork = $('#tablenewmember tr').length;
            if (countwork > 1) {
                jQuery(this).parents("tr").remove();
                var tabletwo='tablenewmember';
                arrangeSno(tabletwo);
            }
        });
        $(document).on('click', '.deletesix', function () {
            var countwork = $('#tablenextmonthinitiatives tr').length;
            if (countwork > 1) {
                jQuery(this).parents("tr").remove();
                var tabletwo='tablenextmonthinitiatives';
                arrangeSno(tabletwo);
            }
        });


        function arrangeSno(table) {
            var i = 0;
            $('#'+table+' tr').each(function () {
                $(this).find(".sNo").html(i);
                i++;
            });

        }
    </script>

    <script type="text/javascript">
        $("#appClearenceForm").validate();
        $("#pr_division").change(function () {
            var divisionId = $(this).val();
            $(this).after('<span class="loading_data">Loading...</span>');
            var self = $(this);
            $.ajax({
                type: "GET",
                url: "<?php echo url(); ?>/users/get-district-by-division",
                data: {
                    divisionId: divisionId
                },
                success: function (response) {
                    var option = '<option value="">Select One</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            option += '<option value="' + id + '">' + value + '</option>';
                        });
                    }
                    $("#pre_district").html(option);
                    $(self).next().hide();
                }
            });
        });
        // get thana list by district id for Office Address

        $("#pre_district").change(function () {
            var self = $(this);
            var districtId = $(this).val();
            if (districtId !== '') {
                $(this).after('<span class="loading_data">Loading...</span>');
                $("#loaderImg").html("<img style='margin-top: -15px;' src='<?php echo url(); ?>/public/assets/images/ajax-loader.gif' alt='loading' />");
                $.ajax({
                    type: "GET",
                    url: "<?php echo url(); ?>/users/get-thana-by-district-id",
                    data: {
                        districtId: districtId
                    },
                    success: function (response) {
                        var option = '<option value="">Select One</option>';
                        if (response.responseCode == 1) {
                            $.each(response.data, function (id, value) {
                                // for edit list , applicant thana
                                {{--if (id == '{{$users->thana}}'){--}}
                                {{--option += '<option value="'+ id + '" selected>' + value + '</option>';--}}
                                {{--}--}}
                                //                                    else {
                                option += '<option value="' + id + '">' + value + '</option>';
//                                    }
                            });
                        }
                        $("#pre_thana").html(option);
                        self.next().hide();
                    }
                });
            }
        });


        $("#per_division").change(function () {
            var divisionId = $(this).val();
            $(this).after('<span class="loading_data">Loading...</span>');
            var self = $(this);
            $.ajax({
                type: "GET",
                url: "<?php echo url(); ?>/users/get-district-by-division",
                data: {
                    divisionId: divisionId
                },
                success: function (response) {
                    var option = '<option value="">Select One</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            option += '<option value="' + id + '">' + value + '</option>';
                        });
                    }
                    $("#per_district").html(option);
                    $(self).next().hide();
                }
            });
        });
        $("#per_district").change(function () {
            var self = $(this);
            var districtId = $(this).val();
            if (districtId !== '') {
                $(this).after('<span class="loading_data">Loading...</span>');
                $("#loaderImg").html("<img style='margin-top: -15px;' src='<?php echo url(); ?>/public/assets/images/ajax-loader.gif' alt='loading' />");
                $.ajax({
                    type: "GET",
                    url: "<?php echo url(); ?>/users/get-thana-by-district-id",
                    data: {
                        districtId: districtId
                    },
                    success: function (response) {
                        var option = '<option value="">Select One</option>';
                        if (response.responseCode == 1) {
                            $.each(response.data, function (id, value) {
                                // for edit list , applicant thana
                                {{--if (id == '{{$users->thana}}'){--}}
                                {{--option += '<option value="'+ id + '" selected>' + value + '</option>';--}}
                                {{--}--}}
                                //                                    else {
                                option += '<option value="' + id + '">' + value + '</option>';
//                                    }
                            });
                        }
                        $("#per_thana").html(option);
                        self.next().hide();
                    }
                });
            }
        });


        $("#bank").change(function () {
            var bank_id = $(this).val();
            $(this).after('<span class="loading_data">Loading...</span>');
            var self = $(this);
            $.ajax({
                type: "GET",
                url: "<?php echo url(); ?>/loan-locator/get-branch-by-bank",
                data: {
                    bank_id: bank_id
                },
                success: function (response) {
                    var option = '<option value="">Select One</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            option += '<option value="' + id + '">' + value + '</option>';
                        });
                    }
                    $("#branch").html(option);
                    $(self).next().hide();
                }
            });
        });

        function uploadDocument(targets, id, vField, isRequired) {
            var inputFile = $("#" + id).val();
            if (inputFile == '') {
                $("#" + id).html('');
                document.getElementById("isRequired").value = '';
                document.getElementById("selected_file").value = '';
                document.getElementById("validateFieldName").value = '';
                document.getElementById(targets).innerHTML = '<input type="hidden" class="required" value="" id="' + vField + '" name="' + vField + '">';
                if ($('#label_' + id).length)
                    $('#label_' + id).remove();
                return false;
            }

            try {
                document.getElementById("isRequired").value = isRequired;
                document.getElementById("selected_file").value = id;
                document.getElementById("validateFieldName").value = vField;
                document.getElementById(targets).style.color = "red";
                var action = "{{url('/loan-locator/upload-document')}}";
                $("#" + targets).html('Uploading....');
                var file_data = $("#" + id).prop('files')[0];
                var form_data = new FormData();
                form_data.append('selected_file', id);
                form_data.append('isRequired', isRequired);
                form_data.append('validateFieldName', vField);
                form_data.append('_token', "{{ csrf_token() }}");
                form_data.append(id, file_data);
                $.ajax({
                    target: '#' + targets,
                    url: action,
                    dataType: 'text', // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    type: 'post',
                    success: function (response) {
                        $('#' + targets).html(response);
                        var fileNameArr = inputFile.split("\\");
                        var l = fileNameArr.length;
                        if ($('#label_' + id).length)
                            $('#label_' + id).remove();
                        var doc_id = parseInt(id.substring(4));
                        var newInput = $('<label class="saved_file_' + doc_id + '" id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + ' <a href="javascript:void(0)" onclick="EmptyFile(' + doc_id + ')"><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a></b></label>');
//                        var newInput = $('<label id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + '</b></label>');
                        $("#" + id).after(newInput);
                        //check valid data
                        var validate_field = $('#' + vField).val();
                        if (validate_field == '') {
                            document.getElementById(id).value = '';
                        }
                    }
                });
            } catch (err) {
                document.getElementById(targets).innerHTML = "Sorry! Something Wrong.";
            }
        }

        /*Click Here for Previous Month Data show modal*/
        $(document).ready(function () {
            $('.previousmonthdata').click(function () {
                var section_data = $(this).attr('section-data');
                preMonth(section_data);
                $('#preMtonth tbody').empty();
                $('#previousMonthHead').empty();

                if(section_data == 'section1'){
                    $('#previousMonthHead').text('বিগত মাসের উল্লেখযোগ্য কার্যক্রম-সংক্রান্ত তথ্যঃ');
                }else if(section_data == 'section2'){
                    $('#previousMonthHead').text('বিগত মাসে টিমের গঠনমূলক কার্যক্রমঃ');
                }else if(section_data == 'section3'){
                    $('#previousMonthHead').text('বিগত মাসের টিমের দক্ষতা বৃদ্ধি প্রসঙ্গঃ');
                }else if(section_data == 'section4'){
                    $('#previousMonthHead').text('বিগত মাসে মানব সম্পদের যথাযথ ব্যবহারঃ');
                }else if(section_data == 'section5'){
                    $('#previousMonthHead').text('বিগত মাসের নতুন সদস্য অন্তর্ভুক্তকরণঃ');
                }else if(section_data == 'section6'){
                    $('#previousMonthHead').text('পরবর্তী মাসে পরিকল্পিত উদ্যোগসমূহঃ');
                }else{
                    console.log("Section Not Found!");
                }
            });
        });

        function preMonth(section_data){
            $.ajax({
                url: "<?php echo url(); ?>/meeting-form/previous-month-data",
                type: "POST",
                data: {
                    section_data: section_data
                },
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.responsecode==1){
                        $('#preMonth tbody').html(response.data);
                        if(section_data == 'section6'){
                            $('#preMonth thead').html('<tr>'+
                                '<th class="text-center" width="80%">Description</th>'+
                                '<th class="text-center" width="20%">Created at</th></tr>');
                        }else{
                            $('#preMonth thead').html('<tr><th class="text-center" width="5%" id="checkbox"><button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="top" title="বিগত মাসের পরিকল্পিত" style="border-radius: 50%;border: 2px solid gray;">\n' +
                                '  <i class=" fa fa-question"></i>\n' +
                                '</button></th>'+
                                '<th class="text-center" width="75%">Description</th>'+
                                '<th class="text-center" width="20%">Created at</th></tr>');
                        }
                    }
                },
                error: function (response) {
                    var nodata='<tr><td class="text-center">No Records Found</td></tr>';
                    $('#preMonth tbody').html(nodata);
                }
            });
        }
        /*Click Here for Previous Month Data show modal*/
    </script>
@endsection