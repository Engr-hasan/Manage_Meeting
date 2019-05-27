@extends('layouts.plane')

@section('body')
<div id="wrapper">
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            @include ('navigation.topbar')
        </nav>

    <div id="page-wrapper" style="border: none !important;margin: 0 !important;">
        <div class="row">
            <br/>
            @yield('content')
        </div>
    </div>
</div>
@stop

