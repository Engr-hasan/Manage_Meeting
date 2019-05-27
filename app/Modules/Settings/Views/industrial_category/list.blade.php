@extends('layouts.admin')

@section('content')
    <?php
    $accessMode = ACL::getAccsessRight('settings');
    if (!ACL::isAllowed($accessMode, 'V')) {
        die('You have no access right! Please contact with system admin for more information.');
    }
    ?>
    @include('partials.messages')
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="pull-left">
                    <h5><strong><i class="fa fa-list"></i> <strong>{!! trans('messages.industrial_category') !!}</strong></strong></h5>
                </div>
                <div class="pull-right">
                    @if(ACL::getAccsessRight('settings','A'))
                        <a class="" href="{{ url('/settings/create-indus-cat') }}">
                            {!! Form::button('<i class="fa fa-plus"></i> <b>'.trans('messages.industrial_category_new'). ' </b>', array('type' =>'button', 'class' => 'btn btn-default')) !!}
                        </a>
                    @endif
                </div>
                <div class="clearfix"></div>
            </div><!-- /.panel-heading -->

            <div class="panel-body">
                <div class="table-responsive">
                    <table id="list" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Industrial Category</th>
                            <th>Color Name</th>
                            <th>Active Status</th>
                            <th width="10%">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1; ?>
                        @foreach($rows as $row)
                            <tr>
                                <td>{!! $i++ !!}</td>
                                <td>{!! $row->indus_cat !!}</td>
                                <td>{!! $row->colo !!}</td>
                                <td>
                                    @if($row->is_active)
                                        <span class="text-success"><strong>Active</strong></span>
                                    @else
                                        <span class="text-warning"><strong>Inactive</strong></span>
                                    @endif
                                </td>
                                <td>
                                    @if(ACL::getAccsessRight('settings','E'))
                                        <a href="{!! url('settings/edit-indus-cat/'. Encryption::encodeId($row->indus_id)) !!}" class="btn btn-xs btn-success">
                                            <i class="fa fa-folder-open-o"></i> Open
                                        </a>
                                        <a href="javascript:void(0)"
                                           class="btn btn-xs btn-danger" onclick="ConfirmDelete('{{Encryption::encodeId($row->indus_id)}}')">
                                            <i class="fa fa-times"></i></a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div><!-- /.table-responsive -->
            </div><!-- /.panel-body -->
        </div><!-- /.panel -->
    </div><!-- /.col-lg-12 -->

@endsection

@section('footer-script')

    @include('partials.datatable-scripts')
    <script>
        $(function () {
            $('#list').DataTable({
                "paging": true,
                "lengthChange": true,
                "ordering": true,
                "info": false,
                "autoWidth": false,
                "iDisplayLength": 50
            });
        });

        function ConfirmDelete(id) {
            var sure_del = confirm("Are you sure you want to delete this item?");
            if (sure_del) {
                var url = '<?php echo url();?>';
                window.location=(url+"/settings/delete/IndustryCategories/"+id);
            }else {
                return false;
            }
        }

    </script>
@endsection
