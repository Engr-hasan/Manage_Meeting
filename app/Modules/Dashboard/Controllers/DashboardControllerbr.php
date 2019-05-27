<?php namespace App\Modules\Dashboard\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Libraries\CommonFunction;
use App\Modules\Dashboard\Models\Dashboard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use DB;

class DashboardController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
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
        }
//        dd($deshboardObject);
		return view('Dashboard::index', compact('log', 'dbMode', 'widgetsGroup', 'notice', 'services', 'deshboardObject', 'dashboardObject','pageTitle'));
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
