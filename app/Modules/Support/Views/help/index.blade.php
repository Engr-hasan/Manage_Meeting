@extends('layouts.admin')

@section('page_heading',trans('messages.feedback_form_title'))

@section('content')

    {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
    {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}


    <div class="col-md-12"><br/></div>

    @if($faqs)
        @if(count($faqs) > 0)
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-md-12">
                        <div class="">
                            <div class="panel-heading">
                                <span class="text-left"><strong>{{trans('messages.feedback')}}</strong></span>
                                @if($user_manual != null)
                                    <span class="pull-right">
                        <a href="{{ url('files/download/user_manual/none') }}" target="_blank">
                            <span class="btn btn-xs btn-success text-right">
                                <i class="fa fa-file-pdf-o" style="background-color: #DC322F;"></i>
                                <b>{{trans('messages.user_manual')}} </b></span></a>
                    </span>
                                @endif
                            </div>
                            @foreach($faqs as $faq)
                                <div class="form-group">
                                    <a href='#faq_{{$faq->id}}' class="col-lg-12"><strong>{{ $faq->question}}</strong> </a>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="">
                            @foreach($faqs as $faq)
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label  class="col-lg-12"><code id='faq_{{$faq->id}}'>প্রশ্ন:</code> {{ $faq->question}} </label>
                                            <div class="col-lg-12"> <code>উত্তর:</code>
                                                {!! $faq->answer !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @else

            @if($user_manual != null)
                <div class="col-md-12">
                    <span class="pull-right">
                        <a href="{{'/manuals/'.$user_manual}}" target="_blank">
                            <span class="btn btn-xs btn-success text-right">
                                <i class="fa fa-file-pdf-o" style="background-color: #DC322F;"></i>
                                <b>{{trans('messages.user_manual')}} </b></span>
                        </a>
                    </span>
                </div>
            @endif
            <div class="col-md-12">
            <div class="panel panel-danger">
                <div class="panel-heading"></div>
                <div class="panel-body">
                    <h4>  {!!Html::image('assets/images/warning.png') !!}
                        এই বিষয়ে এই মুহূর্তে আমাদের ডাটাবেজে কোন তথ্য নেই। বিস্তারিত জানার জন্য আমাদের হজ তথ্য সেবাকেন্দ্রে (ফোন নম্বর: 09602666707)  যোগাযোগ করুন। </h4>
                </div>
            </div>
            </div>
        @endif
    @endif
    {{--<div class="panel panel-warning">--}}
        {{--<div class="panel-body">--}}
            {{--<h4>  আপনার আকাঙ্ক্ষিত তথ্য এখানে না পেলে  <a href='{{url('/support/create-feedback')}}'>Support Ticket </a>--}}
                {{--মেনু থেকে এই বিষয়ে সাহায্য চেয়ে নতুন একটি টিকেট খুলুন। </h4>--}}
        {{--</div>--}}
    {{--</div>--}}

@endsection