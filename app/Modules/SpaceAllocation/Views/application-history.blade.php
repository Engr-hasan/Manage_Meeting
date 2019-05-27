<div class="panel panel-orange">
    <div class="panel-heading"> Application Process History</div><!-- /.panel-heading -->
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-responsive table-striped table-bordered table-hover ">
                <thead>
                <tr>
                    <th width="10%" class="text-center">On Desk</th>
                    <th width="15%">Updated By</th>
                    <th width="15%">Status</th>
                    <th width="15%">Process Time</th>
                    <th width="10%">Remark</th>
                    <th width="10%">File</th>
                </tr>
                </thead>
                <tbody>
                <?php $sl = 0; ?>
                @forelse($process_history as $history)
                    <?php $sl++; ?>
                    <tr>
                        <td class="text-center">{{ $history->deskname }}</td>
                        <td>{{$history->user_full_name}}</td>
                        <td>{{$history->status_name}}</td>
                        <td>{{  \App\Libraries\CommonFunction::updatedOn($history->updated_at) }}</td>
                        <td>
                            @if($history->process_remarks != '')
                                <a target="_blank" href="{{url('space-allocation/view-comment/'.\Encryption::encodeId($history->id))}}"
                                   class="btn btn-xs btn-warning" data-target="#myModal" data-toggle="modal">View Remarks</a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($history->files != '')
                                <?php $historyFile = explode(",", $history->files); ?>
                                @foreach($historyFile as $value)
                                    <a target="_blank" href="{{ url($value) }}" class="btn btn-primary show-in-view btn-xs  download" data="{{$sl}}">
                                        <i class="fa fa-save"></i> Download</a>
                                @endforeach
                            @endif {{-- history files --}}
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
                        <td colspan="6" style="text-align: center">No result found!</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div><!-- /.table-responsive -->
    </div><!-- /.panel-body -->
</div>
