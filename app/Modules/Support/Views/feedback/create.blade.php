@extends('layouts.admin')

@section('page_heading',trans('messages.feedback_form_title'))

@section('content')

    <div class="col-lg-12">

        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

        <div class="panel panel-primary">
            <div class="panel-heading">
                <b> {!!trans('messages.feedback_form')!!} </b>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                {!! Form::open(array('url' => '/support/store-feedback','method' => 'patch', 'class' => 'form-horizontal smart-form', 'id' => 'feedback-info',
                'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}

                <div class="col-md-12">
                    <div class="form-group col-md-10 {{$errors->has('topic_id') ? 'has-error' : ''}}">
                        {!! Form::label('topic_id','Topic: ',['class'=>'col-md-5  required-star']) !!}
                        <div class="col-md-7">
                            {!! Form::select('topic_id',$topics, null, array('class'=>'form-control required')) !!}
                            {!! $errors->first('topic_id','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group col-md-10 {{$errors->has('description') ? 'has-error' : ''}}">
                        {!! Form::label('description','আপনার সমস্যাটি বর্ণনা করুন ',['class'=>'col-md-5  required-star']) !!}
                        <div class="col-md-7">
                            {!! Form::textarea('description', '', ['class'=>'form-control bnEng required', 'size' => "10x6"]) !!}
                            {!! $errors->first('description','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <?php
                    $priorities = array(
                            'low' => 'Low',
                            'medium' => 'Medium',
                            'high' => 'High',
                    );
                    ?>
                    <div class="form-group col-md-10 {{$errors->has('priority') ? 'has-error' : ''}}">
                        {!! Form::label('priority','Priority: ',['class'=>'col-md-5  required-star']) !!}
                        <div class="col-md-7">
                            {!! Form::select('priority',$priorities, null, array('class'=>'form-control required')) !!}
                            {!! $errors->first('priority','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <!--                <div class="form-group col-md-10 {{$errors->has('screenshot') ? 'has-error' : ''}}">
                                    {!! Form::label('screenshot','সমস্যাটির স্ক্রিনশট সংযুক্তি (প্রযোজ্য ক্ষেত্রে)',['class'=>'col-md-5']) !!}
                            <div class="col-md-7">
                                {!! Form::file('screenshot', '', ['class'=>'form-control']) !!}
                    {!! $errors->first('screenshot','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>-->


                    @if(isset($sysAdmin_email) && !empty($sysAdmin_email))
                        <div>
                            আপনি চাইলে সমস্যাটির একটি স্ক্রিনশট তুলে সিস্টেম অ্যাডমিনের ইমেইল অ্যাড্রেসে পাঠাতে পারেন। <br/>
                            সিস্টেম অ্যাডমিনের ইমেইল -
                            @foreach($sysAdmin_email as $mail)
                                {{$mail->user_email }} <br/>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="col-md-12">
                    <div class="col-md-6">
                        <a href="{{ url('/support/feedback') }}">
                            {!! Form::button('<i class="fa fa-times"></i> <b>Close</b>', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                        </a>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" name="draft" value="draft" class="btn btn-success pull-right"><i class="fa fa-dot-circle-o"></i>
                            <b> Save as draft </b></button>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" name="sent" value="sent" class="btn btn-primary pull-right"><i class="fa fa-chevron-circle-right"></i>
                            <b>Send</b></button>
                    </div>
                </div><!-- /.box-footer -->

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
            $("#feedback-info").validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });
    </script>
    @endsection <!--- footer script--->