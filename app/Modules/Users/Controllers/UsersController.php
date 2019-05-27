<?php namespace App\Modules\Users\Controllers;

use App\ActionInformation;
use App\Http\Controllers\Controller;

use App\Modules\Apps\Models\EmailQueue;
use App\Modules\Faq\Models\Faq;
use App\Modules\Files\Controllers\FilesController;
use App\Modules\ProcessPath\Models\Desk;
use App\Modules\ProcessPath\Models\UserDesk;
use App\Modules\projectClearance\Models\Agency;
use App\Modules\Users\Models\CompanyInfo;
use App\Modules\Users\Models\Countries;
use App\Modules\Users\Models\Delegation;
use App\Modules\Users\Models\EconomicZones;
use App\Modules\Users\Models\FailedLogin;
use App\Modules\Users\Models\Notification;
use App\Modules\Users\Models\ParkInfo;
use App\Modules\Users\Models\UserLogs;
use App\Modules\Users\Models\Users;
use App\Modules\Users\Models\UsersModel;
use App\Modules\Users\Models\UsersModelEditable;
use App\Modules\Users\Models\UserTypes;
use App\Modules\Users\Models\AreaInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\LoginController;
use App\Http\Requests\profileEditRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use yajra\Datatables\Datatables;
use Validator;

class UsersController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view("Users::index");
	}

	/*
	 * user's list for system admin
	 */
	public function lists()
	{
		if (!CommonFunction::isAdmin())
		{
			Session::flash('error', 'Permission Denied');
			return redirect('dashboard');
		}
		$logged_in_user_type = Auth::user()->user_type;
		$user = 'user';
		return view('Users::user_list', compact('logged_in_user_type','user'))
			->with('title', 'User List');
	}


	/*
     * user's details information by ajax request
     */
	public function getRowDetailsData(Users $user)
	{
		$mode = ACL::getAccsessRight('user', 'V');
		$userList = $user->getUserList();
		return Datatables::of($userList)
			->addColumn('action', function ($userList) use ($mode) {
				if ($mode) {
					if (Auth::user()->user_type == '1x101')
					{
                        $assign_desk_btn='';
                        $parkAssign='';
                        $company_associated='';
					    if($userList->user_type == '4x404') {
                            $assign_desk_btn = ' <a href="' . url('/users/assign-desk/' . Encryption::encodeId($userList->id)) .
                                '" class="btn btn-xs btn-info" ><i class="fa fa-check-circle"></i> Assign Desk</a>';

                            $parkAssign = ' <a href="' . url('/users/assign-park/' . Encryption::encodeId($userList->id)) .
                                '" class="btn btn-xs btn-warning" ><i class="fa fa-check-circle"></i> Assign Park</a>';
                            $company_associated = ' <a href="' . url('/users/company-associated/' . Encryption::encodeId($userList->id)) .
                                '" class="btn btn-xs btn-default" ><i class=" fa fa-group"></i> Company Assoc</a>';

                        }

					}
                    if (Auth::user()->user_type == '1x101')
                    {
                        $accessLog = ' <a href="' . url('/users/access-log/' . Encryption::encodeId($userList->id)) .
                            '" class="btn btn-xs btn-success" ><i class="fa fa-key"></i> Access Log</a>';

                    }
					if (Auth::user()->user_type == '1x101' && !empty($userList->login_token))
					{
						$force_log_out_btn = ' <a onclick="return confirm(\'Are you sure?\')" href="' . url('/users/force-logout/' . Encryption::encodeId($userList->id)) .
							'" class="btn btn-xs btn-danger" ><i class="fa fa-sign-out "></i> Force Log out</a>';
					}
					else
					{
						$force_log_out_btn = '';
					}
					return ' <a href="' . url('users/view/' . Encryption::encodeId($userList->id)) . '" class="btn btn-xs btn-primary open" ><i class="fa fa-folder-open-o"></i> Open</a>' . $force_log_out_btn.$accessLog.$company_associated;
				}
				else
				{
					return '';
				}
			})
			->editColumn('user_status', function ($userList) {
				if ($userList->user_status == 'inactive') {
					$activate = 'class="text-danger" ';
				} else {
					$activate = 'class="text-success" ';
				}
				return '<span ' . $activate . '><b>' . $userList->user_status . '</b></span>';
			})
			->removeColumn('id', 'is_sub_admin')
			->make(true);
	}


	public function assignDesk($id) {
        if (!ACL::getAccsessRight('user', 'A'))
            abort('400', 'You have no access right! This incidence will be reported. Contact with system admin for more information.');
		try {
			$user_id = Encryption::decodeId($id);
			$user_exist_desk = Users::where('id', $user_id)->first(['desk_id','user_email']);
			$select = array();
			if ($user_exist_desk != null) {
				$user_exist_desk_arr = explode(',', $user_exist_desk->desk_id);
				foreach ($user_exist_desk_arr as $user_desk) {
					$select[] = $user_desk;
				}
			}
//			$desk_list = Desk::where('status', 1)->lists('desk_name', 'id')->all();
			$desk_list = UserDesk::where('status', 1)->get(['desk_name', 'id']);
			$desk_status = ['0' => 'Inactive', '1' => 'Active'];
			$user_id = Encryption::encodeId($user_id);
			return view('Users::assign-desk', compact('desk_list', 'select', 'user_id', 'desk_status','user_exist_desk'));
		} catch (\Exception $e) {
			Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
			return Redirect::back()->withInput();
		}
	}
    public function assignDeskSave(Request $request) {
        if (!ACL::getAccsessRight('user', 'E'))
            abort('400', 'You have no access right! This incidence will be reported. Contact with system admin for more information.');
            try {

            $user_id = Encryption::decodeId($request->get('user_id'));
            $assign_desk=0;
            if($request->get('user_types') != null)
                $assign_desk = implode(',', $request->get('user_types'));

            DB::beginTransaction();
            $deskData = Users::FirstorNew(['id' => $user_id]);
            $deskData->desk_id = $assign_desk;
            $deskData->save();
            DB::commit();
            $loginController = new LoginController();
            $loginController::killUserSession($user_id);
            Session::flash('success', 'Successfully assigned desk.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Sorry, Something went wrong');
            return redirect()->back();
        }
    }
    public function assignPark($id) {
        if (!ACL::getAccsessRight('user', 'A'))
            abort('400', 'You have no access right! This incidence will be reported. Contact with system admin for more information.');
        try {
            $user_id = Encryption::decodeId($id);
            $user_exist_park = Users::where('id', $user_id)->first(['park_id','user_email']);
            $select = array();
            if ($user_exist_park != null) {
                $user_exist_park_arr = explode(',', $user_exist_park->park_id);
                foreach ($user_exist_park_arr as $user_park) {
                    $select[] = $user_park;
                }
            }
            $park_list = ParkInfo::where('status', 1)->where('is_archive',0)->get(['park_name', 'id']);
            $user_id = $id;
            return view('Users::assign-park', compact('park_list', 'select', 'user_id', 'desk_status','user_exist_park'));
        } catch (\Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }
    }
    public function assignParkSave(Request $request) {
        if (!ACL::getAccsessRight('user', 'E'))
            abort('400', 'You have no access right! This incidence will be reported. Contact with system admin for more information.');
        try {

            $user_id = Encryption::decodeId($request->get('user_id'));
            $assign_park=0;
            if($request->get('park_name') != null)
                $assign_park = implode(',', $request->get('park_name'));

            DB::beginTransaction();
            $deskData = Users::FirstorNew(['id' => $user_id]);
            $deskData->park_id = $assign_park;
            $deskData->save();
            DB::commit();
            $loginController = new LoginController();
            $loginController::killUserSession($user_id);
            Session::flash('success', 'Successfully assigned Park.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Sorry, Something went wrong');
            return redirect()->back();
        }
    }

    public function companyAssociated($id){
        if (!ACL::getAccsessRight('user', 'A'))
            abort('400', 'You have no access right! This incidence will be reported. Contact with system admin for more information.');
        try {
            $user_id = Encryption::decodeId($id);
            $user_exist_company = Users::where('id', $user_id)->first(['user_sub_type','user_email']);
            $select = array();
            if ($user_exist_company != null) {
                $user_exist_company_arr = explode(',', $user_exist_company->user_sub_type);
                foreach ($user_exist_company_arr as $company) {
                    $select[] = $company;
                }
            }

            $company_list = CompanyInfo::leftJoin('area_info as ai', 'ai.area_id', '=', 'company_info.division')
                ->leftJoin('area_info as di', 'di.area_id', '=', 'company_info.thana')
                ->select('id', DB::raw('CONCAT(company_name, ", ", ai.area_nm,", ", di.area_nm) AS company_info'))
                ->where('is_approved', 1)
                ->where('company_status',1)
                ->orderBy('company_name','ASC')->get(['company_info', 'id']);

            $desk_status = ['0' => 'Inactive', '1' => 'Active'];
            $user_id = Encryption::encodeId($user_id);
            return view('Users::company-associated', compact('company_list', 'select', 'user_id', 'desk_status','user_exist_company'));
        } catch (\Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }
    }
    public function CompanyAssociatedSave(request $request) {
        if (!ACL::getAccsessRight('user', 'E'))
            abort('400', 'You have no access right! This incidence will be reported. Contact with system admin for more information.');
        try {

            $user_id = Encryption::decodeId($request->get('user_id'));
            $company_associated=0;
            if($request->get('company_associated') != null)
                $company_associated = implode(',', $request->get('company_associated'));

            DB::beginTransaction();
            $companyData = Users::FirstorNew(['id' => $user_id]);
            $companyData->user_sub_type = $company_associated;
            $companyData->save();
            DB::commit();
//            $loginController = new LoginController();
//            $loginController::killUserSession($user_id);
            Session::flash('success', 'Successfully Company Associated.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Sorry, Something went wrong');
            return redirect()->back();
        }
    }

    public function companyAssociatedByUser(){
        if (!ACL::getAccsessRight('user', 'V'))
            abort('400', 'You have no access right! This incidence will be reported. Contact with system admin for more information.');
        try {
            $user_id = CommonFunction::getUserId();
            $user_exist_company = Users::where('id', $user_id)->first(['user_sub_type','user_email']);
            $select = array();
            if ($user_exist_company != null) {
                $user_exist_company_arr = explode(',', $user_exist_company->user_sub_type);
                foreach ($user_exist_company_arr as $company) {
                    $select[] = $company;
                }
            }

            $company_list = CompanyInfo::leftJoin('area_info as ai', 'ai.area_id', '=', 'company_info.division')
                ->leftJoin('area_info as di', 'di.area_id', '=', 'company_info.thana')
                ->select('id', DB::raw('CONCAT(company_name, ", ", ai.area_nm,", ", di.area_nm) AS company_info'))
                ->where('is_approved', 1)->where('created_by',$user_id)->where('company_status',1)->orderBy('company_name','ASC')->get(['company_info', 'id']);

            $divisions = ['' => 'Select Division '] + AreaInfo::orderby('area_nm')->where('area_type', 1)->lists('area_nm', 'area_id')->all();
            $user_id = Encryption::encodeId($user_id);
            return view('Users::company-info', compact('company_list', 'select', 'user_id', 'desk_status','user_exist_company','divisions','districts','thana'));
        } catch (\Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }
    }
    public function getDeligatedUserInfo(Request $request) {
//        if (!ACL::getAccsessRight('user', 'A'))
//            abort('400', 'You have no access right! This incidence will be reported. Contact with system admin for more information.');
        $userType = $request->get('designation');
        $result = Users::where('user_type', '=', $userType)
            ->Where('user_status', '=', 'active')
            ->Where(function($result) {
                return $result->where('delegate_to_user_id', '=', null)
                    ->orWhere('delegate_to_user_id', '=', 0);
            })
            ->Where('id', '!=', Auth::user()->id)
            ->get(['user_full_name', 'id']);
        echo json_encode($result);
    }
    function processDeligation(Request $request) {

        $delegate_by_user_id = Auth::user()->id;
        $delegate_to_user_id = $request->get('delegated_user');
        $dependend_on_from_userid = Users::where('delegate_to_user_id', '=', $delegate_by_user_id)->get(['id', 'delegate_to_user_id']);

        DB::beginTransaction();
        foreach ($dependend_on_from_userid as $dependentUser) {
            $updateDependent = Users::findOrFail($dependentUser->id);
            $updateDependent->delegate_to_user_id = $delegate_to_user_id;
            $updateDependent->delegate_by_user_id = $delegate_by_user_id;
            $updateDependent->save();

            $delegation = new Delegation();
            $delegation->delegate_form_user = $dependentUser->id;
            $delegation->delegate_by_user_id = $delegate_by_user_id;
            $delegation->delegate_to_user_id = $delegate_to_user_id;
            $delegation->remarks = $request->get('remarks');
            $delegation->status = 1;
            $delegation->save();
        }
        DB::commit();

        $UData = array(
            'delegate_to_user_id' => $delegate_to_user_id,
            'delegate_by_user_id' => $delegate_by_user_id,
        );

        $complete = Users::where('id', $delegate_by_user_id)
            ->orWhere('delegate_to_user_id', $delegate_by_user_id)
            ->update($UData);

        $type = Auth::user()->user_type;

        $user_type = explode('x', $type)[0];

        if ($user_type != 1 || $user_type != 2) {
            Session::put('sess_delegated_user_id', $delegate_by_user_id);
        }

        if ($complete) {
            Delegation::create([
                'delegate_form_user' => $delegate_by_user_id,
                'delegate_by_user_id' => $delegate_by_user_id,
                'delegate_to_user_id' => $delegate_to_user_id,
                'remarks' => $request->get('remarks'),
                'status' => 1,
            ]);
            return redirect()
                ->intended('/users/delegate')
                ->with('success', 'Delegation process completed Successfully');
        } else {
            Session::flash('error', 'Delegation Not completed');
            return redirect('users/profileinfo/#tab_3');
        }
    }
    public function delegate() {
        $delegate_to_user_id = Auth::user()->delegate_to_user_id;
        $info = Users::leftJoin('user_desk as ud', 'ud.id', '=', 'users.desk_id')
            ->where('users.id', $delegate_to_user_id)->first(['user_full_name', 'user_email', 'user_phone', 'ud.desk_name']);
        return view("Dashboard::delegated", compact('info'));
    }
//    public function removeDeligation($id) {
//        $sess_user_id = Encryption::decodeId($id);
////        dd($sess_user_id);
//        //USER INFO DELATION REMOVE
//        Users::where('id', $sess_user_id)
//            ->update(['delegate_to_user_id' => 0, 'delegate_by_user_id' => 0]);
//
//        Users::where('delegate_by_user_id', $sess_user_id)
//            ->where('id', '!=', $sess_user_id)
//            ->update(['delegate_to_user_id' => $sess_user_id, 'delegate_by_user_id' => $sess_user_id]);
//
//        //DELEGATION HISTORY UPDATE
//        $id = Delegation::where('delegate_by_user_id', $sess_user_id)
//            ->where('delegate_to_user_id', Auth::user()->delegate_to_user_id)
//            ->orderBy('created_at', 'desc')
//            ->limit(1)
//            ->update(['remarks' => '', 'status' => 0]);
//
//        //REMOVE DELEGATION HISTORY ENTRY
//        Delegation::where('delegate_by_user_id', $sess_user_id)
//            ->where('delegate_to_user_id', Auth::user()->delegate_to_user_id)
//            ->orderBy('created_at', 'DESC')->first();
//
//        Session::forget('sess_delegated_user_id');
////        return redirect('dashboard')->with('success','Remove Delegation Successfully');
//        return redirect()->back()->with('success','Remove Delegation Successfully');
//    }


    public function removeDeligation($DelegateId = '') {

        if($DelegateId == ''){
            $sess_user_id = Auth::user()->id;
        }else{
            $sess_user_id = Encryption::decodeId($DelegateId);
        }


        //USER INFO DELATION REMOVE
        Users::where('id', $sess_user_id)
            ->update(['delegate_to_user_id' => 0, 'delegate_by_user_id' => 0]);

        Users::where('delegate_by_user_id', $sess_user_id)
            ->where('id', '!=', $sess_user_id)
            ->update(['delegate_to_user_id' => $sess_user_id, 'delegate_by_user_id' => $sess_user_id]);

        //DELEGATION HISTORY UPDATE
        $id = Delegation::where('delegate_by_user_id', $sess_user_id)
            ->where('delegate_to_user_id', Auth::user()->delegate_to_user_id)
            ->orderBy('created_at', 'desc')
            ->limit(1)
            ->update(['remarks' => '', 'status' => 0]);

        //REMOVE DELEGATION HISTORY ENTRY
        Delegation::where('delegate_by_user_id', $sess_user_id)
            ->where('delegate_to_user_id', Auth::user()->delegate_to_user_id)
            ->orderBy('created_at', 'DESC')->first();

        Session::flash('success', 'Remove Delegation Successfully');
        Session::forget('sess_delegated_user_id');

        if($DelegateId == ''){
            return redirect("dashboard");
        }else{
            return redirect("users/delegations/" . Encryption::encodeId($sess_user_id));
        }

    }


	public function failedLoginHist(request $request,$email){
        if (!CommonFunction::isAdmin())
        {
            Session::flash('error', 'Permission Denied');
            return redirect('dashboard');
        }
        $logged_in_user_type = Auth::user()->user_type;
        $decodedUserEmail = Encryption::decodeId($email);
        $user = Users::where('user_email', $decodedUserEmail)->get(['user_full_name','user_phone']);
      return view('Users::failed-loginHistory', compact('logged_in_user_type','user','decodedUserEmail','email'));
    }

	public function accessLogHist($userId){
		$decodedUserId = Encryption::decodeId($userId);
        if (!CommonFunction::isAdmin())
        {
            Session::flash('error', 'Permission Denied');
            return redirect('dashboard');
        }
        $logged_in_user_type = Auth::user()->user_type;
		$user = Users::find($decodedUserId);
        $user_name = $user->user_full_name;
        $user_phone = $user->user_phone;
        $email = $user->user_email;
      return view('Users::access-log', compact('logged_in_user_type','user','userId','email','user_name','user_phone'));
    }

	public function getAccessLogData($userId) {
		$decodedUserId = Encryption::decodeId($userId);
		$user_logs = UserLogs::JOIN('users', 'users.id', '=', 'user_logs.user_id')
            ->where('user_logs.user_id', '=', $decodedUserId)
			->orderBy('user_logs.id', 'desc')
			->limit(10)
			->get(['users.designation','users.user_phone','users.user_full_name','user_logs.user_id', 'ip_address', 'login_dt', 'logout_dt', DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
		return Datatables::of($user_logs)
			->make(true);
	}
    public function getAccessLogDataForSelf() {
        $user_logs = UserLogs::where('user_id', '=', CommonFunction::getUserId())
            ->orderBy('user_logs.id', 'desc')
            ->get(['user_id', 'ip_address', 'login_dt', 'logout_dt', DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
        return Datatables::of($user_logs)
            ->make(true);
    }


    public function getAccessLogFailed() {
        $user_Failed = FailedLogin::where('user_email', '=', Auth::user()->user_email)
            ->get(['remote_address', 'created_at', DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
        return Datatables::of($user_Failed)
            ->make(true);
    }


    public function getLast50Action(){
        DB::statement(DB::raw('set @rownum=0'));
        $last50Action = ActionInformation::where('user_id', '=', Auth::user()->id)->orderBy('id','DESC')->take(50)
            ->get(['action_info.action','action_info.ip_address','action_info.created_at',DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
        return Datatables::of($last50Action)
            ->editColumn('rownum', function ($data) {
                return $data->rownum;
            })
            ->make(true);
    }


    public function getRowFailedData(request $request,Users $email)
    {
       $email= Encryption::decodeId($request->get('email'));
        $mode = ACL::getAccsessRight('user', 'V');
        $failed_login_history=DB::table('failed_login_history')->where('user_email',$email);
        //$userList = $user->getUserList();
        return Datatables::of($failed_login_history)
            ->addColumn('action', function ($failed_login_history) use ($mode) {
                if ($mode) {
                    return '<a  data-toggle="modal" data-target="#myModal" id="'.$failed_login_history->id.'" onclick="myFunction('.$failed_login_history->id.')" class="ss btn btn-xs btn-primary" ><i class="fa fa-retweet"></i> Resolved</a>';
                }
            })
            ->editColumn('remote_address', function ($failed_login_history) {
                return '' . $failed_login_history->remote_address . '</span>';
            })
            ->removeColumn('id', 'is_sub_admin')
            ->make(true);
    }
    public function FailedDataResolved(request $request){
        if (!ACL::getAccsessRight('user', 'E'))
            abort('400', 'You have no access right!. Contact with system admin for more information.');

        $date = date('Y-m-d h:i:s a', time());
        $failed_login_history=DB::table('failed_login_history')->where('id',$request->get('failed_login_id'))->first();
        DB::beginTransaction();
        DB::table('delete_login_history')->insert(
            [
                'remote_address' => $failed_login_history->remote_address,
                'user_email' => $failed_login_history->user_email,
                'deleted_by' => $logged_in_user_type = Auth::user()->id,
                'remarks' => $request->get('remarks'),
                'created_at' => $date,
                'updated_at' =>$date
            ]
        );
        DB::table('failed_login_history')->where('id', $request->get('failed_login_id'))->delete();
        DB::commit();
        return redirect()->back()->with('success','Successfully Resolved');
    }

	/*
     * view individual user from admin panel
     */
	public function view($id, Users $usersModel)
	{
        if (!ACL::getAccsessRight('user', 'V'))
            abort('400', 'You have no access right!. Contact with system admin for more information.');
		$user_id = Encryption::decodeId($id);
		$profile_pic = CommonFunction::getPicture('user', $user_id);
		$user = $usersModel->getUserRow($user_id);
        //desk name
        $desk_id = explode(',', $user->desk_id);
        $desk=UserDesk::whereIn('id',$desk_id)->get(['desk_name']);
       //park name
        $park_id = explode(',', $user->park_id);
        $park=ParkInfo::whereIn('id',$park_id)->get(['park_name']);

        $company_id = explode(',', $user->user_sub_type);

//        dd($company_id);
        $company_list = CompanyInfo::leftJoin('area_info as ai', 'ai.area_id', '=', 'company_info.division')
            ->leftJoin('area_info as di', 'di.area_id', '=', 'company_info.thana')
            ->select('company_info.id', DB::raw('CONCAT(company_name, ", ", ai.area_nm,", ", di.area_nm) AS company_info'))
            ->whereIn('company_info.id',$company_id)->orderBy('company_name','ASC')->get(['company_info']);

		//$userMoreInfo = ACL::getUserDetails($user->id, $user->user_type, $user->user_sub_type);
		$user_type_part = explode('x', $user->user_type);

        $delegateInfo = '';
        // get delegation info if user is delegated
        if($user->delegate_to_user_id != 0){
            $delegateInfo = UsersModel::leftJoin('user_desk as ud', 'ud.id', '=', 'users.desk_id')
                ->leftJoin('user_types as ut','ut.id','=','users.user_type')
                ->where('users.id', $user->delegate_to_user_id)
                ->first(['users.id', 'user_full_name', 'users.desk_id','ut.type_name',
                    'user_email', 'user_phone', 'users.user_type', 'ud.desk_name',
                    'designation','user_phone'
                ]);
        }

		$auth_file = '';
		if (count($user_type_part) > 1)
		{
			$user_types = UserTypes::where('id', 'LIKE', "$user_type_part[0]_" . substr($user_type_part[1], 0, 2) . "_")
				->where('id', 'NOT LIKE', "$user_type_part[0]_" . substr($user_type_part[1], 0, 2) . "0")
				->where('status', 'active')
				->orderBy('type_name')
				->lists('type_name', 'id');
            $delegationInfo='';
            if($user->delegate_to_user_id>0){
                $delegationInfo = Users::leftJoin('user_desk as ud', 'ud.id', '=', 'users.desk_id')
                    ->where('users.id', $user->delegate_to_user_id)
                    ->first(['users.id','user_full_name', 'user_email', 'user_phone', 'ud.desk_name']);
            }
			return view('Users::view-printable', compact("user", "user_types", "profile_pic", "userMoreInfo",'auth_file','desk','park','delegationInfo','delegateInfo','company_list'));
		}
		else
		{
			Session::flash('error', 'User Type not defined.');
			return redirect('users/lists');
		}
	}


	// for adding new users from Authentic Admin's end
	public function createNewUser() {
		if (!ACL::getAccsessRight('user', 'A')) {
			die('You have no access right! Please contact with system admin for more information');
		}
		$logged_user_type = Auth::user()->user_type;
		$user_type_part = explode('x', $logged_user_type);
		if ($logged_user_type == '1x101') { // 1x101 is Sys Admin
			$user_types = UserTypes::where('is_registarable', '!=', '-1')
				->whereNotIn('id',['5x505'])
                ->where('status','=','active')
				->lists('type_name', 'id');
		} else {
			$user_types = UserTypes::where('id', 'LIKE', "$user_type_part[0]x" . substr($user_type_part[1], 0, 2) . "_")
				->where('id', 'NOT LIKE', "$user_type_part[0]_" . substr($user_type_part[1], 0, 2) . "0")
                ->where('status','=','active')
				->orderBy('type_name')->lists('type_name', 'id');
		}

        $user_desk = UserDesk::orderBy('desk_name')->lists('desk_name', 'id');
//		$economicZone = EconomicZones::select('id', DB::raw('CONCAT(name, ", ", upazilla, ", ", district) AS zone'))
//			->orderBy('zone')->lists('zone', 'id');

        $company_list = CompanyInfo::where('is_approved', 1)->orderBy('company_name', 'ASC')->lists('company_name', 'id')->all();
		$nationalities = Countries::orderby('nationality')
			->where('nationality', '!=', '')
			->lists('nationality', 'iso');
		$countries = Countries::orderby('nicename')->lists('nicename', 'iso');
		$divisions = AreaInfo::orderby('area_nm')->where('area_type', 1)->lists('area_nm', 'area_id');
		$districts = AreaInfo::orderby('area_nm')->where('area_type', 2)->lists('area_nm', 'area_id');

		return view("Users::new-user", compact("user_types", "logged_user_type",
                    "user_desk","districts", "divisions", "countries", 'nationalities', "economicZone","company_list"));
	}

	public function verification($confirmationCode) {
		$user = Users::where('user_hash', $confirmationCode)->first();
		if (!$user) {
			\Session::flash('error', 'Invalid Token! Please resend email verification link.');
			return redirect('login');
		}
		$currentTime = new Carbon;
		$validateTime = new Carbon($user->created_at . '+6 hours');
		if ($currentTime >= $validateTime) {
			Session::flash('error', 'Verification link is expired (validity period 6 hrs). Please sign up again!');
			return redirect('/login');
		}

		$user_type = $user->user_type;
		$districts = ['' => 'Select one'] + AreaInfo::where('area_type', 2)->orderBy('area_nm', 'ASC')->lists('area_nm', 'area_id')->all();

		if ($user->user_verification != 'yes') {
			$districts = ['' => 'Select one'] + AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
			return view('Users::verification', compact('user_type', 'confirmationCode', 'districts'));
		} else {
			\Session::flash('error', 'Invalid Token! Please sign up again.');
			return redirect('/');
		}
	}

	//When completing registration, to get thana after selecting district
	public function getThanaByDistrictId(Request $request) {
		$district_id = $request->get('districtId');

		$thanas = AreaInfo::where('PARE_ID', $district_id)->orderBy('AREA_NM', 'ASC')->lists('AREA_NM', 'AREA_ID');
		$data = ['responseCode' => 1, 'data' => $thanas];
		return response()->json($data);
	}

	public function getDistrictByDivision(Request $request) {
		$division_id = $request->get('divisionId');

		$districts = AreaInfo::where('PARE_ID', $division_id)->orderBy('AREA_NM', 'ASC')->lists('AREA_NM', 'AREA_ID');
		$data = ['responseCode' => 1, 'data' => $districts];
		return response()->json($data);
	}

	/*
	 * individual User's profile Info view
	 */
	public function profileInfo()
	{
        if (!ACL::getAccsessRight('user', '-V-'))
            abort('400', 'You have no access right!. Contact with system admin for more information.');

        $desk='';
        $park='';
        $companyAssociated='';
		$users = Users::find(Auth::user()->id);
        if($users->park_id!='') {

            $park_id = explode(',', $users->park_id);
            $park=ParkInfo::whereIn('id',$park_id)->get(['park_name']);
        }
        if($users->desk_id!='') {
            $desk_id = explode(',', $users->desk_id);
            $desk=UserDesk::whereIn('id',$desk_id)->get(['desk_name']);
        }
        if($users->user_sub_type!='') {
            $company_id = explode(',', $users->user_sub_type);
            $companyAssociated = CompanyInfo::leftJoin('area_info as ai', 'ai.area_id', '=', 'company_info.division')
                ->leftJoin('area_info as di', 'di.area_id', '=', 'company_info.thana')
                ->select('company_info.id', DB::raw('CONCAT(company_name, ", ", ai.area_nm,", ", di.area_nm) AS company_info'))
                ->whereIn('company_info.id',$company_id)->orderBy('company_name','ASC')->get(['company_info']);

        }
        $userType=CommonFunction::getUserType();
        $designationUserType=UserTypes::where('status','active')->where('id',$userType)->pluck( 'delegate_to_types');
        $type_id=explode(",",$designationUserType);
        $delegate_to_types=UserTypes::whereIn('id',array_map('trim',$type_id))->lists('type_name','id');

        $profile_pic = CommonFunction::getPicture('user', Auth::user()->id);
		$user_type_info = UserTypes::where('id', $users->user_type)->first();
		$image_config = CommonFunction::getImageConfig('IMAGE_SIZE');
		$doc_config = CommonFunction::getImageConfig('DOC_IMAGE_SIZE');
		$auth_file = '';
		$id = Encryption::encodeId(Auth::user()->id);
		$districts = ['' => 'Select one'] + AreaInfo::where('area_type', 2)->orderBy('area_nm', 'ASC')->lists('area_nm', 'area_id')->all();
		return view('Users::profile-info', compact('id','users', 'user_type_info', 'profile_pic', 'districts', 'image_config', 'doc_config', 'auth_file','desk','delegate_to_types','park','companyAssociated'));
	}

	/*
	 * User's Profile info update function
	 * this method not found to route!!!
	 */
	public function profileUpdate(profileEditRequest $request) {
        if (!ACL::getAccsessRight('user', 'E'))
            abort('400', 'You have no access right!. Contact with system admin for more information.');

		$this->validate($request, [
			'user_full_name' => 'required',
			'user_DOB' => 'required',
			'user_phone' => 'required|bd_mobile',
		]);

		try {
			$auth_token_allow = 0;
			if ($request->get('auth_token_allow') == '1') {
				$auth_token_allow = 1;
			}

			if (substr($request->get('user_phone'), 0, 2) == '01') {
				$mobile_no = '+88' . $request->get('user_phone');
			} else {
				$mobile_no = $request->get('user_phone');
			}
			$type = explode('x', Auth::user()->user_type);

			$userData=UsersModelEditable::find(Auth::user()->id);
			$userData->user_full_name=$request->get('user_full_name');
			$userData->auth_token_allow=$auth_token_allow;
			$userData->user_DOB=Carbon::createFromFormat('d-M-Y', $request->get('user_DOB'))->format('Y-m-d');
			$userData->user_phone=$mobile_no;
			$userData->district = $request->get('district');
			$userData->thana = $request->get('thana');
			if($userData->user_status == "rejected"){
				$userData->user_status='inactive';
				$userData->user_sub_type=$request->get('agency_id');

			}
			$userData->save();

			\Session::flash('success', 'Your profile has been updated successfully.');
			return redirect('users/profileinfo');
		} catch (\Exception $e) {
			Session::flash('error', 'Sorry! Something is Wrong.');
			return Redirect::back()->withInput();
		}
	}

	/*
	 * user's account activaton
	 */
	public function activate($id) {
        if(!ACL::getAccsessRight('user','E')) die ('no access right!');
		$user_id = Encryption::decodeId($id);
		try {
			$user = Users::where('id', $user_id)->first();
			$user_active_status = $user->user_status;

			if ($user_active_status == 'active') {
				Users::where('id', $user_id)->update(['user_status' => 'inactive']);
				\Session::flash('error', "User's Profile has been deactivated Successfully!");
			} else {

				// Agency user can active a single user at a time.
				$user_type = explode('x', $user->user_type);
				if ($user_type[0] == '12') {
					$isGovt = Agency::where('id', $user->user_sub_type)
						->pluck('is_govt');
					if ($isGovt == 'Private') {
						$anotherUser = Users::where('id', '!=', $user->id)
							->where('user_status', 'active')
							->where('user_sub_type', $user->user_sub_type)
							->Where(function ($query) {
								$query->where('user_type', '5x505');
							})
							->first();
						if ($anotherUser) {
							\Session::flash('error', "Already have a user with : $anotherUser->user_email, Not more than one user can be activated at a time for a hajj agency.");
							return redirect()->back();
						}
					}
				}

				Users::where('id', $user_id)->update(['user_status' => 'active']);
				\Session::flash('success', "User's profile has been activated successfully!");

				$email = Users::where('id', $user_id)->pluck('user_email');

				$body_msg = "আপনার একাউন্টটি সফল ভাবে সক্রিয় করা হয়েছে । <br/><br/>ধন্যবাদ। <br/>";



				$params = array([
					'emailYes' => '1',
					'emailTemplate' => 'Users::message',
					'emailBody' => $body_msg,
					'emailSubject' => 'Board Meeting User Status',
					'emailHeader' => 'Forgotten Password Recovery',
					'emailAdd' => $email,
					'mobileNo' => '01767957180',
					'smsYes' => '0',
					'smsBody' => '',
				]);
				CommonFunction::sendMessageFromSystem($params);
			}
			LoginController::killUserSession($user_id);
			return redirect('users/lists/');
		} catch (\Exception $e) {
			Session::flash('error', 'Sorry! Something is Wrong.' . $e->getMessage());
			return Redirect::back()->withInput();
		}
	}

	/*
	 * User's password update function
	 */
	public function updatePassFromProfile(Request $request) {
	    $userId = Encryption::decodeId($request->get('Uid'));
        if (!ACL::getAccsessRight('user', 'SPU', $userId))
            abort('400', 'You have no access right!. Contact with system admin for more information.');
//
//        $this->validate($request, [
//			'user_old_password' => 'required',
//			'user_new_password' => [
//				'required',
//				'min:6',
//				'regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{6,}$/'
//			],
//			'user_confirm_password' => [
//				'required',
//				'same:user_new_password',
//			]
//		]);

        $dataRule = [
            'user_old_password' => 'required',
            'user_new_password' => [
                'required',
                'min:6',
                'regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{6,}$/'
            ],
            'user_confirm_password' => [
                'required',
                'same:user_new_password',
            ]
        ];

        $validator = Validator::make($request->all(), $dataRule);
        if ($validator->fails()) {
            return redirect('users/profileinfo#tab_2')->withErrors($validator)->withInput();
        }

		try {
			$old_password = $request->get('user_old_password');
			$new_password = $request->get('user_new_password');

			$password_match = Users::where('id', Auth::user()->id)->pluck('password');
			$password_chk = Hash::check($old_password, $password_match);

			if ($password_chk == true) {
				Users::where('id', Auth::user()->id)
					->update(array('password' => Hash::make($new_password)));

				Auth::logout();
				$loginObj = new LoginController();
				$loginObj->entryAccessLogout();

				\Session::flash('success', 'Your password has been changed successfully! Please login with the new password.');
				return redirect('login');
			} else {
				\Session::flash('error', 'Password do not match');
				return Redirect('users/profileinfo#tab_2')->with('status', 'error');
			}
		} catch (\Exception $e) {
			Session::flash('error', 'Sorry! Something is Wrong.');
			return Redirect::back()->withInput();
		}
	}

	/*
	 * password update from admin panel
	 */
	public function resetPassword($id) {
		if (!ACL::getAccsessRight('user', 'R'))
			die('no access right!');
		try {
			$user_id = Encryption::decodeId($id);
			$password = str_random(10);

			$user_active_status = DB::table('users')->where('id', $user_id)->pluck('user_status');
			$email_address = DB::table('users')->where('id', $user_id)->pluck('user_email');

			if ($user_active_status == 'active') {
				Users::where('id', $user_id)->update([
					'password' => Hash::make($password)
				]);

				$body_msg = '<span style="color:#000;text-align:justify;"><b>';
				$body_msg .= 'অভিনন্দন!</b><br/><br/>';
				$body_msg .= 'OSS Framework কর্তৃক আপনার পাসওয়ার্ড সফলভাবে পরিবর্তন করা হয়েছে।';
				$body_msg .= '<br/>নতুন পাসওয়ার্ড : <code>' . $password . '</code>';
				$body_msg .= '</span><br/><br/><br/>';
				$body_msg .= 'এটি সিস্টেম হতে সরাসরি প্রদত্ত, যা কেউ জানেন না। তবুও নিরাপত্তার স্বার্থে আপনি সিস্টেমে ঢোকার পর অবশ্যই তা পরিবর্তন করতে হবে।';
				$body_msg .= '<br/><br/><br/>ধন্যবাদান্তে,<br/>';
				$body_msg .= '<b></b>';

				$params = array([
					'emailYes' => '1',
					'emailTemplate' => 'Users::message',
					'emailBody' => $body_msg,
					'emailSubject' => 'Board Meeting Password Reset Information',
					'emailHeader' => 'Board Meeting Password Reset Information',
					'emailAdd' => $email_address,
					'mobileNo' => '01767957180',
					'smsYes' => '0',
					'smsBody' => '',
				]);
				CommonFunction::sendMessageFromSystem($params);

				\Session::flash('success', "User's password has been reset successfully! An email has been sent to the user!");
			} else {
				\Session::flash('error', "User profile has not been activated yet! Password can not be changed");
			}
			return redirect('users/lists');
		} catch (\Exception $e) {
			Session::flash('error', 'Sorry! Something is Wrong.' . $e->getMessage());
			return Redirect::back()->withInput();
		}
	}



	public function storeNewUser(Request $request) {
		if (!ACL::getAccsessRight('user', 'A')) {
			die('You have no access right! Please contact with system admin for more information');
		}
        try {
		$this->validate($request, [
			'user_full_name' => 'required',
			'user_DOB' => 'required|date',
			'user_phone' => 'required',
			'user_email' => 'required|email|unique:users',
			'country' => 'required',
			'nationality' => 'required',
			'road_no' => 'required'
		]);

		if ($request->get('country') == 'BD') { // 001 is country code of Bangladesh
			$this->validate($request, [
				'division' => 'required',
				'district' => 'required'
			]);
		} else {
			$this->validate($request, [
				'state' => 'required',
				'province' => 'required'
			]);
		}
            if ($request->get('identity_type') == 1) { // 1 =Passport no
                $this->validate($request, [
                    'passport_no' => 'required',
                ]);
            } else {
                $this->validate($request, [
                    'user_nid' => 'required',
                ]);
            }

		$token_no = hash('SHA256', "-" . $request->get('user_email') . "-");
		$encrypted_token = Encryption::encodeId($token_no);

		if (Auth::user()->user_type == '1x101') {     //System admin
            $desk_id='';
			//$desk_id = $request->get('desk_id');
			$user_type = $request->get('user_type');
		} else {
			$desk_id = Auth::user()->desk_id;
			$user_type = Auth::user()->user_type;
		}

        $user_sub_type = $request->get('bank_id');

        if (in_array($request->get('user_type'), ['12x431', '12x432'])) {
            $rules['company_id'] = 'required';
            $user_sub_type = $request->get('company_id');
        }

		$data = array(
			'user_full_name' => $request->get('user_full_name'),
			'user_DOB' => CommonFunction::changeDateFormat($request->get('user_DOB'), true),
			'user_phone' => $request->get('user_phone'),
			'user_email' => $request->get('user_email'),
			'user_hash' => $encrypted_token,
			'user_sub_type' => $user_sub_type,
			'user_type' => $user_type,
			//'eco_zone_id' => $request->get('eco_zone_id'),
			'desk_id' => $desk_id,
			'country' => $request->get('country'),
			'nationality' => $request->get('nationality'),
            'identity_type' => $request->get('identity_type'),
			'passport_no' => $request->get('passport_no'),
			'user_nid' => $request->get('user_nid'),
			'division' => $request->get('division'),
			'district' => $request->get('district'),
			'state' => $request->get('state'),
			'province' => $request->get('province'),
			'road_no' => $request->get('road_no'),
			'house_no' => $request->get('house_no'),
			'post_code' => $request->get('post_code'),
			'user_status' => 'active',
			'is_approved' => 1,
			'user_agreement' => 0,
			'first_login' => 0,
			'user_verification' => 'no',
			'user_hash_expire_time' => new Carbon('+6 hours')
		);
		Users::create($data);

		$email = $request->get('user_email');
		$user_phone = $request->get('user_phone');
		$verify_link = 'users/verify-created-user/' . ($encrypted_token);

		$body_msg = "Thanks you for requesting to open an account in our system.<br/>
                              Click the following link to confirm your e-mail account.
                            <br/> <a href='" . url($verify_link) . "'>Verify the e-mail address you have provided earlier</a>";

		$params = array([
			'emailYes' => '1',
			'emailTemplate' => 'Users::message',
			'emailBody' => $body_msg,
			'emailSubject' => 'Board Meeting Verify your email address',
			'emailHeader' => 'Verify your email address',
			'emailAdd' => $email,
			'mobileNo' => $user_phone,
			'smsYes' => '0',
			'smsBody' => '',
		]);
		CommonFunction::sendMessageFromSystem($params);

		Session::flash('success', 'User has been created successfully! An email has been sent to the user for account activation.');
//		dd(Session::get("success"));
		return redirect('users/create-new-user');

        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something is Wrong.' . $e->getMessage());
            return Redirect::back()->withInput();
        }
	}

	// Verifying new users created by admin
	public function verifyCreatedUser($encrypted_token) {
		$user = Users::where('user_hash', $encrypted_token)->first();
		if (!$user) {
			Session::flash('error', 'Invalid Token. Please try again...');
			return redirect('login');
		}
		$currentTime = new Carbon;

//		if ($currentTime >= $user->user_hash_expire_time) {
//			Session::flash('error', 'Verifying link is expired (Validity period is 1 hours). Please sign-up again to continue.');
//			return redirect('\login');
//		}

		if ($user->user_verification == 'no') {
			return view('Users::verify-created-user', compact('encrypted_token'));
		} else {
			Session::flash('error', 'Invalid Token! Please sign-up again to continue');
			return redirect('/');
		}
	}
	function createdUserVerification($encrypted_token, Request $request, Users $usersmodel) {
		$user = Users::where('user_hash', $encrypted_token)->first();
		$email = $user->user_email;
		$user_password = str_random(10);

		if (!$user) {
			Session::flash('error', 'Invalid token! Please sign up again to complete the process');
			return redirect('create');
		}

		$this->validate($request, [
			'user_agreement' => 'required',
		]);

		$data = array(
			'user_agreement' => $request->get('user_agreement'),
			'password' => Hash::make($user_password),
			'user_verification' => 'yes',
			'user_first_login' => Carbon::now()
		);

		$usersmodel->chekced_verified($encrypted_token, $data);

		$body_msg = "Your password is :<strong><code>" . $user_password . '</code></strong>';
		$body_msg .= "<br/>This is a sectret password generated by the system.
                                        But to ensure your own security and convenience, you should change the password after logging in.
                                        <br/><br/><br/>Thanks, <br/> OSS Framework";


		$params = array([
			'emailYes' => '1',
			'emailTemplate' => 'Users::message',
			'emailBody' => $body_msg,
			'emailSubject' => 'Board Meeting registration details',
			'emailHeader' => 'Board Meeting Account Access Information',
			'emailAdd' => $email,
			'mobileNo' => '000',
			'smsYes' => '0',
			'smsBody' => '',
		]);
		CommonFunction::sendMessageFromSystem($params);

		Session::flash('success', 'An account activation message has been sent to your email address. Please check your email');
		return redirect('login');
	}


	/*
	 * edit individual user from admin panel
	 */
	public function edit($id) {
		$user_id = Encryption::decodeId($id);
//        ACL must be modified for IT admin edit permission
        if (!ACL::getAccsessRight('user', 'E', $user_id))
            die('no access right!');
		$users = Users::where('id', $user_id)->first();

		list($user_type) = explode('x',$users->user_type);
		$districts = ['' => 'Select one'] + AreaInfo::where('area_type', 2)->orderBy('area_nm', 'ASC')->lists('area_nm', 'area_id')->all();
		$logged_in_user_type = CommonFunction::getUserType();
		if ($user_type == '11') {
			$bank_name = Bank::where('id', $users->user_sub_type)->pluck('name');
		} else {
			$bank_name = '';
		}
		$user_type_part = explode('x', $logged_in_user_type);
		$edit_user_type = UserTypes::where('id', $users->user_type)->pluck('type_name');

		$IT_users = array('2x201', '2x202', '2x203', '2x205');
		if ($logged_in_user_type == '2x201'){ // 2x201 for IT admin
			if (in_array($users->user_type, $IT_users)){
				$user_types = [$users->user_type => $edit_user_type] + UserTypes::where('id', 'LIKE', "$user_type_part[0]x" . substr($user_type_part[1], 0, 2) . "_")
						->orderBy('type_name')->lists('type_name', 'id')
						->all();
			}
			else{
				$user_types = [$users->user_type => $edit_user_type];
			}
		}
		else{
			$user_types = [$users->user_type => $edit_user_type] + UserTypes::where('id', 'LIKE', "$user_type_part[0]x" . substr($user_type_part[1], 0, 2) . "_")
					->where('id', 'NOT LIKE', "$user_type_part[0]_" . substr($user_type_part[1], 0, 2) . "0")
					->where('id', '!=', '1X101')
					->orderBy('type_name')->lists('type_name', 'id')
					->all();
		}
		$branch_list = array();
		if($user_type=='11'){
			$branch_list = BankBranch::where('bank_id',$users->user_sub_type)->orderBy('name', 'ASC')->lists('name','id')->all();
		}
		return view('Users::edit', compact("users", "user_types", 'logged_in_user_type', 'districts', 'bank_name','branch_list'));
	}

	public function update($id, UserUpdateRequest $request) {
		$user_id = Encryption::decodeId($id);
//          ACL must be modified for IT admin update permission
        if (!ACL::getAccsessRight('user', 'E', $user_id))
            abort('400', 'You have no access right!. Contact with system admin for more information.');
		try {
			CommonFunction::createAuditLog('User.edit', $request);
			$mobile_no_validate = CommonFunction::validateMobileNumber($request->get('user_phone'));
			if ($mobile_no_validate != 'ok') {
				\Session::flash('error', $mobile_no_validate);
				return redirect('users/edit/' . $id)->withInput();
			}
			if (substr($request->get('user_phone'), 0, 2) == '01') {
				$mobile_no = '+88' . $request->get('user_phone');
			} else {
				$mobile_no = $request->get('user_phone');
			}

			UsersModelEditable::find($user_id)->update([
				'user_full_name' => $request->get('user_full_name'),
				'designation' => $request->get('designation'),
				'user_nid' => $request->get('user_nid'),
				'passport_no' => $request->get('passport_no'),
				'house_no' => $request->get('house_no'),
				'road_no' => $request->get('road_no'),
				'post_code' => $request->get('post_code'),
				'user_phone' => $mobile_no,
				'district' => $request->get('district'),
				'thana' => $request->get('thana'),
				'state' => $request->get('state'),
				'province' => $request->get('province'),
				'updated_by' => CommonFunction::getUserId(),
			]);

			$user_data = Users::where('id', $user_id)->first();

			list($user_type) = explode('x', $user_data->user_type);

			if ($user_type == '7') {
				$request->get('district');
				UsersModelEditable::find($user_id)->update([
					'user_sub_type' => $request->get('district'),
					'district' => $request->get('district')
				]);
			} elseif ($user_type == '11') {
				UsersModelEditable::find($user_id)->update([
					'bank_branch_id' => $request->get('bank_branch_id')
				]);
			}
			\Session::flash('success', "User's profile has been updated successfully!");
			return redirect('users/edit/' . $id);
		} catch (\Exception $e) {
			Session::flash('error', 'Sorry! Something is Wrong.');
			return Redirect::back()->withInput();
		}
	}
	public function companyInfoSave(Request $request){
        if (!ACL::getAccsessRight('user', 'E')) {
            abort('400', 'You have no access right!. Contact with system admin for more information.');
        }
        $this->validate($request, [
            'company_name' => 'required|regex:/^[a-zA-Z\'\. \&]+$/',
            'division' => 'required',
            'district' => 'required',
            'thana' => 'required',
        ]);
        try {

        $companyData = new CompanyInfo();
        $companyData->company_name = $request->get('company_name');
        $companyData->division = $request->get('division');
        $companyData->district = $request->get('district');
        $companyData->thana = $request->get('thana');
        $companyData->save();

        \Session::flash('success', "Company information save successfully!");
            return Redirect::back();

        } catch (\Exception $e) {
//			dd($e->getMessage());
            Session::flash('error', 'Sorry! Something is Wrong.');
            return Redirect::back()->withInput();
        }
    }

	/*
	 * function for approve a user
	 */
	public function approveUser($id, Request $request) {
//        ACL must be checked for user approved case of IT admin
		if (!ACL::getAccsessRight('user', 'A')) {
			die('no access right!');
		}
		try {
			$user_id = Encryption::decodeId($id);
			$user = Users::find($user_id);
            // if user don't verify his email then sysadmin cann't approve this user
            if(($user->user_agreement == 0) || ($user->user_verification == 'no')){
                Session::flash('error', "Sorry ! This user has not verified his email yet");
                return redirect('users/lists');
            }

            if (in_array($user->user_type, ['5x505', '12x431'])) {
                $alreadyApprovedUsersForCompany = Users::where(function($query) {
                    $query->where('is_approved', 1);
                    $query->where('user_status', 'active');
                })
                    ->where('user_sub_type', $user->user_sub_type)
                    ->where('id', '!=', $user->id)
                    ->where('user_type', $user->user_type)
                    ->count();
                if ($alreadyApprovedUsersForCompany > 0) {
                    Session::flash('error', "Multiple user approve not allow for a company [UC1270]");
                    return redirect('users/lists');
                }
                // company approved when user is approved
                $company_info = CompanyInfo::find($user->user_sub_type);
                $company_info->is_approved = 1;
                $company_info->save();
            }

			if (!(Auth::user()->user_type == '1x101' || Auth::user()->user_type == '10x411' || Auth::user()->user_type == '12x431' || Auth::user()->user_type == '11x422')) {
				Session::flash('error', "Your have no right to approve it.");
				return redirect('users/lists');
			}

			// Agency user can active a single user at a time.
			$user_type = explode('x', $user->user_type);
			if ($user_type[0] == '12') {

					$anotherUser = Users::where('id', '!=', $user->id)
						->where('user_status', 'active')
						->where('user_sub_type', $user->user_sub_type)
						->Where(function ($query) {
							$query->where('user_type', '5x505');
								//->orwhere('user_type', '12x432');
						})
						->first();


			}
			$user->user_status = 'active';
			$user->is_approved = 1;
			if ($request->get('user_type')) {
				$user->user_type = $request->get('user_type');
			}

			if ($user->user_type == "10x412") { //for UNO
				$user->user_sub_type = $user->thana;
			}
			$user->save();
			\Session::flash('success', "The user has been approved successfully!");

			$email = $user->user_email;

			$body_msg = "আপনার অ্যাকাউন্টটি সফলভাবে অনুমোদিত হয়েছে।<br/> অনুগ্রহ করে ব্যবহারের শর্তাবলী যথাযথভাবে অনুসরণ করুন।
                                <br/><br/>ধন্যবাদ। <br/>";

			$params = array([
				'emailYes' => '1',
				'emailTemplate' => 'Users::message',
				'emailBody' => $body_msg,
				'emailSubject' => 'Board Meeting Account Verification',
				'emailHeader' => 'Board Meeting Account Verification',
				'emailAdd' => $email,
				'mobileNo' => '01767957180',
				'smsYes' => '0',
				'smsBody' => '',
			]);
			CommonFunction::sendMessageFromSystem($params);
			return redirect('users/lists');
		} catch (\Exception $e) {
//			dd($e->getMessage(), $e->getLine());
			Session::flash('error', 'Sorry! Something is Wrong.');
			return Redirect::back()->withInput();
		}
	}

	/*
	 * function for reject a user
	 */
	public function rejectUser($id, Request $request) {
		if (!ACL::getAccsessRight('user', 'E'))
			die('no access right!');

		try {
			$user_id = Encryption::decodeId($id);
			$reject_reason=$request->get('reject_reason');

			if (!(Auth::user()->user_type == '1x101' )) {
				Session::flash('error', "Your have no right to approve it.");
				return redirect('users/lists');
			}
			Users::where('id', $user_id)->update([
				'user_status' => 'rejected',
				'user_status_comment' =>$reject_reason,
				'is_approved' => 0
			]);
			\Session::flash('error', "User's Profile has been Rejected Successfully!");

			$email = Users::where('id', $user_id)->pluck('user_email');
			$body_msg = $reject_reason. " কারণবশতঃ আপনার অ্যাকাউন্টটি বাতিল করা হয়েছে। <br/><br/>ধন্যবাদ। <br/>";



			$params = array([
				'emailYes' => '1',
				'emailTemplate' => 'Users::message',
				'emailBody' => $body_msg,
				'emailSubject' => 'Board Meeting Account Verification',
				'emailHeader' => 'Board Meeting Account Verification',
				'emailAdd' => $email,
				'mobileNo' => '01767957180',
				'smsYes' => '0',
				'smsBody' => '',
			]);
			CommonFunction::sendMessageFromSystem($params);

			return redirect('users/lists');
		} catch (\Exception $e) {
			dd($e->getMessage().'-'.$e->getLine());
			Session::flash('error', 'Sorry! Something went wrong.');
			return Redirect::back();
		}
	}


	//When completing registration, to get thana after selecting district
	public function get_thana_by_district_id(Request $request) {
		$district_id = $request->get('districtId');

		$thanas = AreaInfo::where('PARE_ID', $district_id)->orderBy('AREA_NM', 'ASC')->lists('AREA_NM', 'AREA_ID');
		$data = ['responseCode' => 1, 'data' => $thanas];
		return response()->json($data);
	}

	public function profile_update(profileEditRequest $request, UsersModel $usersmodel, FilesController $files) {
        $userId = Encryption::decodeId($request->get('Uid'));
        if (!ACL::getAccsessRight('user', 'SPU', $userId))
            abort('400', 'You have no access right!. Contact with system admin for more information.');
		$rules = [
			'user_full_name' => 'required',
			'designation' => 'required',
			'user_DOB' => 'required',
			'user_phone' => 'required',
			'district' => 'required',
			'thana' => 'required',
		];

		if ($request->hasFile('profile_image'))
			$rules['profile_image'] = 'required|mimes:jpeg,png,jpg|image300x300|max:100';
//
		if ($request->hasFile('signature'))
			$rules['signature'] = 'required|mimes:jpeg,png,jpg|image300x80|max:100';



		$this->validate($request, $rules);

		$auth_token_allow = 0;
//		dd($request->all());
		if ($request->get('auth_token_allow') == '1') {
			$auth_token_allow = 1;
		}
		$mobile_no_validate = CommonFunction::validateMobileNumber($request->get('user_phone'));
		if ($mobile_no_validate != 'ok') {
			\Session::flash('error', $mobile_no_validate);
			return redirect('users/profileinfo')->withInput();
		}
		//$session_user_id = Encryption::decodeId($id);

		if (substr($request->get('user_phone'), 0, 2) == '01') {
			$mobile_no = '+88' . $request->get('user_phone');
		} else {
			$mobile_no = $request->get('user_phone');
		}

		$data = [
			'user_full_name' => $request->get('user_full_name'),
			'auth_token_allow' => $auth_token_allow,
			'user_DOB' => Carbon::createFromFormat('d-M-Y', $request->get('user_DOB'))->format('Y-m-d'),
			'user_phone' => $mobile_no,
			'passport_no'=> $request->get('passport_no'),
			'user_nid'=> $request->get('user_nid'),
			'road_no'=> $request->get('road_no'),
			'house_no'=> $request->get('house_no'),
			'district' => $request->get('district'),
			'thana' => $request->get('thana'),
			'designation' => $request->get('designation'),
			'updated_by' => CommonFunction::getUserId()
		];
        $prefix = date('Y_');
		$_file = $request->file('authorization_file');
		$_ifile = $request->file('signature');
		$_imfile = $request->file('profile_image');

		if ($request->hasFile('authorization_file')) {
			$original_file = $_file->getClientOriginalName();
			$file_type = $_file->getClientMimeType();

//
			$_file->move('uploads', $original_file);
			$data['authorization_file'] = $original_file;
		}
        if ($request->hasFile('signature')) {

            $s_file = trim(sprintf("%s", uniqid($prefix, true))) . $_ifile->getClientOriginalName();

            $mime_type = $_ifile->getClientMimeType();
            if ($mime_type == 'image/jpeg' || $mime_type == 'image/jpg' || $mime_type == 'image/png') {
                $_ifile->move('users/signature', $s_file);
                $data['signature'] = $s_file;
            } else {
                \Session::flash('error', 'Signature image type must be png or jpg or jpeg format');
                return redirect('users/profileinfo');
            }
        }

        if ($request->hasFile('profile_image')) {
            $img_file = trim(sprintf("%s", uniqid($prefix, true))) . $_imfile->getClientOriginalName();
            $mime_type = $_imfile->getClientMimeType();
            if ($mime_type == 'image/jpeg' || $mime_type == 'image/jpg' || $mime_type == 'image/png') {
                $_imfile->move('users/upload', $img_file);
                $data['user_pic'] = $img_file;
            } else {
                \Session::flash('error', 'Profile Picture type must be png or jpg or jpeg format');
                return redirect('users/profileinfo');
            }
        }

		UsersModelEditable::find($userId)->update($data);
		UsersModel::find($userId)->first();

		\Session::flash('success', 'Your profile has been updated successfully.');
		return redirect('users/profileinfo');
	}


	/*
	 * forcefully logout a user by admin
	 */
	
	public function forceLogout($user_id) {
        if (!ACL::getAccsessRight('user', 'E'))
            abort('400', 'You have no access right!. Contact with system admin for more information.');

        $id = Encryption::decodeId($user_id);
		$loginController = new LoginController();
		$loginController::killUserSession($id);
		Session::flash('success', "User has been successfully logged out by force!");
		return redirect('users/lists');
	}

	/*
	 * forget-password
	 */
    public function forgetPassword() {
        return view('Users::forget-password');
    }
	//For Forget Password functionality
	public function resetForgottenPass(Request $request) {


        $this->validate($request, [
			'g-recaptcha-response' => 'required',
		]);
		$email = $request->get('user_email');
		$users = DB::table('users')
			->where('user_email', $email)
			->first();



		if (!empty($users)) {

            if ($users->user_status == 'inactive' && $users->user_verification == 'no'){
                \Session::flash('error', 'No user with this email is existed in our current database. Please sign-up first');
                return Redirect('forget-password')->with('status', 'error');
            }
            if ($users->social_login == 1){
                \Session::flash('error', 'This option is not allowed for the user who has signed-up from Google or Facebook!');
                return Redirect('forget-password')->with('status', 'error');
            }


			$token_no = hash('SHA256', "-" . $email . "-");
			$update_token_in_db = array(
				'user_hash' => $token_no,
			);
			DB::table('users')
				->where('user_email', $email)
				->update($update_token_in_db);

			$encrytped_token = Encryption::encode($token_no);
			$verify_link = 'users/verify-forgotten-pass/' . ($encrytped_token);

			$body_msg = "Your password has been successfully reset. <br/> <a href='" . url($verify_link) . "'>Please click to the link to get the new password.</a>";
			$body_msg .= "<br/><br/>Thank you,<br/> OSS Framework</Project> <br/>";

			$params = array([
				'emailYes' => '1',
				'emailTemplate' => 'Users::message',
				'emailBody' => $body_msg,
				'emailSubject' => 'Board Meeting password reset',
				'emailHeader' => 'Board Meeting Forgotten Password Recovery',
				'emailAdd' => $email,
				'mobileNo' => '01767957180',
				'smsYes' => '0',
				'smsBody' => '',
			]);
			CommonFunction::sendMessageFromSystem($params);

			\Session::flash('success', 'Please check your email to verify Password Change');
			return redirect('login');
		} else {
			\Session::flash('error', 'No user with this email is existed in our current database. Please sign-up first');
			return Redirect('forget-password')->with('status', 'error');
		}
	}

	// Forgotten Password reset after verification
	function verifyForgottenPass($token_no) {
		$TOKEN_NO = Encryption::decode($token_no);

		$user = UsersModel::where('user_hash', $TOKEN_NO)->first();

		if ($user) {
			$fetched_email_address = $user->user_email;

			$user_password = str_random(10);

			DB::table('users')
				->where('user_hash', $TOKEN_NO)
				->update(array('password' => Hash::make($user_password)));

			$body_msg = "Your new password :<strong><code>" . $user_password . '</code></strong>';
			$body_msg .= "<br/>Please change the password after your first login for your safety.
                            <br/>This is a system generated email, do not reply.
                                            <br/><br/>Thanks, <br/> OSS Framework";

			$params = array([
				'emailYes' => '1',
				'emailTemplate' => 'Users::message',
				'emailBody' => $body_msg,
				'emailSubject' => 'Board Meeting New Password Details',
				'emailHeader' => 'Board Meeting Forgotten Password Recovery',
				'emailAdd' => $fetched_email_address,
				'mobileNo' => '01856228493',
				'smsYes' => '0',
				'smsBody' => '',
			]);
			CommonFunction::sendMessageFromSystem($params);

			\Session::flash('success', 'Your password has been reset successfully! Please check your mail for access information.');
			return redirect('login');
		} else { /* If User couldn't be found */
			\Session::flash('error', 'Invalid token! No such user is found. Please sign up first.');
			return redirect('signup');
		}
	}
	/*
	 * user support
	 */
    public function support() {
        $faqs = Faq::leftJoin('faq_multitypes', 'faq.id', '=', 'faq_multitypes.faq_id')
            ->leftJoin('faq_types', 'faq_multitypes.faq_type_id', '=', 'faq_types.id')
            ->where('status', 'public')
            ->where('faq_types.name', 'login')
            ->get(['question', 'answer', 'status', 'faq_type_id as types', 'name as faq_type_name', 'faq.id as id']);

        return view("Users::support", compact('faqs'));
    }

	public function getUserSession(Request $request) {
		if (Auth::user()) {
			$checkSession = UsersModel::where(['id' => Auth::user()->id, 'login_token' => Encryption::encode(Session::getId())])->count();
			if ($checkSession >= 1) {
				$data = ['responseCode' => 1, 'data' => 'matched'];
			} else {
				Auth::logout();
				$data = ['responseCode' => -1, 'data' => 'not matched'];
			}
		} else {
			Auth::logout();
			$data = ['responseCode' => -1, 'data' => 'closed'];
		}

//		$LgController = new LoginController;
//		if (!$LgController->_checkSecurityProfile($request)) {
//			Auth::logout();
//			$data = ['responseCode' => -1, 'data' => 'Security Profile does not matched'];
//		}

		return response()->json($data);
	}

//System admin delegation process
    public function delegations($id) {
        $delegate_to_user_id = Encryption::decodeId($id);

        // check this user is delegated or not ???
        $isDelegate = Users::where('id', $delegate_to_user_id)->pluck('delegate_to_user_id');
        if ($isDelegate != 0) {
            $info = UsersModel::leftJoin('user_desk as ud', 'ud.id', '=', 'users.desk_id')
                ->leftJoin('user_types as ut','ut.id','=','users.user_type')
                ->where('users.id', $isDelegate)
                ->first(['users.id', 'user_full_name', 'users.desk_id','ut.type_name',
                    'user_email', 'user_phone', 'users.user_type', 'ud.desk_name',
                    'designation','user_phone'
                ]);
            Session::put('sess_delegated_user_id', $isDelegate);
        }else{
            $info = UsersModel::leftJoin('user_desk as ud', 'ud.id', '=', 'users.desk_id')
                ->leftJoin('user_types as ut','ut.id','=','users.user_type')
                ->where('users.id', $delegate_to_user_id)
                ->first(['users.id', 'user_full_name', 'users.desk_id','ut.type_name',
                    'user_email', 'user_phone', 'users.user_type', 'ud.desk_name',
                    'designation','user_phone'
                ]);

        }

        $desk_id = $info->desk_id;
        $user_type = $info->user_type;

        if ($desk_id == '' || $desk_id == 0) {
            Session::flash('error', 'Desk id is empty!');
            return redirect("users/view/" . Encryption::encodeId($delegate_to_user_id));
        }

        $deligate_to_desk_data = UserTypes::where('id', $user_type)->first(['delegate_to_types']);
        if (count($deligate_to_desk_data) > 0) {
            $deligate_to_type = explode(',', $deligate_to_desk_data->delegate_to_types);
            $designation = UserTypes::whereIn('id', $deligate_to_type)->lists('type_name', 'id');
        }

        return view("Users::delegation", compact('isDelegate', 'delegate_to_user_id', 'info', 'designation'));


    }


    function storeDelegation(Request $request) {
        $delegate_by_user_id = Auth::user()->id;
        $delegate_to_user_id = $request->get('delegated_user');
        $delegate_from_user_id = $request->get('user_id');


        $dependend_on_from_userid = UsersModel::where('delegate_to_user_id','=',$delegate_from_user_id)->get(['id','delegate_to_user_id']);

        DB::beginTransaction();
        foreach ($dependend_on_from_userid as $dependentUser){
            $updateDependent = UsersModel::findOrFail($dependentUser->id);
            $updateDependent->delegate_to_user_id = $delegate_to_user_id;
            $updateDependent->delegate_by_user_id = $delegate_by_user_id;
            $updateDependent->save();

            $delegation = new Delegation();
            $delegation->delegate_form_user = $dependentUser->id;
            $delegation->delegate_by_user_id = $delegate_by_user_id;
            $delegation->delegate_to_user_id = $delegate_to_user_id;
            $delegation->remarks = $request->get('remarks');
            $delegation->status = 1;
            $delegation->save();

        }
        DB::commit();

        $data = [
            'delegate_form_user' => $delegate_from_user_id,
            'delegate_by_user_id' => $delegate_by_user_id,
            'delegate_to_user_id' => $delegate_to_user_id,
            'remarks' => $request->get('remarks'),
            'status' => 1
        ];
        Delegation::create($data);

        $udata = array(
            'delegate_to_user_id' => $delegate_to_user_id,
            'delegate_by_user_id' => $delegate_by_user_id
        );

        $complt = UsersModel::where('id', $delegate_from_user_id)
            ->orWhere('delegate_to_user_id', $delegate_from_user_id)
            ->update($udata);

        if ($complt) {
            Session::flash('success', 'Delegation process completed Successfully');
            return redirect("users/lists");
        } else {
            Session::flash('error', 'Delegation Not completed');
            return redirect("users/view/" . Encryption::encodeId($delegate_from_user_id));
        }
    }

    public function getDeligatedUserInfos(Request $request) {
        $type_id = $request->get('designation');
        $delegate_form_user_id = $request->get('delegate_form_user_id');
//        dd($delegate_form_user_id);
        $result = Users::where('user_type', '=', $type_id)
            ->Where(function($result) use($delegate_form_user_id) {
                return  $result->where('delegate_to_user_id','=', null)
                    ->orWhere('delegate_to_user_id','=',0);
            })
            ->where('id','!=', $delegate_form_user_id)
            ->get(['user_full_name', 'id']);
        echo json_encode($result);
    }
    public function twoStep() {
        try{
            return view("Users::two-step");
        } catch (\Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }
    }
    public function checkTwoStep(Request $request) {
        try {
            $steps = $request->get('steps');
            $code = rand(1000, 9999);
            $body_msg = "Security code for 2nd step login is: <strong><code>" . $code . "</code></strong>";

            $user_email = Auth::user()->user_email;
            $user_phone = Auth::user()->user_phone;
            $token = $code . '-' . Auth::user()->id;
            $encrypted_token = Encryption::encode($token);
            UsersModelEditable::where('user_email', $user_email)->update(['auth_token' => $encrypted_token]);

            if ($steps == 'email') {
                $body_msg = '<span style="color:#000;text-align:justify;"><b>';
                $body_msg .= 'অভিনন্দন!</b><br/><br/>';
                $body_msg .= 'This is a secret password generated by the system.But to ensure your own security and convenience, you should change the password after logging in.</br>';
                $body_msg .= "Security code for 2nd step login is: <strong><code>" . $code . "</code></strong>";
                $body_msg .= '<br/><br/><br/>ধন্যবাদান্তে,<br/>';
                $body_msg .= '<b></b>';

                $params = array([
                    'emailYes' => '1',
                    'emailTemplate' => 'Users::message',
                    'emailBody' => $body_msg,
                    'emailSubject' => 'Board Meeting Two Step verification password',
                    'emailHeader' => 'Board Meeting Information',
                    'emailAdd' => $user_email,
                    'mobileNo' => '01856228493',
                    'smsYes' => '0',
                    'smsBody' => '',
                ]);
                CommonFunction::sendMessageFromSystem($params);
            } else {
//                dd("sorry page not work!!!");
//                $smsData['source'] = 'Your OCPL BASE verification code: '.$code;
//
//                $smsData['destination'] = Auth::user()->user_phone;
//                $smsData['msg_type'] = 'SMS';
//                $smsData['ref_id'] = Auth::user()->id;
//                $smsData['is_sent'] = 0;
//                $smsData['template_id'] = 0;
//                $smsData['priority'] = 9;

//                Notification::create($smsData);



                $params = array([
                    'emailYes' => '0',
                    //'emailTemplate' => 'Users::message',
                    'emailBody' => '',
                    'emailSubject' => '',
                    'emailHeader' => '',
                    //'emailAdd' => $user_email,
                    'mobileNo' => Auth::user()->user_phone,
                    'smsYes' => '1',
                    'smsBody' => 'Your verification code:'.$code,
                ]);


               // $notification = new Notification();
                CommonFunction::sendMessageFromSystem($params);
//                $notification->sendSecondStepSMS($code);
            }
            $emailQueueId = EmailQueue::where('user_id', Auth::user()->id)->orderby('id','DESC')->first(['id']);
            Session::put('email_queue_id', $emailQueueId->id);
            if ($request->get('req_dta') != null) {
                $req_dta = $request->get('req_dta');
                return view("Users::check-two-step", compact('steps', 'user_email', 'user_phone', 'req_dta'));
            } else {
                return view("Users::check-two-step", compact('steps', 'user_email', 'user_phone'));
            }
        } catch (\Exception $e) {
//            dd($e->getMessage());
            Session::flash('error', 'Sorry! Something is Wrong.');
            return Redirect::back()->withInput();
        }
    }
    public function verifyTwoStep(Request $request) {
        $this->validate($request, [
            'security_code' => 'required',
        ]);

        try {
            $security_code = trim($request->get('security_code'));
            $user_id = Auth::user()->id;
            $token = $security_code . '-' . $user_id;
            $encrypted_token = Encryption::encode($token);
            $count = UsersModel::where('id', $user_id)->where(['auth_token' => $encrypted_token])->count();

            UsersModel::where('id', $user_id)->update(['auth_token' => '']);
            // Profile updated related
            if ($request->get('req_dta') != null) {
                $req_dta = (array) json_decode(Encryption::decode($request->get('req_dta')));

                if ($count > 0) {
                    //=====================updating information=========================
                    $auth_token_allow = 0;
                    if (isset($req_dta['auth_token_allow']) == '1') {
                        $auth_token_allow = 1;
                    }

                    if (substr($req_dta['user_phone'], 0, 2) == '01') {
                        $mobile_no = '+88' . $req_dta['user_phone'];
                    } else {
                        $mobile_no = $req_dta['user_phone'];
                    }
                    $type = explode('x', Auth::user()->user_type);
                    $is_address_change = 0;
                    if (in_array($type[0], CommonFunction::firstTimeAddressChangeUserType())) {
                        if (isset($req_dta['district']) || isset($req_dta['thana'])) {
                            $is_address_change = 1;
                        }
                    }
                    $userData = UsersModelEditable::find(Auth::user()->id);
                    $userData->user_full_name = $req_dta['user_full_name'];
                    $userData->auth_token_allow = $auth_token_allow;
                    $userData->user_DOB = Carbon::createFromFormat('d-M-Y', $req_dta['user_DOB'])->format('Y-m-d');
                    $userData->user_phone = $mobile_no;
                    $userData->is_address_change = $is_address_change;
                    $userData->district = $req_dta['district'];
                    $userData->thana = $req_dta['thana'];
                    $userData->save();
                    $this->entryAccessLog();
                    //----------------------end----------------------------
                    Session::flash('success', "Updated profile successfully");
                    return redirect('users/profileinfo');
                } else {
                    Session::flash('error', "Security Code doesn't match.");
                    return redirect('/users/two-step/profile-update?req=' . $request->get('req_dta'));
                }
            } else {
                // Default two step verification


                if ($count > 0) {
                    $this->entryAccessLog();
                    $project_name = env('PROJECT_NAME');
                    Session::flash('success', "Security match successfully! Welcome to .$project_name. platform");
                    return redirect('dashboard');
                } else {

                    Session::flash('error', "Security Code doesn't match.");
                    return redirect('users/two-step');
                }
            }
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something is Wrong.');
            return Redirect::back()->withInput();
        }
    }

    public function getServerTime()
    {
        $databaseTime = DB::select("SELECT NOW() as db_time");
        $db_date = date('d-M-Y',strtotime($databaseTime[0]->db_time));
        $db_time = date('g:i:s A',strtotime($databaseTime[0]->db_time));

        $app_date = date('d-M-Y');
        $app_time = date('g:i:s A');

        $dateTime = [
            'db_date'=>$db_date,
            'db_time'=>$db_time,
            'app_date'=>$app_date,
            'app_time'=>$app_time,
        ];

        return $dateTime;
    }

    public function entryAccessLog()
    {
        // access_log table.
        $str_random = str_random(10);
        $insert_id = DB::table('user_logs')->insertGetId(
            array(
                'user_id' => Auth::user()->id,
                'login_dt' => date('Y-m-d H:i:s'),
                'ip_address' => \Request::getClientIp(),
                'access_log_id' => $str_random
            )
        );

        Session::put('access_log_id', $str_random);
    }

}
