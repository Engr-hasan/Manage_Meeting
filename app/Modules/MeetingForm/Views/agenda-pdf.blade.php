<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
{{--first section--}}
<div style="page-break-after:always;">
    <br><br><br><br><br><br>
    <div class="row">
        <div class="col-md-12" style="text-align: center;margin-bottom:10px;">
            <img src="assets/images/batworld-logo.png"/><br/>
            <p style="font-size:80px;">দলগত তথ্যের</p>
            <p style="font-size:80px;">প্রতিবেদন</p>
            <?php
            $date = $appInfo->created_at;
            $month = $date->format('m');
            $year = $date->format('Y');
            ?>
            <p style="font-size:80px;">{{\App\Libraries\CommonFunction::getMonthCurrentPrevious($month)}},{{\App\Libraries\CommonFunction::convert2Bangla($year)}}</p>
        </div>
    </div>
</div>

{{--first section end--}}
{{--second section--}}
       <div style="page-break-after:always;">
           <div class="panel panel-info">
               <div class="panel-heading">
                   <div class="row">
                       <div class="col-md-6 pull-left">
                           <h3>টিমের নাম: {{$appInfo->team_name}}</h3>
                           <h3>টিম লিডারঃ {{$appInfo->team_leader_name}}</h3>
                           <h3>টিমের মোট সদস্য সংখ্যাঃ {{$appInfo->no_of_member}} </h3>
                           <h3>মহিলা সদস্যঃ {{$appInfo->women_member}}</h3>
                           <h3>ইন্টার্ন সদস্যঃ {{$appInfo->intern_member}}</h3>
                       </div>
                       <div class="col-md-5" style="margin-left: 150px;">
                           <br><br>
                           <img src="assets/images/batworld-logo.png" alt="logo"/>
                       </div>
                   </div>
               </div>
           </div>
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
                    <h3>টিমের নাম: {{$appInfo->team_name}}</h3>
                    <h3>টিম লিডারঃ {{$appInfo->team_leader_name}}</h3>
                    <h3>টিমের মোট সদস্য সংখ্যাঃ {{$appInfo->no_of_member}} </h3>
                    <h3>মহিলা সদস্যঃ {{$appInfo->women_member}}</h3>
                    <h3>ইন্টার্ন সদস্যঃ {{$appInfo->intern_member}}</h3>
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
<div> {{--style="page-break-after:always;"--}}
    <div class="panel panel-info">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-6 pull-left">
                    <h3>টিমের নাম: {{$appInfo->team_name}}</h3>
                    <h3>টিম লিডারঃ {{$appInfo->team_leader_name}}</h3>
                    <h3>টিমের মোট সদস্য সংখ্যাঃ {{$appInfo->no_of_member}} </h3>
                    <h3>মহিলা সদস্যঃ {{$appInfo->women_member}}</h3>
                    <h3>ইন্টার্ন সদস্যঃ {{$appInfo->intern_member}}</h3>
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
                    <h4 style="color:green;" class="text-center">বিগত পরিকল্পিত উদ্যোগসমূহ:</h4>
                </div>
                <div class="col-md-5">
                    <h4 style="color:green;" class="text-center">উল্লেখযোগ্য কাযক্রম:</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">

            </div>
            <div class="col-md-5">
                @foreach($notblinfo as $key => $value)
                    <p style="font-size:18px;text-align: justify;"><img src="assets/images/list.PNG"/> {{$value->description}}</p>
                @endforeach
            </div>
        </div>
    </div>
</div>
{{--fourth section end--}}
</body>
</html>
