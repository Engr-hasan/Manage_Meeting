@extends('layouts.admin')

@section('content')
    <?php
    $accessMode = ACL::getAccsessRight('BoardMeting');
    if (!ACL::isAllowed($accessMode, 'A')) {
        die('You have no access right! Please contact with system admin for more information.');
    }
    $board_meeting_id = Encryption::encodeId($committee_data->board_meeting_id);
    ?>
    @include('BoardMeting::progress-bar')
    <div class="col-lg-12">

        <div class="panel panel-info">
            <div class="panel-heading">
                <b>{{trans('messages.edit_committee_page_title')}}</b>
            </div>
            <!-- /.panel-heading -->

            @include('BoardMeting::board-meeting-info')
            {{--board meeting info end--}}

            <div class="panel-body">

                @include('partials.messages')

                {!! Form::open(array('url' => '/board-meting/committee/member-edit','method' => 'post', 'class' => 'form-horizontal smart-form', 'id' => 'entry-form',
                'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}

                <div class="col-md-12">

                    <div class="row">
                        <input type="hidden" name="member_id" value="{{$member_id}}">
                        <input type="hidden" name="board_meeting_id" value="{{Encryption::encodeId($committee_data->board_meeting_id)}}">

                        <div class="col-md-12 form-group">
                            {!! Form::label('user_name',trans('messages.meeting_member_name'), ['class'=>'col-md-3 required-star']) !!}
                            <div class="col-md-5 {{$errors->has('user_name') ? 'has-error': ''}}">
                                {!! Form::text('user_name', $committee_data->user_name, ['class' => 'col-md-12 form-control bnEng input-sm required']) !!}
                                {!! $errors->first('user_name','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="col-md-12 form-group">
                            {!! Form::label('organization',trans('messages.member_organization'), ['class'=>'col-md-3 required-star']) !!}
                            <div class="col-md-5 {{$errors->has('organization') ? 'has-error': ''}}">
                                {!! Form::text('organization', $committee_data->organization, ['class' => 'col-md-12 form-control bnEng required bnEng input-sm required']) !!}
                                {!! $errors->first('organization','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="col-md-12 form-group">
                            {!! Form::label('designation',trans('messages.member_designation'), ['class'=>'col-md-3 required-star']) !!}
                            <div class="col-md-5 {{$errors->has('designation') ? 'has-error': ''}}">
                                {!! Form::text('designation', $committee_data->designation, ['class' => 'col-md-12 form-control bnEng input-sm required']) !!}
                                {!! $errors->first('designation','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="col-md-12 form-group">
                            {!! Form::label('user_mobile',trans('messages.member_mobile'), ['class'=>'col-md-3 required-star']) !!}
                            <div class="col-md-5 {{$errors->has('user_mobile') ? 'has-error': ''}}">
                                {!! Form::text('user_mobile', $committee_data->user_mobile, ['class' => 'col-md-12 form-control input-sm required']) !!}
                                {!! $errors->first('user_mobile','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>


                        <div class="col-md-12 form-group">
                            {!! Form::label('email',trans('messages.member_email'), ['class'=>'col-md-3 required-star']) !!}
                            <div class="col-md-5 {{$errors->has('user_email') ? 'has-error': ''}}">
                                {!! Form::email('user_email', $committee_data->user_email, ['class' => 'col-md-12 form-control input-sm required','readonly'=>true]) !!}
                                {!! $errors->first('user_email','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="col-md-12 form-group">
                            {!! Form::label('user_phone',trans('messages.member_phone'), ['class'=>'col-md-3']) !!}
                            <div class="col-md-5 {{$errors->has('user_phone') ? 'has-error': ''}}">
                                {!! Form::text('user_phone', $committee_data->user_phone, ['class' => 'col-md-12 onlyNumber form-control input-sm']) !!}
                                {!! $errors->first('user_phone','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        {{--<div class="col-md-12 form-group">--}}
                            {{--{!! Form::label('type','Chairperson', ['class'=>'col-md-3 required-star']) !!}--}}
                            {{--<div class="col-md-2 {{$errors->has('type') ? 'has-error': ''}}">--}}
                                {{--<select name="type" class="col-md-12 form-control input-sm required">--}}
                                    {{--<option value="No" @if($committee_data->type == 'No') selected @endif>No</option>--}}
                                    {{--<option value="Yes" @if($committee_data->type == 'Yes') selected @endif>Yes</option>--}}
                                {{--</select>--}}
                                {{--{!! $errors->first('type','<span class="help-block">:message</span>') !!}--}}
                            {{--</div>--}}
                        {{--</div>--}}

                    </div>

                    <div>
                        <a href="{{ url('board-meting/agenda/list/'. \App\Libraries\Encryption::encodeId($committee_data->board_meeting_id)) }}">
                            {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                        </a>

                        <button type="submit" class="btn btn-primary tostar pull-right">
                            <i class="fa fa-chevron-circle-right"></i> Save</button>

                    </div>


                {!! Form::close() !!}<!-- /.form end -->

                    <div class="overlay" style="display: none;">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div><!-- /.box -->
            </div>
        </div>


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

                    $('#user_mobile').removeClass('engOnly');

                    $("#entry-form").validate({
                        errorPlacement: function () {
                            return false;
                        }
                    });
                });
            </script>


    @endsection <!--- footer script--->