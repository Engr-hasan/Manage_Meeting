
<div style="text-align: center" class="text-center">
    <h3>{{$board_meeting_data->org_name}}</h3>
    <h4>{{$board_meeting_data->org_address}}</h4><br>
    <h5><u>{{$meetingInfo[0]->meting_subject}}</u></h5>
    <h5><u>Held on {{$meetingInfo[0]->bmlocation}} at  {{date("d", strtotime($meetingInfo[0]->meting_date))}}<sup>th</sup> {{date("M, Y", strtotime($meetingInfo[0]->meting_date))}}</u></h5>

</div>

{{--<div class="container">--}}
    <div class="row">

        <div class="col-md-7" style="float: left;width: 70%;">Ref: {{$meetingInfo[0]->reference_no}} </div>
        <div class="col-md-5" style="float: left;width: 20%;text-align: right"> {{date("d", strtotime($meetingInfo[0]->meting_date))}}<sup>th</sup> {{date("M, Y", strtotime($meetingInfo[0]->updatedAt))}}  </div>
        <br>
        <div class="col-md-12">
           The {{$meetingInfo[0]->meting_number}}<sup>th</sup> meeting of the Management Committee was held on
            {{date("d", strtotime($meetingInfo[0]->meting_date))}}<sup>th</sup>  {{date("M, Y", strtotime($meetingInfo[0]->meting_date))}} at {{date("h:i A", strtotime($meetingInfo[0]->meting_date))}}
            in the meeting room. The meeting was presided over by {{$getChairperson[0]->user_name}},{{$getChairperson[0]->designation}}
            with the following Director and Executives were present in the meeting:-<br><br>
            <?php
            $i=1;
            ?>
            @foreach($getChairperson as $userInfo)
                @if($userInfo->type =='Yes') {{-- chairperson --}}
                    <?php  continue ?>
                @else
                    @if($userInfo->user_email == Auth::user()->user_email) {{-- checking board admin only alow member --}}
                        <?php  continue ?>
                    @endif
                        {{$i++}}. {{$userInfo->user_name}}- {{$userInfo->designation}}<br>
                @endif
            @endforeach
            <br>
            The following decision was passed in the {{$meetingInfo[0]->meting_number}}<sup>th</sup> meeting of MCM held on {{date("d", strtotime($meetingInfo[0]->meting_date))}}<sup>th</sup> {{date("M, Y", strtotime($meetingInfo[0]->meting_date))}}.
        </div>

    </div>



    <div class="row"><br></div>
    Agenda Details:
    <table style="border:1px solid black" width="100%">

        <tr>
            <td></td>
            <td>Tracking ID</td>
            <td style="border: 1px solid black">Headline</td>
            <td style="border: 1px solid black">Description</td>
            <td style="border: 1px solid black">Decision</td>
            <td style="border: 1px solid black">Remarks</td>
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
                <td style="border: 1px solid black">{{$count1}}</td>
                <td style="border: 1px solid black"colspan="5"><center>{{$details->name}}</center></td>
            </tr>


            @foreach($process_list_data as $specificRow)
                <?php $rowDataSplit = explode("AAAAA",$specificRow);


                if(count($process_list_data) >= 1 && $rowDataSplit[0] != null){

//                    dd($rowDataSplit);
                ?>
                <tr>
                    <td style="border: 1px solid black">{{$count1}}.{{$count2}}</td>
                    <td style="border: 1px solid black">{{$rowDataSplit[0]}}</td>
                    @if(isset($rowDataSplit[3]))
                        <?php

                        $objstr = json_decode($rowDataSplit[3],true);
                        ?>
                        <td style="border: 1px solid black">

                            {{$objstr["Headline"]}}

                        </td>
                        <td style="border: 1px solid black">
                            {{$objstr["Discussion"]}} {{$objstr["Remarks"]}}
                        </td>
                    @else
                        <td></td>
                        <td></td>
                    @endif
                    <td style="border: 1px solid black">{{$rowDataSplit[2]}}</td>
                    <td style="border: 1px solid black">{{$rowDataSplit[1]}}</td>
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
As there was no other agenda the meeting was ended exchanging thanks to each other.<br><br><br>
<?php $signature = "users/signature/".Auth::user()->signature;?>
<img style="width: 20%"  src="{{ $signature }}" class="signature-user-img img-responsive img-rounded user_signature"><br>
 {{Auth::user()->user_full_name}}<br>
 {{Auth::user()->designation}}
</div>