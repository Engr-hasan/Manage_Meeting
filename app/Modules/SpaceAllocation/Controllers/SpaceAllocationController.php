<?php

namespace App\Modules\SpaceAllocation\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Modules\Apps\Models\AppDocuments;
use App\Modules\apps\Models\Colors;
use App\Modules\Apps\Models\DocInfo;
use App\Modules\Apps\Models\IndustryCategories;
use App\Modules\Apps\Models\pdfQueue;
use App\Modules\Apps\Models\ProcessListHist;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessStatus;
use App\Modules\settings\Models\PdfServerInfo;
use App\Modules\SpaceAllocation\Models\BusinessService;
use App\Modules\SpaceAllocation\Models\EmployeeDetails;
use App\Modules\SpaceAllocation\Models\IndustryType;
use App\Modules\SpaceAllocation\Models\JointCompany;
use App\Modules\SpaceAllocation\Models\OrganizationType;
use App\Modules\SpaceAllocation\Models\ProposedBusiness;
use App\Modules\SpaceAllocation\Models\Sponsors;
use App\Modules\SpaceAllocation\Models\TradeBody;
use App\Modules\Settings\Models\Bank;
use App\Modules\Settings\Models\Configuration;
use App\Modules\Settings\Models\Currencies;
use App\Modules\Settings\Models\PdfPrintRequest;
use App\Modules\Settings\Models\Units;
use App\Modules\SpaceAllocation\Models\LoanLocator;
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
use yajra\Datatables\Datatables;

class SpaceAllocationController extends Controller
{

    protected $process_type_id;

    public function __construct()
    {
        if (Session::has('lang'))
            App::setLocale(Session::get('lang'));
        $this->process_type_id = 1; // 1 is Space Allocation process type
    }


    public function form(){
        return \view('SpaceAllocation::new-form');
    }


    /*
     * Show application form
     */
    public function applicationForm()
    {

        if (!ACL::getAccsessRight('spaceAllocation', '-A-')) {
            abort('400', 'You have no access right! Contact with system admin for more information.');
        }
        try {
            $authUserId = CommonFunction::getUserId();
            $company_id = Auth::user()->user_sub_type;
            $company_name = CompanyInfo::where('id', $company_id)->pluck('company_name');

            // Check existing application
            $statusArr = array(5, 8, 22, '-1'); //5 is shortfall, 8 is Discard, 22 is Rejected Application and -1 is draft
            $alreadyExistApplicant =ProcessList::leftJoin('space_allocation as spacAlloc', 'spacAlloc.id', '=', 'process_list.ref_id')
                ->where('process_list.process_type_id', $this->process_type_id)
                ->where('process_list.company_id', $company_id)
                ->whereNotIn('process_list.status_id', $statusArr)
                ->first();

            $countries = Countries::where('country_status', 'Yes')->orderBy('name', 'asc')->lists('name', 'iso')->all();
            $currencies = Currencies::orderBy('code')->where('is_archive', 0)->where('is_active', 1)->lists('code', 'id');
            $divition_eng = AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $district_eng = AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $zoneType = [
                'Private Owned Economic Site' => 'Private Owned Economic Site',
                'Government Owned Economic Site' => 'Government Owned Economic Site'
            ];
            $parkInfo = ParkInfo::select('id', 'park_name')
                ->where('status', 1)
                ->orderBy('park_name')
                ->lists('park_name', 'id');

            $businessIndustryServices = BusinessService::where('is_archive', 0)->orderBy('name')->lists('name', 'id');
            $typeofOrganizations = OrganizationType::where('is_archive', 0)->orderBy('name')->lists('name', 'id');
            $typeofIndustry = IndustryType::where('is_archive', 0)->orderBy('name')->lists('name', 'id');
            $units = Units::where('is_active', 1)->where('is_archive', 0)->orderBy('name')->lists('name', 'id');
            $document = DocInfo::where('process_type_id', $this->process_type_id)->orderBy('order')->get();
            $industry_cat = IndustryCategories::where('is_active', 1)->where('is_archive', 0)->orderBy('name')->lists('name', 'id');
            $nationality = Countries::orderby('nationality')->where('nationality', '!=', '')->lists('nationality', 'iso');
            $logged_user_info = Users::where('id', $authUserId)->first();
            $colors = Colors::where('is_active', 1)->where('is_archive', 0)->orderBy('name')->lists('name', 'id');
            $code = Colors::where('is_active', 1)->where('is_archive', 0)->orderBy('name')->lists('code');
            $viewMode = 'off';
            $mode = 'A';
            return view("SpaceAllocation::application-form", compact('countries', 'colors', 'code', 'currencies',
                'divition_eng', 'district_eng', 'parkInfo',
                'zoneType', 'units', 'company_name', 'businessIndustryServices',
                'typeofOrganizations', 'typeofIndustry', 'document',
                'alreadyExistApplicant', 'logged_user_info',
                'nationality', 'viewMode', 'mode', 'industry_cat'));
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }
    }


    /*
     * Application view and edit
     */
//    public function applicationViewEdit($openMode, $applicationId)
    public function applicationViewEdit($applicationId)
    {
        $applicationId = Encryption::decodeId($applicationId);
//        if ($openMode == 'edit') {
//            if (!ACL::getAccsessRight('spaceAllocation', '-E-', $applicationId))
//                abort('400', 'You have no access right! Contact with system admin for more information!');
//        } elseif ($openMode == 'view') {
//            if (!ACL::getAccsessRight('spaceAllocation', '-V-', $applicationId))
//                abort(401, 'You have no access right! Contact with system admin for more information!');
//        }
        $process_type_id = $this->process_type_id;
        $user_type = Auth::user()->user_type;
        if (in_array($user_type,['5x505'])) {
            $company_id = CommonFunction::getUserSubTypeWithZero();
            $data = ProcessList::where([
                'ref_id' => $applicationId,
                'process_type_id' => $process_type_id,
            ])->first(['status_id', 'created_by','company_id','tracking_no']);

            if ($data->company_id == $company_id && in_array($data->status_id, [-1, 5, 6])) {
                $openMode='edit';
            }else{
                $openMode='view';
            }
        } else{
            $openMode='view';
        }
        try {

            $process_type_id = $this->process_type_id;
            $application = ProcessList::leftJoin('space_allocation as apps', 'apps.id', '=', 'process_list.ref_id')
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

            $countries = ['' => 'Select One'] + Countries::orderby('nationality')->where('nationality', '!=', '')->lists('nationality', 'iso')->all();
            $divition_eng = AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $district_eng = AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $parkInfo = ParkInfo::select('id', 'park_name')
                ->where('status', 1)
                ->orderBy('park_name')
                ->lists('park_name', 'id');

            $businessIndustryServices = BusinessService::where('is_archive', 0)->orderBy('name')->lists('name', 'id');
            $typeofOrganizations = OrganizationType::where('is_archive', 0)->orderBy('name')->lists('name', 'id');
            $typeofIndustry = IndustryType::where('is_archive', 0)->orderBy('name')->lists('name', 'id');
            $units = Units::where('is_active', 1)->where('is_archive', 0)->orderBy('name')->lists('name', 'id');
            $document = DocInfo::where('process_type_id', $this->process_type_id)->orderBy('order')->get();
            $industry_cat = IndustryCategories::where('is_active', 1)->where('is_archive', 0)->orderBy('name')->lists('name', 'id');
            $nationality = Countries::orderby('nationality')->where('nationality', '!=', '')->lists('nationality', 'iso');
            $banks = Bank::where('is_archive', 0)->where('is_active', 1)->orderBy('name')->lists('name', 'id');
            $challanReg = Configuration::where('caption', 'CHALLAN_REG')->first(['value', 'value2']);

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
            $appInfo = ProcessList::leftJoin('space_allocation as apps', 'apps.id', '=', 'process_list.ref_id')
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
                    'process_list.process_desc',
                    'user_desk.desk_name',
                    'ps.status_name',
                    'ps.color',
                    'apps.*'
                ]);
//            dd($appInfo);
            $hasDeskParkWisePermission=CommonFunction::hasDeskParkWisePermission($appInfo->desk_id,$appInfo->park_id);

            $industryCatInfo = LoanLocator::where('industry_category_id', $appInfo->industry_category_id)
                ->leftJoin('industry_categories', 'industry_categories.id', '=', 'space_allocation.industry_category_id')
                ->leftJoin('colors', 'industry_categories.color_id', '=', 'colors.id')
                ->first(['industry_categories.name as industry_name', 'colors.name as color_name', 'colors.code as color_code']);
            $jointOrganizations = JointCompany::where('reg_id', $applicationId)->get();
            $proposedBusinessInfo = ProposedBusiness::where([
                'reg_id' => $applicationId,
                'type_id' => 1,
            ])->get();
            $proposedBusinessPlan = ProposedBusiness::where([
                'reg_id' => $applicationId,
                'type_id' => 2,
            ])->get();

            $existingEmployee = EmployeeDetails::where([
                'reg_id' => $applicationId,
                'type_id' => 1,
            ])->get();
            $nextEmployee = EmployeeDetails::where([
                'reg_id' => $applicationId,
                'type_id' => 2,
            ])->get();

            $foreignPartner = Sponsors::where('reg_id', $applicationId)->get();
            $tradeBody = TradeBody::where('reg_id', $applicationId)->get();

            $clrDocuments = [];
            $clr_document = AppDocuments::where('ref_id', $applicationId)->where('process_type_id', $this->process_type_id)->get();
            foreach ($clr_document as $documents) {
                $clrDocuments[$documents->doc_info_id]['doucument_id'] = $documents->id;
                $clrDocuments[$documents->doc_info_id]['doc_file_path'] = $documents->doc_file_path;
                $clrDocuments[$documents->doc_info_id]['doc_name'] = $documents->doc_name;
            }


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
            return view('SpaceAllocation::application-form-edit',
                compact('industryCatInfo', 'agencyInfo', 'countries', 'divition_eng',
                    'businessIndustryServices', 'typeofOrganizations','statusArray','userDeskIds',
                    'industry_cat', 'units', 'typeofIndustry', 'jointOrganizations', 'proposedBusinessInfo',
                    'proposedBusinessPlan', 'existingEmployee', 'nextEmployee', 'foreignPartner', 'tradeBody',
                    'clrDocuments', 'verificationData', 'mode', 'nationality', 'document', 'district_eng',
                    'parkInfo', 'appInfo', 'AgencyDirectorDetailsList', 'agencyEmployeeList', 'reg_app_id',
                    'viewMode', 'banks', 'challanReg', 'process_history', 'hasDeskParkWisePermission'));
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [RC-1040]');
            return Redirect::back()->withInput();
        }
    }


    /*
     * Industry Category Color
     */
    public function colorChange(Request $request)
    {
        $industry_cat_id = $request->get('industry_category_id');

        $color_code = DB::table('industry_categories')
            ->leftJoin('colors', 'industry_categories.color_id', '=', 'colors.id')
            ->where('industry_categories.id', $industry_cat_id)
            ->select('colors.code', 'colors.name')->first();
        echo json_encode($color_code);
        exit();
    }


    /*
     * Application Store function
     */
    public function appStore(Request $request)
    {
//        if ($request->get('app_id')) {
//            if (!ACL::getAccsessRight('spaceAllocation', '-E-', $request->get('app_id')))
//                abort('400', 'You have no access right! Contact with system admin for more information!');
//        } else {
//            if (!ACL::getAccsessRight('spaceAllocation', '-A-'))
//                abort(401, 'You have no access right! Contact with system admin for more information!');
//        }
//        dd($request->all());

        $rules = [
            'applicant_name' => 'required',
            'country' => 'required',
            'address_line1' => 'required',
            'phone_no' => 'required',
            'email' => 'required|email',
            'identification_type' => 'required',
            'park_id' => 'required',
            'type_of_business_service' => 'required',
            'organization_type' => 'required',
            'industry_type_id' => 'required',
            'industry_category_id' => 'required',

            // Services/Products panel
            'sp_product_description' => 'required',
            'sp_product_usage' => 'required',
            'sp_manufacture_process' => 'required',
            'sp_project_cost' => 'required',
            'sp_annual_turnover' => 'required',
            'sp_liquid_asset' => 'required',

            // Space Required (In Sqft)
            'infrastructure_space' => 'required',
            'infrastructure_power' => 'required',
            'infrastructure_gas' => 'required',
            'infrastructure_water' => 'required',

            // Paid-up Capital (%)
            'paidup_capital_local' => 'required',
            'paidup_capital_foreign' => 'required',

            'acceptTerms' => 'required',
        ];

        // Validate company logo
        if ($request->get('actionBtn') == 'save') {
            $messages = [];
            if ($request->hasFile('company_logo')) {
                $rules['company_logo'] = 'required|mimes:jpeg,png,jpg|max:3072';
                $messages ['company_logo.max'] = 'The company logo may not be greater than 3 MB.';
            } else {
                $filePath = realpath('company_logo/' . $request->get('company_logo'));
                // list($width, $height) = getimagesize($filePath);
                $fileSize = number_format(filesize($filePath) / 1048576, 0);
                if ($fileSize > 3) {
                    Session::flash('error', 'Company logo should be maximum 3MB');
                    return redirect()->back()->withInput();
                }
            }
            $this->validate($request, $rules, $messages);
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
            if ($request->get('app_id')) {
                $decodedId = Encryption::decodeId($data['app_id']);
                $appData = LoanLocator::find($decodedId);
                $processData = ProcessList::firstOrNew(['company_id' => $companyId, 'process_type_id' => $this->process_type_id, 'ref_id' => $appData->id,]);
            } else {
                $appData = new LoanLocator();
                $appData->company_name = '';
                $processData = new ProcessList();
                $processData->company_id = $companyId;
                $processData->created_by = $appData->created_by;
            }

            $appData->applicant_name = $data['applicant_name'];
            if ($data['country'] == 'BD') {
                $appData->country_type = 1; //Type 1 is for BD
            } else {
                $appData->country_type = 2; //Type 2 is for others country
            }
            $appData->country = $data['country'];
            $appData->state = $data['state'];
            $appData->province = $data['province'];
            $appData->division_id = $data['division_id'];
            $appData->district_id = $data['district_id'];
            $appData->address_line1 = $data['address_line1'];
            $appData->address_line2 = $data['address_line2'];
            $appData->post_code = $data['post_code'];
            $appData->phone_no = $data['phone_no'];
            $appData->fax_no = $data['fax_no'];
            $appData->email = $data['email'];
            $appData->website = $data['website'];
            //$appData->identification_type = $data['identification_type'];

            if ($data['identification_type'] == 'passport') {
                $appData->identification_type = 2;
                $appData->passport = $data['pass_nid_data'];
            } else {
                $appData->identification_type = 1;
                $appData->nid = $data['pass_nid_data'];
            }
            $appData->vat_reg_no = $data['vat_reg_no'];
            $appData->tin_reg_no = $data['tin_reg_no'];
            $appData->company_name = $data['company_name'];
            $appData->type_of_business_service = $data['type_of_business_service'];
            $appData->organization_type = $data['organization_type'];
            $appData->industry_type_id = $data['industry_type_id'];
            $appData->industry_category_id = $data['industry_category_id'];
            $appData->eia_cer_exist = $request->get('eia_cer_exist');

            // Environment Impact Assessment Certificate upload
            if ($request->get('eia_cer_file')) {
                $file = $request->file('eia_cer_file');
                $size = $file->getSize();
                $extension = $file->getClientOriginalExtension();
                $valid_formats = array("pdf");
                if (in_array($extension, $valid_formats)) {
                    if ($size < (1024 * 1024 * 3)) { // 3 MB maximum size
                        $original_file = $file->getClientOriginalName();
                        $file->move('uploads/', time() . $original_file);
                        $fileName = 'uploads/' . time() . $original_file;

                        $appData->eia_cer_file = $fileName;
                    } else {
                        Session::flash('error', "File size must be less than 3 megabyte");
                        return redirect()->back();
                    }
                } else {
                    Session::flash('error', "File format is not valid! Please upload a pdf file");
                    return redirect()->back();
                }
            }

            $appData->sp_product_description = $data['sp_product_description'];
            $appData->sp_product_usage = $data['sp_product_usage'];
            $appData->sp_manufacture_process = $data['sp_manufacture_process'];
            $appData->sp_project_cost = $data['sp_project_cost'];
            $appData->sp_annual_turnover = $data['sp_annual_turnover'];
            $appData->sp_liquid_asset = $data['sp_liquid_asset'];
            $appData->infrastructure_space = $data['infrastructure_space'];
            $appData->infrastructure_power = $data['infrastructure_power'];
            $appData->infrastructure_gas = $data['infrastructure_gas'];
            $appData->infrastructure_water = $data['infrastructure_water'];
            $appData->paidup_capital_local = $data['paidup_capital_local'];
            $appData->paidup_capital_foreign = $data['paidup_capital_foreign'];
            $appData->paidup_capital_total = $data['paidup_capital_total'];
            $appData->acceptTerms = (!empty($data['acceptTerms']) ? 1 : 0);

            // company Logo upload
            $_companyLogo = $request->file('company_logo');
            $path = "company_logo/";
            if ($request->hasFile('company_logo')) {
                $companyLogo = trim(sprintf("%s", uniqid('BHTP_', true)) . $_companyLogo->getClientOriginalName());
                $mime_type = $_companyLogo->getClientMimeType();
                if ($mime_type == 'image/jpeg' || $mime_type == 'image/jpg' || $mime_type == 'image/png') {
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                        $myfile = fopen($path . "/index.html", "w");
                        fclose($myfile);
                    }
                    $_companyLogo->move($path, $companyLogo);
                    $appData->company_logo = $companyLogo;
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
            $processData->park_id = $data['park_id'];


            $jsonData['Applicant Name']=$request->get('applicant_name');
            $jsonData['company name']=$request->get('company_name');
            $jsonData['Park name']=CommonFunction::getParkNameById($data['park_id']);
            $jsonData['Industry Category']=CommonFunction::getIndustryCatNameById($data['industry_category_id']);
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

            }


            // Joint Company Section
            if ($appData->organization_type == 3) {
                $jointCompanyIds = [];
                foreach ($data['joint_company'] as $key => $description) {
                    if (empty($data['joint_id'][$key])) {
                        $jointData = new JointCompany();
                    } else {
                        $jointCompanyId = $data['joint_id'][$key];
                        $jointData = JointCompany::where('id', $jointCompanyId)->first();
                    }
                    $jointData->reg_id = $appData->id;
                    $jointData->joint_company = $data['joint_company'][$key];
                    $jointData->joint_company_address = $data['joint_company_address'][$key];
                    $jointData->joint_com_country = $data['joint_com_country'][$key];
                    if ($request->get('actionBtn') == 'draft') {
                        $jointData->status = -1; //Draft User
                    } else {
                        $jointData->status = 1; //submitted application
                    }
                    $jointData->save();
                    $jointCompanyIds[] = $jointData->id;
                }
                if (!empty($jointCompanyIds)) {
                    JointCompany::where('reg_id', $appData->id)->whereNotIn('id', $jointCompanyIds)->delete();
                }
            }


            // proposed business section
            $proposeBusinessIds = [];
            foreach ($data['production_desc'] as $key => $description) {
                if (empty($data['proposeBusinessIds'][$key])) {
                    $ProBusiData = new ProposedBusiness();
                } else {
                    $proposeBusinessId = $data['proposeBusinessIds'][$key];
                    $ProBusiData = ProposedBusiness::where('id', $proposeBusinessId)->first();
                }
                $ProBusiData->reg_id = $appData->id;
                $ProBusiData->type_id = 1;
                $ProBusiData->description = $data['production_desc'][$key];
                $ProBusiData->unit_id = $data['production_unit'][$key];
                $ProBusiData->qty_1st = $data['production_1st'][$key];
                $ProBusiData->qty_2nd = $data['production_2nd'][$key];
                $ProBusiData->qty_3rd = $data['production_3rd'][$key];
                $ProBusiData->qty_4th = $data['production_4th'][$key];
                $ProBusiData->qty_5th = $data['production_5th'][$key];
                $ProBusiData->qty_total = $data['production_total'][$key];

                if ($request->get('actionBtn') == 'draft') {
                    $ProBusiData->status = -1; //Draft User
                } else {
                    $ProBusiData->status = 1; //submitted application
                }
                $ProBusiData->save();
                $proposeBusinessIds[] = $ProBusiData->id;

            }

            if (!empty($proposeBusinessIds)) {
                ProposedBusiness::where('reg_id', $appData->id)->where('type_id', 1)->whereNotIn('id', $proposeBusinessIds)->delete();
            }


            // proposed business Plan section
            $proposeBusinessPlanIds = [];
            foreach ($data['production_desc_plan'] as $key => $description) {
                if (empty($data['proposeBusinessPlanIds'][$key])) {
                    $ProBusiPlanData = new ProposedBusiness();
                } else {
                    $proposeBusinessPlanId = $data['proposeBusinessPlanIds'][$key];
                    $ProBusiPlanData = ProposedBusiness::where('id', $proposeBusinessPlanId)->first();
                }
                $ProBusiPlanData->reg_id = $appData->id;
                $ProBusiPlanData->type_id = 2;
                $ProBusiPlanData->description = $data['production_desc_plan'][$key];
                $ProBusiPlanData->unit_id = $data['production_unit_plan'][$key];
                $ProBusiPlanData->qty_1st = $data['production_plan_1st'][$key];
                $ProBusiPlanData->qty_2nd = $data['production_plan_2nd'][$key];
                $ProBusiPlanData->qty_3rd = $data['production_plan_3rd'][$key];
                $ProBusiPlanData->qty_4th = $data['production_plan_4th'][$key];
                $ProBusiPlanData->qty_5th = $data['production_plan_5th'][$key];
                $ProBusiPlanData->qty_total = $data['production_total_plan'][$key];

                if ($request->get('actionBtn') == 'draft') {
                    $ProBusiPlanData->status = -1; //Draft User
                } else {
                    $ProBusiPlanData->status = 1; //submitted application
                }
                $ProBusiPlanData->save();
                $proposeBusinessPlanIds[] = $ProBusiPlanData->id;
            }
            if (!empty($proposeBusinessPlanIds)) {
                ProposedBusiness::where('reg_id', $appData->id)->where('type_id', 2)->whereNotIn('id', $proposeBusinessPlanIds)->delete();
            }


            //Number of existing employees Section
            $exEmployeeIds = [];
            foreach ($data['ex_year'] as $key => $description) {
                if (empty($data['exEmployeeIds'][$key])) {
                    $ExEmployeeData = new EmployeeDetails();
                } else {
                    $exEmployeeId = $data['exEmployeeIds'][$key];
                    $ExEmployeeData = EmployeeDetails::where('id', $exEmployeeId)->first();
                }
                $ExEmployeeData->reg_id = $appData->id;
                $ExEmployeeData->type_id = 1;
                $ExEmployeeData->year = $data['ex_year'][$key];
                $ExEmployeeData->it_managerial = $data['ex_it_managerial'][$key];
                $ExEmployeeData->it_skilled = $data['ex_it_skilled'][$key];
                $ExEmployeeData->it_unskilled = $data['ex_it_unskilled'][$key];
                $ExEmployeeData->it_total = $data['ex_it_total'][$key];
                $ExEmployeeData->ss_managerial = $data['ex_ss_managerial'][$key];
                $ExEmployeeData->ss_skilled = $data['ex_ss_skilled'][$key];
                $ExEmployeeData->ss_unskilled = $data['ex_ss_unskilled'][$key];
                $ExEmployeeData->ss_total = $data['ex_ss_total'][$key];
                $ExEmployeeData->grand_total = $data['ex_grand_total'][$key];

                if ($request->get('actionBtn') == 'draft') {
                    $ExEmployeeData->status = -1; //Draft User
                } else {
                    $ExEmployeeData->status = 1; //submitted application
                }
                $ExEmployeeData->save();
                $exEmployeeIds[] = $ExEmployeeData->id;
            }
            if (!empty($exEmployeeIds)) {
                EmployeeDetails::where('reg_id', $appData->id)->where('type_id', 1)->whereNotIn('id', $exEmployeeIds)->delete();
            }


            //Planned Employment For Next 5 Years Section
            $nextEmployeeIds = [];
            foreach ($data['year'] as $key => $description) {
                if (empty($data['nextEmployeeIds'][$key])) {
                    $NextEmployeeData = new EmployeeDetails();
                } else {
                    $nextEmployeeId = $data['nextEmployeeIds'][$key];
                    $NextEmployeeData = EmployeeDetails::where('id', $nextEmployeeId)->first();
                }
                $NextEmployeeData->reg_id = $appData->id;
                $NextEmployeeData->type_id = 2;
                $NextEmployeeData->year = $data['year'][$key];
                $NextEmployeeData->it_managerial = $data['it_managerial'][$key];
                $NextEmployeeData->it_skilled = $data['it_skilled'][$key];
                $NextEmployeeData->it_unskilled = $data['it_unskilled'][$key];
                $NextEmployeeData->it_total = $data['it_total'][$key];
                $NextEmployeeData->ss_managerial = $data['ss_managerial'][$key];
                $NextEmployeeData->ss_skilled = $data['ss_skilled'][$key];
                $NextEmployeeData->ss_unskilled = $data['ss_unskilled'][$key];
                $NextEmployeeData->ss_total = $data['ss_total'][$key];
                $NextEmployeeData->grand_total = $data['grand_total'][$key];

                if ($request->get('actionBtn') == 'draft') {
                    $NextEmployeeData->status = -1; //Draft User
                } else {
                    $NextEmployeeData->status = 1; //submitted application
                }
                $NextEmployeeData->save();
                $nextEmployeeIds[] = $NextEmployeeData->id;
            }
            if (!empty($nextEmployeeIds)) {
                EmployeeDetails::where('reg_id', $appData->id)->where('type_id', 2)->whereNotIn('id', $nextEmployeeIds)->delete();
            }


            //Foreign Partner, Investor Section
            $sponsorsIds = [];
            foreach ($data['sponsor_name'] as $key => $description) {
                if (empty($data['sponsorsIds'][$key])) {
                    $SponsorsData = new Sponsors();
                } else {
                    $sponsorsId = $data['sponsorsIds'][$key];
                    $SponsorsData = Sponsors::where('id', $sponsorsId)->first();
                }
                $SponsorsData->reg_id = $appData->id;
                $SponsorsData->sponsor_name = $data['sponsor_name'][$key];
                $SponsorsData->sponsor_address = $data['sponsor_address'][$key];
                $SponsorsData->sponsor_nationality = $data['sponsor_nationality'][$key];
                $SponsorsData->sponsor_status = $data['sponsor_status'][$key];
                $SponsorsData->sponsor_share_ext = $data['sponsor_share_ext'][$key];

                if ($request->get('actionBtn') == 'draft') {
                    $SponsorsData->status = -1; //Draft User
                } else {
                    $SponsorsData->status = 1; //submitted application
                }
                $SponsorsData->save();
                $sponsorsIds[] = $SponsorsData->id;
            }
            if (!empty($sponsorsIds)) {
                Sponsors::where('reg_id', $appData->id)->whereNotIn('id', $sponsorsIds)->delete();
            }




            // Trade Body Membership Number section
            foreach ($data['tb_org'] as $key => $description) {
                if (empty($data['tb_ids'][$key])) {
                    $tradeData = new TradeBody();
                } else {
                    $tradeId = $data['tb_ids'][$key];
                    $tradeData = TradeBody::where('id', $tradeId)->first();
                }
                $tradeData->reg_id = $appData->id;
                $tradeData->tb_org = $data['tb_org'][$key];
                $tradeData->tb_no = $data['tb_no'][$key];

                // here goes file upload script
                if (isset($data['tb_file'][$key])){
                    if ($data['tb_file'][$key] != null) {
                        // Check fhis file type is pdf,else show error
                        $fileType = $data['tb_file'][$key]->getClientOriginalExtension();
                        if ($fileType != 'pdf') {
                            Session::flash('error', 'Trade Body Membership type must be PDF format');
                            return redirect()->back()->withInput();
                        }
                        $original_file = $data['tb_file'][$key]->getClientOriginalName();
                        $data['tb_file'][$key]->move('uploads/', time() . $original_file);
                        $tradeData->tb_file = time() . $original_file;
                    }
                }


                if ($request->get('actionBtn') == 'draft') {
                    $tradeData->status = -1; //Draft User
                } else {
                    $tradeData->status = 1; //submitted application
                }
                $tradeData->save();
            }





            //  Required Documents for attachment
            $doc_row = DocInfo::where('process_type_id', $this->process_type_id)// 1 is service id for Space Allocation
            ->get(['doc_id', 'doc_name']);
            if (isset($doc_row)) {

                foreach ($doc_row as $docs) {
                    $documentName = (!empty($request->get('other_doc_name_' . $docs->id)) ? $request->get('other_doc_name_' . $docs->id) : $request->get('doc_name_' . $docs->id));
                    $document_id = $docs->id;

                    // if this input file is new data then create
                    if ($request->get('document_id_' . $docs->id) == '') {

                        $insertArray = [
                            'process_type_id' => $this->process_type_id, // 1 for Space Allocation
                            'ref_id' => $appData->id,
                            'doc_info_id' => $document_id,
                            'doc_name' => $documentName,
                            'doc_file_path' => $request->get('validate_field_' . $docs->id)
                        ];
                        AppDocuments::insert($insertArray);
                    } // if this input file is old data then update
                    else {
                        $oldDocumentId = $request->get('document_id_' . $docs->id);
                        $insertArray = [
                            'process_type_id' => $this->process_type_id, // 1 for Space Allocation
                            'ref_id' => $appData->id,
                            'doc_info_id' => $document_id,
                            'doc_name' => $documentName,
                            'doc_file_path' => $request->get('validate_field_' . $docs->id)
                        ];
                        AppDocuments::where('id', $oldDocumentId)->update($insertArray);
                    }
                }
            }

            DB::commit();

            if ($processData->status_id == -1) {
                Session::flash('success', 'Successfully updated the Application!');
            } elseif ($processData->status_id == 1) {
                Session::flash('success', 'Successfully Application Submitted !');
            } elseif ($processData->status_id == 2) {
                Session::flash('success', 'Successfully Application Re-Submitted !');
            } else {
                Session::flash('error', 'Failed due to Application Status Conflict. Please try again later!');
            }
            return redirect('space-allocation/list/'.Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            dd($e->getLine(), $e->getMessage(), $e->getFile());
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [RC-1060]');
            return redirect()->back()->withInput();
        }
    }


    /*
     * Download Application
     */
    public function applicationDownload($reg_appID)
    {
        if (!ACL::getAccsessRight('spaceAllocation', '-V-')) {
            abort(401, 'No access right!');
        }

        try {
            $reg_app_id = Encryption::decodeId($reg_appID);
            $process_type_id = $this->process_type_id;
            $application = processlist::leftJoin('agency_reg_apps', function ($join) use ($process_type_id) {
                $join->on('process_list.record_id', '=', 'agency_reg_apps.id');
                $join->on('process_list.process_type_id', '=', DB::raw($process_type_id));
            })
                ->leftJoin('app_status', 'app_status.status_id', '=', 'process_list.status_id')
                ->where('agency_reg_apps.id', $reg_app_id)
                ->where('process_list.process_type_id', $this->process_type_id)
                ->first([
                    'agency_reg_apps.*',
                    'process_list.process_type_id', 'process_list.desk_id',
                    'process_list.process_desc',
                    'app_status.status_name'
                ]);

            //Get Approved Agency's Espire Time for renewal condition check
            $agencyInfo = Agency::where('id', $application->agency_id)->first();

            $nationality = ['' => 'Select One'] + Countries::orderby('nationality')->where('nationality', '!=', '')->lists('nationality', 'iso')->all();
            $AgencyDirectorDetailsList = DirectorDetails::where('record_id', $reg_app_id)->where('process_type_id', $this->process_type_id)->get();
            $agencyEmployeeList = EmpDetails::where('record_id', $reg_app_id)->where('process_type_id', $this->process_type_id)->get();
            $pageTitle = "Space Allocation Information View";

            $process_history = DB::select(DB::raw("select `p_hist_id`, `process_list_hist`.`desk_id`,`as`.`status_name`,
                                `process_list_hist`.`process_id`,
                                if(`process_list_hist`.`desk_id`=0,\"Applicant\",`ud`.`desk_name`) `deskname`,
                                `users`.`user_full_name`,
                                `process_list_hist`.`updated_by`,
                                `process_list_hist`.`status_id`,
                                `process_list_hist`.`process_desc`,
                                `process_list_hist`.`record_id`,
                                `process_list_hist`.`updated_at` ,
                                group_concat(`pd`.`file`) as files
                                from `process_list_hist`
                                left join `user_desk` as `ud` on `process_list_hist`.`desk_id` = `ud`.`desk_id`
                                left join `users` on `process_list_hist`.`updated_by` = `users`.`id`
                                left join `process_documents` as `pd` on `process_list_hist`.`p_hist_id` = `pd`.`process_hist_id`
                                left join `app_status` as `as` on `process_list_hist`.`status_id` = `as`.`status_id` and `process_list_hist`.`process_type_id` = `as`.`process_type_id`
                                where `process_list_hist`.`record_id`  = '$reg_app_id'
                                and `process_list_hist`.`process_type_id` = '$this->process_type_id'
                                and `process_list_hist`.`status_id` != -1
                    group by `process_list_hist`.`record_id`,`process_list_hist`.`desk_id`, `process_list_hist`.`status_id`
                    order by process_list_hist.updated_at desc
                    "));

            $viewStatus = 'on';
            $contents = view('SpaceAllocation::application-form-download', compact(
                'agencyInfo', 'nationality', 'application', 'AgencyDirectorDetailsList', 'agencyEmployeeList', 'pageTitle', 'reg_app_id', 'viewStatus', 'process_history'))->render();

            $mpdf = new mPDF(
                'utf-8', // mode - default ''
                'A4', // format - A4, for example, default ''
                12, // font size - default 0
                'nikosh', // default font family
                10, // margin_left
                10, // margin right
                10, // margin top
                15, // margin bottom
                10, // margin header
                9, // margin footer
                'P'
            );

            $mpdf->Bookmark('Start of the document');
            $mpdf->useSubstitutions;
            $mpdf->SetProtection(array('print'));
            $mpdf->SetDefaultBodyCSS('color', '#000');
            $mpdf->SetTitle("OCPL OSS Framework");
            $mpdf->SetSubject("Subject");
            $mpdf->SetAuthor("Business Automation Limited");
            $mpdf->autoScriptToLang = true;
            $mpdf->baseScript = 1;
            $mpdf->autoVietnamese = true;
            $mpdf->autoArabic = true;

            $mpdf->autoLangToFont = true;
            $mpdf->SetDisplayMode('fullwidth');
            $mpdf->setAutoTopMargin = 'stretch';
            $mpdf->setAutoBottomMargin = 'stretch';

            $mpdf->WriteHTML($contents);

            $mpdf->defaultfooterfontsize = 10;
            $mpdf->defaultfooterfontstyle = 'B';
            $mpdf->defaultfooterline = 0;

            $mpdf->SetCompression(true);
            $mpdf->Output('test' . '.pdf', 'I');   // Saving pdf "F" for Save only, "I" for view only.
        } catch (\Exception $e) {
            dd($e->getLine(), $e->getMessage(), $e->getFile());
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [RC-1060]');
            return redirect()->back();
        }
    }


    /*
    * Open certificate
    */
    public function openCertificate($appId)
    {
        $decodedAppId = Encryption::decodeId($appId);
        try {
            ini_set('memory_limit', '99M');
            $mpdf = new mPDF(
                'utf-8', // mode - default ''
                'A4', // format - A4, for example, default ''
                9, // font size - default 0
                'Times New Roman', // default font family
                17, // margin_left
                10, // margin right
                30, // margin top
                10, // margin bottom
                9, // margin header
                9, // margin footer
                'P'
            );
            $mpdf->useSubstitutions;
            $mpdf->SetProtection(array('print'));
            $mpdf->SetDefaultBodyCSS('color', '#000');
            $mpdf->SetTitle("OCPL OSS Framework $decodedAppId");
            $mpdf->SetSubject("OCPL OSS Framework $decodedAppId");
            $mpdf->SetAuthor("Business Automation Limited");
            $mpdf->SetHTMLHeader('<img src="assets/images/01_BHTP_New_Logo.png" alt="OCPL-OSS-Framework" width="50" height="75"/>');
            $mpdf->SetWatermarkImage('assets/images/01_BHTP_New_Logo.png');
            $mpdf->showWatermarkImage = false;
            $mpdf->setFooter('{PAGENO} / {nb}');

            $baseURL = "uploads/";
            $directoryName = $baseURL . date("Y/m");
            $directoryNameYear = $baseURL . date("Y");

            if (!file_exists($directoryName)) {
                $oldmask = umask(0);
                mkdir($directoryName, 0777, true);
                umask($oldmask);
                $f = fopen($directoryName . "/index.html", "w");
                fclose($f);
                if (!file_exists($directoryNameYear . "/index.html")) {
                    $f = fopen($directoryNameYear . "/index.html", "w");
                    fclose($f);
                }
            }
            $certificateName = uniqid("BHTPA_" . $decodedAppId . "_", true);

            $mpdf->autoScriptToLang = true;
            $mpdf->baseScript = 1;
            $mpdf->autoVietnamese = true;
            $mpdf->autoArabic = true;

            $mpdf->autoLangToFont = true;
            $mpdf->SetDisplayMode('fullwidth');

            $pdf_body = View::make("SpaceAllocation::certificate")->render();

            $mpdf->SetCompression(true);
            $mpdf->WriteHTML($pdf_body);
            $pdfFilePath = $directoryName . "/" . $certificateName . '.pdf';
            $mpdf->Output($pdfFilePath, 'I'); // Saving pdf *** F for Save only, I for view only.

        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }
    }


    /*
    * Dynamic Application
    */
//    public function dynamicCertificate($appId)
//    {
//        $decodedAppId = $appId;
//        try {
//            $appData = P2ProcessList::leftJoin('space_allocation', 'space_allocation.id', '=', 'p2_process_list.ref_id')
//                ->leftJoin('park_info', 'park_info.id', '=', 'space_allocation.park_id')
//                ->leftJoin('space_type_of_industry as industry_type', 'industry_type.id', '=', 'space_allocation.industry_type_id')
//                ->leftJoin('area_info', function ($join) {
//                    $join->on('area_info.area_id', '=', 'space_allocation.district_id');
//                    $join->where('area_info.area_type', '=', 2, 'and'); //Type 2 Means District
//                })
//                ->leftJoin('space_emp_details', function ($join) {
//                    $join->on('space_emp_details.reg_id', '=', 'space_allocation.id');
//                    $join->where('space_emp_details.type_id', '=', 1, 'and'); //Type 1 Means Existing Employee
//                })
//                ->where('p2_process_list.process_type_id', $this->process_type_id)
//                ->where('p2_process_list.ref_id', $decodedAppId)
//                ->first([
//                    'p2_process_list.id as process_id',
//                    'p2_process_list.ref_id',
//                    'p2_process_list.*',
//                    'space_allocation.*',
//                    'park_info.*',
//                    'industry_type.name as industry_type_name',
//                    'area_info.area_nm as area_name',
//                    'space_emp_details.grand_total as total_employee',
//                    'space_emp_details.it_total as foreign_employee'
//                ]);
//
//            $directory = 'signatures/';
//            $approver = 'Mohammed Ayub';
//            $signature = (file_exists($directory . 'certificate_approver.jpg')) ?
//                'signatures/certificate_approver.jpg' : '';
//
//            if (!$appData) {
//                return '';
//            } else {
//
//                $ApproveDate = P2ProcessListHist::where('process_id', $appData->process_id)->where('ref_id', $decodedAppId)
//                    ->where('process_status_id', 23)// 23 = approved
//                    ->orderBy('id', 'desc')->pluck('created_at');
//                $appApproveDate = '';
//                if (!empty($ApproveDate)) {
//                    $appApproveDate = date_format($ApproveDate, "d-M-Y");
//                }
//
//
//                $ReceivedDate = P2ProcessListHist::where('process_id', $appData->process_id)->where('ref_id', $decodedAppId)
//                    ->where('process_status_id', 1)// 1 = submitted
//                    ->orderBy('id', 'desc')->pluck('created_at');
//                $appReceivedDate = '';
//                if (!empty($ReceivedDate)) {
//                    $appReceivedDate = date_format($ReceivedDate, "d-M-Y");
//                }
////                $production_capacity_arr = Production::leftJoin('units', 'clr_production.production_unit', '=', 'units.id')
////                    ->where('clearance_id', $appID)->get(['production_desc', 'production_5th', 'units.name as unit_name']);
////                $production_capacity_str = array();
////                foreach ($production_capacity_arr as $row) {
////                    $production_capacity_str[] = $row->production_desc . ' ' . $row->production_5th . ' ' . $row->unit_name;
////                }
////                $production_capacity = implode(', ', $production_capacity_str);
//
////                $production_cost_arr = ProductionCost::where('clearance_id', $appID)->get(['production_cost']);
////                $production_cost = 0;
////                foreach ($production_cost_arr as $row) {
////                    $production_cost += $row->production_cost;
////                }
////
////                $export_projection_arr = Export::where('clearance_id', $appID)->get(['pro_ext_5th']); // have to take 5th year data
////                $total_export_projection = 0;
////                foreach ($export_projection_arr as $row) {
////                    $total_export_projection += $row->pro_ext_5th;
////                }
////
////                $domestic_projection_arr = DomesticExport::where('clearance_id', $appID)->get(['pro_dom_5th']); // have to take 5th year data
////                $total_domestic_projection = 0;
////                foreach ($domestic_projection_arr as $row) {
////                    $total_domestic_projection += $row->pro_dom_5th;
////                }
////
////                $sum_export_domestic = $total_export_projection + $total_domestic_projection;
////                if ($sum_export_domestic > 0) {
////                    $export_percentage = number_format(($total_export_projection / $sum_export_domestic) * 100, 2, '.', ','); // percentage of export
////                    $domestic_percentage = number_format(($total_domestic_projection / $sum_export_domestic) * 100, 2, '.', ','); // percentage of domestic
////                } else {
////                    $export_percentage = 0;
////                    $domestic_percentage = 0;
////                }
//                $qrCodeGenText = $appData->tracking_no . '-' . $appData->company_name . '-' .
//                    'BHTP' . '-' . $appApproveDate;
//                $qrcodeRule = str_replace(' ', '+', $qrCodeGenText);
//                $qrCode = "http://chart.apis.google.com/chart?chs=100x100&cht=qr&chl=$qrcodeRule&choe=ISO-8859-1";
//            }
//
//            ini_set('memory_limit', '99M');
//            $mpdf = new mPDF(
//                'utf-8', // mode - default ''
//                'A4', // format - A4, for example, default ''
//                9, // font size - default 0
//                'Times New Roman', // default font family
//                17, // margin_left
//                10, // margin right
//                30, // margin top
//                10, // margin bottom
//                9, // margin header
//                9, // margin footer
//                'P'
//            );
//            $mpdf->useSubstitutions;
//            $mpdf->SetProtection(array('print'));
//            $mpdf->SetDefaultBodyCSS('color', '#000');
//            $mpdf->SetTitle("OCPL OSS Framework $decodedAppId");
//            $mpdf->SetSubject("OCPL OSS Framework $decodedAppId");
//            $mpdf->SetAuthor("Business Automation Limited");
//            $mpdf->SetHTMLHeader('<img src="assets/images/01_BHTP_New_Logo.png" alt="OCPL OSS Framework" width="50" height="75"/>');
//            $mpdf->SetWatermarkImage('assets/images/01_BHTP_New_Logo.png');
//            $mpdf->showWatermarkImage = false;
//            $mpdf->setFooter('{PAGENO} / {nb}');
//
//            $baseURL = "uploads/";
//            $directoryName = $baseURL . date("Y/m");
//            $directoryNameYear = $baseURL . date("Y");
//
//            if (!file_exists($directoryName)) {
//                $oldmask = umask(0);
//                mkdir($directoryName, 0777, true);
//                umask($oldmask);
//                $f = fopen($directoryName . "/index.html", "w");
//                fclose($f);
//                if (!file_exists($directoryNameYear . "/index.html")) {
//                    $f = fopen($directoryNameYear . "/index.html", "w");
//                    fclose($f);
//                }
//            }
//            $certificateName = uniqid("BHTPA_" . $decodedAppId . "_", true);
//
//            $mpdf->autoScriptToLang = true;
//            $mpdf->baseScript = 1;
//            $mpdf->autoVietnamese = true;
//            $mpdf->autoArabic = true;
//
//            $mpdf->autoLangToFont = true;
//            $mpdf->SetDisplayMode('fullwidth');
//
//            $pdf_body = View::make("SpaceAllocation::dynamic-certificate",
//                compact('appData', 'appReceivedDate', 'qrCode', 'signature', 'approver'))->render();
//
//            $mpdf->SetCompression(true);
//            $mpdf->WriteHTML($pdf_body);
//            $pdfFilePath = $directoryName . "/" . $certificateName . '.pdf';
//            $mpdf->Output($pdfFilePath, 'I'); // Saving pdf *** F for Save only, I for view only.
//
//        } catch (Exception $e) {
//            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
//            return Redirect::back()->withInput();
//        }
//    }


    /*
     * Show space allocation Form Preview page
     */
    public function preview()
    {
        if (!ACL::getAccsessRight('spaceAllocation', '-V-')) {
            abort(401, 'No access right!');
        }
        return view("SpaceAllocation::preview");
    }


    /*
     * File Upload Ajax File include
     */
    public function uploadDocument()
    {
        return View::make('SpaceAllocation::ajaxUploadFile');
    }

    /*
     * To store the challan amount
     */
    public function challanStore($id, Request $request)
    {
        if (!ACL::getAccsessRight('spaceAllocation', '-E-')) {
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
            LoanLocator::find($app_id)->update($data);
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


    /*
     *  remarks view
     */
    public function remarksView($processId)
    {
        if (!ACL::getAccsessRight('spaceAllocation', '-V-')) {
            abort(401, 'You have no access right! Please contact to system admin for more information.');
        }
        try {
            $process_hist_id = Encryption::decodeId($processId);
            $processData = P2ProcessListHist::where('id', $process_hist_id)->first(['process_remarks', 'updated_by']);
            $userInfo = Users::where('id', $processData->updated_by)->first(['user_full_name', 'signature']);
            return view('partials.remarks', compact('processData', 'userInfo'));
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [RC-1090]');
            return Redirect::back()->withInput();
        }
    }


    /*
     * view Negative Remarks
     */
    public function viewNegativeRemarks($appId, $_process_type_id)
    {
        if (!ACL::getAccsessRight('spaceAllocation', '-V-')) {
            abort(401, 'You have no access right! Please contact to system admin for more information.');
        }
        try {

            $app_id = Encryption::decodeId($appId);
            $process_type_id = Encryption::decodeId($_process_type_id);

            $processData = Processlist::where('process_list.record_id', $app_id)
                ->where('process_list.process_type_id', $process_type_id)
                ->first(['process_list.process_desc']);

            return view('partials.remarks-reason', compact('processData', 'userInfo'));

        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [RC-1100]');
            return Redirect::back()->withInput();
        }
    }


    /*
     * sent mail for payment
     */
    public function sendingMailForProceedPayment($app_id, $applicantEmail, $officerInfo, $applicantInfo)
    {
        try {
            $ref_no = CommonFunction::convert2Bangla('30.00.0000.011.31.036.2016-');

            $date = date('d/m/Y');
            $dateNow = CommonFunction::convert2Bangla($date);

            $subject = "ট্র্যাভেল এজেন্সির তিন বছর মেয়াদে নিবন্ধনের জন্য  ফি জমা দেওয়ার দাবিপত্র  ";
            $body_msg = "আপনার আবেদনের প্রেক্ষিতে জানানো যাচ্ছে যে,  'বাংলাদেশ  ট্রাভেল এজেন্সি (নিবন্ধন ও নিয়ন্ত্রণ) বিধিমালা,  ২০১৪'  বিধি-৪(১)(ক)(অ) অনুযায়ী  'বেসামরিক বিমান পরিবহন ও পর্যটন মন্ত্রণালয় - সচিবালয়-ফার্ম ও কোম্পানিসমূহের রেজিস্ট্রেশন ফি'  খাতে (কোড নম্বর ১-৫৩০১-০০০১-১৮১৬)  তিন বছর মেয়াদী নিবন্ধন ফি বাবদ ৫০০০০/- (পঞ্চাশ হাজার
) টাকা এবং  ভ্যাট (কোড নম্বর  ১-১১৩১-০০১০-০৩১১) বাবদ ৭৫০০/- (সাত হাজার পাঁচশত) টাকাসহ 
             মোট ৫৭৫০০/- (সাতান্ন হাজার পাঁচশত) টাকা  ট্রেজারী  চালানের  মাধ্যমে  বাংলাদেশ ব্যাংকে  অথবা  অনুমোদিত সোনালী ব্যাংকে জমা দিয়ে চালানের মূল কপি  <b>
             আগামী ১৫ (পনেরো) কর্ম দিবসের মধ্যে</b> রেজিস্ট্রেশন কর্তৃপক্ষের নিকট প্রেরণের জন্য অনুরোধ করা হল। 
             অন্যথায় ট্রাভেল এজেন্সির অনুকূলে নিবন্ধন সনদ প্রদানের সিদ্ধান্ত বাতিলের কার্যক্রম গ্রহণ করা হবে। ";

            if (!empty($officerInfo->signature)) {
                $signature = url('users/signature/' . $officerInfo->signature);
            } else {
                $signature = url('/assets/images/no_image.png');
            }

            $officer = "<img src='" . $signature . "' alt='Signature' width='100'/><br/>";
            $officer .= "( " . $officerInfo->user_full_name . " )<br/>";
            $officer .= "RA<br/>";

            $applicant = $applicantInfo->user_full_name . "<br/>";
            $applicant .= $applicantInfo->agency_name_bn . "<br/>";
            $applicant .= $applicantInfo->present_address . "<br/>";

            //$header = "নিবন্ধনের জন্য ফি জমা দেওয়ার দাবীপত্র  ";
            $header = "Claim submission fee for Space Allocation Application";

            $email_content = view("customized-template", compact('header', 'ref_no', 'dateNow', 'subject', 'body_msg', 'officer', 'applicant'))->render();

            $emailQueue = new EmailQueue();
            $emailQueue->process_type_id = $this->process_type_id;
            $emailQueue->app_id = $app_id;
            $emailQueue->email_content = $email_content;
            $emailQueue->email_to = $applicantEmail;
            $emailQueue->email_cc = 'ishrat@batworld.com';
            $emailQueue->email_subject = $header;
            $emailQueue->attachment = '';
            $emailQueue->secret_key = '';
            $emailQueue->pdf_type = '';
            $emailQueue->save();

        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [RC-1110]');
            return Redirect::back()->withInput();
        }
    }


    /*
     * sent mail for negative remarks
     * like shortfall, discard, challan declined
     */
    public function sendingMailWithRemarks($app_id, $applicantEmail, $applicantPhone, $status_id, $remarks)
    {
        try {

            $state = '';
            if ($status_id == 5) { // shortfall
                $state = "তথ্য  ঘাটতির জন্য  পুনরায় যথাযথ ভাবে দাখিল করতে হবে। ";
                $stateEn = 'Need to re-submit with appropriate data for the data deficit.';
            } else if ($status_id == 6) { // discarded
                $state = "বাতিল করা হয়েছে";
                $stateEn = 'has been discard.';
            } elseif ($status_id == 18) { // challan declined
                $state = "এর জন্য প্রেরণকৃত চালানটি বাতিল করা হয়েছে। আবার চালান প্রেরণ করা প্রয়োজন  ";
                $stateEn = 'Submitted invoice has been canceled. Need to send the invoice again';
            }
            $body_msg = "আপনার আবেদনের সর্বশেষ অগ্রগতি সম্পর্কে আপনাকে অবগত করার জন্য জানানো যাচ্ছে যে, আপনার আবেদনটি <b>" . $state . "</b><br/><br/>";
            $body_msg .= "এ বিষয়ে সংশ্লিষ্ট কর্তৃপক্ষের মন্তব্য- <br/>" . $remarks . "<br/> ";
            $body_msg .= "অনুগ্রহপূর্বক সিস্টেমে লগিন করে যথাযথ পদক্ষেপ গ্রহণ করুন।";
            $body_msg .= "<br/><br/><br/>";
            $body_msg .= "ধন্যবাদান্তে,<br/>";
            $body_msg .= "বেসামরিক বিমান পরিবহন ও পর্যটন মন্ত্রণালয় কর্তৃপক্ষ";

            //$header = "আপনার নিবন্ধনের আবেদন প্রসঙ্গে";
            $header = "Regarding your Space Allocation application";
            $param = $body_msg;

            $email_content = view("Users::message", compact('header', 'param'))->render();

            $emailQueue = new EmailQueue();
            $emailQueue->process_type_id = $this->process_type_id;
            $emailQueue->app_id = $app_id;
            $emailQueue->email_content = $email_content;
            $emailQueue->email_to = $applicantEmail;
            $emailQueue->email_cc = 'ishrat@batworld.com';
            $emailQueue->email_subject = $header;
            $emailQueue->sms_content = 'It is being informed that your application ' . $stateEn . ' Please check email for details and take appropriate steps.(OCPL OSS Framework)';
            $emailQueue->sms_to = $applicantPhone;
            $emailQueue->attachment = '';
            $emailQueue->secret_key = '';
            $emailQueue->pdf_type = '';

            $emailQueue->save();

        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [OF-1100]');
            return Redirect::back()->withInput();
        }
    }


    /*
     * certificate generation
     */
    public function certificateGenForUpdateBatch($process_type_id = 0, $app_id = 0, $pdf_type = "", $reg_key = "", $certificate_type = "")
    {

        $data = array();
        $data['data'] = array(
            'reg_key' => $reg_key, // Secret authentication key
            'pdf_type' => $pdf_type, // letter type
            'ref_id' => $app_id, // app_id
            'param' => array(
                'app_id' => $app_id // app_id
            )
        );

        $qrCodeGenText = "test qr code";
        $qrcodeRule = str_replace(' ', '+', $qrCodeGenText);
        $url = "http://chart.apis.google.com/chart?chs=100x100&cht=qr&chl=$qrcodeRule&choe=ISO-8859-1";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 150);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo curl_error($ch);
            echo "\n<br />";
            $response = '';
        } else {
            curl_close($ch);
        }
        // Its should be needed for officer signature
        $user_data = Users::where('id', '=', CommonFunction::getUserId())->first();
        // File path URL comes from env url variable
        $signature_url = env('sign_url') . $user_data->signature;
        $signature = '';
        if (env('SERVER_TYPE') != 'local') {
            // this is will be comment out for dev
            $signature = file_get_contents($signature_url);
        } else {
            $signature = 'No signature found';
        }

        $get_pdf_signature = DB::table('pdf_signature_qrcode')->where('app_id', $app_id)->first();

        if ($get_pdf_signature) {
            // It will use in pdf server for retrieve signature and user info
            DB::table('pdf_signature_qrcode')->where('app_id', $app_id)->where('process_type_id', $process_type_id)->update([
                'signature' => $signature,
                'user_id' => $user_data->id,
                'desk_id' => $user_data->desk_id
            ]);
        } else {
            // It will use in pdf server for retrieve signature and user info
            $pdfSinaQr = new pdfSignatureQrcode();
            $pdfSinaQr->signature = $signature;
            $pdfSinaQr->app_id = $app_id;
            $pdfSinaQr->process_type_id = $process_type_id;
            $pdfSinaQr->qr_code = $response;
            $pdfSinaQr->user_id = $user_data->id;
            $pdfSinaQr->desk_id = $user_data->desk_id;
            $pdfSinaQr->save();
        }
        // End of the signature function

        $encode_data = json_encode($data);
        switch ($pdf_type) {

            case 'tacert.en.d':
            case 'tacert.bn.d':
            case 'ta.cert.en.uat':
            case 'ta.cert.bn.uat':
                // Request send to the pdf server
                $pdf_info = PdfServiceInfo::where('certificate_type', $certificate_type)->first();
                $url_request = $pdf_info->pdf_server_url . "api/new-job?requestData=$encode_data";
                $url_store = new PdfPrintRequest();
                $url_store->app_id = $app_id;
                $url_store->process_type_id = $this->process_type_id;
                $url_store->url_request = $url_request;
                $url_store->save();

                break;

            default:
        }

        return true; // return true for success
    }


    public function certificate_gen($application_id)
    {
        try {
            ini_set('memory_limit', '99M');
            $mpdf = new mPDF(
                'utf-8', // mode - default ''
                'A4', // format - A4, for example, default ''
                9, // font size - default 0
                'Times New Roman', // default font family
                17, // margin_left
                10, // margin right
                30, // margin top
                10, // margin bottom
                9, // margin header
                9, // margin footer
                'P'
            );
            $mpdf->useSubstitutions;
            $mpdf->SetProtection(array('print'));
            $mpdf->SetDefaultBodyCSS('color', '#000');
            $mpdf->SetTitle("OCPL OSS Framework $application_id");
            $mpdf->SetSubject("OCPL OSS Framework $application_id");
            $mpdf->SetAuthor("Business Automation Limited");
            $mpdf->SetHTMLHeader('<img src="assets/images/01_BHTP_New_Logo.png" alt="BHTP" width="50" height="75"/>');
            $mpdf->SetWatermarkImage('assets/images/01_BHTP_New_Logo.png');
            $mpdf->showWatermarkImage = false;
            $mpdf->setFooter('{PAGENO} / {nb}');

            $baseURL = "uploads/";
            $directoryName = $baseURL . date("Y/m");
            $directoryNameYear = $baseURL . date("Y");

            if (!file_exists($directoryName)) {
                $oldmask = umask(0);
                mkdir($directoryName, 0777, true);
                umask($oldmask);
                $f = fopen($directoryName . "/index.html", "w");
                fclose($f);
                if (!file_exists($directoryNameYear . "/index.html")) {
                    $f = fopen($directoryNameYear . "/index.html", "w");
                    fclose($f);
                }
            }
            $certificateName = uniqid("BHTP_" . $application_id . "_", true);

            $mpdf->autoScriptToLang = true;
            $mpdf->baseScript = 1;
            $mpdf->autoVietnamese = true;
            $mpdf->autoArabic = true;

            $mpdf->autoLangToFont = true;
            $mpdf->SetDisplayMode('fullwidth');

            $alreadyExistApplicant = Processlist::leftJoin('space_allocation', 'space_allocation.id', '=', 'process_list.ref_id')
                ->where('process_list.process_type_id', $this->process_type_id)
                ->where('process_list.ref_id', $application_id)
                ->first();

            $directory = 'users/signature/';
            $approver = 'Mohammed Ayub';
            $signature = (file_exists($directory . 'rd3-sign.jpg')) ?
                'users/signature/rd3-sign.jpg' : '';

            if (!$alreadyExistApplicant) {
                return '';
            } else {

                $track_no = (!empty($alreadyExistApplicant->tracking_no) ? $alreadyExistApplicant->tracking_no : '');
                $ApproveData = ProcessListHist::where('process_id', $alreadyExistApplicant->process_id)->where('ref_id', $application_id)
                    ->where('status_id', 23)// 23 = approved
                    ->where('process_type', $this->process_type_id)
                    ->orderBy('id', 'desc')->first();

                $formatted_date = '';
                if (!empty($ApproveData->created_at)) {
                    $formatted_date = date_format($ApproveData->created_at, "d-M-Y");
                }
                $dateNow = !empty($formatted_date) ? $formatted_date : '';

                $ReceivedData = ProcessListHist::where('process_id', $alreadyExistApplicant->process_id)->where('ref_id', $application_id)
                    ->where('status_id', 1)// 1 = submitted
                    ->orderBy('id', 'desc')->first();


                $app_received_date = '';
                if (!empty($ReceivedData->created_at)) {
                    $app_received_date = date_format($ReceivedData->created_at, "d-M-Y");
                }

                $proposed_name = (!empty($alreadyExistApplicant->proposed_name) ? $alreadyExistApplicant->proposed_name : '');
                $product_name = (!empty($alreadyExistApplicant->product_name) ? $alreadyExistApplicant->product_name : '');

                $eco_zone_id = (!empty($alreadyExistApplicant->eco_zone_id) ? $alreadyExistApplicant->eco_zone_id : '');
                $economicZones = ParkInfo::where('id', $eco_zone_id)->first(['park_name', 'upazilla_name', 'district_name', 'park_area']);

                $organization_type = (!empty($alreadyExistApplicant->organization_type) ? $alreadyExistApplicant->organization_type : '');
                $business_type = (!empty($alreadyExistApplicant->business_type) ? $alreadyExistApplicant->business_type : '');
                $industry_type = (!empty($alreadyExistApplicant->industry_type) ? $alreadyExistApplicant->industry_type : '');

                $total_employee = $alreadyExistApplicant->gr_total_5; // have to consider 5th year only according to new changes
                $foreign_employee = $alreadyExistApplicant->for_total_5; // have to consider 5th year only according to new changes

                $qrCodeGenText = $alreadyExistApplicant->tracking_number . '-' . $alreadyExistApplicant->proposed_name . '-' .
                    $economicZones->name . '-' . $dateNow;
                $qrcodeRule = str_replace(' ', '+', $qrCodeGenText);
                $url = "http://chart.apis.google.com/chart?chs=100x100&cht=qr&chl=$qrcodeRule&choe=ISO-8859-1";
                $pdf_body = View::make("SpaceAllocation::certificate", compact('track_no', 'dateNow', 'proposed_name', 'alreadyExistApplicant', 'business_type', 'app_received_date', 'product_name', 'export_percentage', 'domestic_percentage', 'industry_type', 'production_capacity', 'total_employee', 'foreign_employee', 'organization_type', 'machineries_cost', 'm_state', 'url', 'signature', 'approver'))
                    ->render();


                $mpdf->SetCompression(true);
                $mpdf->WriteHTML($pdf_body);

                $pdfFilePath = $directoryName . "/" . $certificateName . '.pdf';

                $mpdf->Output($pdfFilePath, 'F'); // Saving pdf *** F for Save only, I for view only.
                return $pdfFilePath;
            }
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }
    }

    public function discardCertificate($id) {
        try {

            $app_id = Encryption::decodeId($id);
            if (Auth::user()->user_type == '1x101') {
                $processData = P2ProcessList::where(array('ref_id'=>$app_id))->first();
                $appInfo = LoanLocator::find($app_id);

                if ($processData->process_status_id == 25) {
                    $appInfo->certificate = '';
                    $appInfo->save();

                    P2ProcessList::where(['ref_id' => $app_id, 'process_type_id' => $this->process_type_id])
                        ->update(['desk_id' => 0, 'process_status_id' => 40]);
                    Session::flash('success', 'Certificate Discard');
                    return redirect()->back();
                } else {
                    Session::flash('error', 'Certificate discard is not possible [PC9002]');
                    return redirect()->back();
                }
            } else {
                Session::flash('error', 'You are not authorized to discard certificate [PC9001]');
                return redirect()->back();
            }
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }
    }


    public function certificate_re_gen($id) {
        try {

            $app_id = Encryption::decodeId($id);
            $processlistExist = ProcessList::where('ref_id', $app_id)->where('process_type_id', $this->process_type_id)->first(['process_type_id', 'status_id']);

            $status_id = $processlistExist->status_id;
            if ($status_id == 25) {
                $billMonth = date('Y-m');

                $certificate = $this->certificate_gen($app_id);
                //Certificate Generation after payment accepted
                LoanLocator::where('id', $app_id)->update(['certificate' => $certificate, 'bill_month' => $billMonth]);

                $this->bhtpCertificateReGenFromPdfServer($app_id, 0);

            }
            Session::flash('success', 'Certificate Re-generate Successfully');
            return redirect()->back();
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }
    }

    public function bhtpCertificateReGenFromPdfServer($app_id = 0, $other_significant_id = 0) {
        try {
            // Starting pdf server code
            $pdf_info = PdfServerInfo::where('certificate_type', 'sa')->first();

            $pdf_type = $pdf_info->project_code . "." . $pdf_info->certificate_type . "." . $pdf_info->server_type;
            $reg_key = $pdf_info->key;

            $data = array();
            $data['data'] = array(
                'reg_key' => trim($reg_key), // Secret authentication key
                'pdf_type' => $pdf_type, // PDF certificate type
                'ref_id' => $app_id, // app_id
                'param' => array(
                    'app_id' => $app_id,
                    'service_id' => $this->process_type_id,
                    'other_significant_id' => $other_significant_id, // will be 0 as it is not applicable here
                )
            );
            $encode_data = json_encode($data);
            $url_request = $pdf_info->pdf_server_url . "api/new-job?requestData=$encode_data";

            switch ($pdf_type) {
                case 'BHTP.sa.local':
                case 'BHTP.sa.l':
                case 'BHTP.sa.d':
                case 'BHTP.sa.uat':

                    // Saving data into pdf_print_requests table
                    $url_store = PdfPrintRequest::firstOrNew([
                        'app_id' => $app_id,
                        'other_significant_id' => $other_significant_id,
                        'service_id' => $this->process_type_id
                    ]);
                    $url_store->url_request = $url_request;
                    $url_store->status = 0; // for new as well as old
                    $url_store->save();

                    break;

                default:
            }

            // Saving data into pdf_queue table
            $pdfQueue = pdfQueue::firstOrCreate([
                'app_id' => $app_id,
                'other_significant_id' => $other_significant_id,
                'service_id' => $this->process_type_id
            ]);

            $pdfQueue->secret_key = $reg_key;
            $pdfQueue->pdf_type = $pdf_type;
            $pdfQueue->status = 0; // for new as well as old
            $pdfQueue->save();

            return true; // return true for success
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }
    }
    public function certificateAndOther($ref_id, $process_type_id){
        $ref_id = Encryption::decodeId($ref_id);
        $process_type_id = Encryption::decodeId($process_type_id);

        $appInfo = ProcessList::leftJoin('space_allocation as apps', 'apps.id', '=', 'process_list.ref_id')
            ->where('process_list.ref_id', $ref_id)
            ->where('process_list.process_type_id', $process_type_id)
            ->first([
                'process_list.company_id',
                'process_list.desk_id',
                'process_list.park_id',
                'process_list.ref_id',
                'process_list.tracking_no',
                'process_list.status_id',
                'process_list.locked_by',
                'process_list.locked_at',
                'apps.*',
            ]);

        $status_from = $appInfo->status_id;
        $applyStausId = 25;
        $company_emails = Users::where('user_sub_type', $appInfo->company_id)
            ->where('is_approved', 1)
            ->where('user_status', 'active')
            ->get(['user_email', 'user_phone']);

        $billMonth = date('Y-m');
        $body_msg = '<span style="color:#000;text-align:justify;"><b>';

        $body_msg .= 'Your application for Space Allocation on OCPL OSS Framework with Tracking Number: ' . $appInfo->tracking_no . ' is now in status: <b>' . CommonFunction::getFieldName($appInfo->status_id, 'id', 'status_name', 'process_status') . '</b>';
        $body_msg .= '</span>';
        $body_msg .= '<br/><br/><br/>Thanks<br/>';
        $body_msg .= '<b>OCPL OSS Framework</b>';
        $certificate = $this->certificate_gen($appInfo->ref_id);   //Certificate Generation after payment accepted
        LoanLocator::where('id', $appInfo->ref_id)->update(['certificate' => $certificate, 'bill_month' => $billMonth]);

        $header = "Application Update Information for OCPL OSS Framework Space Allocation";
        $param = $body_msg;
        $email_content = view("Users::message", compact('header', 'param'))->render();
//                    dd($company_emails);
//                    foreach ($company_emails as $companyuser) {
//                        $emailQueue = new EmailQueue();
//                        dd('hihi');
//
//                        $emailQueue->service_id = 1; // process_type_id of project Space Allocation
//                        $emailQueue->app_id = $application_id;
//
//                        //email info
//                        $emailQueue->email_content = $email_content;
//                        $emailQueue->email_to = $companyuser->user_email;
//                        $emailQueue->email_subject = $header;
//                        $emailQueue->attachment = $certificate;
//
//                        // sms info
//                        if ($applyDeskId == 20) {
//                            // TODO::will add later
////                            $emailQueue->sms_to = $companyuser->user_phone;
////                            $emailQueue->sms_content = 'Your application has been submitted with tracking id: ' . $process_data->tracking_no .
////                            ' received. Please fill up your Pay order information!';
//                        }
//                        $emailQueue->save();
//                    }





        DB::commit();

        // for previous and present status
        $appStatus = ProcessStatus::where('status', 1)->get();
        $statusList = array();
        foreach ($appStatus as $k => $v) {
            $statusList[$v->id] = $v->status_name;
        }

        Session::flash('success', "Application status updated Previous status: $statusList[$status_from] || Present Status: $statusList[$applyStausId]");
        return redirect()->back();
    }



    public function updateADInfo($ref_id, $process_type_id){
        $ref_id = Encryption::decodeId($ref_id);
        $process_type_id = Encryption::decodeId($process_type_id);

        $appInfo = ProcessList::leftJoin('space_allocation as apps', 'apps.id', '=', 'process_list.ref_id')
            ->where('process_list.ref_id', $ref_id)
            ->where('process_list.process_type_id', $process_type_id)
            ->first([
                'process_list.ref_id',
            ]);

        $requestData=session('requestData');
        // Update AD desk info
        $adData = array(
            'ad_desk_level' => $requestData['ad_desk_level'],
            'ad_desk_space' =>$requestData['ad_desk_space'],
            'ad_desk_security_deposite' => $requestData['ad_desk_security_deposite'] ,
            'ad_desk_rent' =>$requestData['ad_desk_rent'],
            'ad_desk_service_charge' => $requestData['ad_desk_service_charge'],
            'ad_desk_remarks' =>$requestData['ad_desk_remarks'],
        );

        LoanLocator::where('id', $appInfo->ref_id)->update($adData);
        Session::flash('success', "Application status updated successfully");


        return redirect()->back();
    }


//it will be enable after duplicate certificate functionality is on
//    public function downloadCertificate($certificateLink,Request $request){
//
//        try{
//            $certificateLink = Encryption::decode($certificateLink);
//            $userSubType = $request->get('userSubType');
//            $certificateType = $request->get('certificateType');
//            $agencyRegApps = SpaceAllocation::where('agency_id', '=', $userSubType)
//                ->where('status_id', '=', 25)
//                ->where('certificate_en','!=','')
//                ->where('certificate_bn','!=','')
//                ->orderby('created_at', 'DESC')
//                ->first();
//
//            $download_update = 0;
//            if( $certificateType == 'certificate_en'){
//                if($agencyRegApps->download_certificate_en == 0){
//                    $data=['download_certificate_en'=>1];
//                    $download_update = SpaceAllocation::where('agency_id',$userSubType)->where('status_id',25)->where('certificate_en','!=','')->where('certificate_bn','!=','')->update($data);
//                }
//            }
//            if( $certificateType == 'certificate_bn'){
//                if($agencyRegApps->download_certificate_bn == 0){
//                    $data=['download_certificate_bn'=>1];
//                    $download_update = SpaceAllocation::where('agency_id',$userSubType)->where('status_id',25)->where('certificate_en','!=','')->where('certificate_bn','!=','')->update($data);
//                }
//            }
//
//            if($download_update){
//                return response()->json(array(
//                    'success' => true,
//                    'data' => 'Successfully Certificate Downloaded!'
//                ));
//            }else{
//                return response()->json(array(
//                    'success' => false,
//                    'data' => 'There is an error ocured ! Please try later'
//                ));
//            }
//            dd($download_update);
//        }catch (Exception $e){
//            return response()->json(array(
//                'error' => true,
//                'data' => $e->getMessage()
//            ));
//        }
//
//    }

    /*     * ********************************************End of Controller Class************************************************* */
}
