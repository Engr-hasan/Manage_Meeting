<div class="text-center" style="color: black;font-size: 19px">
    <?php echo $meetingInfo->org_name;?>
</div>
<div class="text-center">
    {{$meetingInfo->org_address}}
</div>

<div class="container">
    <div class="row"><br></div>
    <div class="row"><br></div>
    <div class="col-md-12">

        <div class="col-md-7" style="float: left;width: 70%;color: white;">. </div>
        <div class="col-md-4" style="float: left;width: 20%">Date. {{date("d M Y", strtotime($meetingInfo->meting_date))}}  </div>
    </div>




    <div class="col-md-12"><br></div>
    <div class="col-md-12"><br></div>
    <div class="col-md-12">
        <div class="col-md-4 text-center" style="font-weight: bold">Meeting Notice</div>
    </div>
    <div class="row"><br></div>
    <div class="row"><br></div>
    <div class="col-md-12">
        <div class="col-md-12" style="text-align: justify;font-size: 16px;color: black">{{$meetingInfo->meting_subject}}</div>
    </div>
    <br>
    <div class="col-md-12" style=" height: 300px;">
        <div class="col-md-12" style="line-height: 1.3em;text-align: justify">
            {{$meetingInfo->notice_details}}
        </div>
    </div>
    <div class="col-md-12">

        <div class="col-md-7" style="float: left;width: 70%;text-align: justify">
            <br>
            <br>
            <br>
            <br>
            <?php $count = 1;?>
            @foreach($committeeInfo as $committeeName)

                <span style="color: black;font-size: 11px">{{$count}}. {{$committeeName->user_name}}, {{$committeeName->designation}}, {{$committeeName->organization}}</span><br>

                <?php $count++;?>
            @endforeach
        </div>


        <div class="col-md-4" style="float: left;width: 20%;"><?php $signature = "users/signature/".Auth::user()->signature;?>

            <img src="{{ $signature }}" class="signature-user-img img-responsive img-rounded user_signature"
            alt="User Signature" id="user_signature" width="200"/>
            <br><span style="font-size: 11px">{{Auth::user()->user_full_name}}</span>
            <br><span style="font-size: 11px">{{Auth::user()->designation}}</span>
        </div>

    </div>
</div>