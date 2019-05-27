@extends('layouts.admin')


@section('content')

    <div class="col-lg-12">
        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading panel-title">
                    {{ $resourceDetail->resource_title }}
                </div>
                <div class="panel-body">
                    <div class="col-md-9 col-md-offset-3">
                        <iframe style="border: 3px solid #EEE;" width="560" height="315" src="https://www.youtube.com/embed/{{ $resourceDetail->resource_link }}" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection