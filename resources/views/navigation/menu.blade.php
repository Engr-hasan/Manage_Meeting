<?php
$regActive = '';
$epActive = '';
$ipActive = '';
$vaActive = '';
$vrActive = '';
$wpActive = '';
$lrActive = '';
$lsp = '';
$lpp = '';
$sampleDocActive = '';
$colpseIn = '';

//if (Request::is('space-allocation/list') || Request::is('space-allocation/add') || Request::is('space-allocation/application/edit/*') ||
//    Request::is('space-allocation/application/view/*')
//) {
//    $regActive = 'active';
//    $colpseIn = 'in';
//}

?>


{{--<li class="{{ (Request::is('/space-allocation') ? 'active' : '') }}">--}}
{{--<a class="@if (Request::is('space-allocation/list/*') || Request::is('space-allocation/add') || Request::is('space-allocation/view/*'))  active @endif" href="{{ url ('/space-allocation/list/'.\App\Libraries\Encryption::encodeId(1)) }}">--}}
{{--<i class="fa fa-home fa-fw" aria-hidden="true"></i>Space Allocation--}}
{{--</a>--}}
{{--</li>--}}


{{--<li class="{{ (Request::is('/general-apps') ? 'active' : '') }}">--}}
    {{--<a class="@if (Request::is('general-apps/list/*') || Request::is('general-apps/add') || Request::is('general-apps/view/*'))  active @endif"--}}
       {{--href="{{ url ('/general-apps/list/'.\App\Libraries\Encryption::encodeId(2)) }}">--}}
        {{--<i class="fa  fa-file-text fa-fw" aria-hidden="true"></i> {{trans('messages.new_registration')}}--}}
    {{--</a>--}}
{{--</li>--}}


{{--<li class="{{ (Request::is('/issuance_of_licenses') ? 'active' : '') }}">--}}
    {{--<a class="@if (Request::is('issuance_of_licenses/*') || Request::is('issuance_of_licenses/add') || Request::is('issuance_of_licenses/view/*'))  active @endif"--}}
       {{--href="##">--}}
        {{--<i class="fa  fa-credit-card fa-fw" aria-hidden="true"></i> {{trans('messages.issuance_of_licenses')}}--}}
    {{--</a>--}}
{{--</li>--}}
{{--<li class="{{ (Request::is('/permit_later') ? 'active' : '') }}">--}}
    {{--<a class="@if (Request::is('permit_later/*') || Request::is('permit_later/add') )  active @endif" href="##">--}}
        {{--<i class="fa  fa-list-alt fa-fw" aria-hidden="true"></i> {{trans('messages.permit_later')}}--}}
    {{--</a>--}}
{{--</li>--}}
{{--<li class="{{ (Request::is('/visa_recommendation') ? 'active' : '') }}">--}}
    {{--<a class="@if (Request::is('visa_recommendation/*') || Request::is('visa_recommendation/add') )  active @endif" href="##">--}}
        {{--<i class="fa  fa-cc-visa fa-fw" aria-hidden="true"></i> {{trans('messages.visa_recommendation')}}--}}
    {{--</a>--}}
{{--</li>--}}
{{--<li class="{{ (Request::is('/work_permit') ? 'active' : '') }}">--}}
    {{--<a class="@if (Request::is('work_permit/*') || Request::is('work_permit/add') )  active @endif" href="##">--}}
        {{--<i class="fa  fa-adjust fa-fw" aria-hidden="true"></i> {{trans('messages.work_permit')}}--}}
    {{--</a>--}}
{{--</li>--}}




{{--<li class="{{ (Request::is('/co-branded-card') ? 'active' : '') }}">--}}
    {{--<a class="@if (Request::is('/co-branded-card/list/*') || Request::is('co-branded-card/*') || Request::is('co-branded-card/add') )  active @endif"--}}
       {{--href="{{ url ('/co-branded-card/list/'.\App\Libraries\Encryption::encodeId(3)) }}">--}}
        {{--<i class="fa  fa-adjust fa-fw" aria-hidden="true"></i> {{trans('messages.new_card_application_recommendation')}}--}}
    {{--</a>--}}
{{--</li>--}}

{{--<li class="{{ (Request::is('/limit-renewal') ? 'active' : '') }}">--}}
    {{--<a class="@if (Request::is('/limit-renewal/list/*') || Request::is('limit-renewal/*') || Request::is('limit-renewal/add') )  active @endif"--}}
       {{--href="{{ url ('/limit-renewal/list/'.\App\Libraries\Encryption::encodeId(4)) }}">--}}
        {{--<i class="fa  fa-adjust fa-fw" aria-hidden="true"></i> {{trans('messages.limit_renewal_recommendation')}}--}}
    {{--</a>--}}
{{--</li>--}}
<li class="{{ (Request::is('/meeting-form') ? 'active' : '') }}">
    <a class="@if(Request::is('meeting-form/list/*') || Request::is('meeting-form/view/*') || Request::is('meeting-form/edit') || Request::is('meeting-form/add') )  active @endif"
       href="{{ url ('/meeting-form/list/'.\App\Libraries\Encryption::encodeId(10)) }}">
        <i class="fa  fa-adjust fa-fw" aria-hidden="true"></i> {{trans('messages.meeting_form')}}
    </a>
</li>



