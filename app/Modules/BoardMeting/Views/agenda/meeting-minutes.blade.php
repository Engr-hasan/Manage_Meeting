<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
{{--first section--}}


{{--first section end--}}
{{--second section--}}
<?php $pageheader=1;?>

@foreach($data as $meetingkey=>$meetingdata)

    <div style="page-break-after:always;">
        @if($pageheader==1)
            <br><br><br><br><br><br>
            <div class="row" >
                <div class="col-md-12" style="text-align: center;margin-bottom:10px;">
                    <img src="assets/images/batworld-logo.png"/><br/>
                    <p style="font-size:80px;">দলগত তথ্যের</p>
                    <p style="font-size:80px;">প্রতিবেদন</p>
                    <?php
                    $date = $meetingdata->created_at;
                    $month = $date->format('m');
                    $year = $date->format('Y');
                    ?>
                    <p style="font-size:80px;">{{\App\Libraries\CommonFunction::getMonthCurrentPrevious($month)}},{{\App\Libraries\CommonFunction::convert2Bangla($year)}}</p>
                </div>
            </div>
        @endif


    </div>
    @if($pageheader==1)
    <?php $count = 1;?>
    <div style="page-break-after:always;margin-top: 250px;">
        <br><br>
        <h2 style="text-align: center;">Meeting Members</h2>
            @foreach($committeeInfo as $committeeName)
                <h2 style=" color: black;font-size: 20px;">{{$count}}. {{$committeeName->user_name}}, {{$committeeName->designation}}, {{$committeeName->organization}} {{ $committeeName->type =='Yes' ? ' (Chairperson)' : '' }}</h2>
                <?php $count++;?>
            @endforeach
        </div>

    </div>
    @endif

    <div style="page-break-after:always;">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-6 pull-left">
                        <h3>টিমের নাম: {{$meetingdata->team_name}}</h3>
                        <h3>টিম লিডারঃ {{$meetingdata->team_leader_name}}</h3>
                        <h3>টিমের মোট সদস্য সংখ্যাঃ {{$meetingdata->no_of_member}} </h3>
                        <h3>মহিলা সদস্যঃ {{$meetingdata->women_member}}</h3>
                        <h3>ইন্টার্ন সদস্যঃ {{$meetingdata->intern_member}}</h3>
                        <h3> মিটিং সিধান্তঃ {{$meetingdata->status_name}}</h3>
                    </div>
                    <div class="col-md-5" style="margin-left: 150px;">
                        <br><br>
                        <img src="assets/images/batworld-logo.png" alt="logo"/>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $notblinfo =\App\Libraries\CommonFunction::getNotableInfo($meetingdata->id);
        $activities=\App\Libraries\CommonFunction::getAcitvites($meetingdata->id);
        $resource =\App\Libraries\CommonFunction::getResource($meetingdata->id);
        $efficiency = \App\Libraries\CommonFunction::getEfficiency($meetingdata->id);
        $newmember = \App\Libraries\CommonFunction::getNewMember($meetingdata->id);
        $nextmonthpl =\App\Libraries\CommonFunction::getNextMOnthPlan($meetingdata->id);
        $previousmonthininitiative =\App\Libraries\CommonFunction::getPreviousInitiative($meetingdata->company_id,$meetingdata->process_created);
        $notablework =\App\Libraries\CommonFunction::getNotableWork($meetingdata->id);

        ?>
        <h3 style="color:green;">বিগত মাসের  উল্লেখযোগ্য কার্যক্রম-সংক্রান্ত তথ্যঃ</h3>
        @foreach($notblinfo as $key => $value)
            <p style="font-size:18px;text-align: justify;"><img src="assets/images/list.PNG"/> {{$value->description}}</p>
        @endforeach
        <h3 style="color:green;">বিগত মাসে টিমের গঠনমূলক কার্যক্রমঃ</h3>
        @foreach($activities as $key => $value)
            <p style="font-size:18px;text-align: justify;"><img src="assets/images/list.PNG"/> {{$value->description}}</p>
        @endforeach
        <h3 style="color:green;">বিগত মাসের টিমের দক্ষতা বৃদ্ধি প্রসঙ্গঃ</h3>
        @foreach($efficiency as $key => $value)
            <p style="font-size:18px;text-align: justify;"><img src="assets/images/list.PNG"/> {{$value->description}}</p>
        @endforeach
    </div>






    {{--second section end--}}

    {{--third section--}}
    <div style="page-break-after:always;">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-6 pull-left">
                        <h3>টিমের নাম: {{$meetingdata->team_name}}</h3>
                        <h3>টিম লিডারঃ {{$meetingdata->team_leader_name}}</h3>
                        <h3>টিমের মোট সদস্য সংখ্যাঃ {{$meetingdata->no_of_member}} </h3>
                        <h3>মহিলা সদস্যঃ {{$meetingdata->women_member}}</h3>
                        <h3>ইন্টার্ন সদস্যঃ {{$meetingdata->intern_member}}</h3>
                        <h3> মিটিং সিধান্তঃ {{$meetingdata->status_name}}</h3>
                    </div>
                    <div class="col-md-5" style="margin-left: 150px;">
                        <br><br>
                        <img src="assets/images/batworld-logo.png" alt="logo"/>
                    </div>
                </div>
            </div>
        </div>

        <h3 style="color:green;">বিগত মাসে মানব সম্পদের যথাযথ ব্যবহারঃ</h3>
        @foreach($resource as $key => $value)
            <p style="font-size:18px;text-align: justify;"><img src="assets/images/list.PNG"/> {{$value->description}}</p>
        @endforeach

        <h3 style="color:green;">বিগত মাসের নতুন সদস্য অন্তর্ভুক্তকরণঃ</h3>
        @foreach($newmember as $key => $value)
            <p style="font-size:18px;text-align: justify;"><img src="assets/images/list.PNG"/> {{$value->description}}</p>
        @endforeach

        <h3 style="color:green;">পরবর্তী মাসে পরিকল্পিত উদ্যোগসমূহঃ</h3>
        @foreach($nextmonthpl as $key => $value)
            <p style="font-size:18px;text-align: justify;"><img src="assets/images/list.PNG"/> {{$value->description}}</p>
        @endforeach

    </div>
    {{--third section end--}}

    {{--fourth section--}}
    <div > {{--style="page-break-after:always;"--}}
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-6 pull-left">
                        <h3>টিমের নাম: {{$meetingdata->team_name}}</h3>
                        <h3>টিম লিডারঃ {{$meetingdata->team_leader_name}}</h3>
                        <h3>টিমের মোট সদস্য সংখ্যাঃ {{$meetingdata->no_of_member}} </h3>
                        <h3>মহিলা সদস্যঃ {{$meetingdata->women_member}}</h3>
                        <h3>ইন্টার্ন সদস্যঃ {{$meetingdata->intern_member}}</h3>
                        <h3> মিটিং সিধান্তঃ {{$meetingdata->status_name}}</h3>
                    </div>
                    <div class="col-md-5" style="margin-left: 150px;">
                        <br><br>
                        <img src="assets/images/batworld-logo.png" alt="logo"/>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-6" style="border-right: 2px solid green">
                        <h4 style="color:green;" class="text-center">বিগত মাসের  পরিকল্পিত উদ্যোগসমূহ:</h4>
                    </div>
                    <div class="col-md-5">
                        <h4 style="color:green;" class="text-center">উল্লেখযোগ্য কাযক্রম:</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    @if(count($previousmonthininitiative)>0)
                        @foreach($previousmonthininitiative as $key => $value)
                            <p style="font-size:18px;text-align: justify;"><img src="assets/images/list.PNG"/> {{$value->description}}</p>
                        @endforeach
                    @else
                        <p CLASS="text-center">তথ্য  নেই </p>
                    @endif


                </div>
                <div class="col-md-5">
                    @foreach($notablework as $key => $value)

                        <p style="font-size:18px;text-align: justify;"><img src="assets/images/list.PNG"/> {{$value['description']}}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <?php $pageheader++;?>
    @endforeach
    {{--fourth section end--}}
    </body>
</html>
