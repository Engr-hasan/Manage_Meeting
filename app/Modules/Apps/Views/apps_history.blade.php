<div class="panel panel-primary">
    <div class="panel-heading">Application process history</div>
    <!-- /.panel-heading -->
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover ">
                <thead>
                <tr>
                    <th class="text-center">On Desk</th>
                    <th>Updated By</th>
                    <th>Status</th>
                    <th>Process Time</th>
                    <th>Remark</th>
                    <th>File</th>
                </tr>
                </thead>
                <tbody>
                <?php $sl = 0; ?>
                @forelse($process_history as $history)
                    <?php $sl++; ?>
                    <tr>
                        <td class="text-center">{{$history->deskname}}</td>

                        <td>{{$history->user_full_name}}</td>

                        <td>{{$history->status_name}}</td>

                        <td>{{$history->created_at}}</td>

                        <td>{{$history->process_desc}}</td>

                        <td>
                            @if($history->files != '')
                                <a class="btn btn-primary show-in-view btn-xs  download" data="{{$sl}}">
                                    <i class="fa fa-save"></i> Download</a>
                            @endif
                        </td>

                    </tr>
                    <tr style="display: none;" class="show_{{$sl}}">
                        <td colspan="6">
                            <?php
                            $file = explode(",", $history->files);
                            $sl2 = 0;
                            ?>
                            @foreach($file as $value)
                                <a href="{{url($value)}}" target="_blank"> File {{++$sl2}}</a>
                            @endforeach
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td>No result found!</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <!-- /.table-responsive -->
    </div>
    <!-- /.panel-body -->
</div>

@section('footer-script')
    <script type="text/javascript">
        $(document).ready(function () {
            $(document).on('click', '.download', function (e) {
                var value = $(this).attr('data');
                $('.show_' + value).show();
            });
        });
    </script>
@endsection
