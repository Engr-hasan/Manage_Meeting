@extends('layouts.front')

@section("content")

<div class="container" style="margin-top:60px;">
    <div class="row">
        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

        <div class="col-md-10 col-md-offset-1" style="background: snow; opacity:0.88; border-radius:8px;">

            <div class="col-md-12 col-sm-12">
                {!! Form::open(array('url' => 'users/check-two-step/','method' => 'patch', 'class' => 'form-horizontal', 'id' => 'packagesCreateForm',
                'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
                @if(Request::get('req')!=null)
                    {!! Form::hidden('req_dta',Request::get('req')) !!}
                @endif
                <div class="col-sm-6">
                    <h3 class="">Two Step Verification </h3>
                    <div class="box-body">
                        <div class="form-group col-sm-12 {{$errors->has('steps') ? 'has-error' : ''}}">
                            <div class="col-sm-12">                            
                                <img src="{{url('assets/images/email_icon.png')}}" class= img-responsive"  alt="Two-step verification by email" 
                                     id="email_verification_img"  width="150" />
                                <br/>
                                <label>
                                    <?php $email = Auth::user()->user_email; ?>
                                    {!! Form::radio('steps', 'email',  true, ['class' => ' required']) !!}
                                    Get code in Email
                                </label>
                                (<?php echo substr($email, 0, 3) . '***************' . substr($email, -9); ?>)
                            </div>
                            <div class="col-sm-12"><br/></div>
                            <div class="col-sm-12">
                                <img src="{{url('assets/images/sms.png')}}" class= img-responsive"  alt="Two-step verification by SMS" 
                                     id="sms_verification_img"  width="150" />
                                <br/>
                                <label>
                                    {!! Form::radio('steps', 'mobile_no', null, ['class' => 'required']) !!}
                                    Get SMS in Mobile No.
                                    {!! $errors->first('state','<span class="help-block">:message</span>') !!}
                                </label>
                                (<?php echo substr(Auth::user()->user_phone, 0, 6) . '**********' . substr(Auth::user()->user_phone, -2); ?>)
                            </div>
                        </div>
                        <div class="email_address form-group col-sm-12 {{$errors->has('email_address') ? 'has-error' : ''}}" style="display: none;">
                            {!! Form::label('email_address','Email Address ',['class'=>'col-sm-5']) !!}
                            <div class="col-sm-7">
                                {!! Form::text('email_address','', ['class' => 'form-control','placeholder'=>'Email Address']) !!}

                                {!! $errors->first('email_address','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <a href="{{ url('logout') }}"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                            <button type="submit" class="btn btn-primary pull-right">
                                <i class="fa fa-chevron-circle-right"></i>Next</button>
                        </div><!-- /.box-footer -->

                    </div>
                    {!! Form::close() !!}<!-- /.form end -->
                </div>
                
                <div>
                    <h4>দ্বিতীয় দফা ভেরিফিকেশন কী?</h4>
                    <ul>
                        <li>দ্বিতীয় দফা ভেরিফিকেশন এই অ্যাপ্লিকেশনের একটি অতিরিক্ত নিরাপত্তা বলয়, যেখানে একজন ব্যবহারকারী তার পাসওয়ার্ড দিয়ে সিস্টেমে প্রবেশ করার পরে 
                            তার ইমেইল অথবা মোবাইলে এস.এম.এস এর মাধ্যমে একটি গোপনীয় ভেরিফিকেশন কোড পাঠানো হয়ে থাকে। 
                            এই ভেরিফিকেশন কোড প্রবেশ করার পরেই শুধুমাত্র কোন ব্যবহারকারী সিস্টেমে লগিন করতে পারবেন। </li>
                    </ul>
                    
                    <h4>কেন আমার দ্বিতীয় দফায় ভেরিফিকেশন দরকার?</h4>
                    <ul>
                        <li>যদি কোন তৃতীয় পক্ষ আপনার পাসওয়ার্ড হ্যাক করে থাকে, তাহলে এই ভেরিফিকেশন প্রক্রিয়া তাকে আপনার অ্যাকাউন্টির অপব্যবহার করা থেকে প্রতিহত করবে। </li>
                        <li> যতক্ষণ না পর্যন্ত কারো কাছে আপনার ইমেইল অথবা মোবাইলে প্রবেশাধিকার না থাকে, এই ভেরিফিকেশন প্রক্রিয়া তাকে সিস্টেমে প্রবেশ করতে দেবে না।</li>
                    </ul>
                    
                    <h4>ভেরিফিকেশন কোড কী?</h4>
                    <ul>
                        <li>আপনার ব্যবহারের জন্য সিস্টেম থেকে চার ডিজিটের একটি নাম্বার স্বয়ংক্রিয়ভাবে প্রস্তুত করা হয়ে থাকে, যাকে আমরা ভেরিফিকেশন কোড বলে আখ্যায়িত করছি। </li>
                        <li>প্রতিটি ভেরিফিকেশন কোড শুধুমাত্র একবারই ব্যবহার করা যাবে। </li>
                    </ul>
                    
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection

@section ('footer-script')
<style>
    ul li {
       list-style-type: none;
    }
</style>
@endsection
