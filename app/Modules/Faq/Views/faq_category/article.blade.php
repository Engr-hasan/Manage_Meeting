@extends('layouts.admin')

@section('page_heading',trans('messages.search_list'))

@section('content')
    <?php
    $accessMode = ACL::getAccsessRight('search');
//    if (!ACL::isAllowed($accessMode, 'V'))
//        die('no access right!');
    ?>
    <div class="col-lg-12">

        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}


        <div class="nav-tabs-custom">
            <div class="tab-content">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            {!! Form::open(['url' => 'faq/index','method' => 'get','id' => 'faq_search'])!!}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group custom-search-form">
                                    <span class="input-group-addon">
                                        <i class="fa fa-list"></i>
                                    </span>
                                        <input name="q" type="text" class="form-control input-sm" value="{{Request::get('q')}}" placeholder="Search FAQ by Keyword">
                                    <span class="input-group-btn">
                                        <button class="btn btn-sm btn-default" type="submit">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group has-feedback {{ $errors->has('faqs_type') ? 'has-error' : ''}}">
                                        <label  class="col-md-4 text-right required-star"> ধরণ</label>
                                        <div class="col-md-8">
                                            {!! Form::select('faqs_type', $faqs_type, Request::get('faqs_type'), ['class'=>'form-control input-sm required',
                                            'id'=>"faqs_type"]) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="btn-group">
                                        <a href="{{ url('faq/faq-cat') }}" class="btn btn-default">FAQ Category List</a>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close()!!}


                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="row">
                                <!--1x101 System Admin,, 2x202 Help Desk, 2x203 Call Center-->
                                @if(ACL::getAccsessRight('search','A'))
                                    <div class="col-md-11">
                                        <div class="pull-right">
                                            <a class="" href="{{ url('/faq/create-faq-article') }}">
                                                {!! Form::button('<i class="fa fa-plus"></i> '.trans('messages.new_faq'), array('type' => 'button', 'class' => 'btn btn-sm btn-default')) !!}
                                            </a>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-1"></div>

                                @if($faqs)
                                    <div class="col-md-12">
                                        <div class="">
                                            <?php $i = 1; ?>
                                            @foreach($faqs as $faq)
                                                <div class="form-group">
                                        <span class="col-lg-12">{{ $i++ }}. <a href='#faq_{{$faq->id}}'>{{ $faq->question}} </a>
                                            <code>{{$faq->faq_type_name}}</code>
                                        </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <div class="col-md-12"><br/></div>

                                @if($faqs)
                                    <div class="col-md-12">
                                        <div class="">
                                            <?php $i = 1; ?>
                                            @foreach($faqs as $faq)
                                                <div class="panel-body">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label  class="col-lg-12"><code id='faq_{{$faq->id}}'>{{ $i++ }}. প্রশ্ন:</code> {{ $faq->question}}
                                                                <code>{{$faq->faq_type_name}}</code></label>
                                                            <div class="col-lg-12"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<code>উত্তর:</code>
                                                                {!! $faq->answer !!}
                                                            </div>
                                                            <div class="col-md-1"></div>
                                                            <div class="col-md-1"></div>
                                                            <div class="col-md-10">
                                                                <div class="col-md-9">
                                                                    {!! CommonFunction::showAuditLog($faq->updated_at,$faq->updated_by) !!}
                                                                </div>
                                                                <div class="col-md-3">
                                                                    @if(ACL::getAccsessRight('search','E'))
                                                                    @if($faq->updated_by == Auth::user()->id || Auth::user()->user_type == '1x101')
                                                                            <!--1x101 System Admin,, 2x202 Help Desk-->
                                                                    <a href="{{url('faq/edit-faq-article/'.Encryption::encodeId($faq->id))}}" id="editBtn" class="btn btn-xs btn-default">
                                                                        <i class="fa fa-edit "></i> Edit</a>
                                                                    @endif
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
            </div>
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->

@endsection

@section('footer-script')

    @include('partials.datatable-scripts')
    {{--<script src="{{ asset("assets/scripts/datatable/handlebars.js") }}" type="text/javascript"></script>--}}

    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <script>
        $(function () {
            $('#list').DataTable();
            $('#faqs_type').change(function () {
                $('#faq_search').submit();
            });
        });
    </script>
@endsection
