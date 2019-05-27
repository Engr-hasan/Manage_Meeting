@if(Auth::user()->is_approved != 1 or Auth::user()->is_approved !=true)
    <div class="col-sm-3">
        <img width="200" src="assets/images/alarm_clock_time_bell_wait-512.png">
    </div>
    <div class="col-sm-9">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title">Please see this instruction</h3>
            </div>
            <div class="panel-body">
                <strong class="text-danger">
                    Kindly contact to System Administrator or IT Help Desk officer to approve your account. <br/>
                    You will get all the available functionality once your account is approved!<br/><br/><br/>
                </strong>
                <strong>
                    Thank you!<br/>
                    OCPL BASE
                </strong>
            </div>
        </div>
    </div>
@endif