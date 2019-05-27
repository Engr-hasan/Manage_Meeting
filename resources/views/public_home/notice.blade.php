<div id="list_1" class="tab-pane active {!! (Request::segment(2)=='index' OR Request::segment(2)=='')?'active':'' !!}">

    <div class="panel-body">
        <div class="row">
            @if($notice)
                <div class="col-lg-12">

                    <?php
                    $arr = $notice;
                    echo '<table class="table basicDataTable">';
                    foreach ($arr as $value) {
                        $update_date = App\Libraries\CommonFunction::changeDateFormat(substr($value->update_date, 0, 10));
                        if($value->prefix == 'board-meeting'){
                            echo "<tr><td width='120px'>$update_date</td><td><span class='text-$value->importance'><a href='#' class='notice_heading'> <b>$value->heading</b></a></span><span class='details' style='display: none;'><br/> <a target='_blank' href='$value->details'> $value->details</a></span></td></tr>";
                        }else{
                            echo "<tr><td width='120px'>$update_date</td><td><span class='text-$value->importance'><a href='#' class='notice_heading'> <b>$value->heading</b></a></span><span class='details' style='display: none;'><br/> $value->details</span></td></tr>";
                        }
                    }
                    echo '</tbody></table>';
                    ?>
                </div>
            @endif
            <div class="panel panel-body">

            </div><!-- /.panel-body -->

        </div>
    </div>
    <!-- /.panel-body -->
    <div class="dod" id="dod_notice">
        {{--Notice loading...--}}

        @foreach($dynamicSection as $service)
         <a class="service" href="#" data-id="{{$service->id}}">
        <div class="col-lg-6 col-md-6">
            <div class="panel panel-{{ !empty($service->panel) ? $service->panel :'info' }}">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i style="font-size: 40px;" class="fa fa-list-alt fa-3x"></i>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div style="font-size: 16px;">
                                {{ !empty($service->name) ? $service->name :'N/A'}}
                            </div>
                        </div>
                    </div>
                </div>

                    <div class="panel-footer" style="padding: 10px;font-size: 16px;">
                        <span class="pull-left">{!!trans('messages.details')!!}</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>

            </div>
        </div>
            </a>
        @endforeach
        <br>
        <div id="details" class="col-sm-12" style="text-align: justify"></div>
    </div>
</div>

<script>
    $('.service').click(function () {
        var type_id = $(this).attr("data-id");
       var obj=$(this).after('<span class="loading_data">Loading...</span>');
        $.ajax({
            url: "<?php echo url(); ?>/login/type_wise_details",
            type: 'post',
            data: {
                _token: $('input[name="_token"]').val(),
                type_id: type_id

            },
            success: function (response) {
                $('#details').html(response.data.login_page_details);
                obj.next().hide();

            }
        });

    });






</script>

