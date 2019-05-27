<?php namespace App\Modules\Dashboard\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Libraries\CommonFunction;
use App\Modules\BoardMeting\Models\BoardMeting;
use App\Modules\BoardMeting\Models\Committee;
use App\Modules\Dashboard\Models\Dashboard;
use App\Modules\Users\Models\UserLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use DB;

class DashboardController extends Controller {

    public function __construct() {
        if (Session::has('lang'))
            App::setLocale(Session::get('lang'));
    }

	public function index(Dashboard $dashboard)
	{
	    $desk_id=explode(",",Auth::user()->desk_id);
        $log=date('H:i:s', time());
        $dbMode = Session::get('DB_MODE');
        $log.=' - '. date('H:i:s', time());
        $log.=' - '. date('H:i:s', time());
        $notice = CommonFunction::getNotice(1);
        $dashboardObject = $dashboard->getDashboardObject();
//		$widgetsGroup = $dashboard->getWidget();

//		dd($widgetsGroup);

		$pageTitle = 'Dashboard';
        $services = DB::table('process_list')
            ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
            ->groupBy('process_type.id')
            ->select(array('process_type.name', 'process_type.id', 'process_type.form_url','process_type.panel', DB::raw('COUNT(process_list.process_type_id) as totalApplication')))
            ->orderBy('process_type.id', 'asc')
            ->where('process_type.status', '!=', -1)
            ->where('process_list.status_id', '!=', -1)
            ->where(function ($query1)  use($desk_id) {
                if (Auth::user()->user_type == '5x505') {
                    $query1->where('process_list.created_by', '=', CommonFunction::getUserId());

                } elseif (Auth::user()->user_type == '4x404') {
                    $query1->whereIn('process_list.desk_id', $desk_id);
                }
            })
            ->get();
        $user_type = Auth::user()->user_type;
        $deshboardObject = [];
        if ($user_type == '1x101' || 1) {
            $deshboardObject = DB::table('dashboard_object')->where('db_obj_status', 1)->get();
			$dashboardObjectBarChart = DB::table('dashboard_object')->where('db_obj_type', 'BAR_CHART')->get();
        }
        $last_login_time = UserLogs::JOIN('users', 'users.id', '=', 'user_logs.user_id')
            ->where('user_logs.user_id', '=', Auth::user()->id)
            ->orderBy('user_logs.id', 'desc')
            ->skip(1)->take(1)
            ->first(['user_logs.login_dt']);
        if($last_login_time !="")
         $last_login_time = date("d-M-Y h:i", strtotime($last_login_time->login_dt));
        Session::put('last_login_time', $last_login_time);
        Session::put('user_pic', Auth::user()->user_pic);
        $lastBoardMeetingInfo = BoardMeting::where('sequence_no', '>=', 4)->orderBy('id','DESC')->first();//5 = board meeting fixed
        if($lastBoardMeetingInfo !=''){
            $getChairperson  = Committee::where('board_meeting_id',$lastBoardMeetingInfo->id)->orderby('type','DESC')->get();
            $result = false;
            foreach ($getChairperson as $userEmail){
                if($userEmail->user_email == Auth::user()->user_email){
                    $result = true;
                }
            }
            if($result == false){
                $getChairperson ='';
            }
        }else{
            $getChairperson ='';
        }

//        dd($getChairperson);
//        $lastBoardMeetingInfo = BoardMeting::join('boared_meeting_committee as bmc', 'bmc.board_meeting_id', '=', 'board_meting.id')
//            ->leftjoin('users', 'bmc.user_email', '=', 'users.user_email')
//            ->where('sequence_no',4)
//            ->where('type','Yes')
//            ->orderBy('board_meting.id','DESC')
//            ->get(['users.user_email','bmc.user_name','bmc.designation']);
//            ->first();


        return view('Dashboard::index', compact('log', 'dbMode', 'widgetsGroup', 'notice', 'services', 'deshboardObject', 'dashboardObject','pageTitle', 'dashboardObjectBarChart','lastBoardMeetingInfo','getChairperson'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
