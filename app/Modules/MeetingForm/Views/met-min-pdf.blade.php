<!doctype html>
<body lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
</body>
{{--first section--}}
<div style="page-break-after:always;">
    <br><br><br><br><br><br>
    <div class="row">
        <div class="col-md-12" style="text-align: center;margin-bottom:10px;">
            <img src="assets/images/batworld-logo.png"/><br/>
            <p style="font-size:80px;">দলগত তথ্যের</p>
            <p style="font-size:80px;">প্রতিবেদন</p>
            <p style="font-size:80px;">মার্চ-এপ্রিল, ২০১৯</p>
        </div>
    </div>
</div>
{{--first section end--}}

@foreach($appData as $achievement)
    <div style="page-break-after:always;">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-6 pull-left">
                        <h3>টিমের নাম: {{ $achievement->team_name }}</h3>
                        <h3>টিম লিডারঃ {{ $achievement->team_leader_name }}</h3>
                        <h3>টিমের মোট সদস্য সংখ্যাঃ {{ $achievement->no_of_member }}</h3>
                        <h3>মহিলা সদস্যঃ {{ $achievement->women_member }}</h3>
{{--                        <h3>ইন্টান সদস্যঃ {{ $achievement->intern_member }}</h3>--}}
                    </div>
                    <div class="col-md-5" style="margin-left: 150px;">
                        <br><br>
                        <img src="assets/images/batworld-logo.png" alt="logo"/>
                    </div>
                </div>
            </div>
        </div>


        <h3 style="color:green;">বিগত মাসের উল্লেখযোগ্য কার্যক্রম-সংক্রান্ত তথ্যঃ</h3>
        <?php $notinfos = explode(',', $achievement->notable_information); ?>
        @foreach($notinfos as $notinfo)
            <p><img src="assets/images/list.PNG"/> {{ $notinfo }}</p>
        @endforeach

        <h3 style="color:green;">বিগত মাসে টিমের গঠনমূলক কার্যক্রমঃ</h3>
        <?php $infos = explode(',', $achievement->constructive_activities); ?>
        @foreach($infos as $info)
        <p><img src="assets/images/list.PNG"/>{{ $info }}</p>
        @endforeach


        <h3 style="color:green;">বিগত মাসের টিমের দক্ষতা বৃদ্ধি প্রসঙ্গঃ</h3>
        <?php $previousms = explode(',', $achievement->increasing_efficiency); ?>
        @foreach($previousms as $previousm)
            <p><img src="assets/images/list.PNG"/> {{ $previousm }}</p>
        @endforeach

        <h3 style="color:green;">বিগত মাসের নতুন সদস্য অন্তর্ভুক্তকরণ প্রসঙ্গঃ</h3>
        <?php $includes = explode(',', $achievement->members_include); ?>
        @foreach($includes as $include)
            <p><img src="assets/images/list.PNG"/> {{ $include }}</p>
        @endforeach

        <h3 style="color:green;">বিগত মাসে মানব সম্পদের যথাযথ ব্যবহারঃ</h3>
        <?php $resources = explode(',', $achievement->human_resources); ?>
        @foreach($resources as $resource)
            <p><img src="assets/images/list.PNG"/> {{ $resource }}</p>
        @endforeach

        <h3 style="color:green;">পরবর্তী মাসে পরিকল্পিত উদ্যোগসমূহঃ</h3>
        <?php $nxtmonths = explode(',', $achievement->next_month); ?>
        @foreach($nxtmonths as $nxtmonth)
            <p><img src="assets/images/list.PNG"/> {{ $nxtmonth }}</p>
        @endforeach


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
                    <p>hasan</p>
                </div>
                <div class="col-md-5">
                    <?php $is_notinfos = explode(',', $achievement->is_notable_information); ?>
                    @foreach($is_notinfos as $is_notinfo)
                        <?php
                        $is_old_not = explode('#', $is_notinfo);

                        if (isset($is_old_not[1])){
                            if ($is_old_not[1] == 1){
                                echo "<p><img src='assets/images/list.PNG'/>$is_old_not[0] </p>";
                            }
                        }
                        ?>
                    @endforeach

                    <?php $old_infos = explode(',', $achievement->is_old_constructive_activities); ?>
                    @foreach($old_infos as $infod)
                        <?php
                        $is_old_d = explode('#', $infod);

                        if (isset($is_old_d[1])){
                            if ($is_old_d[1] == 1){
                                echo "<p><img src='assets/images/list.PNG'/>$is_old_d[0] </p>";
                            }
                        }
                        ?>
                    @endforeach

                    <?php $is_previousms = explode(',', $achievement->is_increasing_efficiency); ?>
                    @foreach($is_previousms as $is_previousm)
                        <?php
                        $is_old_e = explode('#', $is_previousm);

                        if (isset($is_old_e[1])){
                            if ($is_old_e[1] == 1){
                                echo "<p><img src='assets/images/list.PNG'/>$is_old_e[0] </p>";
                            }
                        }
                        ?>
                    @endforeach

                    <?php $is_includes = explode(',', $achievement->is_members_include); ?>
                    @foreach($is_includes as $is_include)
                        <?php
                        $is_old_i = explode('#', $is_include);

                        if (isset($is_old_i[1])){
                            if ($is_old_i[1] == 1){
                                echo "<p><img src='assets/images/list.PNG'/>$is_old_i[0] </p>";
                            }
                        }
                        ?>
                    @endforeach

                    <?php $is_resources = explode(',', $achievement->is_old_human_resources); ?>
                    @foreach($is_resources as $is_resource)
                            <?php
                            $is_old_r = explode('#', $is_resource);

                            if (isset($is_old_r[1])){
                                if ($is_old_r[1] == 1){
                                    echo "<p><img src='assets/images/list.PNG'/>$is_old_r[0] </p>";
                                }
                            }
                            ?>
                    @endforeach

                    <?php $is_nxtmonths = explode(',', $achievement->is_next_month); ?>
                    @foreach($is_nxtmonths as $is_nxtmonth)
                            <?php
                            $is_old_n = explode('#', $is_nxtmonth);

                            if (isset($is_old_n[1])){
                                if ($is_old_n[1] == 1){
                                    echo "<p><img src='assets/images/list.PNG'/>$is_old_n[0] </p>";
                                }
                            }
                            ?>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endforeach
</body>
</html>