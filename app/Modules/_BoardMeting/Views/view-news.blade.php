@extends('layouts.admin')

@section('content')

    <?php
    $accessMode = ACL::getAccsessRight('BoardMeting');
    if (!ACL::isAllowed($accessMode, 'A')) {
        die('You have no access right! Please contact with system admin for more information.');
    }
    ?>
    @include('partials.messages')
    <div class="col-lg-12">

        <div class="panel panel-primary">
            <div class="panel-heading">
                {!!$news->heading!!}
            </div>
            <!-- /.panel-heading trans('messages.notice_view') -->
            <div class="panel-body">
                <div class="col-md-8">
                    <span class="text-{!!$news->importance!!}">{!!$news->details!!}</span>
                </div>
                <div class="col-md-4">
                    {!! CommonFunction::showAuditLog($news->updated_at, $news->updated_by) !!}
                </div>
            </div>
        </div>

@endsection


@section('footer-script')
@endsection <!--- footer script--->