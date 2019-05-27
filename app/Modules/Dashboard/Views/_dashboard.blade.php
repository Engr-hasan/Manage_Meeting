<div class="row">
    <div class="col-sm-12">
    <?php
    $user_type = Auth::user()->user_type;
    $type = explode('x', $user_type);
    ?>
    <!--Widget start-->
    {{--@if($widgetsGroup)--}}
    {{--@foreach($widgetsGroup as $widget)--}}
    {{--<div class="col-lg-2 col-md-6">--}}
    {{--<div class="panel panel-{{ !empty($widget->theme) ? $widget->theme :'default' }}">--}}
    {{--<div class="panel-heading">--}}
    {{--<div class="row">--}}
    {{--<div class="col-xs-3">--}}
    {{--<i class="fa fa-list-alt fa-2x"></i>--}}
    {{--</div>--}}
    {{--<div class="col-xs-9 text-right">--}}
    {{--<div class="h4">{!! $widget->value !!}</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="row">--}}
    {{--<div class="col-xs-12 text-right">--}}
    {{--<div>--}}
    {{--{!!$widget->caption!!}--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<a href="{{url(\App\Libraries\Encryption::encodeId($widget->process_type_id))}}" target="_blank">--}}
    {{--<a href="{{url($widget->url.'/list/'.encodeId($widget->process_type_id))}}" target="_blank">--}}
    {{--<div class="panel-footer" style="padding: 0px 10px;">--}}
    {{--<span class="pull-left">{!!trans('messages.details')!!}</span>--}}
    {{--<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>--}}
    {{--<div class="clearfix"></div>--}}
    {{--</div>--}}
    {{--</a>--}}

    {{--</div>--}}
    {{--</div>--}}
    {{--@endforeach--}}
    {{--@endif--}}
    <!--Widget End -->


    </div>
</div>
<div class="row">
@if($services && ! in_array($type[0], [11,13,5]) ) <!-- Bank User -->
    @foreach($services as $service)
        <div class="col-lg-2 col-md-6">
            <div class="panel panel-{{ !empty($service->panel) ? $service->panel :'default' }}">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i style="font-size: 22px;" class="fa fa-list-alt fa-3x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="h3" style="margin-top:0;margin-bottom:0;font-size:20px;">
                                {{ !empty($service->totalApplication) ? $service->totalApplication :'0' }}</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 text-right">
                            <div style="font-size: 13px;">
                                {{ !empty($service->name) ? $service->name :'N/A'}}
                            </div>
                        </div>
                    </div>
                </div>
                <a href="{{ !empty($service->form_url) && $service->form_url =='/#' ?
                'javascript:void(0)' : url($service->form_url.'/list/'.\App\Libraries\Encryption::encodeId( $service->id)) }}" {{ !empty($service->form_url) && $service->form_url !='/#' ? 'target="_blank"' :'' }}>
                    <div class="panel-footer" style="padding: 0px 10px;">
                        <span class="pull-left">{!!trans('messages.details')!!}</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
    @endforeach
<!--Widget End -->
    @endif
</div>


<br>
<div class="row">
    <?php
    $desk_id_array = explode(',', \Session::get('user_desk_ids'));
    ?>
    @if((!empty($desk_id_array[0])) || Auth::user()->user_type=="1x101"|| Auth::user()->user_type=="5x505")

        <div class="text-center">
            <!-- Moris Chart -->
            <?php
            if (!empty($deshboardObject)) {
            foreach ($deshboardObject as $row) {
            $div = 'dbobj_' . $row->db_obj_id;
            ?>
            <div class="col-md-4">
                <?php
                $para1 = DB::select(DB::raw($row->db_obj_para1));
                switch ($row->db_obj_type) {
                case 'SCRIPT':
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5><strong><?php echo $row->db_obj_title; ?></strong></h5>
                    </div>
                    <div class="panel-body">
                        <div id="<?php echo $div; ?>" style="width: 100%; height: 350px; text-align:center;"><br/><br/>Chart will be loading in 5 sec...</div>
                    </div>
                </div>

                <?php
                $script = $row->db_obj_para2;
                //$datav['charttitle'] = $row->db_obj_title;
                $datav['charttitle'] = '';
                $datav['chartdata'] = json_encode($para1);
                $datav['baseurl'] = url();
                $datav['chartediv'] = $div;
                echo '<script type="text/javascript">' . CommonFunction::updateScriptPara($script, $datav) . '</script>';
                break;
                default:
                    break;
                }
                ?>
            </div>
            <?php
            }
            }
            ?>
        </div>
            <!-- Bar Chart -->
            @if(!empty($dashboardObjectBarChart))
                <?php
                $i=0;
                ?>
                @foreach($dashboardObjectBarChart as $record)
                    <?php
                    $i++;
                    ?>
                    <div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5><strong>{!! $record->db_obj_title !!}</strong>
                            </div>
                            <div class="panel-body">
                                <?php
                                $barChartData = DB::select(DB::raw($record->db_obj_para1));
                                //                                dd($barChartData);
                                $barChartArray = array();

                                foreach ($barChartData as $data)
                                {
                                    $barChartArray[$data->TITLE] = $data->VALUE;
                                }
                                ?>
                                <div id="bar-example<?php echo $i;?>"></div>

                                <script>
                                    Morris.Bar({
                                        element: 'bar-example'+'{{ $i }}',
                                        data: [
                                                <?php
                                                foreach ($barChartArray as $key => $val) {?>
                                            {
                                                y: '<?php echo $key?>',
                                                a: '<?php echo $val?>'
                                            },
                                            <?php

                                            }?>
                                        ],
                                        xkey: 'y',
                                        ykeys: ['a'],
                                        labels: ['Series A'],
                                        barColors: function (row, series, type) {
                                            if (series.key == 'a') {
                                                if (row.y < 10)
                                                    return "red";
                                                else if (row.y >= 10 && row.y <= 50)
                                                    return "green";
                                                else
                                                    return "blue";
                                            }
                                            else {
                                                return "green";
                                            }
                                        }
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif


    @endif
</div>
<!-- /.row -->
<!-- charts -->




<!-- Notice & Instruction -->
<div class="row">
    <div class="col-sm-10">
        @if($notice)
            <?php
            $arr = $notice;
            echo '<table class="table basicDataTable">';
            echo "<caption>Notice & Instructions:</caption><tbody>";
            foreach ($arr as $value) {
                echo "<tr><td width='150px'>$value->Date</td><td><span class='text-$value->importance'><a href='".url('support/view-notice/'.\App\Libraries\Encryption::encodeId($value->id))."'> <b>$value->heading</b></a></span></td></tr>";
            }
            echo '</tbody></table>';
            ?>
        @endif
    </div>
</div>




