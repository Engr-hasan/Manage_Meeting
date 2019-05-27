@extends('layouts.admin')

@section('page_heading',trans('messages.session_view'))
<?php $accessMode=ACL::getAccsessRight('settings');
if(!ACL::isAllowed($accessMode,'V')) die('no access right!');
?>
@section('content')


    <section class="col-md-12">
        <div class="col-md-12">
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <b>{!! trans('messages.session_view') !!}</b>

                    </div> <!-- /.panel-heading -->

                    <div class="panel-body">
                        <div class="col-md-7">
                            <dl class="dl-horizontal">
                                <dt>Caption</dt>
                                <dd>{!!$hajj_sessions->caption!!}</dd>
                            </dl>
                        </div>
                        <div class="col-md-7">
                            <dl class="dl-horizontal">
                                <dt>State</dt>
                                <dd>{!!$hajj_sessions->state!!}</dd>
                            </dl>
                        </div>

                    </div><!-- /.box -->
                    <div class="panel-footer">
                        <a href="{{url('settings/edit-session/'.Encryption::encodeId($hajj_sessions->id))}}" class="btn btn-default">
                            <i class="glyphicon glyphicon-edit"></i> Edit Session</a>
                    </div>
                </div>

            </div>
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        Agency Type wise allocated quota
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="55%">
                            <thead>
                            <tr>
                                <th>Agency Type</th>
                                <th>Allocated Quota</th>
                                <th>Quota Used</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($agency_type_quota as $quota)
                                <tr>
                                    <td>{{$quota->name}}</td>
                                    <td>{{$quota->quota}}</td>
                                    <td>{{$quota->total_quota}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Demographical List --}}
        @include('settings::demo_group.list', ['sessionId' => $decoded_id, 'encodedSessionId' => $id])


        <div class="col-md-12">
            <span class="pull-left">
                <a href="{{url('settings/list-session')}}" id="printBtn" class="btn btn-default"><i class="fa fa-times"></i> Close</a>
            </span>
        </div>
    </div>
</section>

@endsection

@section('footer-script')
<script>
    $(document).ready(function () {

    });
</script>
@endsection <!--- footer script--->
