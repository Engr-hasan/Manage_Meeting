<?php

namespace App\Modules\MeetingForm\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Modules\Apps\Models\AppDocuments;
use App\Modules\Apps\Models\DocInfo;
use App\Modules\Apps\Models\EmailQueue;
use App\Modules\Apps\Models\pdfQueue;
use App\Modules\Apps\Models\pdfSignatureQrcode;
use App\Modules\Apps\Models\ProcessListHist;
use App\Modules\BoardMeting\Models\BoardMeting;
use App\Modules\LoanLocator\Models\LoanType;
use App\Modules\MeetingForm\Models\Achievement;
use App\Modules\MeetingForm\Models\ConstructiveActivities;
use App\Modules\MeetingForm\Models\HumanResource;
use App\Modules\MeetingForm\Models\IncreasingEfficiency;
use App\Modules\MeetingForm\Models\MeetingApp;
use App\Modules\MeetingForm\Models\NewMemberInclude;
use App\Modules\MeetingForm\Models\NextMonthPlan;
use App\Modules\MeetingForm\Models\NotableInformation;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessStatus;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\Settings\Models\BankBranch;
use App\Modules\Settings\Models\PdfPrintRequest;
use App\Modules\Settings\Models\PdfPrintRequestQueue;
use App\Modules\Settings\Models\PdfServerInfo;
use App\Modules\Settings\Models\PdfServiceInfo;
use App\Modules\SpaceAllocation\Models\Sponsors;
use App\Modules\SpaceAllocation\Models\TradeBody;
use App\Modules\Settings\Models\Bank;
use App\Modules\Settings\Models\Configuration;
use App\Modules\LoanLocator\Models\LoanLocator;
use App\Modules\Users\Models\AreaInfo;
use App\Modules\Users\Models\Users;
use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Mockery\CountValidator\Exception;
use Mpdf\Mpdf;
use Symfony\Component\HttpFoundation\AcceptHeader;
use yajra\Datatables\Datatables;
use \ParagonIE\EasyRSA\KeyPair;
use \ParagonIE\EasyRSA\EasyRSA;

class MeetingFormController extends Controller
{

    protected $process_type_id;

    public function __construct()
    {
        if (Session::has('lang'))
            App::setLocale(Session::get('lang'));
        $this->process_type_id = 10; // 10 is Meeting Form
    }


    public function form()
    {
        return \view('SpaceAllocation::new-form');
    }


    /*
     * Show application form
     */
    public function applicationForm()
    {
        try {
            $company_id=CommonFunction::getUserSubTypeWithZero();

            $previous_month = date('Y-m');
            $checkexists = ProcessList::where('company_id',$company_id)
                ->where('created_at','like',$previous_month.'%')
                ->first(['ref_id','status_id','created_at']);

                if (count($checkexists)>0){
                    Session::flash('error', 'Application is already exists in this month.');
                    return Redirect::to(URL::to('meeting-form/list/'.Encryption::encodeId($this->process_type_id)));
                }


            return view("MeetingForm::application-form", compact('data'));
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

        if (in_array($user_type, ['4x404','13x303'])) {

            $company_id = CommonFunction::getUserSubTypeWithZero();
            $data = ProcessList::where([
                'ref_id' => $applicationId,
                'process_type_id' => $process_type_id,
            ])->first(['status_id', 'created_by', 'company_id', 'tracking_no']);
            if ($data->company_id == $company_id && in_array($data->status_id, [-1, 5, 6])) {
                $openMode = 'edit';
            } else {
                $openMode = 'view';
            }
        } else {
            $openMode = 'view';
        }

        try {

            $process_type_id = $this->process_type_id;
            $appInfo = ProcessList::leftJoin('achievement as apps', 'apps.id', '=', 'process_list.ref_id')
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
                    'process_list.priority',
                    'user_desk.desk_name',
                    'ps.status_name',
                    'ps.color',
                    'apps.*'
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
            $PrvNotableInfo = NotableInformation::where('app_id',$applicationId)->get();
            $consActivity = ConstructiveActivities::where('app_id',$applicationId)->get();

            $efficency = IncreasingEfficiency::where('app_id',$applicationId)->get();
            $humanresource = HumanResource::where('app_id',$applicationId)->get();
            $newmember = NewMemberInclude::where('app_id',$applicationId)->get();

            $nextmonthplan = NextMonthPlan::where('app_id',$applicationId)->get();

            //$statusName = ProcessStatus::whereIn('id', explode(',', $getStatus))->where('process_type_id', $this->process_type_id)->get(['status_name', 'id']);
            $statusArray = ProcessStatus::where('process_type_id', $this->process_type_id)->lists('status_name', 'id');
            $viewMode = 'SecurityBreak';
            $statusId=$appInfo->status_id;
            if ($statusId == 1) {
                $viewMode = 'on';
                $mode = '-V-';
            } else if ($openMode == 'edit') {
                $viewMode = 'off';
                $mode = '-E-';
            }
            return view('MeetingForm::application-form-edit',
                compact('viewMode', 'mode', 'banks', 'data', 'statusArray', 'statusName', 'appInfo', 'verificationData', 'clrDocuments', 'process_history', 'hasDeskParkWisePermission','PrvNotableInfo','consActivity','efficency','humanresource','newmember','nextmonthplan'));
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [RC-1040]');
            return Redirect::back()->withInput();
        }
    }

    public function getBranch(Request $request)
    {
        $branch_name = BankBranch::where('bank_id', $request->get('bank_id'))->where('is_active', 1)->orderBy('branch_name')->lists('branch_name', 'id');
        $data = ['responseCode' => 1, 'data' => $branch_name];
        return response()->json($data);
    }

    /*
     * Application Store function
     */
    public function appStore(Request $request)
    {
        $applicationid="";
        if ($request->get('app_id')!=""){
            $applicationid= Encryption::decodeId($request->get('app_id'));
        }


        $rules = [
            'team_name' => 'required',
            'no_of_member' => 'required',
            'women_member' => 'required',
            'intern_member' => 'required'
        ];

        // Validate company logo
        if ($request->get('actionBtn') != 'draft') {
//            $this->validate($request, $rules);
        }
        try {

            DB::beginTransaction();
            $companyId = CommonFunction::getUserSubTypeWithZero();
            // Check existing application
            $statusArr = array(5, 8, 22, '-1'); //5 is shortfall, 8 is Discard, 22 is Rejected Application and -1 is draft
            if ($applicationid == ""){
//                $year=date('Y');
//                $month=date('m');
//                $monthdate=$year.'-'.$month.'-';
//
//                $noofdays=30;
//                $checkexists=ProcessList::where('company_id',$companyId)
//                    ->orderBy('created_at','desc')
//                    ->first(['created_at']);


//
//                if(count($checkexists)>0){
//                    $date1 = new DateTime($checkexists->created_at);
//                    $date2 = new DateTime();
//                    $interval = $date2->diff($date1);
//                    $year=$interval->y*365;
//                    $month=$interval->m*30;
//                    $day=$interval->d;
//                    $totalday=$year+$month+$day;
//
//                    if ($totalday <=$noofdays){
//                        Session::flash('error', 'Application is already exists in this month.');
//                        return Redirect::to(URL::to('meeting-form/list/'.Encryption::encodeId($this->process_type_id)));
//                    }
//
//
//                }


                $previous_month = date('Y-m');
                $checkexists=ProcessList::where('company_id',$companyId)
                    ->where('created_at','like',$previous_month.'%')
                    ->first(['ref_id','status_id','created_at']);

                if (count($checkexists)>0){
                    Session::flash('error', 'Application is already exists in this month.');
                    return Redirect::to(URL::to('meeting-form/list/'.Encryption::encodeId($this->process_type_id)));
                }





                /*$checkexists=ProcessList::where('company_id',$companyId)
                    ->where('created_at','like',$monthdate.'%')
                    ->count();

                if($checkexists >=1){
                    Session::flash('error', 'Application is already exists in this month.');
                    return Redirect::to(URL::to('meeting-form/list/'.Encryption::encodeId($this->process_type_id)));
                }*/
                $achievement=new Achievement();
                $processData=new ProcessList();
            }else{
                $achievement=Achievement::where('id',$applicationid)->first();
                $processData=ProcessList::where('process_type_id',$this->process_type_id)
                    ->where('ref_id',$applicationid)
                    ->first();
            }
//            $achievement->team_name="প্রয়োজনীয় তথ্য";
            $achievement->team_name=$request->get('team_name');
            $achievement->team_leader_name=Auth::user()->user_full_name;
            $achievement->no_of_member=$request->get('no_of_member');
            $achievement->women_member=$request->get('women_member');
            $achievement->intern_member=$request->get('intern_member');

            $achievement->save();




            $data = $request->all();

            $previous_month_main_work=$request->get('previous_month_main_work_info');
            if($applicationid !=''){
                NotableInformation::where('app_id', $applicationid)->delete();
            }
//            dd($previous_month_main_work);
            if (count($previous_month_main_work)>0){
                    foreach ($previous_month_main_work as $key => $value) {
                    $notableinfo = new NotableInformation();
                        if (isset($data['oldone'])){
                            if (isset($data['oldone'][$key])){
                                $notableinfo->is_old=1;
                            }
                        }
                        $notableinfo->app_id = $achievement->id;

                    /*if (CommonFunction::asciiCharCheck($previous_month_main_work[$key])){

                    }else{
                        Session::flash('error', 'non-ASCII Characters found in objective [OB-1002]');

                        return Redirect::to(URL::previous());
                    }*/
                        $notableinfo->description = $previous_month_main_work[$key];

                        $notableinfo->save();
                }
            }

            if($applicationid !='') {
                ConstructiveActivities::where('app_id', $applicationid)->delete();
            }
            if (count($data['previous_month_constructive_work'])>0){
                foreach ($data['previous_month_constructive_work'] as $key => $value) {
                    $ConstructiveActivity = new ConstructiveActivities();
                    if (isset($data['oldtwo'])){
                        if (isset($data['oldtwo'][$key])){
                            $ConstructiveActivity->is_old=1;
                        }
                    }
                    $ConstructiveActivity->app_id = $achievement->id;
                    $data['previous_month_constructive_work'][$key];

                    /*if (CommonFunction::asciiCharCheck($data['previous_month_constructive_work'][$key])){
                        $ConstructiveActivity->description = $data['previous_month_constructive_work'][$key];
                    }else{
                        Session::flash('error', 'non-ASCII Characters found in objective [OB-1002]');
                        return Redirect::to(URL::previous());
                    }*/
                    $ConstructiveActivity->description = $data['previous_month_constructive_work'][$key];
                    $ConstructiveActivity->save();
                }
            }


            //dd('ss');
            if($applicationid !='') {
                IncreasingEfficiency::where('app_id', $applicationid)->delete();
            }
            if (isset($data['increase_capability'])){
                foreach ($data['increase_capability'] as $key => $value) {
                    $efficiency = new IncreasingEfficiency();
                    if (isset($data['oldthree'])){
                        if (isset($data['oldthree'][$key])){
                            $efficiency->is_old=1;
                        }
                    }
                    $efficiency->app_id = $achievement->id;
                    /*if (CommonFunction::asciiCharCheck($data['increase_capability'][$key])){
                        $efficiency->description = $data['increase_capability'][$key];
                    }else{
                        Session::flash('error', 'non-ASCII Characters found in objective [OB-1002]');
                        return Redirect::to(URL::previous());
                    }*/
                    $efficiency->description = $data['increase_capability'][$key];
                    $efficiency->save();
                }
            }

            if($applicationid !='') {
                HumanResource::where('app_id', $applicationid)->delete();
            }
            if (count($data['manpower_uses'])>0){
                foreach ($data['manpower_uses'] as $key => $value) {
                    $humanresource = new HumanResource();
                    if (isset($data['oldfour'])){
                        if (isset($data['oldfour'][$key])){
                            $humanresource->is_old=1;
                        }
                    }

                    $humanresource->app_id = $achievement->id;
                   /* if (CommonFunction::asciiCharCheck($data['manpower_uses'][$key])){
                        $humanresource->description = $data['manpower_uses'][$key];
                    }else{
                        Session::flash('error', 'non-ASCII Characters found in objective [OB-1002]');
                        return Redirect::to(URL::previous());
                    }*/
                    $humanresource->description = $data['manpower_uses'][$key];
                    $humanresource->save();
                }
            }
            if($applicationid !='') {
                NewMemberInclude::where('app_id', $applicationid)->delete();
            }
            if (count($data['new_member'])>0){
                foreach ($data['new_member'] as $key => $value) {
                    $newmember = new NewMemberInclude();
                    if (isset($data['oldfive'])){
                        if (isset($data['oldfive'][$key])){
                            $newmember->is_old=1;
                        }
                    }

                    $newmember->app_id = $achievement->id;
                    /*if (CommonFunction::asciiCharCheck($data['new_member'][$key])){
                        $newmember->description = $data['new_member'][$key];
                    }else{
                        Session::flash('error', 'non-ASCII Characters found in objective [OB-1002]');
                        return Redirect::to(URL::previous());
                    }*/
                    $newmember->description = $data['new_member'][$key];

                    $newmember->save();
                }
            }
            if($applicationid !='') {
                NextMonthPlan::where('app_id', $applicationid)->delete();
            }
            if (count($data['next_month_initiative'])>0){
                foreach ($data['next_month_initiative'] as $key => $value) {
                    $nextmonthplan = new NextMonthPlan();
                    /*if (isset($data['oldsix'])){
                        if (isset($data['oldsix'][$key])){
                            $nextmonthplan->is_old=1;
                        }
                    }*/

                    $nextmonthplan->app_id = $achievement->id;
                    /*if (CommonFunction::asciiCharCheck($data['next_month_initiative'][$key])){
                        $nextmonthplan->description = $data['next_month_initiative'][$key];
                    }else{
                        Session::flash('error', 'non-ASCII Characters found in objective [OB-1002]');
                        return Redirect::to(URL::previous());
                    }*/
                    $nextmonthplan->description = $data['next_month_initiative'][$key];
                    $nextmonthplan->save();
                }
            }

            if ($request->get('actionBtn') == "draft") {
                $processData->status_id = -1;
                $processData->desk_id = 0;
            } else {

                if ($processData->status_id == 5) { // For shortfall
                    $processData->status_id = 2;
                } else {
                    $processData->status_id = 1;
                }
                $processData->desk_id = 0; // 2 is desk RD2
            }


            $processData->company_id =$companyId;
            $processData->ref_id = $achievement->id;
            $processData->process_type_id = $this->process_type_id;
            //$processData->park_id = $data['park_id'];

      /*      $jsonData['Headline'] = $request->get('task_name');
            $jsonData['Discussion'] = $request->get('comments');
            $jsonData['Remarks'] = $request->get('remarks');
            $processData['json_object'] = json_encode($jsonData);*/

            // Generate Tracking No for Submitted application

            $processData->save();


            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2) { // when application submitted but not as re-submitted
                $trackingPrefix = "AC" . date("dmY");
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
            DB::commit();

            if ($processData->status_id == -1) {
                \Session::flash('success', 'Successfully updated the Application!');
            } elseif ($processData->status_id == 1) {
                Session::flash('success', 'Successfully Application Submitted !');
            } elseif ($processData->status_id == 2) {
                Session::flash('success', 'Successfully Application Re-Submitted !');
            } else {
                Session::flash('error', 'Failed due to Application Status Conflict. Please try again later!');
            }
            return redirect('meeting-form/list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            dd($e->getLine(), $e->getMessage(), $e->getFile());
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [RC-1060]');
            return redirect()->back()->withInput();
        }
    }

    public  function downloadAsPdf($app_id){

        $process_type_id=$this->process_type_id;
        $applicationId=Encryption::decodeId($app_id);


       try{
           $appInfo = ProcessList::leftJoin('achievement as apps', 'apps.id', '=', 'process_list.ref_id')
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
                'process_list.created_at as process_created',
                'process_list.park_id',
                'process_list.process_type_id',
                'process_list.status_id',
                'process_list.locked_by',
                'process_list.locked_at',
                'process_list.ref_id',
                'process_list.tracking_no',
                'process_list.company_id',
                'process_list.process_desc',
                'process_list.priority',
                'user_desk.desk_name',
                'ps.status_name',
                'ps.color',
                'apps.*'
            ]);

        $contents = view("MeetingForm::application-pdf",compact('appInfo'))->render();;
           $mpdfConfig = array(
               'mode' => 'utf-8',
               'format' => 'A4',
               'default_font_size' => 12,
               'default_font' => 'dejavusans',
               'margin_left' => 15,
               'margin_right' => 15,
               'margin_top' => 15,
               'margin_bottom' => 15,
               'margin_header' => 10,
               'margin_footer' => 10,
               'orientation' => 'L'
           );

           $mpdf = new \Mpdf\Mpdf($mpdfConfig);
           $mpdf->useSubstitutions;
           $mpdf->SetProtection(array('print'));
           $mpdf->SetDefaultBodyCSS('color', '#000');
           $mpdf->SetTitle("BMAS One Stop Service");
           $mpdf->SetSubject("Subject");
           $mpdf->SetAuthor("Business Automation Limited");
           $mpdf->autoScriptToLang = true;
           $mpdf->baseScript = 1;
           $mpdf->autoVietnamese = true;
           $mpdf->autoArabic = true;
           $mpdf->autoArabic = true;
           $mpdf->autoLangToFont = true;
           $mpdf->SetDisplayMode('fullwidth');
           $mpdf->SetHTMLFooter('
                        <table width="100%">
                            <tr>
                                <td width="50%"><i style="font-size: 10px;">Download time: {DATE j-M-Y h:i a}</i></td>
                                <td width="50%" align="right"><i style="font-size: 10px;">{PAGENO}/{nbpg}</i></td>
                            </tr>
                        </table>');
           $stylesheet = file_get_contents('assets/stylesheets/appviewPDF.css');

           $mpdf->setAutoTopMargin = 'stretch';
           $mpdf->setAutoBottomMargin = 'stretch';
           $mpdf->WriteHTML($stylesheet, 1);
           $mpdf->WriteHTML($contents, 2);
           $mpdf->defaultfooterfontsize = 10;
           $mpdf->defaultfooterfontstyle = 'B';
           $mpdf->defaultfooterline = 0;
           $mpdf->SetCompression(true);
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
           $certificateName = uniqid("board-meeting_", true);
           $pdfFilePath = $directoryName . "/" . $certificateName . '.pdf';
           $mpdf->Output();
           return Redirect::to(URL::previous() . "#step14");
    } catch (\Exception $e) {
        Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [CR-1115]');
        return Redirect()->back()->withInput();
        }
    }



    public function appsDownloadPDF($app_id){

        try {
//            $applicationId = Encryption::decodeId($app_id);
            $app_id = 49;

            /*$process_type_id = $this->process_type_id;
            $appInfo = ProcessList::leftJoin('meeting_app as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })

                ->where('process_list.ref_id', $app_id)
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
                    'process_list.priority',
                    'user_desk.desk_name',
                    'ps.status_name',
                    'ps.color',
                    'apps.*'
                ]);*/

            $appData = Achievement::leftJoin('previous_month_constructive_activities', 'achievement.id', '=', 'previous_month_constructive_activities.app_id')

                ->leftJoin('previous_month_use_human_resources', 'achievement.id', '=', 'previous_month_use_human_resources.app_id')
                ->leftJoin('previous_month_increasing_efficiency', 'achievement.id', '=', 'previous_month_increasing_efficiency.app_id')
                ->leftJoin('previous_month_new_members_include', 'achievement.id', '=', 'previous_month_new_members_include.app_id')
                ->leftJoin('planned_enterprises_next_month', 'achievement.id', '=', 'planned_enterprises_next_month.app_id')
                ->leftJoin('previous_month_notable_information', 'achievement.id', '=', 'previous_month_notable_information.app_id')

//                ->where('achievement.id', $app_id)

                ->groupBy('previous_month_constructive_activities.app_id')
                ->groupBy('previous_month_use_human_resources.app_id')
                ->groupBy('previous_month_increasing_efficiency.app_id')
                ->groupBy('previous_month_new_members_include.app_id')
                ->groupBy('planned_enterprises_next_month.app_id')
                ->groupBy('previous_month_notable_information.app_id')
                ->get([
                    'achievement.*',
                    DB::raw('group_concat(distinct(previous_month_constructive_activities.description)) as constructive_activities'),
                    DB::raw('group_concat(distinct( CONCAT(previous_month_constructive_activities.description, \'#\', previous_month_constructive_activities.is_old) )) as is_old_constructive_activities'),
                    DB::raw('group_concat(distinct(previous_month_use_human_resources.description)) as human_resources'),
                    DB::raw('group_concat(distinct( CONCAT(previous_month_use_human_resources.description, \'#\', previous_month_use_human_resources.is_old) )) as is_old_human_resources'),
                    DB::raw('group_concat(distinct(previous_month_increasing_efficiency.description)) as increasing_efficiency'),
                    DB::raw('group_concat(distinct( CONCAT(previous_month_increasing_efficiency.description, \'#\', previous_month_increasing_efficiency.is_old) )) as is_increasing_efficiency'),
                    DB::raw('group_concat(distinct(previous_month_new_members_include.description)) as members_include'),
                    DB::raw('group_concat(distinct( CONCAT(previous_month_new_members_include.description, \'#\', previous_month_new_members_include.is_old) )) as is_members_include'),
                    DB::raw('group_concat(distinct(planned_enterprises_next_month.description)) as next_month'),
                    DB::raw('group_concat(distinct( CONCAT(planned_enterprises_next_month.description, \'#\', planned_enterprises_next_month.is_old) )) as is_next_month'),
                    DB::raw('group_concat(distinct(previous_month_notable_information.description)) as notable_information'),
                    DB::raw('group_concat(distinct( CONCAT(previous_month_notable_information.description, \'#\', previous_month_notable_information.is_old) )) as is_notable_information'),
//                    'previous_month_constructive_activities.id as aaaaa',
//                    'previous_month_constructive_activities.description',
//                    'previous_month_use_human_resources.id as hid',
//                    'previous_month_use_human_resources.description',
//                    'previous_month_increasing_efficiency.id',
//                    'previous_month_increasing_efficiency.description',
//                    'previous_month_new_members_include.id',
//                    'previous_month_new_members_include.description',
//                    'planned_enterprises_next_month.id',
//                    'planned_enterprises_next_month.description',
//                    'previous_month_notable_information.id',
//                    'previous_month_notable_information.description',
                ]);
//            dd($appData);

            /*$achievements = Achievement::all();
            $activities = ConstructiveActivities::where('app_id',$app_id)->get();
            $resources = HumanResource::where('app_id',$app_id)->get();
            $efficiencys = IncreasingEfficiency::where('app_id',$app_id)->get();
            $newmembers = NewMemberInclude::where('app_id',$app_id)->get();
            $nextmonthpls = NextMonthPlan::where('app_id',$app_id)->get();
            $notblinfos = NotableInformation::where('app_id',$app_id)->get();
            $meetingforms = MeetingApp::where('id',$app_id)->get();*/

            $contents = view("MeetingForm::met-min-pdf",compact('appData','appInfo','achievements','activities','resources','efficiencys','newmembers','nextmonthpls','notblinfos','meetingforms'))->render();
            $mpdfConfig = array(
                'mode' => 'utf-8',
                'format' => 'A4',
                'default_font_size' => 12,
                'default_font' => 'dejavusans',
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 15,
                'margin_bottom' => 15,
                'margin_header' => 10,
                'margin_footer' => 10,
                'orientation' => 'L'
            );
            $mpdf = new \Mpdf\Mpdf($mpdfConfig);
            $mpdf->useSubstitutions;
            $mpdf->SetProtection(array('print'));
            $mpdf->SetDefaultBodyCSS('color', '#000');
            $mpdf->SetTitle("BMAS One Stop Service");
            $mpdf->SetSubject("Subject");
            $mpdf->SetAuthor("Business Automation Limited");
            $mpdf->autoScriptToLang = true;
            $mpdf->baseScript = 1;
            $mpdf->autoVietnamese = true;
            $mpdf->autoArabic = true;
            $mpdf->autoArabic = true;
            $mpdf->autoLangToFont = true;
            $mpdf->SetDisplayMode('fullwidth');
            $mpdf->SetHTMLFooter('
                        <table width="100%">
                            <tr>
                                <td width="50%"><i style="font-size: 10px;">Download time: {DATE j-M-Y h:i a}</i></td>
                                <td width="50%" align="right"><i style="font-size: 10px;">{PAGENO}/{nbpg}</i></td>
                            </tr>
                        </table>');
            $stylesheet = file_get_contents('assets/stylesheets/appviewPDF.css');
            $mpdf->setAutoTopMargin = 'stretch';
            $mpdf->setAutoBottomMargin = 'stretch';
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->WriteHTML($contents, 2);
            $mpdf->defaultfooterfontsize = 10;
            $mpdf->defaultfooterfontstyle = 'B';
            $mpdf->defaultfooterline = 0;
            $mpdf->SetCompression(true);
            $mpdf->Output();
        } catch (\Exception $e) {
            dd($e->getMessage().$e->getLine());
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [CR-1115]');
            return Redirect()->back()->withInput();
        }
    }

    public  function agendaPdf($app_id=""){

        $process_type_id = $this->process_type_id;
        try {
            if ($app_id !="" && $app_id != 0){
                $data=ProcessList::leftJoin('achievement as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->where('process_list.process_type_id', $process_type_id)
                    ->where('process_list.ref_id', $app_id)
                    ->get(['process_list.company_id','process_list.created_at as process_created','apps.*']);
            }else{
                $data=ProcessList::leftJoin('achievement as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->where('process_list.process_type_id', $process_type_id)
                ->get(['process_list.company_id','process_list.created_at as process_created','apps.*']);
            }

            $contents = view("MeetingForm::agenda-pdf-all",compact('data'))->render();

            $mpdfConfig = array(
                'mode' => 'utf-8',
                'format' => 'A4',
                'default_font_size' => 12,
                'default_font' => 'dejavusans',
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 15,
                'margin_bottom' => 15,
                'margin_header' => 10,
                'margin_footer' => 10,
                'orientation' => 'L'
            );

            $mpdf = new \Mpdf\Mpdf($mpdfConfig);
            $mpdf->useSubstitutions;
            $mpdf->SetProtection(array('print'));
            $mpdf->SetDefaultBodyCSS('color', '#000');
            $mpdf->SetTitle("BMAS One Stop Service");
            $mpdf->SetSubject("Subject");
            $mpdf->SetAuthor("Business Automation Limited");
            $mpdf->autoScriptToLang = true;
            $mpdf->baseScript = 1;
            $mpdf->autoVietnamese = true;
            $mpdf->autoArabic = true;
            $mpdf->autoArabic = true;
            $mpdf->autoLangToFont = true;
            $mpdf->SetDisplayMode('fullwidth');
            $mpdf->SetHTMLFooter('
                        <table width="100%">
                            <tr>
                                <td width="50%"><i style="font-size: 10px;">Download time: {DATE j-M-Y h:i a}</i></td>
                                <td width="50%" align="right"><i style="font-size: 10px;">{PAGENO}/{nbpg}</i></td>
                            </tr>
                        </table>');
            $stylesheet = file_get_contents('assets/stylesheets/appviewPDF.css');

            $mpdf->setAutoTopMargin = 'stretch';
            $mpdf->setAutoBottomMargin = 'stretch';
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->WriteHTML($contents, 2);
            $mpdf->defaultfooterfontsize = 10;
            $mpdf->defaultfooterfontstyle = 'B';
            $mpdf->defaultfooterline = 0;
            $mpdf->SetCompression(true);
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
            $certificateName = uniqid("board-meeting_", true);
            $pdfFilePath = $directoryName . "/" . $certificateName . '.pdf';
            $mpdf->Output($pdfFilePath, 'F'); // Saving pdf *** F for Save only, I for view only.
            $chekexists=BoardMeting::where('id',14)->first();
            if($chekexists->meeting_minutes_path !="" || $chekexists->meeting_agenda_path != "") {
                BoardMeting::where('id',14)->update([
                    'meeting_agenda_path' =>$pdfFilePath,
                    'meeting_minutes_path' =>'',
                ]);
            }

           // $mpdf->Output();

        } catch (\Exception $e) {
            dd($e->getMessage().$e->getLine().$e->getFile());
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [CR-1115]');
            return Redirect()->back()->withInput();
        }
    }

    public function getPreviousMonthData(Request $request){
        $section_data = $request->get('section_data');
        $msg = "Unknown Data";
        $company_id=CommonFunction::getUserSubTypeWithZero();
        $previous_month = date('Y-m', strtotime('last month'));
        $checkexists=ProcessList::where('company_id',$company_id)
            ->where('created_at','like',$previous_month.'%')
            ->first(['ref_id','status_id','created_at']);
        $previousMonth = '';
        if ($section_data == 'section1'){
            $previousMonth=NotableInformation::where('app_id',$checkexists->ref_id)
                ->get();
        }elseif ($section_data == 'section2'){
            $previousMonth = ConstructiveActivities::where('app_id',$checkexists->ref_id)
                ->get();
        }elseif ($section_data == 'section3'){
            $previousMonth=IncreasingEfficiency::where('app_id',$checkexists->ref_id)
                ->get();
        }elseif ($section_data == 'section4'){
            $previousMonth=HumanResource::where('app_id',$checkexists->ref_id)
                ->get();
        }elseif ($section_data == 'section5'){
            $previousMonth=NewMemberInclude::where('app_id',$checkexists->ref_id)
                ->get();
        }elseif ($section_data == 'section6'){
            $previousMonth=NextMonthPlan::where('app_id',$checkexists->ref_id)
                ->get();
        }else{
            echo $msg;
        }

        $html='';
        foreach ($previousMonth as $v_pre_mo){
            $checkNull = '';
            if ($v_pre_mo->is_old == 1){
                $checkNull = "checked";
            }
            $checkbox = '';
            if ($section_data != 'section6'){
                $checkbox = '<td align="center"><input type="checkbox"'.$checkNull.' disabled/></td>';
            }

            $html.='<tr>
                        '.$checkbox.'
                        <td><p style="text-align: justify;padding-left: 5px;">'.$v_pre_mo->description.'</p></td>
                        <td class="text-center">'.$checkexists->created_at.'</td>
                    </tr>';
        }

        return response()->json(['data'=>$html,'responsecode'=>1]);
    }
    /*     * ********************************************End of Controller Class************************************************* */
}
