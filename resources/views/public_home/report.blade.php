<style type="text/css">
    .top-buffer { margin-top:10px; }
    .advance{
        cursor: pointer;
    }
    .collapsed > .fa-arrow-up::before{
        content: "";
    }

    .widget-custom{
        border: 1px solid;
        border-color: #E3E3E3;
        padding: 10px 0;
    }
    .vk_ard::before {
        border-top: 16px solid #e5e5e5;
        top: 6px;
    }
    .vk_ard::after, .vk_ard::before, .vk_aru::after, .vk_aru::before {
        border-left: 32px solid rgba(229, 229, 229, 0);
        border-right: 32px solid rgba(229, 229, 229, 0);
    }
    .vk_ard::after, .vk_ard::before, .vk_aru::after, .vk_aru::before {
        content: " ";
        height: 0;
        left: 0;
        position: absolute;
        width: 0;
    }
    .vk_ard::after {
        border-top: 16px solid #fff;
    }
    .vk_ard::after {
        top: 0;
    }
    .vk_ard::after, .vk_ard::before, .vk_aru::after, .vk_aru::before {
        border-left: 32px solid rgba(229, 229, 229, 0);
        border-right: 32px solid rgba(229, 229, 229, 0);
    }
    .vk_ard::after, .vk_ard::before, .vk_aru::after, .vk_aru::before {
        content: " ";
        height: 0;
        left: 0;
        position: absolute;
        width: 0;
    }
    ._LJ._qxg .vk_ard, ._LJ._qxg .vk_aru {
        margin-left: 15px;
    }
    .vk_ard, .vk_aru {
        height: 6px;
        width: 64px;
        margin-top: 10px;
    }
    .vk_ard, .vk_aru {
        background-color: #e5e5e5;
        margin-left: auto;
        margin-right: auto;
        position: relative;
    }
    .vk_ard {
        top: -11px;
    }
    .nav-tabs-custom .nav-tabs > li > a {
        border: none;
        color: #ffffff !important;
        background: #5cb85c;
    }
</style>
<div class="nav-tabs-custom">
    <div class="panel with-nav-tabs panel-green">
        <div class="panel-heading" >

            <ul class="nav nav-tabs">
                <li  class=" active {!! (Request::segment(2)=='index' OR Request::segment(2)=='')?'active':'' !!}">
                    <a data-toggle="tab"  href="#list_1" aria-expanded="true">
                        <i class="fa fa-clock-o"></i>
                        {!! trans('messages.latest_notice_title') !!}
                    </a>
                </li>
                <li class="{!! ((Request::segment(2)=='training'))?'active':'' !!}"   >
                    <a data-toggle="tab" href="#list_3" aria-expanded="true" id="training">
                        <i class="fa fa-pencil-square-o"></i>
                        {!! trans('messages.training_tab_text') !!}
                    </a>
                </li>
            </ul>
        </div>
        <div class="panel-body">
            <div class="tab-content">
                @include('public_home.notice')
                @include('public_home.report_dashboard')
                @include('public_home.training')

            </div>
        </div>
    </div>
</div>
<input type="hidden" name="_token" value="{{ csrf_token() }}">