<div class="row">
    <style>
        .col-md-offset-4 {
            margin-left: 37%;
        }
    </style>
    <div class="col-sm-12">
        <h2 style="text-align: center;color: #2d5a94"><b>WELCOME TO THE BOARD MEETING</b><br>
            <?php
            $i = 0;
            if($getChairperson !=''){
            ?>
           Meeting No. {{$lastBoardMeetingInfo->meting_number}}
            <br>
            <span style="color: #5cb85c">Chairperson of the board</span><br>

            @foreach($getChairperson as  $data)
                @if($data->type == 'Yes')
                <span style="font-size: 22px">{{$data->user_name}} ,{{$data->designation}}</span><br><br>

                @elseif($data->type == 'No')
                    <?php
                    $i = $i+1;
                    if($i ==1){
                    ?>
                    <span style="color: #5cb85c; margin-right:50px ">Members of the board</span><br>
                    <?php
                        }
                        ?>
                    <div class="col-lg-6 col-md-offset-4" style="text-align: left;font-size: 22px;">{{$data->user_name}} ,{{$data->designation}}</div>
                        {{--<span style="font-size: 22px; text-align: left">{{$data->user_name}} ,{{$data->designation}}</span><br>--}}
                @endif
                @endforeach
            <?php } ?>

        </h2>
</div>
</div>


<!-- Notice & Instruction -->
<div class="row"><br>
    <div class="panel panel-danger">
        <div class="panel-heading">Notice & Instructions:</div>
        <div class="panel-body">
            <div class="col-sm-12">

                @if($notice)
                    <?php
                    $arr = $notice;
                    echo '<table class="table basicDataTable ">';
                    // echo "<caption class='panel panel-heading'></caption><tbody>";
                    foreach ($arr as $value) {
                        echo "<tr class='abc'><td width='150px'>$value->Date</td><td><span class='text-$value->importance'><a href='".url('support/view-notice/'.\App\Libraries\Encryption::encodeId($value->id))."'> <b>$value->heading</b></a></span></td></tr>";
                    }
                    echo '</tbody></table>';
                    ?>
                @endif
            </div>
        </div>
    </div>
</div>




