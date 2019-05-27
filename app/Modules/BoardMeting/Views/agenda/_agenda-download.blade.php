

<div class="container">
    <div class="col-md-12" style="border: 1px solid black; min-height: 100px;">

        <center class="text-center"><font color="#8b0000">Address</font></center>


    </div>

    <div class="row"><br></div>
    <div class="row"><br></div>

    <table width="100%">
        <tr>
            <td> <span style="font-weight: bold">Location</span> </td>
            <td> {{$meetingInfo[0]->bmlocation}}</td>
        </tr>

        <tr>
            <td> <span style="font-weight: bold"> Date & Time</span> </td>
            <td> {{$meetingInfo[0]->meting_date}}</td>
        </tr>

        <tr>
            <td><span style="font-weight: bold">Subject</span></td>
            <td>{{$meetingInfo[0]->meting_subject}}</td>
        </tr>

        <tr>
            <td><span style="font-weight: bold">Meeting No.</span></td>
            <td>{{$meetingInfo[0]->meting_number}}</td>
        </tr>

    </table>


    <div class="row"><br></div>
    <div class="row"><br></div>

    Agenda Details:

    <div class="row"><br></div>

    <table style="border:1px solid black" width="100%">

        <tr>
            <td></td>
            <td>Tracking ID</td>
            <td>Title</td>
            <td>Details</td>
            <td>Remarks</td>
        </tr>


        <?php
        $count1 = 1;
        ?>
        @foreach($meetingInfo as $details)
            <?php
                $count2=1;
                $process_list_data = explode("@@", $details->pbmdatas);
            ?>
                {{--@if($count1 == 1)--}}
                    {{--<tr>--}}
                        {{--<td></td>--}}
                        {{--<td>Tracking ID</td>--}}
                        {{--<td>Title</td>--}}
                        {{--<td>Details</td>--}}
                        {{--<td>Remarks</td>--}}
                    {{--</tr>--}}
                {{--@endif--}}
            <tr>
                <td>{{$count1}}</td>
                <td colspan="4"><center>{{$details->name}}</center></td>
            </tr>


            @foreach($process_list_data as $specificRow)
                <?php $rowDataSplit = explode("AAAAA",$specificRow);


                    if(count($process_list_data) >= 1 && $rowDataSplit[0] != null){

                        ?>
            <tr>
                <td>{{$count1}}.{{$count2}}</td>
                <td>{{$rowDataSplit[0]}}</td>
                @if(isset($rowDataSplit[1]))
                <?php

                    $objstr = json_decode($rowDataSplit[1],true);
                ?>
                <td>

                        {{$objstr["Task Name"]}}

                </td>
                <td>
                    {{$objstr["Comments"]}} {{$objstr["Remarks"]}}
                </td>
                @else
            <td></td>
            <td></td>
                @endif
                <td></td>
            </tr>
                    <?php
                        }else{

                    }
                    $count2++;
                    ?>
                    @endforeach

            <?php
            $count1++;
            ?>
        @endforeach
    </table>

</div>