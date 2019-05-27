@extends('layouts.admin')

@section('content')
    <?php
    $accessMode = ACL::getAccsessRight('BoardMeting');
    if (!ACL::isAllowed($accessMode, 'A')) {
        die('You have no access right! Please contact with system admin for more information.');
    }
    ?>
    @include('BoardMeting::progress-bar')
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>
                    {{trans('messages.add_committee_page_title')}}
                    ({{$board_meeting_data->meting_number}})
                </b>
                {{--<b>{!! trans('messages.new_agenda') !!}</b>--}}
            </div>
            <!-- /.panel-heading -->
            @include('BoardMeting::board-meeting-info')
            {{--board meeting info end--}}


            <div class="panel-body">

                @include('partials.messages')

                <div class="col-md-12">
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <div class="center-block">
                            <input type="radio" name="member_type" onclick="memberType('1')"> System User
                            <input type="radio" name="member_type" checked onclick="memberType('2')"> Manual User
                        </div>
                    </div>
                    <div class="col-md-4"></div>

                </div>
                </br>
                <div id="manualUser">
                    </br>
                    {!! Form::open(array('url' => '/board-meting/committee/store','method' => 'post', 'class' => 'form-horizontal smart-form', 'id' => 'entry-form1',
                    'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}

                    <div class="col-md-12">

                        <div class="row">
                            <input type="hidden" name="board_meeting_id" value="{{$board_meeting_id}}">

                            <div class="col-md-12 form-group">
                                {!! Form::label('user_name',trans('messages.meeting_member_name'), ['class'=>'col-md-3 required-star']) !!}
                                <div class="col-md-5 {{$errors->has('user_name') ? 'has-error': ''}}">
                                    {!! Form::text('user_name', null, ['class' => 'col-md-12 form-control bnEng input-sm required']) !!}
                                    {!! $errors->first('user_name','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12 form-group">
                                {!! Form::label('organization',trans('messages.member_organization'), ['class'=>'col-md-3 required-star']) !!}
                                <div class="col-md-5 {{$errors->has('organization') ? 'has-error': ''}}">
                                    {!! Form::text('organization', null, ['class' => 'col-md-12 form-control bnEng required bnEng input-sm required']) !!}
                                    {!! $errors->first('organization','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="col-md-12 form-group">
                                {!! Form::label('designation',trans('messages.member_designation'), ['class'=>'col-md-3 required-star']) !!}
                                <div class="col-md-5 {{$errors->has('designation') ? 'has-error': ''}}">
                                    {!! Form::text('designation', null, ['class' => 'col-md-12 bnEng required bnEng form-control input-sm']) !!}
                                    {!! $errors->first('designation','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="col-md-12 form-group">
                                {!! Form::label('user_mobile',trans('messages.member_mobile'), ['class'=>'col-md-3 required-star']) !!}
                                <div class="col-md-5 {{$errors->has('user_mobile') ? 'has-error': ''}}">
                                    {!! Form::text('user_mobile', null, ['class' => 'col-md-12 form-control bnEng required input-sm']) !!}
                                    {!! $errors->first('user_mobile','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12 form-group">
                                {!! Form::label('email',trans('messages.member_email'), ['class'=>'col-md-3 required-star']) !!}
                                <div class="col-md-5 {{$errors->has('user_email') ? 'has-error': ''}}">
                                    {!! Form::email('user_email', null, ['class' => 'col-md-12 form-control input-sm required  user_email']) !!}
                                    {!! $errors->first('user_email','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="col-md-12 form-group">
                                {!! Form::label('user_phone',trans('messages.member_phone'), ['class'=>'col-md-3']) !!}
                                <div class="col-md-5 {{$errors->has('user_phone') ? 'has-error': ''}}">
                                    {!! Form::text('user_phone', null, ['class' => 'col-md-12 onlyNumber form-control input-sm']) !!}
                                    {!! $errors->first('user_phone','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                        </div>

                        <div>
                            <a href="{{ url('board-meting/agenda/list/'.$board_meeting_id) }}">
                                {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                            </a>

                            <a href="{{ url('board-meting/committee/chairman/'.$board_meeting_id) }}" class="btn btn-info  pull-right">
                                <i class="fa fa-chevron-circle-right"></i> {!! trans('messages.next') !!}
                            </a>
                            <button style="margin-right: 2px;" type="submit" class="btn btn-primary tostar pull-right">
                                <i class="fa fa-chevron-circle-right"></i> {!! trans('messages.save') !!}
                            </button>

                        </div>


                    {!! Form::close() !!}<!-- /.form end -->

                        <div class="overlay" style="display: none;">
                            <i class="fa fa-refresh fa-spin"></i>
                        </div>
                    </div><!-- /.box -->
                </div>
                {{--manually add members--}}


                {{--from system members--}}
                </br>
                <div id="sysUser" style="display: none">


                    {!! Form::open(array('url' => '/board-meting/committee/store','method' => 'post', 'class' => 'form-horizontal smart-form', 'id' => 'entry-form',
                  'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}

                    <div class="col-md-12">

                        <div class="row">
                            <input type="hidden" name="board_meeting_id" value="{{$board_meeting_id}}">

                            <div class="col-md-12 form-group">
                                {!! Form::label('users_id',trans('messages.user_type'), ['class'=>'col-md-3 required-star']) !!}
                                <div class="col-md-5 {{$errors->has('user_type') ? 'has-error': ''}}">
                                    {!! Form::select('user_type', $usersType, null, array('class'=>'form-control required',
                                     'placeholder' => 'Select a user type', 'onchange'=>'getUserType()', 'id'=>"user_type")) !!}
                                    {!! $errors->first('user_type','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="col-md-12 form-group">
                                {!! Form::label('users_id',trans('messages.select_new_user'), ['class'=>'col-md-3 required-star']) !!}
                                <div class="col-md-5 {{$errors->has('type') ? 'has-error': ''}}">
                                    {!! Form::select('users_id',$user_list, null, array('class'=>'form-control users required',
                               'placeholder' => 'Select a User', 'id'=>"users_id")) !!}
                                    {!! $errors->first('users_id','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            {!! Form::hidden('user_name', null, ['class' => 'col-md-12 form-control input-sm bnEng required','id'=>'UN']) !!}


                            <div class="col-md-12 form-group">
                                {!! Form::label('organization',trans('messages.member_organization'), ['class'=>'col-md-3 required-star']) !!}
                                <div class="col-md-5 {{$errors->has('organization') ? 'has-error': ''}}">
                                    {!! Form::text('organization', null, ['class' => 'col-md-12 form-control required bnEng input-sm required','id'=>'UN']) !!}
                                    {!! $errors->first('organization','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="col-md-12 form-group">
                                {!! Form::label('designation',trans('messages.member_designation'), ['class'=>'col-md-3 required-star']) !!}
                                <div class="col-md-5 {{$errors->has('designation') ? 'has-error': ''}}">
                                    {!! Form::text('designation', null, ['class' => 'col-md-12 required bnEng form-control input-sm','id'=>'DEG']) !!}
                                    {!! $errors->first('designation','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="col-md-12 form-group">
                                {!! Form::label('user_mobile',trans('messages.member_mobile'), ['class'=>'col-md-3 required-star']) !!}
                                <div class="col-md-5 {{$errors->has('user_mobile') ? 'has-error': ''}}">
                                    {!! Form::text('user_mobile', null, ['class' => 'col-md-12 form-control bnEng required input-sm','id'=>'UM', 'readonly'=> true]) !!}
                                    {!! $errors->first('user_mobile','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="col-md-12 form-group">
                                {!! Form::label('email',trans('messages.member_email'), ['class'=>'col-md-3 required-star']) !!}
                                <div class="col-md-5 {{$errors->has('user_email') ? 'has-error': ''}}">
                                    {!! Form::email('user_email', null, ['class' => 'col-md-12 form-control input-sm required','id'=>'user_email', 'readonly'=> true]) !!}
                                    {!! $errors->first('user_email','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12 form-group">
                                {!! Form::label('user_phone',trans('messages.member_phone'), ['class'=>'col-md-3 required-star']) !!}
                                <div class="col-md-5 {{$errors->has('user_phone') ? 'has-error': ''}}">
                                    {!! Form::text('user_phone', null, ['class' => 'col-md-12 required onlyNumber form-control input-sm','id'=>'UM']) !!}
                                    {!! $errors->first('user_phone','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            {{--<div class="col-md-12 form-group">--}}
                            {{--{!! Form::label('type','Chairperson', ['class'=>'col-md-3 required-star']) !!}--}}
                            {{--<div class="col-md-3 {{$errors->has('type') ? 'has-error': ''}}">--}}
                            {{--<select name="type" class="col-md-12 form-control input-sm required Chairperson">--}}
                            {{--<option value="No">No</option>--}}
                            {{--<option value="Yes">Yes</option>--}}
                            {{--</select>--}}
                            {{--<span class="AleradyExest"></span>--}}
                            {{--{!! $errors->first('type','<span class="help-block">:message</span>') !!}--}}
                            {{--</div>--}}
                            {{--</div>--}}

                        </div>

                        <div>
                            <a href="{{ url('board-meting/agenda/list/'.$board_meeting_id) }}">
                                {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                            </a>
                            <a href="{{ url('board-meting/committee/chairman/'.$board_meeting_id) }}" class="btn btn-info  pull-right">
                                {!! trans('messages.next') !!} <i class="fa fa-chevron-right"></i>
                            </a>
                            <button style="margin-right: 2px;" type="submit" class="btn btn-primary tostar pull-right">
                                <i class="fa fa-save"></i> {!! trans('messages.save') !!}
                            </button>

                        </div>


                    {!! Form::close() !!}<!-- /.form end -->

                        <div class="overlay" style="display: none;">
                            <i class="fa fa-refresh fa-spin"></i>
                        </div>
                    </div><!-- /.box -->
                </div>
                {{--from system members--}}
            </div>
        </div>
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>
                    {{trans('messages.committeemembers')}}
                </b>
                {{--<b>{!! trans('messages.new_agenda') !!}</b>--}}
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="committeeList" class="table table-striped table-bordered dt-responsive " cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th>{!! trans('messages.meeting_member_name') !!}</th>
                            <th>{!! trans('messages.member_email') !!}</th>
                            <th>{!! trans('messages.member_mobile') !!}</th>
                            <th>{!! trans('messages.member_designation') !!}</th>
                            <th>{!! trans('messages.created_at') !!}</th>
                            <th>{!! trans('messages.action') !!}</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div><!-- /.table-responsive -->
            </div><!-- /.panel-body -->
            <div class="col-md-12"><br></div>
            <div class="col-md-12"><br></div>
            <div class="col-md-12"><br></div>
        </div><!--/col-md-12-->

        @endsection


        @section('footer-script')
            @include('Users::partials.datatable')
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
                    $("#entry-form1").validate({
                        errorPlacement: function () {
                            return false;
                        }
                    });

                    $('.Chairperson').change(function () {
                        var board_id = '{{$board_meeting_id}}';
                        var ChairpersonType = $(this).val();
                        var _token = $('input[name="_token"]').val();
                        $(this).after('<span class="loading_data">Loading...</span>');
                        var self = $(this);
                        $.ajax({
                            type: "post",
                            url: "/board-meting/committee/check-chairperson-type",
                            data: {
                                _token: _token,
                                board_meeting_id: board_id,
                                chairperson_type: ChairpersonType
                            },
                            success: function (response) {
                                if (response.responseCode == 1) {

                                    if (response.status >= 1) {
                                        var html = "<span style='color:#e68880'>Chairperson Already added!!</span>";
                                        $('.AleradyExest').html(html);
                                        $(".tostar").prop("disabled", true);

                                    } else {
                                        $('.AleradyExest').html('');
                                        $(".tostar").prop("disabled", false);
                                    }
                                }
                                $(self).next().hide();
                            }
                        });

                    });

                });
                function getUserType() {
                    var _token = $('input[name="_token"]').val();
                    var user_type = $('#user_type').val();
                    $.ajax({
                        url: '{{url("board-meting/committee/user_list")}}',
                        type: 'post',
                        data: {
                            _token: _token,
                            user_type: user_type
                        },
                        dataType: 'json',
                        success: function (response) {
                           var  html = '<option value="">Select User</option>';
                            $.each(response, function (index, value) {
                                html += '<option value="' + value.id + '" >' + value.user_full_name + '</option>';
                            });
                            $('.users').html(html);
                        },
                        beforeSend: function (xhr) {
                            console.log('before send');
                        },
                        complete: function () {
                            //completed
                        }
                    });
                }
            </script>

            <script>
                $(function () {
                    var board_id = '{{$board_meeting_id}}';
                    committeeList = $('#committeeList').DataTable({
                        processing: true,
                        serverSide: true,

                        ajax: {
                            url: '{{url("board-meting/committee/get-data")}}',
                            method: 'post',
                            data: function (d) {
                                d.board_meting_id = board_id;
                                d._token = $('input[name="_token"]').val();

                            }
                        },
                        columns: [
                            {data: 'user_name', name: 'user_name'},
                            {data: 'user_email', name: 'user_email'},
                            {data: 'user_mobile', name: 'user_mobile'},
//                            {data: 'type', name: 'type'},
                            {data: 'designation', name: 'designation'},
                            {data: 'created_at', name: 'created_at'},
                            {data: 'action', name: 'action', orderable: false, searchable: false}
                        ],
                        "aaSorting": []
                    });
                });

                function deleteMember(member_id) {
                    toastr.error("<br /><br /><button type='button' style='color:black' id='confirmationRevertYes' class='btn clear'>Yes</button> <button style='margin-left: 120px;color: black' class='btn clear' type='button'>No</button>", 'Are you sure you want to delete?',
                        {
                            closeButton: true,
                            allowHtml: true,
                            timeOut: 0,
                            extendedTimeOut: 0,
                            positionClass: "toast-top-center",
                            onShown: function (toast) {
                                $("#confirmationRevertYes").click(function () {
                                    var _token = $('input[name="_token"]').val();

                                    $.ajax({
                                        type: "post",
                                        url: "<?php echo url(); ?>/board-meting/committee/deleteMember",
                                        data: {
                                            _token: _token,
                                            member_id: member_id
                                        },
                                        success: function (response) {
                                            if (response.responseCode == 1) {
                                                committeeList.ajax.reload();
                                            }
                                        }
                                    });
                                });
                            }
                        });
                }

                function memberType(member_type) {
                    $('.smart-form')[0].reset();
                    if (member_type == 1) {
                        $('#sysUser').show();
                        $('#manualUser').hide();
                    }
                    else {
                        $('#sysUser').hide();
                        $('#manualUser').show();
                    }
                }

                $('#users_id').on('change', function () {

                    var _token = $('input[name="_token"]').val();
                    var member_id = this.value;
                    $.ajax({
                        type: "post",
                        url: "<?php echo url(); ?>/board-meting/committee/getUserInfo",
                        data: {
                            _token: _token,
                            member_id: member_id
                        },
                        success: function (response) {
                            if (response.responseCode == 1) {
                                $('#user_email').val(response.data.user_email);
                                $('#DEG').val(response.data.designation);
                                $('#UM').val(response.data.user_phone);
                                $('#UN').val(response.data.user_full_name);
                            }
                        }
                    });
                });
            </script>
    @endsection <!--- footer script--->