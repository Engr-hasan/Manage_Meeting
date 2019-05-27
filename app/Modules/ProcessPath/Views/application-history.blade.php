<div class="panel panel-orange">
    @if(\Illuminate\Support\Facades\Auth::user()->user_type == '1x101')
        <span class="pull-right"><a href="{{url('/loan-locator/verify_history/'.Encryption::encodeId($appInfo->process_type_id).'/'.Encryption::encodeId($appInfo->process_list_id))}}" class="btn btn-primary btn-xs">Block Chain Verification</a> </span>
    @endif
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-responsive table-striped table-bordered table-hover ">
                <thead>
                <tr>
                    <th width="10%" class="text-center">On Desk</th>
                    <th width="15%">Updated By</th>
                    <th width="15%">Status</th>
                    <th width="15%">Process Time</th>
                    <th width="10%">Action</th>
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
                        <td>{{ date('d-m-Y h:i A', strtotime($history->updated_at  ))}}</td>

                        <td>
                            <button type="button" class="btn btn-warning btn-xs MoreInfo" value="{{$sl}}">More <i class="fa fa-angle-down"></i></button>
                        </td>
                    </tr>
                    <tr hidden="" class="{{$sl}}">
                        <td colspan="3"><b>Remarks: </b>{{$history->process_desc}}</td>
                        <td colspan="2"><b>Attachment: </b>

                            @if(@$history->files != '')
                                <?php $historyFile = explode(",", @$history->files); ?>

                                @foreach($historyFile as $value)
                                    <a target="_blank" href="{{ url($value) }}" class="btn btn-primary show-in-view btn-xs  download" data="{{$sl}}">
                                        <i class="fa fa-save"></i> Download
                                    </a>
                                @endforeach
                            @endif {{-- history files --}}
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

