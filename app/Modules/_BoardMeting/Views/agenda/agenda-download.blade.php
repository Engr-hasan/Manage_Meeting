
<div  style="text-align: center" class="text-center">
   <h3>{{$board_meeting_data->org_name}}</h3>
    <h4>{{$board_meeting_data->org_address}}</h4>
</div>

<div class="container">

    <div class="row"><br></div>
    <div class="row"><br></div>

    <table width="100%" cellspacing="0" style="border: 1 px solid #5f5f5f;">
        <tr>
            <td style="border: 1px solid #5f5f5f"> <span style="font-weight: bold">Location</span> </td>
            <td style="border: 1px solid #5f5f5f"> {{$meetingInfo[0]->bmlocation}}</td>
        </tr>

        <tr>
            <td style="border: 1px solid #5f5f5f"> <span style="font-weight: bold"> Date & Time</span> </td>
            <td style="border: 1px solid #5f5f5f"> {{$meetingInfo[0]->meting_date}}</td>
        </tr>

        <tr>
            <td style="border: 1px solid #5f5f5f"><span style="font-weight: bold">Subject</span></td>
            <td style="border: 1px solid #5f5f5f">{{$meetingInfo[0]->meting_subject}}</td>
        </tr>

        <tr>
            <td style="border: 1px solid #5f5f5f"><span style="font-weight: bold">Meeting No.</span></td>
            <td style="border: 1px solid #5f5f5f">{{$meetingInfo[0]->meting_number}}</td>
        </tr>

    </table>


    <div class="row"><br></div>
    <div class="row"><br></div>

    Agenda Details:

    <div class="row"><br></div>

    <table style="border:1px solid #5f5f5f" width="100%">

        <tr>
            <td style="border: 1px solid #5f5f5f">#</td>
            <td style="border: 1px solid #5f5f5f">Tracking ID</td>
            <td style="border: 1px solid #5f5f5f">Headline</td>
            <td style="border: 1px solid #5f5f5f">Description</td>
            <td style="border: 1px solid #5f5f5f">Remarks</td>
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
                <td style="border: 1px solid #5f5f5f">{{$count1}}</td>
                <td style="border: 1px solid #5f5f5f" colspan="4"><center>{{$details->name}}</center></td>
            </tr>


            @foreach($process_list_data as $specificRow)
                <?php $rowDataSplit = explode("AAAAA",$specificRow);


                    if(count($process_list_data) >= 1 && $rowDataSplit[0] != null){

                        ?>
            <tr>
                <td style="border: 1px solid #5f5f5f">{{$count1}}.{{$count2}}</td>
                <td style="border: 1px solid #5f5f5f">{{$rowDataSplit[0]}}</td>
                @if(isset($rowDataSplit[1]))

                <td style="border: 1px solid #5f5f5f">
                    {{$rowDataSplit[1]}}
                </td>
                <td style="border: 1px solid #5f5f5f">
                    {{$rowDataSplit[2]}}
                </td>
            <td style="border: 1px solid #5f5f5f">{{$rowDataSplit[3]}}</td>
                @else
            <td style="border: 1px solid #5f5f5f"></td>
                @endif

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
