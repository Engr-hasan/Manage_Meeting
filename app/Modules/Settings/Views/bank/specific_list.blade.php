
<div class="panel-group panel-info">

    <div class="panel-heading">
        <div class="">
            {!! '<b> ' .trans('messages.bank_user_list').'</b>' !!}
        </div>
    </div>

    <div class="panel-body">
        <div class="table-responsive">
            <table id="list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Payment Type</th>
                        <th>Status</th>
                        <th>No Of Branch</th>
                        <th>Transaction Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach($getList as $row)
                    <tr>
                        <td>{!! $i++ !!}</td>
                        <td>{!! $row->payment_type !!}</td>
                        <td>{!! $row->payment_status==12?'Paid': ($row->payment_status==11?'In-process':'Others ('.$row->payment_status.')') !!}</td>
                        <td>{!! $row->noOfBranch !!}</td>
                        <td>{!! number_format($row->amount,2) !!}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div><!-- /.table-responsive -->
    </div><!-- /.panel-body -->

</div><!-- /.panel -->

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
    "iDisplayLength": 20
});
});
</script>
@endsection
