@extends('layouts.admin')

@section('content')
<?php $accessMode=ACL::getAccsessRight('settings');
if(!ACL::isAllowed($accessMode,'V')) die('no access right!');
?>
<div class="col-lg-12">

    {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
    {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="pull-left">
                <h5><strong><i class="fa fa-list"></i> {!! trans('messages.faq_cat_list') !!}</strong></h5>
            </div>
            <div class="pull-right">
                @if(ACL::getAccsessRight('settings','A'))
                <a class="" href="{{ url('/faq/create-faq-cat') }}">
                    {!! Form::button('<i class="fa fa-plus"></i> <b>'.trans('messages.new_faq_cat'). '</b>', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                </a>
                @endif
            </div>
            <div class="clearfix"></div>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            <div class="table-responsive">
                <table id="list" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Articles</th>
                            <th>Draft</th>
                            <th>Unpublished</th>
                            <th>Private</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div><!-- /.table-responsive -->
        </div><!-- /.panel-body -->
    </div><!-- /.panel -->
</div><!-- /.col-lg-12 -->

@endsection

@section('footer-script')

@include('partials.datatable-scripts')

<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>

<script>
    $(function () {
        $('#list').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{url("faq/get-faq-cat-details-data")}}',
                data: function (d) {
                    d._token = $('input[name="_token"]').val();
                }
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'noOfItems', name: 'noOfItems'},
                {data: 'Draft', name: 'Draft'},
                {data: 'Unpublished', name: 'Unpublished'},
                {data: 'Private', name: 'Private'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });
    });
</script>
@endsection
