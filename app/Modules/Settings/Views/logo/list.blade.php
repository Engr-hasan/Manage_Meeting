@extends('layouts.admin')


<?php $accessMode=ACL::getAccsessRight('settings');
if(!ACL::isAllowed($accessMode,'V')) die('no access right!');
?>
@section('content')
    <section class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5>
                    <strong>{{ trans('messages.logo') }}</strong>
                </h5>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                @include('partials.messages')
                <div class="table-responsive" style="overflow:visible;">
                    <table id="list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" >
                        <thead>
                        <tr>
                            <th>logo</th>
                            <th>Title</th>
                            <th>Manage By</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($logoInformation as $logoData)
                        <tr>
                            <td style="width: 50px;"> {!! Html::image($logoData->logo, 'alt', array( 'width' => 70, 'height' => 50 ))!!}</td>

                            <td>{{$logoData->title}}</td>
                            <td>{{$logoData->manage_by}}</td>
                            <td> <a href="edit-logo/<?php echo Encryption::encodeId($logoData->id)?>" class="btn btn-xs btn-primary"><i class="fa fa-folder-open-o"></i> edit</a></td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div> <!-- /.table-responsive -->
            </div> <!-- /.panel-body -->
        </div><!-- /.panel -->

    </section>

@endsection

@section('footer-script')

    @include('partials.datatable-scripts')

    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

@endsection
