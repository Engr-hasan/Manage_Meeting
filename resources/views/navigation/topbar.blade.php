<style>

    .navbar-top-links li a {
        padding: 5px;
        min-height: 0px;
    }
</style>
<div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
</div>
<!-- /.navbar-header -->
<div class="navbar-header">
    {!! Html::image(Session::get('logo'), 'logo', array( 'width' => 40,'height' => 40,'style'=>' margin: 5px 0 0 10px !important;border-radius:50%' ))!!}
    {{--    {!!  Html::image('assets/images/govt_logo.png','Logo',['width'=>50,'style'=>'margin: 0px 0 0 10px !important;']) !!}--}}

</div>
<div class="navbar-header header-caption">
    <b>{{Session::get('title')}}</b>
</div>
<ul class="nav navbar-top-links navbar-right">

    <li class="dropdown">
        <a class="dropdown-toggle change_url" title="" data-toggle="dropdown" href="#" style="color: #333 !important;">
            <div style="height: 7px;"></div>
            <i class="fa fa-language fa-fw"></i> {!! App::getLocale()=='en'?'English':'বাংলা'!!} <i
                    class="fa fa-caret-down"></i>
            <div></div>
            <small><span>&nbsp;</span></small>
        </a>
        <ul class="dropdown-menu dropdown-tasks language">
            <li>
                <a href="{{ url('language/en') }}">
                    <div>
                        <i class="fa fa-etsy fa-fw"></i> English
                    </div>
                </a>
            </li>
            <li>
                <a href="{{ url('language/bn') }}">
                    <div>
                        <i class="fa fa-bold fa-fw"></i> বাংলা
                    </div>
                </a>
            </li>
        </ul>
    </li>
    <li class="dropdown">
        <a class="dropdown-toggle change_url" title="" data-toggle="dropdown" href="#" style="color: #333 !important;">
            <?php
            if (!empty(Session::get('user_pic'))) {
                $userPic = url() . '/users/upload/' . Session::get('user_pic');
            } else {
                $userPic = URL::to('/assets/images/default_profile.jpg');

            }
            ?>
            <img src="{{ $userPic }}" class=" img-circle"
                 alt="" id="user_sisgnature " width="25" height="27px"/>
            {!! Auth::user()->user_full_name !!} <i class="fa fa-caret-down"></i>
            <div></div>
            <small>Last login: {{ Session::get('last_login_time') }} <span><i
                            class=" fa-lg fa fa-external-link-square change_url " id="dd" aria-hidden="true"
                            title="Access Log"></i></span></small>

        </a>
        <ul class="dropdown-menu dropdown-user" id="dropdown-responsive">
            @if(Auth::user()->user_type == '1x101' || Auth::user()->user_type == '2x202' || Auth::user()->delegate_to_user_id == 0)
                <li>
                    <a href="{{ url('users/profileinfo') }}"><i class="fa fa-user fa-fw"></i> My Profile</a>
                </li>
                <li class="divider"></li>
            @endif
            <li>
                <a href="{{ url('logout') }}"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
            </li>
        </ul>
    </li>


</ul>
<script>
    $(document).ready(function () {

        $('.change_url').click(function () {
            var title = $(this).attr('title');
            if (title == 'Access Log') {
                $('#dropdown-responsive').addClass('hidden');
                document.location.href = '{{'/users/profileinfo#tab_5'}}';
                return false;

            } else {
                $('#dropdown-responsive').removeClass('hidden');
            }
        })
    });
</script>