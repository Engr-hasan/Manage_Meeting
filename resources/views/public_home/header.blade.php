<header style="width: 100%; height: auto; padding: 10px 0 0 0;">
    <div class="container">
        <div class="row">
            <!-- <div class="col-md-3"></div> -->
            <div class="col-md-1">
    {{--            {!! Html::image(Session::get('logo')) !!}--}}
                {!! Html::image(Session::get('logo'), 'logo', array( 'width' => 70 ))!!}
                {{--{!!trans('messages.logo_title')!!}--}}
                {{--{!!trans('messages.managedby')!!}--}}
            </div>
            <div class="col-md-10">
                <h3 class="less-padding"> {{Session::get('title')}}
                <!-- <hr class="hr" /> -->
                <h4><strong>{{Session::get('manage_by')}}</strong></h4>
            </div>
            <div class="col-md-1">
                <div class="dropdown pull-right">
                    <button class="btn btn-primary dropdown-toggle language_btn" type="button" data-toggle="dropdown"><i class="fa fa-language fa-fw"></i> {!! App::getLocale()=='bn'?'English':'বাংলা'!!} <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="{{ url('language/outside/en') }}"><i class="fa fa-language" aria-hidden="true"></i> English</a>
                        </li>
                        <li>
                            <a href="{{ url('language/outside/bn') }}"><i class="fa fa-bold fa-fw"></i> বাংলা</a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- <div class="col-md-3"></div> -->
        <!-- <div class="clearfix"> <br></div> -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</header>