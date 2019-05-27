<?php

namespace App\Modules\Settings\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LoginController;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\apps\Models\Colors;
use App\Modules\Apps\Models\DocInfo;
use App\Modules\apps\Models\IndustryCategories;
use App\Modules\Dashboard\Models\Dashboard;
use App\Modules\Dashboard\Models\Services;
use App\Modules\Faq\Models\FaqTypes;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\Settings\Models\Area;
use App\Modules\Settings\Models\Bank;
use App\Modules\Settings\Models\BankBranch;
use App\Modules\Settings\Models\Configuration;
use App\Modules\Settings\Models\Currencies;
use App\Modules\Settings\Models\HighComissions;
use App\Modules\Settings\Models\HomePageSlider;
use App\Modules\Settings\Models\HsCodes;
use App\Modules\Settings\Models\Logo;
use App\Modules\Settings\Models\Notice;
use App\Modules\Settings\Models\Notification;
use App\Modules\Settings\Models\Ports;
use App\Modules\Settings\Models\SecurityProfile;
use App\Modules\Settings\Models\ServiceDetails;
use App\Modules\Settings\Models\Units;
use App\Modules\Settings\Models\WhatsNew;
use App\Modules\Users\Models\AreaInfo;
use App\Modules\Users\Models\CompanyInfo;
use App\Modules\Users\Models\Countries;
use App\Modules\Users\Models\EconomicZones;
use App\Modules\Users\Models\ParkInfo;
use App\Modules\Users\Models\Users;
use App\Modules\Users\Models\UsersModel;
use App\Modules\Users\Models\UserTypes;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Session;
use yajra\Datatables\Datatables;

class SettingsController extends Controller {

    public function __construct() {
        if (Session::has('lang'))
            \App::setLocale(Session::get('lang'));
        ACL::db_reconnect();
    }
    public function index() {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        return view("Settings::index");
    }

    /* Starting of Bank Related Functions */

    public function bank() {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $getList = Bank::where("is_archive",0)->get();
        return view("Settings::bank.list", compact('getList'));
    }

    public function createBank() {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        return view("Settings::bank.form-basic");
    }

    public function storeBank(Request $request) {

        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        $this->validate($request, [
            'name' => 'required',
            'bank_code' => 'required',
            'location' => 'required',
            'email' => 'required|email',
            'phone' => 'required|Max:50|regex:/[0-9+,-]$/',
            'location' => 'required',
        ]);
        try {
            $insert = Bank::create(
                            array(
                                'name' => $request->get('name'),
                                'bank_code' => $request->get('bank_code'),
                                'location' => $request->get('location'),
                                'email' => $request->get('email'),
                                'phone' => $request->get('phone'),
                                'location' => $request->get('location'),
                                'address' => $request->get('address'),
                                'website' => $request->get('website'),
                                'created_by' => CommonFunction::getUserId()
            ));

            Session::flash('success', 'Data is stored successfully!');
            return redirect('/settings/edit-bank/' . Encryption::encodeId($insert->id));
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Somthing Wrong.');
            return Redirect::back()->withInput();
        }
    }

    public function editBank($id) {
        $bank_id = Encryption::decodeId($id);
        $data = Bank::where('id', $bank_id)->first();

        return view("Settings::bank.edit", compact('data', 'id'));
    }

    public function updateBank($id, Request $request) {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $bank_id = Encryption::decodeId($id);

        $this->validate($request, [
            'name' => 'required',
            'bank_code' => 'required',
            'email' => 'required|email',
            'phone' => 'required|Max:50|regex:/[0-9+,-]$/',
            'location' => 'required',
        ]);

        Bank::where('id', $bank_id)->update([
            'name' => $request->get('name'),
            'bank_code' => $request->get('bank_code'),
            'location' => $request->get('location'),
            'email' => $request->get('email'),
            'phone' => $request->get('phone'),
            'address' => $request->get('address'),
            'website' => $request->get('website'),
            'is_active' => $request->get('is_active'),
            'updated_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('/settings/edit-bank/' . $id);
    }

    public function viewBank($id) {
        $bank_id = Encryption::decodeId($id);
        $data = Bank::where('id', $bank_id)->first();

        return view("Settings::bank.view", compact('data', 'id', 'bank_id', 'getList'));
    }

    public function branch()
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            abort('400', 'You have no access right! Please contact system administration for more information.');
        }
        $getList = BankBranch::where('is_archive', 0)->get();
        return view("Settings::branch.list", compact('getList'));
    }

    public function createBranch()
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            abort('400', 'You have no access right! Please contact system administration for more information.');
        }

        $banks = Bank::orderBy('name')
            ->where('is_active', 1)
            ->where('id', 2)
            ->lists('name', 'id');
        return view("Settings::branch.form-basic", compact('banks'));
    }

    public function storeAndUpdateBranch(Request $request, $id = '')
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            abort('400', 'You have no access right! Please contact system administration for more information.');
        }

        $this->validate($request, [
            'bank_id' => 'required',
            'branch_code' => 'required | numeric | digits_between:1,8',
            'branch_name' => 'required',
            'address' => 'required',
        ]);
        if ($id) {
            $id = Encryption::decodeId($id);
        }

        $isDuplicate = BankBranch::where(['bank_id' => $request->get('bank_id'), 'branch_code' => $request->get('branch_code')])
            ->where('id', '!=', $id)
            ->count();
        if ($isDuplicate > 0) {
            Session::flash('error', 'Duplicate branch for this bank.');
            return Redirect::back()->withInput();
        }

        try {

            $branchData = BankBranch::findOrNew($id);
            $branchData->bank_id = $request->get('bank_id');
            $branchData->branch_code = $request->get('branch_code');
            $branchData->branch_name = $request->get('branch_name');
            $branchData->address = $request->get('address');
            $branchData->manager_info = $request->get('manager_info');
            if ($id) {
                $branchData->is_active = $request->get('is_active');
            }
            $branchData->save();
            Session::flash('success', 'Branch is added successfully!');
            if ($id == $branchData->id) {
                Session::flash('success', 'Branch updated successfully!');
            }


            return redirect('/settings/edit-branch/' . Encryption::encodeId($branchData->id));
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something went wrong.');
            return Redirect::back()->withInput();
        }
    }

    public function editBranch($id)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            abort('400', 'You have no access right! Please contact system administration for more information.');
        }
        $bank_id = Encryption::decodeId($id);
        $banks = Bank::orderBy('name')
            ->where('is_active', 1)
            ->where('id', 2)
            ->lists('name', 'id');
        $data = BankBranch::where('id', $bank_id)->first();

        return view("Settings::branch.edit", compact('data', 'banks', 'id'));
    }

    public function viewBranch($id)
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            abort('400', 'You have no access right! Please contact system administration for more information.');
        }
        $branch_id = Encryption::decodeId($id);
        $data = BankBranch::leftJoin('bank', 'bank.id', '=', 'bank_branches.bank_id')
            ->where('bank_branches.id', $branch_id)
            ->first(['bank_branches.*', 'bank.name as bank_name']);
        return view("Settings::branch.view", compact('data', 'id', 'branch_id'));
    }


    /* Start of Currency related functions */

    public function currency() {
        $rows = Currencies::orderBy('code')->where('is_archive',0)->get();
        return view("Settings::currency.list", compact('rows'));
    }

    public function createCurrency() {
        return view("Settings::currency.create", compact(''));
    }

    public function storeCurrency(Request $request) {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        $this->validate($request, [
            'code' => 'required',
            'name' => 'required',
            'usd_value' => '',
            'bdt_value' => '',
        ]);

        $insert = Currencies::create([
                    'code' => $request->get('code'),
                    'name' => $request->get('name'),
                    'usd_value' => $request->get('usd_value'),
                    'bdt_value' => $request->get('bdt_value'),
                    'created_by' => CommonFunction::getUserId(),
        ]);

        Session::flash('success', 'Data is stored successfully!');
        return redirect('/settings/edit-currency/' . Encryption::encodeId($insert->id));
    }

    public function editCurrency($encrypted_id) {
        $id = Encryption::decodeId($encrypted_id);
        $data = Currencies::where('id', $id)->first();

        return view("Settings::currency.edit", compact('data', 'encrypted_id'));
    }

    public function updateCurrency($enc_id, Request $request) {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $id = Encryption::decodeId($enc_id);

        $this->validate($request, [
            'code' => 'required',
            'name' => 'required',
            'usd_value' => '',
            'bdt_value' => '',
        ]);

        Currencies::where('id', $id)->update([
            'code' => $request->get('code'),
            'name' => $request->get('name'),
            'usd_value' => $request->get('usd_value'),
            'bdt_value' => $request->get('bdt_value'),
            'is_active' => $request->get('is_active'),
            'updated_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('/settings/edit-currency/' . $enc_id);
    }

    /* End of Currency related functions */
    public function parks()
    {
        return view("Settings::park.list");
    }

    public function getEcoParkData()
    {
        $mode = ACL::getAccsessRight('settings', 'V');
        $datas = ParkInfo::where('is_archive', 0)->orderBy('park_name', 'asc')
            ->get(['id', 'park_name', 'upazilla_name', 'district_name', 'park_area', 'remarks', 'status']);
        return Datatables::of($datas)
            ->addColumn('action', function ($datas) use ($mode) {
                if ($mode) {
                    $url = "ConfirmDelete('" . Encryption::encodeId($datas->id) . "')";
                    return '<a href="/settings/edit-park-info/' . Encryption::encodeId($datas->id) .
                        '" class="btn btn-xs btn-success"><i class="fa fa-folder-open-o"></i> Open</a>'
                        . ' <a href="javascript:void(0)" ' .
                        " class='btn btn-xs btn-danger' onclick=$url><i class='fa fa-times'></i></a>";
                }
            })
            ->editColumn('status', function ($datas) {
                if ($datas->status == 1) {
                    $class = 'text-success';
                    $status = 'Active';
                } else {
                    $class = 'text-danger';
                    $status = 'Inactive';
                }
                return '<span class="' . $class . '"><b>' . $status . '</b></span>';
            })
           // ->removeColumn('id')
            ->make(true);
    }
    public function createPark()
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            abort('400', 'You have no access right! Please contact system administration for more information.');
        }
        $districts = Area::orderby('area_nm')->where('area_type', 2)->lists('area_nm', 'area_nm');
        return view("Settings::park.create", compact('districts'));
    }





    public function getPoliceStations(Request $request) {
        if ($request->get('lang') && $request->get('lang') == 'en') {
            $areaField = 'area_info.area_nm';
        } else {
            $areaField = 'area_info.area_nm_ban';
        }

        $data = ['responseCode' => 0, 'data' => ''];
        $area = Area::where($areaField, $request->get('districtId'))->where('area_type', 2)->first();
        if ($area) {
            $area_id = $area->area_id;
            $get_data = Area::where('pare_id', DB::raw($area_id))
                    ->whereNotNull($areaField)
                    ->where('area_type', 3)
                    ->select($areaField)
                    ->orderBy($areaField)
                    ->lists($areaField);

            $data = ['responseCode' => 1, 'data' => $get_data];
        }
        return response()->json($data);
    }

    public function getPoliceStationsWithId(Request $request) {

        if ($request->get('lang') && $request->get('lang') == 'en') {
            $areaField = 'area_info.area_id';
            $areaValue = 'area_info.area_nm';
            $area_select = array('area_info.area_nm', 'area_info.area_id');
        } else {
            $areaField = 'area_info.area_id';
            $areaValue = 'area_info.area_nm_ban';
            $area_select = 'area_info.area_nm_ban,area_info.area_id';
            $area_select = array('area_info.area_nm_ban', 'area_info.area_id');
        }

        $data = ['responseCode' => 0, 'data' => ''];
        $area = Area::where($areaField, $request->get('districtId'))->where('area_type', 2)->first();
        if ($area) {
            $area_id = $area->area_id;
            $get_data = Area::where('pare_id', DB::raw($area_id))
                    ->whereNotNull($areaField)
                    ->where('area_type', 3)
                    ->select($area_select)
                    ->orderBy($areaField)
                    ->lists('area_info.area_nm', 'area_info.area_id');
//                ->lists($areaField, $areaValue);

            $data = ['responseCode' => 1, 'data' => $get_data];
        }
        return response()->json($data);
    }

    public function getThana(Request $request) {

        if ($request->get('lang') && $request->get('lang') == 'en') {
            $areaField = 'area_info.area_nm';
        } else {
            $areaField = 'area_info.area_nm_ban';
        }

        $data = ['responseCode' => 0, 'data' => ''];
        $get_data = Area::where('pare_id', $request->get('districtId'))
                ->whereNotNull($areaField)
                ->where('area_type', 3)
                ->orderBy($areaField)
                ->lists($areaField, 'area_id');
        $data = ['responseCode' => 1, 'data' => $get_data];
        return response()->json($data);
    }

    public function getDistrictUser(Request $request) {
        $area_id = $request->get('districtId');
        $get_data = UsersModel::where('district', '=', $area_id)
                ->where(function ($query) {
                    return $query->where('user_type', '=', '7x713');
                })
                ->select('user_full_name', 'id')
                ->orderBy('user_full_name')
                ->lists('user_full_name', 'id');
        $data = ['responseCode' => 1, 'data' => $get_data];
        return response()->json($data);
    }

    public function areaList() {
        if (!ACL::getAccsessRight('settings', 'V'))
            die('You have no access right! Please contact system administration for more information.');
        $getList = Area::all();
        return view("Settings::area.list", compact('getList'));
    }

    public function createArea() {
        if (!ACL::getAccsessRight('settings', 'A'))
            die('You have no access right! Please contact system administration for more information.');
        $divisions = ['' => 'Select one'] + Area::orderBy('area_nm')
                        ->where('pare_id', 0)->lists('area_nm', 'area_id')->all();

        return view("Settings::area.form-basic", compact('divisions'));
    }

    public function storeArea(Request $request) {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $this->validate($request, [
            'area_nm' => 'required',
            'area_nm_ban' => 'required',
        ]);
        try {
            $area_type = $request->get('area_type');
            if ($area_type == 1) { //for division
                $parent_id = 0;
            } elseif ($area_type == 2) { // for district
                $parent_id = $request->get('division');
            } elseif ($area_type == 3) { //for thana
                $parent_id = $request->get('district');
            }

            $insert = Area::create([
                        'area_type' => $area_type,
                        'pare_id' => $parent_id,
                        'area_nm' => $request->get('area_nm'),
                        'area_nm_ban' => $request->get('area_nm_ban'),
            ]);

            Session::flash('success', 'Data is stored successfully!');
            return redirect('/settings/edit-area/' . Encryption::encodeId($insert->id));
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Somthing Wrong.');
            return Redirect::back()->withInput();
        }
    }

    public function editArea($id) {

        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        $area_id = Encryption::decodeId($id);
        $data = Area::leftJoin('area_info as ai', 'area_info.pare_id', '=', 'ai.area_id')
                        ->where('area_info.area_id', $area_id)
                        ->get(['area_info.*', 'ai.pare_id as division_id'])[0];


        $divisions = ['' => 'Select one'] + Area::orderBy('area_nm')
                        ->where('pare_id', 0)->lists('area_nm', 'area_id')->all();

        return view("Settings::area.edit", compact('data', 'id', 'divisions'));
    }

    public function updateArea($id, Request $request) {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $area_id = Encryption::decodeId($id);

        $this->validate($request, [
            'area_nm' => 'required',
            'area_nm_ban' => 'required',
        ]);

        $area_type = $request->get('area_type');
        if ($area_type == 1) { //for division
            $parent_id = 0;
        } elseif ($area_type == 2) { // for district
            $parent_id = $request->get('division');
        } elseif ($area_type == 3) { //for thana
            $parent_id = $request->get('district');
        }

        Area::where('area_id', $area_id)->update([
            'area_type' => $area_type,
            'pare_id' => $parent_id,
            'area_nm' => $request->get('area_nm'),
            'area_nm_ban' => $request->get('area_nm_ban'),
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('/settings/edit-area/' . $id);
    }

    public function get_district_by_division_id(Request $request) {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $divisionId = $request->get('divisionId');

        $districts = Area::where('PARE_ID', $divisionId)->orderBy('AREA_NM', 'ASC')->lists('AREA_NM', 'AREA_ID');
        $data = ['responseCode' => 1, 'data' => $districts];
        return response()->json($data);
    }

    public function getAreaData() {
        $areas = Area::orderBy('area_nm', 'asc')->get(['area_id', 'area_nm', 'area_nm_ban', 'area_type']);
        $mode = ACL::getAccsessRight('settings', 'E');

        return Datatables::of($areas)
                        ->addColumn('action', function ($areas) use ($mode) {
                            if ($mode) {
                                return '<a href="' . url('settings/edit-area/'. Encryption::encodeId($areas->area_id)) .
                                        '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open-o"></i> Open</a>';
                            } else
                                return '';
                        })
                        ->editColumn('area_type', function ($areas) {
                            if ($areas->area_type == 1) {
                                return 'Division';
                            } elseif ($areas->area_type == 2) {
                                return 'District';
                            } elseif ($areas->area_type == 3) {
                                return 'Thana';
                            }
                        })
                        //->removeColumn('area_id')
                        ->make(true);
    }

    /* Starting of User Type Related Functions */

    public function userType()
    {
        $getList = UserTypes::leftJoin('security_profile as sp', 'sp.id', '=', 'user_types.security_profile_id')
            ->get(['user_types.id', 'type_name', 'security_profile_id', 'week_off_days', 'user_types.status']);

        return view("Settings::user_type.list", compact('getList'));
    }


    public function editUserType($id) {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $id = Encryption::decodeId($id);
        $security_profiles = SecurityProfile::orderBy('profile_name', 'ASC')
                ->lists('profile_name', 'id');
        $data = UserTypes::where('id', $id)
                ->first(['id', 'type_name', 'security_profile_id', 'auth_token_type', 'db_access_data', 'updated_at', 'updated_by', 'status']);
        return view("Settings::user_type.edit", compact('data', 'security_profiles'));
    }

    public function updateUserType($encoded_id, Request $request) {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $this->validate($request, [
            'type_name' => 'required',
            'auth_token_type' => 'required',
        ]);
//        CommonFunction::createAuditLog('userType.edit', $request);
        $id = Encryption::decodeId($encoded_id);
        $update_data = array(
            'type_name' => $request->get('type_name'),
            'security_profile_id' => $request->get('security_profile'),
            'auth_token_type' => $request->get('auth_token_type'),
            'db_access_data' => Encryption::encode($request->get('db_access_data')),
            'status' => $request->get('status'),
            'updated_by' => Auth::user()->id,
        );
        $data = UserTypes::where('id', $id)
                ->update($update_data);

        if ($request->get('status') == 'inactive') {
            $user_ids = UsersModel::where('user_type', $id)->get(['id']);
            foreach ($user_ids as $user_id) {
                LoginController::killUserSession($user_id);
            }
        }

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('settings/edit-user-type/' . $encoded_id);
    }

    /* End of User Type related functions */

    /* Starting of Configuration Related Functions */

    public function configuration() {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $getList = Configuration::where('is_locked', '=', 0)->get();
        return view("Settings::config.list", compact('getList'));
    }

    public function editConfiguration($id) {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $config_id = Encryption::decodeId($id);
        $data = Configuration::where('id', $config_id)->first();
        return view("Settings::config.edit", compact('data', 'id'));
    }

    public function moreConfig() {
        if (Auth::user()->user_email == 'shoeb@batworld.com' || Auth::user()->user_email == 'mitul@batworld.com' || Auth::user()->user_email == 'mithu@batworld.com') {
            $getList = Configuration::where('is_locked', '=', 1)->get();
            return view("Settings::config.list", compact('getList'));
        } else {
            Session::flash('error', 'Not permitted!');
            return redirect()->back();
        }
    }

    public function updateConfig($id, Request $request) {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $config_id = Encryption::decodeId($id);

        $this->validate($request, ['value' => 'required']);

        Configuration::where('id', $config_id)->update([
            'value' => $request->get('value'),
            'details' => $request->get('details'),
            'value2' => $request->get('value2'),
            'value3' => $request->get('value3'),
            'updated_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('/settings/edit-config/' . $id);
    }

    /* Starting of Notification Related Functions */

    public function notification() {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $getList = Notification::where('is_locked', 0)
                ->orderBy('id', 'desc')
                ->take(100)
                ->get();
        return view("Settings::notify.list", compact('getList'));
    }

    public function viewNotify($id) {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $notify_id = Encryption::decodeId($id);
        $data = Notification::where('id', $notify_id)->first();
        return view("Settings::notify.view", compact('data', 'id', '$notify_id'));
    }

    /* Start of FAQ Category related functions */

    public function faqCat() {
        return view("Settings::faq_category.list");
    }

    public function createFaqCat() {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $faq_types = FaqTypes::lists('name', 'id');
        return view("Settings::faq_category.create", compact('faq_types'));
    }

    public function getFaqCatDetailsData() {
        $mode = ACL::getAccsessRight('settings', 'E');
        $faq_types = FaqTypes::leftJoin('faq_multitypes', 'faq_types.id', '=', 'faq_multitypes.faq_type_id')
                ->leftJoin('faq', 'faq.id', '=', 'faq_multitypes.faq_id')
                ->groupBy('faq_types.id')
                ->get(['faq_types.id', 'faq_types.name', 'faq.status as faq_status',
            DB::raw('count(distinct faq_multitypes.faq_id) noOfItems, '
                    . 'sum(case when faq.status="unpublished" then 1 else 0 end) Unpublished,'
                    . 'sum(case when faq.status="draft" then 1 else 0 end) Draft,'
                    . 'sum(case when faq.status="private" then 1 else 0 end) Private')]);

        return Datatables::of($faq_types)
                        ->addColumn('action', function ($faq_types) use ($mode) {
                            if ($mode) {
                                return '<a href="/settings/edit-faq-cat/' . Encryption::encodeId($faq_types->id) .
                                        '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open-o"></i> Open</a> '
                                        . '<a href="/search/index?q=&faqs_type=' . $faq_types->id .
                                        '" class="btn btn-xs btn-info"><i class="fa fa-folder-open-o"></i> Articles</a>';
                            } else {
                                return '';
                            }
                        })
                        ->editColumn('Draft', function ($faq_types) {
                            if ($faq_types->Draft > 0) {
                                return '<a href="/search/index?q=&faqs_type=' . $faq_types->id . "&status=draft" .
                                        '" class="">' . $faq_types->Draft . '</a>';
                            } else {
                                return $faq_types->Draft;
                            }
                        })
                        ->editColumn('Unpublished', function ($faq_types) {
                            if ($faq_types->Unpublished > 0) {
                                return '<a href="/search/index?q=&faqs_type=' . $faq_types->id . "&status=unpublished" .
                                        '" class="">' . $faq_types->Unpublished . '</a>';
                            } else {
                                return $faq_types->Unpublished;
                            }
                        })
                        ->editColumn('Private', function ($faq_types) {
                            if ($faq_types->Private > 0) {
                                return '<a href="/search/index?q=&faqs_type=' . $faq_types->id . "&status=private" .
                                        '" class="">' . $faq_types->Private . '</a>';
                            } else {
                                return $faq_types->Private;
                            }
                        })
                        ->removeColumn('id')
                        ->make(true);
    }

    public function storeFaqCat(Request $request) {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $this->validate($request, [
            'name' => 'required',
        ]);

        $insert = FaqTypes::create(
                        array(
                            'name' => $request->get('name'),
                            'created_by' => CommonFunction::getUserId()
        ));

        Session::flash('success', 'Data is stored successfully!');
        return redirect('/settings/edit-faq-cat/' . Encryption::encodeId($insert->id));
    }

    public function editFaqCat($encrypted_id) {
        $id = Encryption::decodeId($encrypted_id);
        $data = FaqTypes::where('id', $id)->first();

        return view("Settings::faq_category.edit", compact('data', 'encrypted_id'));
    }

    public function updateFaqCat($id, Request $request) {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $faq_id = Encryption::decodeId($id);

        $this->validate($request, [
            'name' => 'required',
        ]);

        FaqTypes::where('id', $faq_id)->update([
            'name' => $request->get('name'),
            'updated_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('/settings/edit-faq-cat/' . $id);
    }

    /* End of FAQ Category related functions */


    /* Starting of Document Related Functions */

    public function document() {
        return view("Settings::document.list");
    }

    public function getDocData() {
        $mode = ACL::getAccsessRight('settings', 'V');
        $datas = docInfo::orderBy('doc_id', 'desc')
                ->get(['doc_id', 'doc_name', 'process_type_id', 'doc_priority', 'updated_at']);
        return Datatables::of($datas)
                        ->addColumn('action', function ($datas) use ($mode) {
                            if ($mode) {
                                return '<a href="/settings/edit-document/' . Encryption::encodeId($datas->doc_id) .
                                        '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open-o"></i> Open</a>';
                            }
                        })
                        ->editColumn('process_type_id', function ($datas) use ($mode) {
                            if ($mode) {
                                $process_name = ProcessType::where('id', $datas->process_type_id)->pluck('name');
                                return $process_name;
                            }
                        })
                        ->editColumn('doc_priority', function ($datas) {
                            if ($datas->doc_priority == 1) {
                                return 'Mandatory';
                            } else {
                                return 'Not Mandatory';
                            }
                        })
                        ->removeColumn('doc_id')
                        ->make(true);
    }

    public function createDocument() {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $services = ProcessType::orderby('name')->lists('name', 'id');
        return view("Settings::document.create", compact('services'));
    }

    public function storeDocument(Request $request) {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $this->validate($request, [
            'doc_name' => 'required',
            'service_id' => 'required',
        ]);

        $insert = docInfo::create([
                    'doc_name' => $request->get('doc_name'),
                    'process_type_id' => $request->get('service_id'),
                    'doc_priority' => $request->get('doc_priority'),
                    'created_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'Data is stored successfully!');
        return redirect('settings/document/');
    }

    public function editDocument($id) {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $_id = Encryption::decodeId($id);
        $data = docInfo::where('doc_id', $_id)->first();
        $services = ProcessType::orderby('name')->lists('name', 'id');
        return view("Settings::document.edit", compact('data', 'id', 'services'));
    }

    public function updateDocument($id, Request $request) {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $_id = Encryption::decodeId($id);

        $this->validate($request, [
            'doc_name' => 'required',
            'service_id' => 'required',
        ]);

        docInfo::where('doc_id', $_id)->update([
            'doc_name' => $request->get('doc_name'),
            'process_type_id' => $request->get('service_id'),
            'doc_priority' => $request->get('doc_priority'),
            'updated_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('settings/edit-document/' . $id);
    }

    /* Starting of Economic Zone Related Functions */

    public function EcoZones() {
        return view("Settings::ecoZone.list");
    }

    public function getEcoZoneData() {
        $mode = ACL::getAccsessRight('settings', 'V');
        $datas = EconomicZones::orderBy('name', 'asc')
                ->get(['id', 'name', 'upazilla', 'district', 'area', 'remarks']);
        return Datatables::of($datas)
                        ->addColumn('action', function ($datas) use ($mode) {
                            if ($mode) {
                                return '<a href="/settings/edit-eco-zone/' . Encryption::encodeId($datas->id) .
                                        '" class="btn btn-xs btn-success"><i class="fa fa-folder-open-o"></i> Open</a>';
                            }
                        })
                        ->removeColumn('id')
                        ->make(true);
    }

    public function createEcoZone() {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $districts = Area::orderby('area_nm')->where('area_type', 2)->lists('area_nm', 'area_nm');
        return view("Settings::ecoZone.create", compact('districts'));
    }

    public function storeEcoZone(Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            abort('400', 'You have no access right! Please contact system administration for more information.');
        }
        $this->validate($request, [
            'name' => 'required',
            'upazilla' => 'required',
            'district' => 'required',
            'area' => 'required',
        ]);

        $ParkInfo = new ParkInfo();

        $ParkInfo->park_name = $request->get('name');
        $ParkInfo->district_name = $request->get('district');
        $ParkInfo->upazilla_name = $request->get('upazilla');
        $ParkInfo->park_area = $request->get('area');
        $ParkInfo->remarks = $request->get('remarks');
        $ParkInfo->status = 1;
        $ParkInfo->save();

        Session::flash('success', 'Data is stored successfully!');
        return redirect('/settings/edit-park-info/' . Encryption::encodeId($ParkInfo->id));
    }

    public function editEcoZone($id)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            abort('400', 'You have no access right! Please contact system administration for more information.');
        }
        $_id = Encryption::decodeId($id);
        $data = ParkInfo::where('id', $_id)->first();
        $districts = Area::orderby('area_nm')->where('area_type', 2)->lists('area_nm', 'area_nm');
        return view("Settings::park.edit", compact('data', 'id', 'districts'));
    }
    public function updatePark($id, Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            abort('400', 'You have no access right! Please contact system administration for more information.');
        }
        $_id = Encryption::decodeId($id);

        $this->validate($request, [
            'name' => 'required',
            'upazilla' => 'required',
            'district' => 'required',
            'area' => 'required',
        ]);

        ParkInfo::where('id', $_id)->update([
            'park_name' => $request->get('name'),
            'upazilla_name' => $request->get('upazilla'),
            'district_name' => $request->get('district'),
            'park_area' => $request->get('area'),
            'status' => $request->get('is_active'),
            'remarks' => $request->get('remarks'),
            'updated_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('settings/edit-park-info/' . $id);
    }

    public function updateEcoZone($id, Request $request) {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $_id = Encryption::decodeId($id);

        $this->validate($request, [
            'name' => 'required',
            'upazilla' => 'required',
            'district' => 'required',
            'area' => 'required',
        ]);

        EconomicZones::where('id', $_id)->update([
            'name' => $request->get('name'),
            'upazilla' => $request->get('upazilla'),
            'district' => $request->get('district'),
            'area' => $request->get('area'),
            'remarks' => $request->get('remarks'),
            'updated_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('settings/edit-eco-zone/' . $id);
    }

    /* Start of Notice related functions */

    public function notice() {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        return view("Settings::notice.list");
    }

    public function createNotice() {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        return view("Settings::notice.create", compact(''));
    }

    public function getNoticeDetailsData()
    {
        $mode = ACL::getAccsessRight('settings', 'V');
        $notice = Notice::where('is_archive', 0)->orderBy('notice.updated_at', 'desc')
            ->get(['notice.id', 'heading', 'details', 'importance', 'status', 'notice.updated_at as update_date', 'is_active']);

        return Datatables::of($notice)

            ->addColumn('action', function ($notice) use ($mode) {
                if ($mode) {
                    $url = "ConfirmDelete('" . Encryption::encodeId($notice->id) . "')";
                    return '<a href="/settings/edit-notice/' . Encryption::encodeId($notice->id) .
                        '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open-o"></i> Open</a> '
                        . ' '
                        . '<a href="javascript:void(0)" ' .
                        " class='btn btn-xs btn-danger' onclick=$url><i class='fa fa-times'></i></a>";
                } else {
                    return '';
                }
            })
//            ->editColumn('details', function ($notice) {
//                return substr($notice->details, 0, 150) . ' <a href="/support/view-notice/' . Encryption::encodeId($notice->id) . '">'
//                    . 'See more... </a>';
//            })
            ->editColumn('update_date', function ($notice) {
                return CommonFunction::changeDateFormat(substr($notice->update_date, 0, 10));
            })
            ->editColumn('status', function ($notice) {
                return ucfirst($notice->status);
            })
            ->editColumn('importance', function ($notice) {
                return ucfirst($notice->importance);
            })
            ->editColumn('is_active', function ($desk) {
                if ($desk->is_active == 1) {
                    $class = 'text-success';
                    $status = 'Active';
                } else {
                    $class = 'text-danger';
                    $status = 'Inactive';
                }
                return '<span class="' . $class . '"><b>' . $status . '</b></span>';
            })
            ->removeColumn('id')
            ->make(true);



    }

    public function storeNotice(Request $request) {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $this->validate($request, [
            'heading' => 'required',
            'details' => 'required',
            'status' => 'required',
            'importance' => 'required',
        ]);
        try {
            $insert = Notice::create(
                            array(
                                'heading' => $request->get('heading'),
                                'details' => $request->get('details'),
                                'status' => $request->get('status'),
                                'importance' => $request->get('importance'),
                                'prefix' => $request->get('board_meeting'),
                                'created_by' => CommonFunction::getUserId()
            ));

            Session::flash('success', 'Data is stored successfully!');
            return redirect('/settings/edit-notice/' . Encryption::encodeId($insert->id));
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Somthing Wrong.');
            return Redirect::back()->withInput();
        }
    }

    public function editNotice($encrypted_id) {
        $id = Encryption::decodeId($encrypted_id);
        $data = Notice::where('id', $id)->first();
        return view("Settings::notice.edit", compact('data', 'encrypted_id'));
    }

    public function updateNotice($id, Request $request) {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $faq_id = Encryption::decodeId($id);

        $this->validate($request, [
            'heading' => 'required',
            'details' => 'required',
            'status' => 'required',
            'importance' => 'required',
        ]);

        Notice::where('id', $faq_id)->update([
            'heading' => $request->get('heading'),
            'details' => $request->get('details'),
            'status' => $request->get('status'),
            'importance' => $request->get('importance'),
            'is_active' => $request->get('is_active'),
            'updated_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('/settings/edit-notice/' . $id);
    }

    /* End of Notice related functions */

    /* Start of Logo related functions */
    public function logo(){
        $logoInformation = logo::all();
        return view("Settings::logo.list", compact('logoInformation'));
    }
    public function storeLogo(request $request){
//        dd($request->all());
        $company_logo = $request->file('company_logo');
        $path = "uploads/logo";
        if ($request->hasFile('company_logo')) {
//            $img_file = trim(sprintf("%s", uniqid($prefix, true))) . $company_logo->getClientOriginalName();
            $img_file = $company_logo->getClientOriginalName();
            $mime_type = $company_logo->getClientMimeType();
            if ($mime_type == 'image/jpeg' || $mime_type == 'image/jpg' || $mime_type == 'image/png') {
                $company_logo->move($path, $img_file);
                $filepath= $path . '/' . $img_file;

                logo::where('id', 1)->update([
                    'logo' => $filepath,
                    'title' => $request->get('title'),
                    'manage_by' => $request->get('manage_by'),
                    'help_link' => $request->get('help_link'),
                    'created_by' => CommonFunction::getUserId()
                ]);
                Session::flash('success', 'Data has been changed successfully.');
                return redirect('/settings/edit-logo');
            } else {
                \Session::flash('error', 'Company logo must be png or jpg or jpeg format');
                return redirect()->back();

            }
        }else{
            logo::where('id', 1)->update([
                'title' => $request->get('title'),
                'manage_by' => $request->get('manage_by'),
                'help_link' => $request->get('help_link'),
                'created_by' => CommonFunction::getUserId()
            ]);
            Session::flash('success', 'Data has been changed successfully.');
            return redirect('/settings/edit-logo');
        }
    }
    public function serviceInfo(){
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $getList = ServiceDetails::leftJoin('process_type as pt', 'pt.id', '=', 'service_details.process_type_id')
            ->get(['service_details.*','pt.name']);
        $services = ProcessType::orderby('name')->where('status',1)->lists('name', 'id')->prepend('Select One','');

        $divisions = ['' => 'Select Division '] + AreaInfo::orderby('area_nm')->where('area_type', 1)->lists('area_nm', 'area_id')->all();
        return view('Settings::service_info.service-info', compact( 'divisions','districts','thana','services','getList'));
    }

    public function createServiceInfoDetails(){
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $getList = ServiceDetails::leftJoin('process_type as pt', 'pt.id', '=', 'service_details.process_type_id')
            ->get(['service_details.*','pt.name']);
        $services = ProcessType::orderby('name')->where('status',1)->lists('name', 'id')->prepend('Select One','');
        $divisions = ['' => 'Select Division '] + AreaInfo::orderby('area_nm')->where('area_type', 1)->lists('area_nm', 'area_id')->all();
        return view('Settings::service_info.create', compact( 'divisions','districts','thana','services','getList'));
    }
    public function serviceSave(Request $request){
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $this->validate($request, [
            'title' => 'required',
            'process_type_id' => 'required|unique:service_details',
            'terms_and_conditions' => 'required',
            'description' => 'required'
        ]);
        try {
            $insert = ServiceDetails::create(
                array(
                    'title' => $request->get('title'),
                    'process_type_id' => $request->get('process_type_id'),
                    'description' => $request->get('description'),
                    'login_page_details' => $request->get('login_page_details'),
                    'terms_and_conditions' => $request->get('terms_and_conditions'),
                    'created_by' => CommonFunction::getUserId(),
                    'status' => 1
                ));

            Session::flash('success', 'Data is stored successfully!');
            return redirect('/settings/edit-service-info-details/' . Encryption::encodeId($insert->id));
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Somthing Wrong.');
            return Redirect::back()->withInput();
        }
    }
    public function editServiceInfoDetails($encrypted_id) {
        $id = Encryption::decodeId($encrypted_id);
        $data = ServiceDetails::where('id', $id)->first();
        $services = ProcessType::orderby('name')->where('status',1)->lists('name', 'id')->prepend('Select One','');
        $getList = ServiceDetails::leftJoin('process_type as pt', 'pt.id', '=', 'service_details.process_type_id')
            ->get(['service_details.*','pt.name']);
        return view("Settings::service_info.edit", compact('data', 'encrypted_id','getList','services'));
    }
    public function updateServiceDetails($id, Request $request) {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $faq_id = Encryption::decodeId($id);

        $this->validate($request, [
            'title' => 'required',
            'terms_and_conditions' => 'required',
            'description' => 'required'
        ]);

        ServiceDetails::where('id', $faq_id)->update([
            'title' => $request->get('title'),
           // 'process_type_id' => $request->get('process_type_id'),
            'description' => $request->get('description'),
            'login_page_details' => $request->get('login_page_details'),
            'terms_and_conditions' => $request->get('terms_and_conditions'),
            'created_by' => CommonFunction::getUserId(),
            'status' => $request->get('is_active'),
            'updated_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect()->back();
    }
    public function editLogo(){
        $logoInfo=Logo::first();
        return view("Settings::logo.edit", compact('logoInfo'));
    }

//    public function storeLogo(request $request,$encrypted_id){
////        dd($request->all());
//        $id = Encryption::decodeId($encrypted_id);
//        $company_logo = $request->file('company_logo');
//        $path = "uploads/logo";
//        if ($request->hasFile('company_logo')) {
////            $img_file = trim(sprintf("%s", uniqid($prefix, true))) . $company_logo->getClientOriginalName();
//            $img_file = $company_logo->getClientOriginalName();
//            $mime_type = $company_logo->getClientMimeType();
//            if ($mime_type == 'image/jpeg' || $mime_type == 'image/jpg' || $mime_type == 'image/png') {
//                $company_logo->move($path, $img_file);
//                $filepath= $path . '/' . $img_file;
//
//                logo::where('id', $id)->update([
//                    'logo' => $filepath,
//                    'title' => $request->get('title'),
//                    'manage_by' => $request->get('manage_by'),
//                    'help_link' => $request->get('help_link'),
//                    'created_by' => CommonFunction::getUserId()
//                ]);
//                Session::flash('success', 'Data has been changed successfully.');
//                return redirect('/settings/logo');
//            } else {
//                \Session::flash('error', 'Company logo must be png or jpg or jpeg format');
//                return redirect()->back();
//
//            }
//        }else{
//            logo::where('id', $id)->update([
//                'title' => $request->get('title'),
//                'manage_by' => $request->get('manage_by'),
//                'help_link' => $request->get('help_link'),
//                'created_by' => CommonFunction::getUserId()
//            ]);
//            Session::flash('success', 'Data has been changed successfully.');
//            return redirect('/settings/logo');
//        }
//    }
//    public function editLogo($encrypted_id){
//        $id = Encryption::decodeId($encrypted_id);
//        $logoInfo=Logo::find($id);
//        return view("Settings::logo.edit", compact('logoInfo','encrypted_id'));
//    }

    /* Start of High Commission related functions */

    public function highCommission() {
        return view("Settings::high_commission.list");
    }

    public function createHighCommission() {
        $countries = Countries::where('country_status', 'Yes')->orderBy('nicename', 'asc')->lists('nicename', 'country_code');
        return view("Settings::high_commission.create", compact('countries'));
    }

    public function getHighCommissionData()
    {
        $mode = ACL::getAccsessRight('settings', 'E');
        $hc = HighComissions::leftJoin('country_info', 'high_comissions.country_id', '=', 'country_info.id')
            ->orderBy('country_info.name')
            ->get(['high_comissions.id', 'high_comissions.name', 'address', 'phone', 'email', 'is_active','country_info.name as country']);
        return Datatables::of($hc)


                        ->addColumn('action', function ($hc) use ($mode) {
                            if ($mode) {
                                return '<a href="/settings/edit-high-commission/' . Encryption::encodeId($hc->id) .
                                        '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open-o"></i> Open</a> ';
                            } else {
                                return '';
                            }
                        })
                        ->editColumn('is_active', function ($notice) {
                            if ($notice->is_active == 1) {
                                $class = 'text-success';
                                $status = 'Active';
                            } else {
                                $class = 'text-danger';
                                $status = 'Inactive';
                            }
                            return '<span class="' . $class . '"><b>' . $status . '</b></span>';
                        })
                        ->editColumn('country', function ($hc) {
                            if ($hc->country) {
                                return ucfirst(strtolower($hc->country));
                            }
                        })
                        ->removeColumn('id')
                        ->make(true);
    }

    public function storeHighCommission(Request $request) {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        $this->validate($request, [
            'country_code' => 'required',
            'name' => 'required',
            'address' => 'required',
            'phone' => '',
            'email' => 'required|email',
        ]);

        $insert = HighComissions::create([
                    'country_id' => $request->get('country_code'),
                    'name' => $request->get('name'),
                    'address' => $request->get('address'),
                    'phone' => $request->get('phone'),
                    'email' => $request->get('email'),
                    'created_by' => CommonFunction::getUserId(),
        ]);

        Session::flash('success', 'Data is stored successfully!');
        return redirect('/settings/edit-high-commission/' . Encryption::encodeId($insert->id));
    }

    public function editHighCommission($encrypted_id) {
        $id = Encryption::decodeId($encrypted_id);
        $data = HighComissions::where('id', $id)->first();
        $hc_country = Countries::where('id', $data->country_id)->pluck('nicename');
        $countries = Countries::where('country_status', 'Yes')->orderBy('name', 'asc')->lists('nicename', 'country_code');

        return view("Settings::high_commission.edit", compact('data', 'encrypted_id', 'hc_country', 'countries'));
    }

    public function updateHighCommission($enc_id, Request $request) {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $id = Encryption::decodeId($enc_id);

        $this->validate($request, [
            'country_code' => 'required',
            'name' => 'required',
            'address' => 'required',
            'phone' => '',
            'email' => 'required',
        ]);

        HighComissions::where('id', $id)->update([
            'country_id' => $request->get('country_code'),
            'name' => $request->get('name'),
            'address' => $request->get('address'),
            'phone' => $request->get('phone'),
            'email' => $request->get('email'),
            'is_active' => $request->get('is_active'),
            'updated_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('/settings/edit-high-commission/' . $enc_id);
    }

    /* End of High Commission related functions */

      /* Start of HS Code related functions */

    public function HsCodes() {
        $rows = HsCodes::orderBy('product_name')
                ->where('is_archive', 0)
                ->get(['product_name','hs_code', 'is_active','id']);
        return view("Settings::hs_codes.list", compact('rows'));
    }

    public function createHsCode() {
        return view("Settings::hs_codes.create");
    }

    public function storeHsCode(Request $request) {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        $this->validate($request, [
            'product_name' => 'required',
        ]);

        $insert = HsCodes::create([
                    'hs_code' => $request->get('hs_code'),
                    'product_name' => $request->get('product_name'),
                    'is_active' => 1,
                    'created_by' => CommonFunction::getUserId(),
        ]);

        Session::flash('success', 'The HS Code is stored successfully!');
        return redirect('/settings/edit-hs-code/' . Encryption::encodeId($insert->id));
    }

    public function editHsCode($encrypted_id) {

        $id = Encryption::decodeId($encrypted_id);
        $data = HsCodes::where('id', $id)->first();
        return view("Settings::hs_codes.edit", compact('data', 'encrypted_id'));
    }

    public function updateHsCode($enc_id, Request $request) {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $id = Encryption::decodeId($enc_id);

        $this->validate($request, [
            'hs_code' => 'required',
        ]);

        HsCodes::where('id', $id)->update([
            'hs_code' => $request->get('hs_code'),
            'product_name' => $request->get('product_name'),
            'is_active' => $request->get('is_active'),
            'updated_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'The HS Code  has been changed successfully.');
        return redirect('/settings/edit-hs-code/' . $enc_id);
    }

    /* End of HS Code related functions */

    /* Start of Industrial Category related functions */

    public function IndusCat()
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            abort('400', 'You have no access right! Please contact system administration for more information.');
        }
        $rows = IndustryCategories::orderBy('industry_categories.name')
            ->leftJoin('colors', 'industry_categories.color_id', '=', 'colors.id')
            ->where('industry_categories.is_archive', 0)
            ->get(['industry_categories.name as indus_cat', 'colors.name as colo', 'industry_categories.id as indus_id', 'industry_categories.is_active']);
        // dd($rows);
        return view("Settings::industrial_category.list", compact('rows'));
    }

    public function createIndusCat()
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            abort('400', 'You have no access right! Please contact system administration for more information.');
        }
        $colors = Colors::where('is_active', 1)->where('is_archive', 0)->orderBy('name')->lists('name', 'id');
        return view("Settings::industrial_category.create", compact('colors'));
    }

//
    public function storeIndusCat(Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            abort('400', 'You have no access right! Please contact system administration for more information.');
        }
        $this->validate($request, [
            'name' => 'required',
            'color_id' => 'required',
        ]);

        $insert = IndustryCategories::create([
            'name' => $request->get('name'),
            'color_id' => $request->get('color_id'),
            'is_active' => 1,
            'created_by' => CommonFunction::getUserId(),
        ]);

        Session::flash('success', 'The industrial category is stored successfully!');
        return redirect('/settings/edit-indus-cat/' . Encryption::encodeId($insert->id));
    }

    public function editIndusCat($encrypted_id)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            abort('400', 'You have no access right! Please contact system administration for more information.');
        }
        $id = Encryption::decodeId($encrypted_id);
        $data = IndustryCategories::where('id', $id)->first();
        $colors = Colors::where('is_active', 1)->where('is_archive', 0)->orderBy('name')->lists('name', 'id');
        return view("Settings::industrial_category.edit", compact('data', 'encrypted_id', 'colors'));
    }

    public function updateIndusCat($enc_id, Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            abort('400', 'You have no access right! Please contact system administration for more information.');
        }
        $id = Encryption::decodeId($enc_id);
        $this->validate($request, [
            'name' => 'required',
            'color_id' => 'required',
        ]);

        IndustryCategories::where('id', $id)->update([
            'name' => $request->get('name'),
            'color_id' => $request->get('color_id'),
            'is_active' => $request->get('is_active'),
            'updated_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'The industrial category  has been changed successfully.');
        return redirect('/settings/edit-indus-cat/' . $enc_id);
    }

    /* End of Industrial Category related functions */


    /* Start of User Desk related functions */

    public function userDesk() {
        return view("Settings::user_desk.list");
    }

    public function createUserDesk() {
        $desks = UserDesk::orderBy('desk_name')->lists('desk_name', 'desk_id');
        return view("Settings::user_desk.create", compact('desks'));
    }

    public function getUserDeskData() {
        $mode = ACL::getAccsessRight('settings', 'E');
        $desk = UserDesk::orderBy('desk_name')
                ->get(['desk_id', 'desk_name', 'desk_status', 'delegate_to_desk']);

        return Datatables::of($desk)
                        ->addColumn('action', function ($desk) use ($mode) {
                            if ($mode) {
                                return '<a href="/settings/edit-user-desk/' . Encryption::encodeId($desk->desk_id) .
                                        '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open-o"></i> Open</a> ';
                            } else {
                                return '';
                            }
                        })
                        ->editColumn('desk_status', function ($desk) {
                            if ($desk->desk_status == 1) {
                                $class = 'text-success';
                                $status = 'Active';
                            } else {
                                $class = 'text-danger';
                                $status = 'Inactive';
                            }
                            return '<span class="' . $class . '"><b>' . $status . '</b></span>';
                        })
                        ->removeColumn('id')
                        ->make(true);
    }

    public function storeUserDesk(Request $request) {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        $this->validate($request, [
            'desk_name' => 'required',
            'desk_status' => 'required',
            'delegate_to_desk' => '',
        ]);

        $insert = UserDesk::create([
                    'desk_name' => $request->get('desk_name'),
                    'desk_status' => $request->get('desk_status'),
                    'delegate_to_desk' => $request->get('delegate_to_desk'),
                    'created_by' => CommonFunction::getUserId(),
        ]);

        Session::flash('success', 'Data is stored successfully!');
        return redirect('/settings/edit-user-desk/' . Encryption::encodeId($insert->id));
    }

    public function editUserDesk($encrypted_id) {
        $id = Encryption::decodeId($encrypted_id);
        $data = UserDesk::where('desk_id', $id)->first();

        $desks = UserDesk::orderBy('desk_name')->lists('desk_name', 'desk_id');

        return view("Settings::user_desk.edit", compact('data', 'encrypted_id', 'desks'));
    }

    public function updateUserDesk($enc_id, Request $request) {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $id = Encryption::decodeId($enc_id);

        $this->validate($request, [
            'desk_name' => 'required',
            'desk_status' => 'required',
            'delegate_to_desk' => '',
        ]);

        UserDesk::where('desk_id', $id)->update([
            'desk_name' => $request->get('desk_name'),
            'desk_status' => $request->get('desk_status'),
            'delegate_to_desk' => $request->get('delegate_to_desk'),
            'updated_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('/settings/edit-user-desk/' . $enc_id);
    }

    /* End of User Desk related functions */

    /* Start of Security related functions */

    public function security() {
        $user_types = UserTypes::lists('type_name', 'id');
        return view("Settings::security.list", compact('user_types'));
    }

    public function getSecurityData() {
        $_data = SecurityProfile::get(['id', 'profile_name', 'allowed_remote_ip', 'week_off_days', 'work_hour_start', 'work_hour_end', 'active_status']);
        return Datatables::of($_data)
                        ->addColumn('action', function ($_data) {
                            if ($_data->id != 1) {
                                return '<a href="/settings/edit-security/' . Encryption::encodeId($_data->id) .
                                        '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open-o"></i> <b>Open<b/></a>';
                            }
                        })
                        ->removeColumn('id')
                        ->make(true);
    }

    public function storeSecurity(Request $request) {
        $this->validate($request, [
            'profile_name' => 'required',
            'allowed_remote_ip' => 'required',
            'week_off_days' => 'required',
        ]);
        SecurityProfile::create(
                array(
                    'profile_name' => $request->get('profile_name'),
//                    'user_type' => $request->get('user_type'),
                    'user_email' => $request->get('user_email'),
                    'allowed_remote_ip' => $request->get('allowed_remote_ip'),
                    'week_off_days' => $request->get('week_off_days'),
                    'work_hour_start' => $request->get('work_hour_start'),
                    'work_hour_end' => $request->get('work_hour_end'),
                    'active_status' => $request->get('active_status'),
                    'created_by' => CommonFunction::getUserId()
        ));

        Session::flash('success', 'Data is stored successfully!');
        return redirect('/settings/security');
    }

    public function editSecurity($_id) {
        $id = Encryption::decodeId($_id);
        $data = SecurityProfile::where('id', $id)->first();
        $user_types = UserTypes::lists('type_name', 'id');
        return view("Settings::security.edit", compact('data', '_id', 'user_types'));
    }

    public function updateSecurity($id, Request $request) {
        $_id = Encryption::decodeId($id);

        $this->validate($request, [
            'profile_name' => 'required',
            'allowed_remote_ip' => 'required',
            'week_off_days' => 'required',
        ]);

        SecurityProfile::where('id', $_id)->update([
            'profile_name' => $request->get('profile_name'),
//            'user_type' => $request->get('user_type'),
            'user_email' => $request->get('user_email'),
            'allowed_remote_ip' => $request->get('allowed_remote_ip'),
            'week_off_days' => $request->get('week_off_days'),
            'work_hour_start' => $request->get('work_hour_start'),
            'work_hour_end' => $request->get('work_hour_end'),
            'active_status' => $request->get('active_status'),
            'updated_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('/settings/security');
    }

    /* End of Security related functions */

    /* Start of Server Inforelated functions */

    public function serverInfo(Request $request) {
        $getList = [
            [
                'caption' => 'Server Status',
                'function' => 'command:apache-status,whoami'
            ],
            [
                'caption' => 'Database',
                'function' => 'command:mysql_stat,show global status,show processlist,show table status,show full processlist'
            ],
            [
                'caption' => 'Process Status',
                'function' => 'command:NID process Status,PDF Gen Status'
            ],
            [
                'caption' => 'env file',
                'function' => "DB Host : " . env('DB_HOST') .
                '<br/>User : ' . env('DB_USERNAME') .
                '<br/>Database : ' . env('DB_DATABASE') .
                '<br/>' .
                '<br/>Mail Driver : ' . env('MAIL_DRIVER') .
                '<br/>Mail Host : ' . env('MAIL_HOST') .
                '<br/>Mail Port : ' . env('MAIL_PORT') .
                '<br/>' .
                '<br/>Recaptcha Public Key : ' . env('RECAPTCHA_PUBLIC_KEY') .
                '<br/>Recaptcha Private Key : ' . env('RECAPTCHA_PRIVATE_KEY')
            ],
            [
                'caption' => 'php Info',
                'function' => 'command:phpinfo'
            ]
        ];
        return view("Settings::server-info.list", compact('getList'));
    }

    public function getCommandResult(Request $request) {
        $command = $request->get('command');
        $output = '';
        $dboutput = '';
        $result = null;
        echo 'Executing command ' . $command . ' at ' . date('h:i:s.u T', time()) . '<br />';
        if (Auth::user()->user_type == '1x101') {
            switch ($command) {
                case 'NID process Status':
                    $result = DB::select(
                                    DB::raw("select 'In last 60 Minutes' as Period, verification_flag status, count(id) as noOfNID from pilgrims_nid where submitted_at > date_add(now(), interval -60 minute) group by Period,verification_flag
                                                union all
                                                select 'Total' as Period, verification_flag status, count(id) as noOfNID from pilgrims_nid group by Period,verification_flag"));
                    $dboutput = count($result);
                    break;
                case 'PDF Gen Status':
                    $result = DB::select(
                                    DB::raw("select 'In last 60 Minutes' as Period, status, pdf_type, count(id) as noOfDoc from pdf_generator where created_at > date_add(now(), interval -60 minute) group by Period, status, pdf_type
                                                union all
                                                select 'Total' as Period, status, pdf_type, count(id) as noOfDoc from pdf_generator  group by Period, status, pdf_type"));
                    $dboutput = count($result);
                    break;
                case 'phpinfo':
                    phpinfo();
                    break;
                case 'apache-status':
                    $output = shell_exec('apachectl status');
                    break;
                case 'whoami':
                    $output = shell_exec('whoami');
                    $output .= '<br />' . $request->ip();
                    break;
                case 'show processlist':
                case 'show table status':
                case 'show full processlist':
                case 'show global status':
                    $result = DB::select(DB::raw($command));
                    $dboutput = count($result);
                    break;
                case 'top':
                    $output = shell_exec('top');
                    break;
                case 'dir':
                    $output = shell_exec('dir');
                    break;
                case 'mysql_stat':
                    $link = mysqli_connect(env('DB_HOST'), env('DB_USERNAME'), env('DB_PASSWORD'));
                    $status = explode('  ', mysqli_stat($link));
                    echo '<pre>';
                    print_r($status);
                    $dboutput = '-1';
                    echo '</pre>';
                    break;
                default:
                    $output = 'command : ' . $command . ' not found!';
                    break;
            }
        } else {
            $output = 'User ' . Auth::user()->user_full_name . ' has no access to execute this command!';
        }
        if ($output) {
            echo "<div><pre>$output</pre></div>";
        } elseif ($dboutput != '') {
            if ($dboutput > 0) {
                echo createHTMLTable($result);
            } elseif ($dboutput < 0) {
                //system table
            } else {
                echo "<pre><strong><em>$command</em></strong> has no result!</pre>";
            }
        } else {
            echo "<pre>command <strong><em>$command</em></strong> has no response text!</pre>";
        }
        echo '<br />Executed on ' . date('h:i:s.u T', time());
        return '';
    }

    public function sendNotificationToUserType(Request $request, $userType) {
        $userTypeDecoded = Encryption::decodeId($userType);
        try {
            if (Auth::user()->user_type == '1x101') {
                $users = User::where('user_type', $userTypeDecoded)->get(['id', 'user_type', 'user_email', 'user_phone']);
                if (isset($users) && count($users) > 0) {
                    foreach ($users as $user) {
                        $smsData['source'] = $request->get('message');
                        $smsData['destination'] = $user->user_phone;
                        $smsData['msg_type'] = 'SMS';
                        $smsData['ref_id'] = $user->id;
                        $smsData['is_sent'] = 0;
                        $smsData['priority'] = $request->get('priority');
                        Notification::create($smsData);
                    }
                }
                Session::flash("success", 'Sent notification.');
            } else {
                Session::flash("error", 'No access right');
            }
            return redirect('settings/edit-user-type/' . $userType);
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Somthing Wrong.');
            return redirect('settings/edit-user-type/' . $userType);
        }
    }

    /* End of Server Inforelated functions */

    /* Start of Ports related functions */

    public function ports() {
        $rows = Ports::orderBy('ports.name')
                ->leftJoin('country_info', 'country_info.iso', '=', 'ports.country_iso')
                ->where('ports.is_archive', 0)
                ->get(['country_info.nicename as country', 'ports.name as port_name', 'ports.id as port_id','ports.is_active']);
        return view("Settings::ports.list", compact('rows'));
    }

    public function createPort() {
        $countries = Countries::orderBy('nicename')->where('country_status', 'Yes')->lists('nicename', 'iso');
        return view("Settings::ports.create", compact('countries'));
    }

    public function storePort(Request $request) {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        $this->validate($request, [
            'country_iso' => 'required',
            'name' => 'required',
        ]);

        $insert = Ports::create([
                    'country_iso' => $request->get('country_iso'),
                    'name' => $request->get('name'),
                    'is_active' => 1,
                    'created_by' => CommonFunction::getUserId(),
        ]);

        Session::flash('success', 'The port is stored successfully!');
        return redirect('/settings/edit-port/' . Encryption::encodeId($insert->id));
    }

    public function editPort($encrypted_id) {
        $id = Encryption::decodeId($encrypted_id);
        $data = Ports::where('id', $id)->first();
        $countries = Countries::orderBy('nicename')->where('country_status', 'Yes')->lists('nicename', 'iso');
        return view("Settings::ports.edit", compact('data', 'countries', 'encrypted_id'));
    }

    public function updatePort($enc_id, Request $request) {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $id = Encryption::decodeId($enc_id);

        $this->validate($request, [
            'country_iso' => 'required',
            'name' => 'required',
        ]);

        Ports::where('id', $id)->update([
            'country_iso' => $request->get('country_iso'),
            'name' => $request->get('name'),
            'is_active' => $request->get('is_active'),
            'updated_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'The port has been changed successfully.');
        return redirect('/settings/edit-port/' . $enc_id);
    }

    /* End of Ports related functions */

    /* Start of Units related functions */

    public function Units() {
        $rows = Units::orderBy('name')->where('is_archive', 0)->get(['id', 'name', 'is_active']);
        return view("Settings::units.list", compact('rows'));
    }

    public function createUnit() {
        $active_status = ['1' => 'Active', '2' => 'Inactive'];
        return view("Settings::units.create", compact('active_status'));
    }

    public function storeUnit(Request $request) {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        $this->validate($request, [
            'name' => 'required',
            'is_active' => 'required',
        ]);

        $insert = Units::create([
                    'name' => $request->get('name'),
                    'is_active' => $request->get('is_active'),
                    'created_by' => CommonFunction::getUserId(),
        ]);

        Session::flash('success', 'The new unit is stored successfully!');
        return redirect('/settings/edit-unit/' . Encryption::encodeId($insert->id));
    }

    public function editUnit($id) {
        $_id = Encryption::decodeId($id);
        $data = Units::where('id', $_id)->first();
        $active_status = ['1' => 'Active', '2' => 'Inactive'];
        return view("Settings::units.edit", compact('data', 'active_status', 'id'));
    }

    public function updateUnit($enc_id, Request $request) {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $id = Encryption::decodeId($enc_id);

        $this->validate($request, [
            'name' => 'required',
            'is_active' => 'required',
        ]);

        Units::where('id', $id)->update([
            'name' => $request->get('name'),
            'is_active' => $request->get('is_active'),
            'updated_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'The unit has been changed successfully.');
        return redirect('/settings/edit-unit/' . $enc_id);
    }

    /* End of Units related functions */
    function softDelete($model, $_id)
    {
        try {
            $id = Encryption::decodeId($_id);

            switch (true) {
                case ($model == "Area"):
                    $cond = Area::where('area_id', $id);
                    $list = 'area-list';
                    break;
                case ($model == "Bank"):
                    $cond = Bank::where('id', $id);
                    $list = 'bank-list';
                    break;
                case ($model == "park-info"):
                    $cond = ParkInfo::where('id', $id);
                    $list = 'park-info';
                    break;
                case ($model == "Branch"):
                    $cond = BankBranch::where('id', $id);
                    $list = 'branch-list';
                    break;
                case ($model == "Currency"):
                    $cond = Currencies::where('id', $id);
                    $list = 'currency';
                    break;
                case ($model == "Document"):
                    $cond = docInfo::where('doc_id', $id);
                    $list = 'document';
                    break;
                case ($model == "EcoZone"):
                    $cond = EconomicZones::where('id', $id);
                    $list = 'eco-zones';
                    break;
                case ($model == "HighCommissions"):
                    $cond = HighComissions::where('id', $id);
                    $list = 'high-commission';
                    break;
                case ($model == "hsCode"):
                    $cond = HsCodes::where('id', $id);
                    $list = 'hs-codes';
                    break;
                case ($model == "IndustryCategories"):
                    $cond = IndustryCategories::where('id', $id);
                    $list = 'indus-cat';
                    break;
                case ($model == "Notice"):
                    $cond = Notice::where('id', $id);
                    $list = 'notice';
                    break;
                case ($model == "Port"):
                    $cond = Ports::where('id', $id);
                    $list = 'ports';
                    break;
                case ($model == "Unit"):
                    $cond = Units::where('id', $id);
                    $list = 'units';
                    break;
                default:
                    Session::flash('error', 'Invalid Model! error code (Del-' . $model . ')');
                    return Redirect::back();
            }

            $cond->update([
                'is_archive' => 1,
                'updated_by' => CommonFunction::getUserId()
            ]);

            Session::flash('success', 'Data has been deleted successfully.');
            return redirect('/settings/' . $list);
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . '[SC0009]');
            return Redirect::back()->withInput();
        }
    }
    /* Start of dashboard Object related functions */

    public function dashboardObj() {
        $getList = Dashboard::orderBy('db_obj_caption')
                ->get(['id', 'db_obj_title', 'db_obj_caption', 'db_obj_type', 'db_user_type']);
        return view("Settings::dashboard_obj.list", compact('getList'));
    }

    public function createDashboardObj() {
        return view("Settings::dashboard_obj.create", compact(''));
    }

    public function storeDashboardObj(Request $request) {
        $this->validate($request, [
            'db_obj_title' => 'required',
            'db_obj_caption' => 'required',
            'db_obj_type' => 'required',
            'db_obj_para1' => 'required',
            'db_user_type' => 'required',
        ]);

        $insert = Dashboard::create(
                        array(
                            'db_obj_title' => $request->get('db_obj_title'),
                            'db_obj_caption' => $request->get('db_obj_caption'),
                            'db_obj_type' => $request->get('db_obj_type'),
                            'db_obj_para1' => $request->get('db_obj_para1'),
                            'db_user_type' => $request->get('db_user_type'),
                            'updated_by' => CommonFunction::getUserId()
        ));

        Session::flash('success', 'Data is stored successfully!');
        return redirect('/settings/edit-dash-obj/' . Encryption::encodeId($insert->id));
    }

    public function editDashboardObj($_id) {
        $id = Encryption::decodeId($_id);
        $data = Dashboard::where('id', $id)->first();
        $user_types = UserTypes::lists('type_name', 'id');
        return view("Settings::dashboard_obj.edit", compact('data', '_id', 'user_types'));
    }

    public function updateDashboardObj($id, Request $request) {
        $_id = Encryption::decodeId($id);
        $this->validate($request, [
            'db_obj_title' => 'required',
            'db_obj_caption' => 'required',
            'db_obj_type' => 'required',
            'db_obj_para1' => 'required',
            'db_user_type' => 'required',
        ]);

        Dashboard::where('id', $_id)->update([
            'db_obj_title' => $request->get('db_obj_title'),
            'db_obj_caption' => $request->get('db_obj_caption'),
            'db_obj_type' => $request->get('db_obj_type'),
            'db_obj_para1' => $request->get('db_obj_para1'),
            'db_user_type' => $request->get('db_user_type'),
            'updated_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('/settings/edit-dash-obj/' . $id);
    }


    public function companyInfo() {
        if (!ACL::getAccsessRight('settings', 'V')) {
            abort('400', 'You have no access right! Please contact system administration for more information.');
        }
        try {
            return view("Settings::company_info.list");
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }
    }

    public function createCompany() {
        if (!ACL::getAccsessRight('settings', 'A')) {
            abort('400', 'You have no access right! Please contact system administration for more information.');
        }
        try {
            $divisions = ['' => 'Select Division '] + AreaInfo::orderby('area_nm')->where('area_type', 1)->lists('area_nm', 'area_id')->all();
            return view("Settings::company_info.create",compact('divisions'));
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }
    }

    public function storeCompany(request $request) {
        if (!ACL::getAccsessRight('settings', 'A')) {
            abort('400', 'You have no access right! Please contact system administration for more information.');
        }
        $this->validate($request, [
            'company_name' => 'required|unique:company_info',
            'division' => 'required',
            'district' => 'required',
            'thana' => 'required'
        ]);
        try {
            $companyData=new CompanyInfo();
            $companyData->company_name=$request->get('company_name');
            $companyData->division = $request->get('division');
            $companyData->district = $request->get('district');
            $companyData->thana = $request->get('thana');
            $companyData->save();
            Session::flash('success', 'Data is stored successfully!');
            return redirect('/settings/company-info-action/' . Encryption::encodeId($companyData->id));
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }
    }

    public function getCompanyData() {

        try {
            $companyInformation = CompanyInfo::leftJoin('area_info as ai', 'ai.area_id', '=', 'company_info.division')
                ->leftJoin('area_info as di', 'di.area_id', '=', 'company_info.thana')
                ->leftJoin('users as user', 'user.id', '=', 'company_info.created_by')
                ->select('company_info.id','company_info.created_at','company_info.is_approved', 'company_info.company_status', DB::raw('CONCAT(company_name, ", ", ai.area_nm,", ", di.area_nm) AS company_name'))
                ->orderBy('company_info.created_at', 'desc');

            $mode = ACL::getAccsessRight('settings', 'V');

            return Datatables::of($companyInformation)
                ->editColumn('is_approved', function ($companyInformation) {
                    if ($companyInformation->is_approved == 1) {
                        $status = "Approved";
                        $class = "text-success";
                    } else if($companyInformation->is_approved == 6) {
                        $status = "Rejected";
                        $class = "text-danger";
                    } else  {
                        $status = "Not Approved";
                        $class = "text-warning";
                    }
                    return '<span class="' . $class . '">' . $status . '</span>';
                })
                ->editColumn('created_at', function ($companyInformation) {
                    return date('d-M-Y', strtotime($companyInformation->created_at));
                })
//                ->editColumn('updated_at', function ($companyInformation) {
//                    return date('d-M-Y h:i:s a', strtotime($companyInformation->updated_at));
//                })
                ->addColumn('action', function ($companyInformation) use ($mode) {
                    if ($mode) {
                        $html = '<a href="' . URL::to('settings/company-info-action/' . Encryption::encodeId($companyInformation->id)) .
                            '" class="btn btn-primary btn-xs">Open</a> ';
                        if($companyInformation->is_approved==1) {
                            if ($companyInformation->company_status == 0) { // status 0 = inactive
                                $html .= '<a href="' . URL::to('settings/company-change-status/' . Encryption::encodeId($companyInformation->id) . '/' .
                                        Encryption::encodeId(1)) . ' " class="btn btn-success btn-xs"onclick="return confirm(\'Are you sure you want to activate?\')" title="Please click to Activate">Activate</a> ';
                            } else { // status 1 = active
                                $html .= '<a href="' . URL::to('settings/company-change-status/' . Encryption::encodeId($companyInformation->id) . '/' .
                                        Encryption::encodeId(0)) . '"class="btn btn-danger btn-xs"onclick="return confirm(\'Are you sure you want to deactivate?\')" title="Please click to deactivate">Deactivate</a> ';
                            }
                        }
                        return $html;
                    } else {
                        return '';
                    }
                })
                ->make(true);
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }
    }

    public function companyInfoAction($id) {
        try {
            $company_id = Encryption::decodeId($id);

            $companyDetails = CompanyInfo::leftJoin('area_info as ai', 'ai.area_id', '=', 'company_info.division')
                ->leftJoin('area_info as di', 'di.area_id', '=', 'company_info.thana')
                ->leftJoin('users as user', 'user.id', '=', 'company_info.created_by')
                ->select('company_info.*','user.user_full_name', DB::raw('CONCAT(company_name, ", ", ai.area_nm,", ", di.area_nm) AS company_info'))
                ->where('company_info.id', $company_id)
                ->first();



//            $companyDetails = CompanyInfo::leftJoin('users as user', 'user.id', '=', 'company_info.created_by')
//                ->where('company_info.id', $company_id)
//                ->first(['company_info.*', 'user.user_full_name']);
            return view("Settings::company_info/edit", compact('companyDetails'));
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }
    }

    public function companyApprovedStatus($id) {
        try {
            $company_id = Encryption::decodeId($id);

            $companyData=CompanyInfo::find($company_id);
            $companyData->is_approved=1;
            $companyData->company_status=1;
            $companyData->save();

            Session::flash('success', 'Company Status Changed Successfully');
            return redirect('/settings/company-info');
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }
    }
    public function companyRejectedStatus($id) {
        try {
            $company_id = Encryption::decodeId($id);
            $companyData=CompanyInfo::find($company_id);
            $companyData->is_approved=6;
            $companyData->save();
            Session::flash('success', 'Company has ben rejected');
            return redirect('/settings/company-info');
        } catch (Exception $e) {
            dd(1);
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }
    }



    public function companyChangeStatus($id, $status_id) {
        try {
            $company_id = Encryption::decodeId($id);
            $status = Encryption::decodeId($status_id);

            $companyData=CompanyInfo::find($company_id);
            $companyData->company_status=$status;
            $companyData->save();

            Session::flash('success', 'Company Status Changed Successfully');
            return redirect()->back();
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }
    }

    /* End of dashboard Object related functions */

    public function HomePageSlider() {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        return view("Settings::home_page_slider.list");
    }

    public function HomePageSliderCreate()
    {
        return view('Settings::home_page_slider.create');
    }

    public function homePageSliderStore(Request $request) {


        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $this->validate($request, [
            'status' => 'required'
        ]);
        try {

            $image = $request->file('slider_image');
            $path = "uploads/sliderImage";
            if ($request->hasFile('slider_image')) {
                $img_file = $image->getClientOriginalName();
                $mime_type = $image->getClientMimeType();
                if ($mime_type == 'image/jpeg' || $mime_type == 'image/jpg' || $mime_type == 'image/png') {
                    $image->move($path, $img_file);
                    $filepath = $path . '/' . $img_file;
                } else {
                    \Session::flash('error', 'Image must be png or jpg or jpeg format');
                    return redirect()->back();

                }
            }
            $insert = HomePageSlider::create(
                array(
                    'slider_title' => $request->get('slider_title'),
                    'slider_url' => $request->get('slider_url'),
                    'slider_type' => $request->get('slider_type'),
                    'status' => $request->get('status'),
                    'slider_image' => $filepath,
                    'created_by' => CommonFunction::getUserId()
                ));

            Session::flash('success', 'Data is stored successfully!');
            return \redirect()->back();
//            return redirect('/settings/edit-notice/' . Encryption::encodeId($insert->id));
        } catch (\Exception $e) {
            dd($e->getMessage());
            Session::flash('error', 'Sorry! Something Wrong.');
            return Redirect::back()->withInput();
        }
    }

    public function editHomePageSlider($encrypted_id)
    {
        $id = Encryption::decodeId($encrypted_id);
        $data = HomePageSlider::where('id', $id)->first();
        return view("Settings::home_page_slider.edit", compact('data', 'encrypted_id'));
    }

    public function updateHomePageSlider(Request $request, $id)
    {

        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $id = Encryption::decodeId($id);

        $this->validate($request, [
            'status' => 'required'
        ]);

        $image = $request->file('slider_image');
        $path = "uploads/sliderImage";

        if ($request->hasFile('slider_image')) {
            $img_file = $image->getClientOriginalName();
            $mime_type = $image->getClientMimeType();
            if ($mime_type == 'image/jpeg' || $mime_type == 'image/jpg' || $mime_type == 'image/png') {
                $image->move($path, $img_file);
                $filepath = $path . '/' . $img_file;
            } else {
                \Session::flash('error', 'Image must be png or jpg or jpeg format');
                return redirect()->back();

            }
        }

        if(isset($filepath)){
        }
        else{
            $filepath = $request->get('exist_slider_image');
        }
        HomePageSlider::where('id', $id)->update([
            'slider_title' => $request->get('slider_title'),
            'slider_url' => $request->get('slider_url'),
            'status' => $request->get('status'),
            'slider_type' => $request->get('slider_type'),
            'slider_image' => $filepath,
            'created_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('/settings/home-page-slider');
    }

    public function getHomePageSlider()
    {
        $mode = ACL::getAccsessRight('settings', 'V');
        $datas = HomePageSlider::orderBy('id', 'desc')
            ->get();
        return Datatables::of($datas)
            ->addColumn('action', function ($datas) use ($mode) {
                if ($mode) {
                    return '<a href="/settings/edit-home-page-slider/' . Encryption::encodeId($datas->id) .
                        '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open-o"></i> Open</a>';
                }
            })
            ->editColumn('slider_image', function ($datas) {
                $title = $datas->slider_image;
                // return '<img src="'.$datas->image.'"';
                return "<img src='/$datas->slider_image' alt='image missing' style='width: 95%; height: 80px;'>";
                // return "ok";
            })
            ->editColumn('status', function ($datas) {
                if ($datas->status == 1) {
                    $class = 'label label-success';
                    $status = 'Active';
                } else {
                    $class = 'label label-danger';
                    $status = 'Inactive';
                }
                return '<span class="' . $class . '"><b>' . $status . '</b></span>';
            })
//            ->rawColumns(['status','slider_image','action'])
            ->make(true);
    }


    public function whatsNew() {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        return view("Settings::whats_new.list");
    }

    public function whatsNewCreate()
    {
        return view('Settings::whats_new.create');
    }

    public function whatsNewStore(Request $request) {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'is_active' => 'required'
        ]);
        try {

            $image = $request->file('image');
            $path = "uploads/logo";
            if ($request->hasFile('image')) {
                $img_file = $image->getClientOriginalName();
                $mime_type = $image->getClientMimeType();
                if ($mime_type == 'image/jpeg' || $mime_type == 'image/jpg' || $mime_type == 'image/png') {
                    $image->move($path, $img_file);
                    $filepath = $path . '/' . $img_file;
                } else {
                    \Session::flash('error', 'Image must be png or jpg or jpeg format');
                    return redirect()->back();

                }
            }
            $insert = WhatsNew::create(
                array(
                    'title' => $request->get('title'),
                    'description' => $request->get('description'),
                    'is_active' => $request->get('is_active'),
                    'image' => $filepath,
                    'created_by' => CommonFunction::getUserId()
                ));

            Session::flash('success', 'Data is stored successfully!');
            return \redirect()->back();
//            return redirect('/settings/edit-notice/' . Encryption::encodeId($insert->id));
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something Wrong.');
            return Redirect::back()->withInput();
        }
    }

    public function getWhatsNew()
    {
        $mode = ACL::getAccsessRight('settings', 'V');
        $datas = WhatsNew::orderBy('id', 'desc')
            ->get();
        return Datatables::of($datas)
            ->addColumn('action', function ($datas) use ($mode) {
                if ($mode) {
                    return '<a href="/settings/edit-whats-new/' . Encryption::encodeId($datas->id) .
                        '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open-o"></i> Open</a>';
                }
            })
            ->editColumn('image', function ($datas) {
                return "<img src='/$datas->image' style='width: 95%'>";
            })
            ->editColumn('is_active', function ($datas) {
                if ($datas->is_active == 1) {
                    $class = 'text-success';
                    $status = 'Active';
                } else {
                    $class = 'text-danger';
                    $status = 'Inactive';
                }
                return '<span class="' . $class . '"><b>' . $status . '</b></span>';
            })
//            ->removeColumn('doc_id')
//            ->rawColumns(['is_active','action','image','description'])
            ->make(true);
    }

    public function editWhatsNew($encrypted_id)
    {
        $id = Encryption::decodeId($encrypted_id);
        $data = WhatsNew::where('id', $id)->first();
        return view("Settings::whats_new.edit", compact('data', 'encrypted_id'));
    }

    public function updateWhatsNew(Request $request, $id)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $id = Encryption::decodeId($id);

        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'is_active' => 'required'
        ]);

        $image = $request->file('image');
        $path = "uploads/logo";

        if ($request->hasFile('image')) {
            $img_file = $image->getClientOriginalName();
            $mime_type = $image->getClientMimeType();
            if ($mime_type == 'image/jpeg' || $mime_type == 'image/jpg' || $mime_type == 'image/png') {
                $image->move($path, $img_file);
                $filepath = $path . '/' . $img_file;
            } else {
                \Session::flash('error', 'Image must be png or jpg or jpeg format');
                return redirect()->back();

            }
        }

        if(isset($filepath)){
        }
        else{
            $filepath = $request->get('exist_image');
        }

        WhatsNew::where('id', $id)->update([
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'is_active' => $request->get('is_active'),
            'image' => $filepath,
            'created_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('/settings/whats-new');
    }

    /*     * ****************************** End of Users Controller Class ********************************** */
}
