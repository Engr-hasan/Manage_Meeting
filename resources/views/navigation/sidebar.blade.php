<?php
$user_type = Auth::user()->user_type;
$type = explode('x', $user_type);
$Segment=Request::segment(3);
?>
@if(Auth::user()->user_status='active' && (Auth::user()->is_approved == 1 or Auth::user()->is_approved == true))
    <div class="navbar-default sidebar" role="navigation" style="margin-top: 60px;">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li class="{{ (Request::is('/dashboard') ? 'active' : '') }}">
                    <a href="{{ url ('/dashboard') }}"><i class="fa fa-dashboard fa-fw"></i>
                        {!!trans('messages.dashboard')!!}
                    </a>
                </li>



                <?php
                $pcActive = '';
                $prActive = '';
                $workPermitActive = '';
                $exportActive = '';
                $importActive = '';
                $needHelpActive = '';
                $visaAssisActive = '';
                $colpseIn = '';
                if(Request::is('process/list/*') || Request::is('space-allocation/list')|| Request::is('import-permit/edit-form/*') || Request::is('import-permit/view/*')){
                    $prActive = 'active';
                    $colpseIn = 'in';
                }
                if(Request::is('support/help') ){
                    $needHelp = 'active';

                }
                if (Request::is('space-allocation/list/*') || Request::is('space-allocation/add')|| Request::is('space-allocation/view/*') || Request::is('space-allocation/application/edit/*') || Request::is('space-allocation/application/view/*')) {
                    $prActive = 'active';
                    $colpseIn = 'in';
                }

                if (Request::is('general-apps/list/*') || Request::is('general-apps/add')|| Request::is('general-apps/view/*')) {
                    $prActive = 'active';
                    $colpseIn = 'in';
                }

                if (Request::is('loan-locator/list/*') || Request::is('loan-locator/add')|| Request::is('loan-locator/view/*')) {
                    $prActive = 'active';
                    $colpseIn = 'in';
                }
                if (Request::is('loan-locator/list/*') || Request::is('loan-locator/add')|| Request::is('loan-locator/view/*')) {
                    $prActive = 'active';
                    $colpseIn = 'in';
                }
                if (Request::is('limit-renewal/list/*') || Request::is('limit-renewal/add')|| Request::is('limit-renewal/view/*')) {
                    $prActive = 'active';
                    $colpseIn = 'in';
                }
                if (Request::is('co-branded-card/list/*') || Request::is('co-branded-card/add')|| Request::is('co-branded-card/view/*')) {
                    $prActive = 'active';
                    $colpseIn = 'in';
                }
                if (Request::is('meeting-form/list/*') || Request::is('meeting-form/add')|| Request::is('meeting-form/view/*')) {
                    $prActive = 'active';
                    $colpseIn = 'in';
                }

                ?>
                @if(Auth::user()->is_approved == 1 && (! (Auth::user()->first_login == 0 AND in_array($type[0], [4,5,6]))))

                    <li class="{{ $prActive }}">
                        <a class="{{$prActive}}" href="{{ url ('process/list/') }}">
                            <i class="fa fa-file-text-o fa-fw"></i> {!! trans('messages.application') !!}<span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level">
                            @include ('navigation.menu')
                        </ul>
                    </li>

                @endif

                <?php

                ?>

                @if(Auth::user()->is_approved == 1 && (!(Auth::user()->first_login == 0 AND (in_array($type[0], [5,6])))))
                    <li>
                        <a class="@if (Request::is('board-meting') || Request::is('board-meting/new-board-meting')  || Request::is('board-meting/list') || Request::is('board-meting/agenda/edit/*') || Request::is('board-meting/agenda/list/*') || Request::is('board-meting/agenda/create-new-agenda/*') ||  Request::is('board-meting/agenda/process/*') ) active @endif" href="{{ url ('/board-meting/lists') }}">
                            <i class="fa fa-users fa-fw"></i> {!!trans('messages.board_meting')!!}
                        </a>
                    </li>
                    <li class="{{ (Request::is('/board-meting/board-meeting-help-desk') ? 'active' : '') }}">
                        <a target="_blank" href="{{ url ('/assets/Guideline for Board Meeting.pdf') }}"><i class="fa fa-dashboard fa-fw"></i>
                            {!!trans('messages.board_meeting_help_desk')!!}
                        </a>
                    </li>
                    <li>
                        <a class="@if (Request::is('reports') || Request::is('reports/*')) active @endif" href="{{ url ('/reports ')}}">
                            <i class="fa fa-book fa-fw"></i> {!! trans('messages.report') !!}
                        </a>
                    </li>

                    {{--<li>--}}
                        {{--<a class="@if (Request::is('csv-upload/list') || Request::is('csv-upload/list/*')) active @endif" href="{{ url ('/csv-upload/list ')}}">--}}
                            {{--<i class="fa fa-align-justify fa-fw"></i> {!! trans('messages.csv_up_down') !!}--}}
                        {{--</a>--}}

                    {{--</li>--}}

                    @if($type[0] ==1) {{-- For System Admin --}}
                    <li>
                        <a class="@if (Request::is('users') || Request::is('users/create-new-user')) active @endif" href="{{ url ('/users/lists') }}">
                            <i class="fa fa-users fa-fw"></i> {!!trans('messages.users')!!}
                        </a>
                    </li>


                    <li class="{{ (Request::is('/Training') ? 'active' : '') }}">
                        <a href="#"><i class="fa fa-file-powerpoint-o  fa-fw"></i>

                            {!! trans('messages.training') !!}
                            <span class="fa arrow"></span>
                        </a>

                        <ul class="nav nav-second-level">
                            <li class="{{ (Request::is('Training') ? 'active' : '') }}">
                                <a href="{{ url ('/Training') }}"><i class="fa fa-hand-o-right"></i>
                                    {!!trans('messages.training_material')!!}
                                </a>
                            </li>
                            <li class="{{ (Request::is('training/schedule') ? 'active' : '') }}">
                                <a href="{{ url ('/training/schedule') }}"><i class="fa fa-calendar"></i>
                                    {!!trans('messages.training_schedule')!!}
                                </a>
                            </li>
                        </ul>
                        {{--<ul class="nav nav-second-level">--}}
                            {{--<li class="{{ (Request::is('Training') ? 'active' : '') }}">--}}
                                {{--<a href="{{ url ('/Training') }}"><i class="fa  fa-hand-o-right"></i>--}}
                                    {{--Training Material--}}
                                {{--</a>--}}
                            {{--</li>--}}
                            {{--<li class="{{ (Request::is('/training/schedule') || Request::is('/training/create-schedule') ? 'active' : '') }}">--}}
                                {{--<a href="{{ url ('/training/schedule') }}"><i class="fa   fa-calendar"></i>--}}
                                    {{--Training Schedule--}}
                                {{--</a>--}}
                            {{--</li>--}}
                        {{--</ul>--}}
                    </li>

                    <li class="{{ (Request::is('settings/*') ? 'active' : '') }}">
                        <a href="{{ url ('/settings') }}"><i class="fa fa-gear fa-fw"></i>
                            <!--Settings--> {!!trans('messages.settings')!!}
                            <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a class="@if(Request::is('settings/area-list') || Request::is('settings/create-area') || Request::is('settings/edit-area/*')) active @endif" href="{{ url ('/settings/area-list') }}">
                                    <i class="fa fa-map-marker fa-fw"></i> {!!trans('messages.area')!!}
                                </a>
                            </li>
                            <li class="{{ (Request::is('/faq/faq-cat') ? 'active' : '') }}">
                                <a href="{{ url ('/faq/faq-cat') }}">
                                    <!--FAQ--><i class="fa fa-list-alt fa-fw" aria-hidden="true"></i>  {!!trans('messages.faq')!!}
                                </a>
                            </li>
                            <li>
                                <a class="@if(Request::is('settings/document') || Request::is('settings/create-document') || Request::is('settings/edit-document/*')) active @endif"
                                   href="{{ url ('/settings/document') }}">
                                    <i class="fa fa-file-text fa-fw" aria-hidden="true"></i> <span>{!! trans('messages.document') !!}</span>
                                </a>
                            </li>
                            <li>
                                <a class="@if(Request::is('settings/bank-list') || Request::is('settings/create-bank')  || Request::is('settings/edit-bank/*')  || Request::is('settings/view-bank/*')) active @endif" href="{{ url ('/settings/bank-list') }}">
                                    <i class="fa fa-bank  fa-fw"></i> {!! trans('messages.bank') !!}
                                </a>
                            </li>
                            <li>
                                <a class="@if(Request::is('settings/branch-list') || Request::is('settings/create-branch') || Request::is('settings/view-branch/*')) active @endif" href="{{ url ('/settings/branch-list') }}">
                                    <i class="fa fa-bank  fa-fw"></i> {!! trans('messages.bank_branch') !!}
                                </a>
                            </li>
                            <li>
                                <a class="@if(Request::is('settings/notice') || Request::is('settings/create-notice') || Request::is('settings/edit-notice/*')) active @endif" href="{{ url ('/settings/notice') }}">
                                    <i class="fa fa-list-alt fa-fw" aria-hidden="true"></i> <span>{!! trans('messages.notice') !!}</span>
                                </a>
                            </li>
                            <li>
                                <a class="@if(Request::is('settings/security') || Request::is('settings/edit-security/*')) active @endif" href="{{ url ('/settings/security') }}" href="{{ url ('/settings/security') }}">
                                    <i class="fa fa-key fa-fw" aria-hidden="true"></i> <span>{!! trans('messages.security_profile') !!}</span>
                                </a>
                            </li>

                            <li class="{{ (Request::is('home-page/*') ? 'active' : '') }}">

                                <a href="{{ url ('/home-page') }}"><i class="fa fa-gear fa-fw"></i>
                                    <!--Settings--> {!!trans('messages.home_page')!!}
                                    <span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a class="@if(Request::is('settings/home-page-slider') OR Request::is('settings/create-home-page-slider') OR Request::is('settings/edit-home-page-slider/*')) active @endif" href="{{ url ('/settings/home-page-slider') }}">
                                            &nbsp;&nbsp;&nbsp;&nbsp;  <i class="fa fa-file-image-o  fa-fw" aria-hidden="true"></i> <span>{!! trans('messages.home_page_slider') !!}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="@if(Request::is('settings/user-manual') OR Request::is('settings/create-user-manual') OR Request::is('settings/edit-user-manual/*')) active @endif" href="{{ url ('/settings/user-manual') }}">
                                            &nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-book  fa-fw" aria-hidden="true"></i> <span>{!! trans('messages.user_manual') !!}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="@if(Request::is('settings/whats-new') OR Request::is('settings/create-whats_new') OR Request::is('settings/edit-whats-new/*')) active @endif" href="{{ url ('/settings/whats-new') }}">
                                            &nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-barcode  fa-fw" aria-hidden="true"></i> <span>{!! trans('messages.whats_new') !!}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="@if(Request::is('settings/notice') || Request::is('settings/create-notice') || Request::is('settings/edit-notice/*')) active @endif" href="{{ url ('/settings/notice') }}">
                                            &nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-list-alt fa-fw" aria-hidden="true"></i> <span>{!! trans('messages.notice') !!}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="@if(Request::is('settings/service-info') || Request::is('settings/create-service-info-details')  || Request::is('settings/service-info/*')) active @endif" href="{{ url ('/settings/service-info') }}">
                                            &nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-user fa-fw"></i> {!! trans('messages.service_info') !!}
                                        </a>
                                    </li>

                                </ul>

                            </li>

                            <li>
                                {{--<a class="@if(Request::is('settings/company-info') )    active @endif" href="{{ url ('/settings/company-info') }}">--}}
                                <a class="@if(Request::is('settings/company-info') || Request::is('settings/company-info') || Request::is('settings/create-company')) active @endif" href="{{ url ('/settings/company-info') }}">
                                    <i class="fa fa-envelope  fa-fw"></i> {!! trans('messages.company_info') !!}
                                </a>
                            </li>
                            <li>
                                <a class="@if(Request::is('settings/currency') || Request::is('settings/create-currency') || Request::is('settings/edit-currency/*')) active @endif" href="{{ url ('/settings/currency') }}">
                                    <i class="fa fa-money  fa-fw"></i> {!! trans('messages.currency') !!}
                                </a>
                            </li>
                            <li>
                                <a class="@if(Request::is('settings/park-info') OR Request::is('settings/create-park-info') OR Request::is('settings/edit-park-info/*')) active @endif" href="{{ url ('/settings/park-info') }}">
                                    <i class="fa fa-tree fa-fw" aria-hidden="true"></i> <span> {!! trans('messages.park_info') !!}</span>
                                </a>
                            </li>
                            {{--<li>--}}
                            {{--<a class="@if(Request::is('settings/high-commission') || Request::is('settings/create-high-commission') ||--}}
                            {{--Request::is('settings/edit-high-commission/*')) active @endif" href="{{ url ('/settings/high-commission') }}">--}}
                            {{--<i class="fa fa-building fa-fw" aria-hidden="true"></i> <span>High Commission</span>--}}
                            {{--</a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                            {{--<a class="@if(Request::is('settings/hs-codes') || Request::is('settings/create-hs-code') ||--}}
                            {{--Request::is('settings/edit-hs-code/*')) active @endif" href="{{ url ('/settings/hs-codes') }}">--}}
                            {{--<i class="fa fa-codepen fa-fw" aria-hidden="true"></i> <span>HS Code</span>--}}
                            {{--</a>--}}
                            {{--</li>--}}
                            <li>
                                <a class="@if(Request::is('settings/indus-cat') || Request::is('settings/create-indus-cat') ||
                           Request::is('settings/edit-indus-cat/*')) active @endif" href="{{ url ('/settings/indus-cat') }}">
                                    <i class="fa fa-indent" aria-hidden="true"></i> <span> {!! trans('messages.industrial_category') !!}</span>
                                </a>
                            </li>
                            {{--<li>--}}
                                {{--<a class="@if(Request::is('settings/ports') || Request::is('settings/create-port') || Request::is('settings/edit-port/*')) active @endif" href="{{ url ('/settings/ports') }}">--}}
                                    {{--<i class="fa fa-support fa-fw" aria-hidden="true"></i> <span>{!! trans('messages.port') !!}</span>--}}
                                {{--</a>--}}
                            {{--</li>--}}
                            <li>
                                <a class="@if(Request::is('settings/service-info') || Request::is('settings/service-info/*')) active @endif" href="{{ url ('/settings/service-info') }}">
                                    <i class="fa fa-user fa-fw"></i> {!! trans('messages.service_info') !!}
                                </a>
                            </li>
                            {{--<li>--}}
                                {{--<a class="@if(Request::is('settings/user-types') || Request::is('settings/edit-user-type/*')) active @endif" href="{{ url ('/settings/user-type') }}">--}}
                                    {{--<i class="fa fa-user fa-fw"></i> {!! trans('messages.user_type') !!}--}}
                                {{--</a>--}}
                            {{--</li>--}}

                            <li>
                                <a class="@if(Request::is('settings/edit-logo')) active @endif" href="{{ url ('/settings/edit-logo') }}">
                                    <i class="fa fa-list-alt fa-fw" aria-hidden="true"></i> <span>{!! trans('messages.title_logo') !!}</span>
                                </a>
                            </li>


                        </ul>
                    </li>
                    @endif
                @endif


                {{--@if(Auth::user()->is_approved == 1)--}}
                    {{--<li class="{{ (Request::is('/exam/*') ? 'active' : '') }}">--}}
                        {{--<a href="{{ url ('/exam') }}"><i class="fa fa-graduation-cap"></i>--}}
                            {{--{!!trans('messages.exam')!!}--}}
                            {{--<span class="fa arrow"></span></a>--}}
                        {{--<ul class="nav nav-second-level">--}}
                            {{--@if($type[0] == 9)   --}}{{-- Only exam controller user will get these menus  --}}
                            {{--<li class="{{ (Request::is('/exam/question-bank/*') ? 'active' : '') }}">--}}
                                {{--<a href="{{ url ('/exam/question-bank/list') }}"><i class="fa fa-question-circle"></i>--}}
                                    {{--{!!trans('messages.question_bank')!!}--}}
                                {{--</a>--}}
                            {{--</li>--}}
                            {{--<li class="{{ (Request::is('/exam/schedule/*') ? 'active' : '') }}">--}}
                                {{--<a href="{{ url ('/exam/schedule/list') }}"><i class="fa fa-list-alt"></i>--}}
                                    {{--{!!trans('messages.scheduling')!!}--}}
                                {{--</a>--}}
                            {{--</li>--}}
                            {{--<li class="{{ (Request::is('/exam/result/*') ? 'active' : '') }}">--}}
                                {{--<a href="{{ url ('/exam/result/list') }}"><i class="fa fa-file-text"></i>--}}
                                    {{--{!!trans('messages.result_process')!!}--}}
                                {{--</a>--}}
                            {{--</li>--}}
                            {{--@endif--}}

                            {{--@if($type[0] != 9) --}}{{-- Only exam controller user will not get this menu --}}
                            {{--<li class="{{ (Request::is('/exam/exam-list/*') ? 'active' : '') }}">--}}
                                {{--<a href="{{ url ('/exam/exam-list/list') }}"><i class="fa fa-laptop"></i>--}}
                                    {{--{!!trans('messages.exam_list')!!}--}}
                                {{--</a>--}}
                            {{--</li>--}}
                            {{--@endif--}}
                        {{--</ul>--}}
                    {{--</li>--}}
                {{--@endif--}}

            </ul>
            <div class="panel panel-default">
                <fieldset class="scheduler-border" style="padding: 0px">
                    <legend class="scheduler-border" style="color: gray;margin-bottom:3px;">Last Activities</legend>
                    <div class="control-group">
                        <?php
                        $lastAction = \App\Libraries\CommonFunction::lastAction();
                        ?>

                         <small style="color: grey;margin: 0px;padding: 0px;">
                             @foreach($lastAction as $actionInfo)
                             <ul style="padding: 0px 15px;"><li><a href="{{url('users/profileinfo#tab_7')}}">@if($actionInfo !=null) {{$actionInfo->action}}  &nbsp;&nbsp;{{date("d-M-y h:i:a", strtotime($actionInfo->updated_at))}}  @endif </a></li></ul>
                             @endforeach
                         </small>
                        <a href="{{url('/users/profileinfo#tab_7')}}" class="pull-right btn btn-link " style="color: #286090">More <i class="fa fa-arrow-right"></i></a>
                    </div>
                </fieldset>
            </div>
            <div class="panel panel-default">

                <div class="panel-header text-center">
                    <br/>Response by<br/><br/>
                    {!!  Html::image('assets/images/business_automation.png','Business Automation logo',['width'=>'75']) !!}<br/><br/>
                    {{--<br/>Supported by <br/><br/>--}}
                    <div class="">
                    </div>
                </div>
            </div>

            <div class="panel-body">
                <small>Developed By <a href="http://ocpl.com.bd/">OCPL</a>.</small>
            </div>

        </div><!-- /.sidebar-collapse -->
    </div><!-- /.navbar-static-side -->
@endif {{--  user is active --}}
