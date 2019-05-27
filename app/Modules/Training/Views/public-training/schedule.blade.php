@if(count($training_resource) > 0)
    <div class="panel panel-primary">
        <div class="panel-heading">Training Resources</div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="alert alert-info">
                    <th>Title</th>
                    <th>Types</th>
                    <th>Action</th>
                    </thead>
                    <tbody>
                    @foreach($training_resource as $resource)
                        <tr>
                            <td>{!! $resource->resource_title !!}</td>
                            <?php
                            $resource_type = '';
                            if ($resource->resource_type == 1)
                            {
                                $resource_type = 'Document/ Excel/ CSV';
                            }
                            elseif ($resource->resource_type == 2)
                            {
                                $resource_type = 'PDF';
                            }
                            elseif ($resource->resource_type == 3)
                            {
                                $resource_type = 'Embedded Video';
                            }
                            ?>
                            <td>{!! $resource_type !!}</td>
                            <?php
                            $action = '';
                            if ($resource->resource_type == 1){ // 1 = Doc/CSV/Excel
                                $action = '<a href="'.url($resource->resource_link).'" class="btn btn-xs btn-info"><i class="fa fa-folder-open-o"></i> Open</a> ';
                            }
                            elseif ($resource->resource_type == 2){ // 2 = PDF
                                $action = '<a href="'.url($resource->resource_link).'" target="_blank" class="btn btn-xs btn-info"><i class="fa fa-folder-open-o"></i> Open</a> ';
                            }
                            elseif($resource->resource_type == 3){ // 3 = Embedded Link
                                $action = '<a href="' . url('training-resource-public/embedded/' . Encryption::encodeId($resource->id)) .
                                        '" target="_blank" class="btn btn-xs btn-info"><i class="fa fa-folder-open-o"></i> View Video</a> ';
                            }
                            ?>
                            <td>{!! $action !!}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
@if(count($schedule_list) > 0)
    <div class="col-md-12 well well-sm">
        <div class="well well-sm">
            <span><p class="lead text-center text-info">{{ $schedule_list[0]->training_title }}<br/>{{ $schedule_list[0]->public_user_types }}</p></span>
        </div>
        <div class="panel panel-success">
            <div class="panel-heading">{{trans('messages.Training Schedule')}}</div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="alert alert-success">
                        <th>Date</th>
                        <th>Time</th>
                        <th>Location</th>
                        <th>Trainer</th>
                        <th>Action</th>
                        </thead>
                        <tbody>
                        @foreach($schedule_list as $schedule)
                            <tr>
                                <?php $start_date = !empty($schedule->start_time) ? date('jS M Y', strtotime($schedule->start_time)) : '';?>
                                <td>{!! $start_date !!}</td>
                                <?php $start_time = !empty($schedule->start_time) ? date('h:i A', strtotime($schedule->start_time)) : '';
                                $end_time = !empty($schedule->end_time) ? date('h:i A', strtotime($schedule->end_time)) : '';?>
                                <td><span>{!! $start_time !!}</span> to <span>{!! $end_time !!}</span></td>
                                <td>{!! $schedule->location !!}</td>
                                <td>{!! $schedule->trainer_name !!}</td>
                                <td>
                                    @if( $schedule->total_seats > $schedule->total_participant)
                                        <a id="{{ \App\Libraries\Encryption::encodeId($schedule->id) }}" class="btn btn-xs btn-primary applyForTraining"><i class="fa fa-sign-out"></i> Apply</a>
                                    @else
                                        <span class="text-primary"><strong>Booked!!!</strong></span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="col-md-12">
        <div class="well well-sm">
            <span><p class="lead text-center text-info">বর্তমানে কোন প্রশিক্ষণ সূচি নেই।  নতুন সময়-সূচি প্রাপ্তি সাপেক্ষে  প্রকাশ করা হবে</p></span>
        </div>
    </div>
@endif