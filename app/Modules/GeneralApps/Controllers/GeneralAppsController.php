<?php

namespace App\Modules\GeneralApps\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Modules\Apps\Models\AppDocuments;
use App\Modules\apps\Models\Colors;
use App\Modules\Apps\Models\DocInfo;
use App\Modules\Apps\Models\EmailQueue;
use App\Modules\Apps\Models\IndustryCategories;
use App\Modules\Apps\Models\pdfQueue;
use App\Modules\Apps\Models\ProcessListHist;
use App\Modules\GeneralApps\Models\GeneralAppsMaster;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessStatus;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\settings\Models\PdfServerInfo;
use App\Modules\Settings\Models\Bank;
use App\Modules\Settings\Models\Configuration;
use App\Modules\Settings\Models\Currencies;
use App\Modules\Settings\Models\PdfPrintRequest;
use App\Modules\Settings\Models\ServiceDetails;
use App\Modules\Settings\Models\Units;
use App\Modules\SpaceAllocation\Models\BusinessService;
use App\Modules\SpaceAllocation\Models\IndustryType;
use App\Modules\SpaceAllocation\Models\OrganizationType;
use App\Modules\Users\Models\AreaInfo;
use App\Modules\Users\Models\CompanyInfo;
use App\Modules\Users\Models\Countries;
use App\Modules\Users\Models\EconomicZones;
use App\Modules\Users\Models\ParkInfo;
use App\Modules\Users\Models\Users;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Mockery\CountValidator\Exception;
use mPDF;
use TinyAda\RSA\RSA;
use yajra\Datatables\Datatables;

class GeneralAppsController extends Controller
{

    protected $process_type_id;

    public function __construct()
    {
        if (Session::has('lang'))
            App::setLocale(Session::get('lang'));
        $this->process_type_id = 2; // 2 is general Appsn process type
    }


    /*
     * Show application form
     */
//    public function applicationForm()
//    {
//
//        if (!ACL::getAccsessRight('generalApps', '-A-')) {
//            abort('400', 'You have no access right! Contact with system admin for more information.');
//        }
//        try {
//            $data['typeofIndustry'] = IndustryType::where('is_archive', 0)->orderBy('name')->lists('name', 'id');
//            $data['countries'] = Countries::where('country_status', 'Yes')->orderBy('name', 'asc')->lists('name', 'iso')->all();
//            $data['districtList'] = AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
//            $data['viewMode'] = 'off';
//            $data['mode'] = 'A';
//            return view("GeneralApps::application-form", $data);
//        } catch (Exception $e) {
//            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
//            return Redirect::back()->withInput();
//        }
//    }
    public function applicationForm()
    {

        if (!ACL::getAccsessRight('generalApps', '-A-')) {
            abort('400', 'You have no access right! Contact with system admin for more information.');
        }
        try {
            $data['dynamicSection'] = ServiceDetails::where('status',1)->orderby('id','DESC')->first();
            $data['document'] = DocInfo::where('process_type_id', $this->process_type_id)->where('is_archive', 0)->orderBy('order')->get();
            $data['typeofIndustry'] = IndustryType::where('is_archive', 0)->orderBy('name')->lists('name', 'id');
            $data['countries'] = Countries::where('country_status', 'Yes')->orderBy('name', 'asc')->lists('name', 'iso')->all();
            $data['districtList'] = AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $data['viewMode'] = 'off';
            $data['mode'] = 'A';
            return view("GeneralApps::application-form", $data);
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }

    }



    /*
     * Application view and edit
     */
    public function applicationViewEdit($applicationId)
    {
        $applicationId = Encryption::decodeId($applicationId);
        $process_type_id = $this->process_type_id;
        $user_type = Auth::user()->user_type;
        $processData = ProcessList::where([
            'ref_id' => $applicationId,
            'process_type_id' => $process_type_id,
        ])->first(['status_id', 'created_by','company_id','tracking_no','desk_id']);

        if (in_array($user_type,['5x505'])) {
            $company_id = CommonFunction::getUserSubTypeWithZero();
            if ($processData->company_id == $company_id && in_array($processData->status_id, [-1, 5, 6])) {
                $openMode='edit';
            }else{
                $openMode='view';
            }
        } else{
            $openMode='view';
        }
        try {
           $dynamicSection = ServiceDetails::where('status',1)->orderby('id','DESC')->first();
            $document = DocInfo::where('process_type_id', $this->process_type_id)->where('is_archive', 0)->orderBy('order')->get();
            $typeofIndustry = IndustryType::where('is_archive', 0)->orderBy('name')->lists('name', 'id');
            $countries = Countries::where('country_status', 'Yes')->orderBy('name', 'asc')->lists('name', 'iso')->all();
            $districtList = AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $policeStation = AreaInfo::where('area_type', 3)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $process_type_id = $this->process_type_id;

            $application = ProcessList::leftJoin('ga_master as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                //->leftJoin('park_info as pi', 'pi.id', '=', 'process_list.park_id')
                ->where('process_list.ref_id', $applicationId)
                ->where('process_list.process_type_id', $process_type_id)
                ->first([
                    'process_list.id as process_list_id',
                    'process_list.desk_id',
                    'process_list.status_id',
                    'process_list.locked_by',
                    'process_list.locked_at',
                    'process_list.ref_id',
                    'process_list.tracking_no',
                    'user_desk.desk_name',
                    'ps.status_name',
                    'ps.color',
                    // 'pi.id as parkId',
                    'apps.*',
                ]);

            // Lock application by current user
            $userDeskIds = CommonFunction::getUserDeskIds();
            if (in_array($application->status_id, [1,2,3,4,5,6]) && in_array($application->desk_id, $userDeskIds)) {
                ProcessList::where('ref_id', $applicationId)->update([
                    'locked_by' => Auth::user()->id,
                    'locked_at' => date('Y-m-d H:i:s')
                ]);
            }

            /* This is for get p2_documents table document data */
            $clrDocuments = array();
            $clr_document = AppDocuments::where('process_type_id', $this->process_type_id)->where('ref_id', $applicationId)->get();

            foreach ($clr_document as $documents) {
                $clrDocuments[$documents->doc_info_id]['doucument_id'] = $documents->id;
                $clrDocuments[$documents->doc_info_id]['file'] = $documents->doc_file_path;
                $clrDocuments[$documents->doc_info_id]['doc_name'] = $documents->doc_name;
            }
            $data['clrDocuments'] = $clrDocuments;

            $process_history = DB::select(DB::raw("select  `process_list_hist`.`desk_id`,`as`.`status_name`,
                                `process_list_hist`.`process_id`,                           
                                if(`process_list_hist`.`desk_id`=0,\"-\",`ud`.`desk_name`) `deskname`,
                                `users`.`user_full_name`, 
                                `process_list_hist`.`updated_by`, 
                                `process_list_hist`.`status_id`, 
                                `process_list_hist`.`process_desc`, 
                                `process_list_hist`.`process_id`, 
                                `process_list_hist`.`updated_at`,
                                 group_concat(`pd`.`file`) as files
                                
                    
                                from `process_list_hist`
                                left join `process_documents` as `pd` on `process_list_hist`.`id` = `pd`.`process_hist_id`
                                left join `user_desk` as `ud` on `process_list_hist`.`desk_id` = `ud`.`id`
                                left join `users` on `process_list_hist`.`updated_by` = `users`.`id`     
                                
                                left join `process_status` as `as` on `process_list_hist`.`status_id` = `as`.`id`
                                and `process_list_hist`.`process_type` = `as`.`process_type_id`
                                where `process_list_hist`.`process_id`  = '$application->process_list_id'
                                and `process_list_hist`.`process_type` = '$this->process_type_id' 
                               
                               
                                and `process_list_hist`.`status_id` != -1
                    group by `process_list_hist`.`process_id`,`process_list_hist`.`desk_id`, `process_list_hist`.`status_id`, process_list_hist.updated_at
                    order by process_list_hist.updated_at desc

                    "));

            $banks = Bank::where('is_archive', 0)->where('is_active', 1)->orderBy('name')->lists('name', 'id');
            $challanReg = Configuration::where('caption', 'CHALLAN_REG')->first(['value', 'value2']);

            $appInfo = ProcessList::leftJoin('ga_master as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                //->leftJoin('park_info as pi', 'pi.id', '=', 'process_list.park_id')
                ->where('process_list.ref_id', $applicationId)
                ->where('process_list.process_type_id', $process_type_id)
                ->first([
                    'process_list.id as process_list_id',
                    'process_list.desk_id',
                    'process_list.park_id',
                    'process_list.process_type_id',
                    'process_list.status_id',
                    'process_list.locked_by',
                    'process_list.locked_at',
                    'process_list.ref_id',
                    'process_list.tracking_no',
                    'process_list.company_id',
                    'process_list.priority',
                    'process_list.process_desc',
                    'user_desk.desk_name',
                    'ps.status_name',
                    'ps.color',
                    'apps.*'
                ]);

            $hasDeskParkWisePermission=CommonFunction::hasDeskParkWisePermission($appInfo->desk_id,$appInfo->park_id);

            $verificationData = ProcessList::where('process_list.ref_id', $applicationId)
                ->where('process_list.process_type_id', $this->process_type_id)
                ->first([
                    'process_type_id',
                    'id as process_list_id',
                    'status_id',
                    'ref_id',
                    'id',
                    'json_object',
                    'desk_id',
                    'updated_at'
                ]);

            if ($openMode == 'view') {
                $viewMode = 'on';
                $mode = '-V-';
            } else if ($openMode == 'edit') {
                $mode = '-E-';
                $viewMode = 'off';
            } else {
                $mode = 'SecurityBreak';
                $viewMode = 'SecurityBreak';
            }
            $statusArray = ProcessStatus::where('process_type_id', $this->process_type_id)->lists('status_name', 'id');
            $getStatus= ProcessType::where('id',$this->process_type_id)->first()->way_to_success;
            $statusName = ProcessStatus::whereIn('id',explode(',', $getStatus))->where('process_type_id', $this->process_type_id)->get(['status_name','id']);


            $sql = "SELECT APS.id, APS.status_name
                        FROM process_status APS
                        WHERE find_in_set(APS.id,
                        (SELECT GROUP_CONCAT(status_to) FROM process_path APP
                        WHERE APP.status_from = '$processData->status_id' AND APP.desk_from = '$processData->desk_id'  AND APP.process_type_id = '$process_type_id'))
                        AND APS.process_type_id = '$process_type_id'
                        order by APS.id DESC limit 1";
            $nextStatus = \DB::select(DB::raw($sql));


            return view('GeneralApps::application-form-edit',
                compact('industryCatInfo', 'agencyInfo', 'countries', 'divition_eng',
                    'businessIndustryServices', 'typeofOrganizations','statusArray','userDeskIds',
                    'industry_cat', 'units', 'typeofIndustry', 'jointOrganizations', 'proposedBusinessInfo',
                    'proposedBusinessPlan', 'existingEmployee', 'nextEmployee', 'foreignPartner', 'tradeBody',
                    'clrDocuments', 'verificationData', 'mode', 'nationality', 'document', 'districtList',
                    'parkInfo', 'appInfo', 'AgencyDirectorDetailsList', 'agencyEmployeeList', 'reg_app_id',
                    'viewMode', 'banks', 'challanReg', 'process_history', 'hasDeskParkWisePermission','policeStation','statusName','nextStatus','dynamicSection'));
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [RC-1040]');
            return Redirect::back()->withInput();
        }
    }



    /*
     * File Upload Ajax File include
     */
    public function uploadDocument() {
        return View::make('GeneralApps::ajaxUploadFile');
    }

    /*
    * Show space allocation Form Preview page
    */
    public function preview()
    {
//        if (!ACL::getAccsessRight('spaceAllocation', '-V-')) {
//            abort(401, 'No access right!');
//        }
        return view("GeneralApps::preview");
    }



    /*
     * Application Store function
     */
    public function appStore(Request $request)
    {
        if ($request->get('app_id')) {
            if (!ACL::getAccsessRight('generalApps', '-E-', $request->get('app_id')))
                abort('400', 'You have no access right! Contact with system admin for more information!');
        } else {
            if (!ACL::getAccsessRight('generalApps', '-A-'))
                abort(401, 'You have no access right! Contact with system admin for more information!');
        }

        $rules = [
            'company_name' => 'required',
            'date_of_submission' => 'required',
            'date_of_approval' => 'required',
            //'tracking_id' => 'required',
            'company_reg_no' => 'required',
            'acceptTerms' => 'required',
            'office_district' => 'required',
            'officce_police_station' => 'required',
            'office_post_office' => 'required',
            'office_post_code' => 'required',
            'office_house_flat_road' => 'required',
            'office_mobile' => 'required',
            'office_email' => 'required',
            'factory_district' => 'required',
            'factory_police_statuion' => 'required',
            'factory_post_office' => 'required',
            'factory_post_code' => 'required',
            'factory_mouza_no' => 'required',
            'factory_house_flat_road' => 'required',
            'factory_mobile' => 'required',
            'factory_email' => 'required',
            'chairman_name' => 'required',
            'chairman_designation' => 'required',
            'chairman_country' => 'required',
            'chairman_house_flat_road' => 'required',
            'chairman_mobile' => 'required',
            'chairman_email' => 'required',
            'industry_type' => 'required',
            'local_executive' => 'required',
            'local_supporting_staff' => 'required',
            'local_total' => 'required',
            'foreign_executive' => 'required',
            'foreign_supporting_staff' => 'required',
            'foreign_total' => 'required',
            'ratio_local' => 'required',
            'ratio_foreign' => 'required',
        ];

        // Validate company logo
        if ($request->get('actionBtn') == 'save') {
//            $this->validate($request, $rules);
        }

        try {

            DB::beginTransaction();
            $companyId = CommonFunction::getUserSubTypeWithZero();
            // Check existing application
            //$statusArr = array(5, 8, 22, 25, '-1'); //5 is shortfall, 8 is Discard, 22 is Rejected Application, 25 is completed and -1 is draft
            $statusArr = array(5, 8, 22, '-1'); //5 is shortfall, 8 is Discard, 22 is Rejected Application and -1 is draft
//            $alreadyExistApplicant = ProcessList::leftJoin('space_allocation as spacAlloc', 'spacAlloc.id', '=', 'process_list.ref_id')
//                ->where('process_list.process_type_id', $process_type_id)
//                ->where('process_list.company_id', $companyId)
//                ->whereNotIn('process_list.status_id', $statusArr)
//                ->first();

            $data = $request->all();
            //dd($request->all());
            if ($request->get('app_id')) {
                $decodedId = Encryption::decodeId($data['app_id']);
                $appData = GeneralAppsMaster::find($decodedId);
                $processData = ProcessList::firstOrNew(['company_id' => $companyId, 'process_type_id' => $this->process_type_id, 'ref_id' => $appData->id,]);
            } else {
                $appData = new GeneralAppsMaster();
                $appData->company_name = '';
                $processData = new ProcessList();
                $processData->company_id = $companyId;
                $processData->created_by = $appData->created_by;
            }

            $appData->company_name = $data['company_name'];
            $appData->date_of_submission = date('Y-m-d', strtotime($data['date_of_submission']));
            $appData->date_of_approval = date('Y-m-d', strtotime($data['date_of_approval']));
            //$appData->tracking_id = $data['tracking_id'];
            $appData->company_reg_no = $data['company_reg_no'];
            $appData->office_district = $data['office_district'];
            $appData->officce_police_station = $data['officce_police_station'];
            $appData->office_post_office = $data['office_post_office'];
            $appData->office_post_code = $data['office_post_code'];
            $appData->office_house_flat_road = $data['office_house_flat_road'];
            $appData->office_telephone = $data['office_telephone'];
            $appData->office_mobile = $data['office_mobile'];
            $appData->office_fax = $data['office_fax'];
            $appData->office_email = $data['office_email'];

            $appData->factory_district = $data['factory_district'];
            $appData->factory_police_statuion = $data['factory_police_statuion'];
            $appData->factory_post_office = $data['factory_post_office'];
            $appData->factory_post_code = $data['factory_post_code'];
            $appData->factory_mouza_no = $data['factory_mouza_no'];
            $appData->factory_house_flat_road = $data['factory_house_flat_road'];
            $appData->factory_telephone = $data['factory_telephone'];
            $appData->factory_mobile = $data['factory_mobile'];
            $appData->factory_fax = $data['factory_fax'];
            $appData->factory_email = $data['factory_email'];

            $appData->chairman_name = $data['chairman_name'];
            $appData->chairman_designation = $data['chairman_designation'];
            $appData->chairman_country = $data['chairman_country'];
            $appData->chairman_district = $data['chairman_district'];
            $appData->chairman_state = $data['chairman_state'];
            $appData->chairman_police_station = $data['chairman_police_station'];
            $appData->chairman_province = $data['chairman_province'];
            $appData->chairman_post_code = $data['chairman_post_code'];
            $appData->chairman_house_flat_road = $data['chairman_house_flat_road'];
            $appData->chairman_telephone = $data['chairman_telephone'];
            $appData->chairman_telephone = $data['chairman_telephone'];
            $appData->chairman_telephone = $data['chairman_telephone'];
            $appData->chairman_mobile = $data['chairman_mobile'];
            $appData->chairman_fax = $data['chairman_fax'];
            $appData->chairman_email = $data['chairman_email'];

            $appData->industry_type = $data['industry_type'];
            $appData->local_executive = $data['local_executive'];
            $appData->local_supporting_staff = $data['local_supporting_staff'];
            $appData->local_total = $data['local_total'];
            $appData->foreign_executive = $data['foreign_executive'];
            $appData->foreign_supporting_staff = $data['foreign_supporting_staff'];
            $appData->foreign_total = $data['foreign_total'];
            $appData->ratio_local = $data['ratio_local'];
            $appData->ratio_foreign = $data['ratio_foreign'];

            $appData->electricity = $data['electricity'];
            $appData->gas = $data['gas'];
            $appData->telephone = $data['telephone'];
            $appData->road = $data['road'];
            $appData->water = $data['water'];
            $appData->drainage = $data['drainage'];
            $appData->tin_no = $data['tin_no'];
            $appData->acceptTerms = (!empty($data['acceptTerms']) ? 1 : 0);

            if ($request->get('license_certificate') != "") {
                $appData->license_certificate = $request->get('license_certificate');
            }
            if ($request->get('memorandum_articles') != "") {
                $appData->memorandum_articles = $request->get('memorandum_articles');
            }
            if ($request->get('joint_agreement') != "") {
                $appData->joint_agreement = $request->get('joint_agreement');
            }
            if ($request->get('shareholder_list') != "") {
                $appData->shareholder_list = $request->get('shareholder_list');
            }
            if ($request->get('trade_license') != "") {
                $appData->trade_license = $request->get('trade_license');
            }
            if ($request->get('tin_certificate') != "") {
                $appData->tin_certificate = $request->get('tin_certificate');
            }
            if ($request->get('project_profile') != "") {
                $appData->project_profile = $request->get('project_profile');
            }
            $appData->authorized_name = $data['authorized_name'];
            $appData->authorized_address = $data['authorized_address'];
            $appData->authorized_email = $data['authorized_email'];
            $appData->authorized_mobile = $data['authorized_mobile'];
            if ($request->get('letter_of_authorization') != "") {
                $appData->letter_of_authorization = $request->get('letter_of_authorization');
            }


            // Incorporation Certificate Upload
            if ($request->get('incorporation_certificate')) {
                $file = $request->file('incorporation_certificate');
                $size = $file->getSize();
                $extension = $file->getClientOriginalExtension();
                $valid_formats = array("pdf");
                if (in_array($extension, $valid_formats)) {
                    if ($size < (1024 * 1024 * 3)) { // 3 MB maximum size
                        $original_file = $file->getClientOriginalName();
                        $file->move('uploads/', time() . $original_file);
                        $fileName = 'uploads/' . time() . $original_file;

                        $appData->incorporation_certificate = $fileName;
                    } else {
                        Session::flash('error', "File size must be less than 3 megabyte");
                        return redirect()->back();
                    }
                } else {
                    Session::flash('error', "File format is not valid! Please upload a pdf file");
                    return redirect()->back();
                }
            }


            if ($request->get('actionBtn') == "draft" && $appData->status_id != 2) {
                $processData->status_id = -1;
                $processData->desk_id = 0;
            } else {

                if ($processData->status_id == 5) { // For shortfall
                    $processData->status_id = 2;
                } else {
                    $processData->status_id = 1;
                }
                $appData->application_date = Carbon::now(); // application Date
                $processData->desk_id = 1; // 1 is desk RD1
            }
            $appData->save();

            $processData->ref_id = $appData->id;
            $processData->process_type_id = $this->process_type_id;

            /*
             * Need to get park id for process list
             * it is default now
             */
            $processData->park_id = 2;

            $jsonData['Applicant Name']=$request->get('applicant_name');
            $jsonData['company name']=$request->get('company_name');
            /*
             * need to get park id for park name
             */
            //$jsonData['Park name'] =CommonFunction::getParkNameById($data['park_id']);
            $jsonData['Park name'] =CommonFunction::getParkNameById(2);
            $jsonData['Industry Category']=CommonFunction::getIndustryCatNameById($data['industry_type']);
            $jsonData['Email']=$request->get('email');
            $jsonData['Phone']=$request->get('phone_no');
            $processData['json_object'] = json_encode($jsonData);
            $processData->save();

            // Generate Tracking No for Submitted application
            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2) { // when application submitted but not as re-submitted
                $trackingPrefix = "SA" . date("dmY");
                $processTypeId = $this->process_type_id;
                $updateTrackingNo = DB::statement("update  process_list, process_list as table2  SET process_list.tracking_no=(
                                                            select concat('$trackingPrefix',
                                                                    LPAD( IFNULL(MAX(SUBSTR(table2.tracking_no,-4,4) )+1,0),4,'0')
                                                                          ) as tracking_no
                                                             from (select * from process_list ) as table2
                                                             where table2.process_type_id ='$processTypeId' and table2.id!='$processData->id' and table2.tracking_no like '$trackingPrefix%'
                                                        )
                                                      where process_list.id='$processData->id' and table2.id='$processData->id'");

//                $processData =  ProcessList::where('id', $processData->id)->first();
//
//
//                $id = $processData->id;
//                $ref_id = $processData->ref_id;
//                $trackingNo = $processData->tracking_no;
//                $desk_id = $processData->desk_id;
//                $processTypeId = $processData->process_type_id;
//                $status_id = $processData->status_id;
//                $on_behalf_of_user = $processData->on_behalf_of_user;
//                $process_desc = $processData->process_desc;
//                $closed_by = $processData->closed_by;
//                $locked_at = $processData->locked_at;
//                $locked_by = $processData->locked_by;
//
//                $result = $id . ', ' .$ref_id . ', ' .$trackingNo. ', ' . $desk_id. ', ' .$processTypeId .','. $status_id. ', '
//                    . $on_behalf_of_user. ', ' . $process_desc. ', ' . $closed_by. ', ' . $locked_at. ', ' . $locked_by;
//                $hashData = RSA::encrypt($result);
//                $previousHash = ProcessList::orderby('id','DESC')->skip(1)->first()->hash_value;
//                ProcessList::where('id', $id)->update(['hash_value' => $hashData, 'previous_hash'=> $previousHash]);

            }

            //  Required Documents for attachment
            $doc_row = DocInfo::where('process_type_id', $this->process_type_id)// 1 is service id for Space Allocation
            ->get(['doc_id', 'doc_name']);
            if (isset($doc_row)) {

                foreach ($doc_row as $docs) {
                    $documentName = (!empty($request->get('other_doc_name_' . $docs->doc_id)) ? $request->get('other_doc_name_' . $docs->doc_id) : $request->get('doc_name_' . $docs->doc_id));
                    $document_id = $docs->doc_id;

                    // if this input file is new data then create
                    if ($request->get('document_id_' . $docs->doc_id) == '') {

                        $insertArray = [
                            'process_type_id' => $this->process_type_id, // 1 for Space Allocation
                            'ref_id' => $appData->id,
                            'doc_info_id' => $document_id,
                            'doc_name' => $documentName,
                            'doc_file_path' => $request->get('validate_field_' . $docs->doc_id)
                        ];
                        AppDocuments::insert($insertArray);
                    } // if this input file is old data then update
                    else {
                        $oldDocumentId = $request->get('document_id_' . $docs->doc_id);
                        $insertArray = [
                            'process_type_id' => $this->process_type_id, // 2 for General Form
                            'ref_id' => $appData->id,
                            'doc_info_id' => $document_id,
                            'doc_name' => $documentName,
                            'doc_file_path' => $request->get('validate_field_' . $docs->doc_id)
                        ];
                        AppDocuments::where('id', $oldDocumentId)->update($insertArray);
                    }
                }
            } /* End file uploading */

//            $id = $processData->id;


            DB::commit();

            if ($processData->status_id == -1) {
                Session::flash('success', 'Successfully updated the Application!');
            } elseif ($processData->status_id == 1) {

                $company_emails = Users::where('id', $appData->created_by)
                        ->where('is_approved', 1)
                        ->where('user_status', 'active')
                        ->first(['user_email', 'user_phone']);

                    $body_msg = '<span style="color:#42ff40;text-align:justify;"><b>';
                    $body_msg .= 'Your application for General Apps with Tracking Number: ' . $appData->tracking_no . ' is now in status: <b>' .
                        'Submitted'.'</b>';
                    $body_msg .= '</span>';
                    $body_msg .= '<br/><br/><br/>Thanks<br/>';
                    $body_msg .= "<b> {{env('PROJECT_NAME') }} </b>";

                    $header = "Application Update Information for General Apps";
                    $param = $body_msg;
                    $email_content = view("Users::message", compact('header', 'param'))->render();

                  //  foreach ($company_emails as $companyuser) {
                        $emailQueue = new EmailQueue();
                        $emailQueue->service_id = $this->process_type_id; // service_id of LPP
                        $emailQueue->app_id = $appData->id;
                        $emailQueue->email_content = $email_content;
                        $emailQueue->email_to = $company_emails->user_email;
                        $emailQueue->sms_to = $company_emails->user_phone;
                        $emailQueue->email_subject = $header;
                        $emailQueue->attachment = '';
                        $emailQueue->save();
                    //}

                Session::flash('success', 'Successfully Application Submitted !');
            } elseif ($processData->status_id == 2) {
                Session::flash('success', 'Successfully Application Re-Submitted !');
            } else {
                Session::flash('error', 'Failed due to Application Status Conflict. Please try again later!');
            }
            return redirect('/general-apps/list/'.Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            dd($e->getLine(), $e->getMessage(), $e->getFile());
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [RC-1060]');
            return redirect()->back()->withInput();
        }
    }


    /*
     * To store the challan amount
     */
    public function challanStore($id, Request $request)
    {
        if (!ACL::getAccsessRight('generalApps', '-E-')) {
            abort(401, 'No access right!');
        }
        try {
            DB::beginTransaction();
            $app_id = Encryption::decodeId($id);

            $this->validate($request, [
                'payorder_no' => 'required',
                'bank_name' => 'required',
                'amount' => 'required',
                'payorder_date' => 'required',
                'branch' => 'required',
                'payorder_file' => 'required'
            ]);

            if (date('Y-m-d', strtotime($request->get('payorder_date'))) > date('Y-m-d', strtotime(Carbon::now()))) {
                Session::flash('error', 'Challan Date can not be Maximum from today. ');
                return redirect()->back()->withInput();
            }

            $file2 = $request->file('payorder_file');
            $original_file2 = $file2->getClientOriginalName();
            $file2->move('uploads/', time() . $original_file2);
            $filename2 = 'uploads/' . time() . $original_file2;

            $current_status = ProcessList::where('ref_id', $app_id)->pluck('status_id');
            $status = 16; // challan submitted
            if ($current_status == 18) { // challan declined
                $status = 17; // for challan re-submission
            }

            ProcessList::where('ref_id', $app_id)->where('process_type_id', $this->process_type_id)->update([
                'status_id' => $status, // This is for challan re-submition as well
                'desk_id' => 4, // 4 = RD4
            ]);

            $challanReg = Configuration::where('caption', 'CHALLAN_REG')->first(['value', 'value2']);

            $po_date = Carbon::createFromFormat('d-M-Y', $request->get('payorder_date'))->format('Y-m-d'); // This is for challan re-submition as well

            $data = [
                'po_no' => $challanReg->value,
                'po_date' => $po_date,
                'po_bank_id' => $request->get('bank_name'),
                'po_bank_branch_id' => $request->get('branch'),
                'po_ammount' => $challanReg->value2,
                'po_file' => $filename2,
                'updated_by' => CommonFunction::getUserId(),
            ];
            GeneralAppsMaster::find($app_id)->update($data);
            DB::commit();
            \Session::flash('success', "Challan information has been successfully updated!");
            return redirect()->back();
        } catch (Exception $e) {
            dd($e->getLine(), $e->getMessage());
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [RC-1080]');
            return Redirect::back()->withInput();
        }
    }
    public function updateADInfo($ref_id, $process_type_id){
        $ref_id = Encryption::decodeId($ref_id);
        $process_type_id = Encryption::decodeId($process_type_id);


        $appInfo = ProcessList::leftJoin('ga_master as apps', 'apps.id', '=', 'process_list.ref_id')
            ->where('process_list.ref_id', $ref_id)
            ->where('process_list.process_type_id', $process_type_id)
            ->first([
                'process_list.ref_id',
                'process_list.status_id',
                'process_list.tracking_no',
                'process_list.created_by as ccreated_by'

            ]);

        $requestData=session('requestData');

        // 5= Shortfall, 8 = Discarded, 21 = Approved & sent to customs, 22 = Rejected,
        if (in_array($requestData['status_id'], array(5, 25))) {
            $company_emails = Users::where('id', $appInfo->ccreated_by)
                ->where('is_approved', 1)
                ->where('user_status', 'active')
                ->first(['user_email', 'user_phone']);
            $project_name = env('PROJECT_NAME');
            $body_msg = '<span style="color:#42ff40;text-align:justify;"><b>';
            $body_msg .= 'Your application for General Apps with Tracking Number: ' . $appInfo->tracking_no . ' is now in status: <b>' .
                CommonFunction::getFieldName($requestData['status_id'], 'id', 'status_name', 'process_status') . '</b>';
            $body_msg .= '</span>';
            $body_msg .= '<br/><br/><br/>Thanks<br/>';
            $body_msg .= "<b>$project_name</b>";

            $header = "Application Update Information for General Apps";
            $param = $body_msg;
            $email_content = view("Users::message", compact('header', 'param'))->render();

//            foreach ($company_emails as $companyuser) {
                $emailQueue = new EmailQueue();
                $emailQueue->service_id = $this->process_type_id; // service_id of LPP
                $emailQueue->app_id = $appInfo->ref_id;
                $emailQueue->email_content = $email_content;
                $emailQueue->email_to = $company_emails->user_email;
                $emailQueue->sms_to =  $company_emails->user_phone;
                $emailQueue->email_subject = $header;
                $emailQueue->attachment = '';
                $emailQueue->save();
           // }
        }




        Session::flash('success', "Application status updated successfully");
        return redirect()->back();
    }
    public function verifyProcessHistory($type_id, $process_list_id){



        $process_history = DB::select(DB::raw("select `process_list_hist`.`desk_id`,
                                `process_list_hist`.`process_id`,                           
                                `process_list_hist`.`ref_id`,                           
                                `process_list_hist`.`process_type`,                           
                                `process_list_hist`.`tracking_no`,                           
                                `process_list_hist`.`closed_by`,                           
                                `process_list_hist`.`locked_by`,                           
                                `process_list_hist`.`locked_at`,                           
                                `process_list_hist`.`desk_id`,                           
                                `process_list_hist`.`status_id`,                           
                                `process_list_hist`.`process_desc`,                           
                                `process_list_hist`.`created_by`,                           
                                `process_list_hist`.`on_behalf_of_user`,                           
                            
                                `process_list_hist`.`updated_by`, 
                                `process_list_hist`.`status_id`, 
                                `process_list_hist`.`process_desc`, 
                                `process_list_hist`.`process_id`, 
                                `process_list_hist`.`updated_at`,
                                `process_list_hist`.`hash_value`
            
                                from  `process_list_hist`
                                where `process_list_hist`.`process_id`  = '$process_list_id'
                                and `process_list_hist`.`process_type` = '$type_id' 
                                and `process_list_hist`.`hash_value` !='' 
                               
                                and `process_list_hist`.`status_id` != -1
                    group by `process_list_hist`.`process_id`,`process_list_hist`.`desk_id`, `process_list_hist`.`status_id`, process_list_hist.updated_at
                    order by process_list_hist.updated_at desc 

                    "));

        dd($process_history);

        $verification = false;
        foreach ($process_history as $data){

            $resultData = $data->process_id . ', ' .$data->ref_id . ', ' .$data->tracking_no. ', ' . $data->desk_id. ', ' .$data->process_type .','. $data->status_id. ', '
                . $data->on_behalf_of_user. ', ' .$data->process_desc. ', ' . $data->closed_by. ', ' . $data->locked_at. ', ' . $data->locked_by;


            $hashValue = RSA::decrypt($data->hash_value);
            if($resultData == $hashValue){
                $verification = true;

            }else{
                Session::flash('error', "sorry this application not valid");
                return redirect()->back();
            }
        }
        if($verification = true){

            dd('ok');
        }




        dd($process_history);

        $hash = '21801BFAB4DB1D4A736C3EC6D0EFCD0B908B9B57C1575E8796E97FFE4C8CD5E1C52F7413497981EB772314B7EE76FF86E351459B76299EDAF138164927396ED517F5D4F679C9937519534563A1E643F4DD7A2017A90BDBEB82ABBC3640B9D95F4241EEB7BD57B758A5AA9234FC8C63C543D22D690569D9371967E56134B0F191';

       $SS=RSA::decrypt($hash);
       dd($SS);

    }


    /*     * ********************************************End of Controller Class************************************************* */
}
