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
use App\Modules\LoanLocator\Models\LoanType;
use App\Modules\MeetingForm\Models\MeetingApp;
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
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Mockery\CountValidator\Exception;
use mPDF;
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
            $appInfo = ProcessList::leftJoin('meeting_app as apps', 'apps.id', '=', 'process_list.ref_id')
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
            $getStatus = ProcessType::where('id', $this->process_type_id)->first()->way_to_success;
            $statusName = ProcessStatus::whereIn('id', explode(',', $getStatus))->where('process_type_id', $this->process_type_id)->get(['status_name', 'id']);
            $statusArray = ProcessStatus::where('process_type_id', $this->process_type_id)->lists('status_name', 'id');
            return view('MeetingForm::application-form-edit',
                compact('viewMode', 'mode', 'banks', 'data', 'statusArray', 'statusName', 'appInfo', 'verificationData', 'clrDocuments', 'process_history', 'hasDeskParkWisePermission'));
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
        $rules = [
            'task_name' => 'required',
        ];

        // Validate company logo
        if ($request->get('actionBtn') == 'save') {
            $this->validate($request, $rules);
        }
        try {

            DB::beginTransaction();
            $companyId = CommonFunction::getUserSubTypeWithZero();
            // Check existing application
            $statusArr = array(5, 8, 22, '-1'); //5 is shortfall, 8 is Discard, 22 is Rejected Application and -1 is draft

            $data = $request->all();

            if ($request->get('app_id')) {
                $decodedId = Encryption::decodeId($data['app_id']);
                $appData = MeetingApp::find($decodedId);
                $processData = ProcessList::firstOrNew(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id]);
            } else {
                $appData = new MeetingApp();
                $processData = new ProcessList();
                $processData->company_id = (!empty($companyId) ? $companyId : 0);;
                $processData->created_by = $appData->created_by;
            }

            $appData->task_name = $data['task_name'];
            $appData->comments = $data['comments'];
            $appData->task_description = $data['task_description'];
            $appData->remarks = $data['remarks'];

            if ($request->get('actionBtn') == "draft" && $appData->status_id != 2) {
                $processData->status_id = -1;
                $processData->desk_id = 0;
            } else {

                if ($processData->status_id == 5) { // For shortfall
                    $processData->status_id = 2;
                } else {
                    $processData->status_id = 1;
                }
                $appData->date_of_submission = Carbon::now(); // application Date
                $processData->desk_id = 0; // 2 is desk RD2
            }
            $appData->save();
            $processData->ref_id = $appData->id;
            $processData->process_type_id = $this->process_type_id;
            $processData->park_id = 2;
            //$processData->park_id = $data['park_id'];

            $jsonData['Headline'] = $request->get('task_name');
            $jsonData['Discussion'] = $request->get('comments');
            $jsonData['Remarks'] = $request->get('remarks');
            $processData['json_object'] = json_encode($jsonData);
            $processData->save();

            // Generate Tracking No for Submitted application
            $processlistExist = Processlist::where('ref_id', $appData->id)->where('process_type_id', $this->process_type_id)->first();

            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2) { // when application submitted but not as re-submitted
                $trackingPrefix = "MF" . date("dmY");
                $processTypeId = $this->process_type_id;
                $updateTrackingNo = DB::statement("update  process_list, process_list as table2  SET process_list.tracking_no=(
                                                            select concat('$trackingPrefix',
                                                                    LPAD( IFNULL(MAX(SUBSTR(table2.tracking_no,-4,4) )+1,0),4,'0')
                                                                          ) as tracking_no
                                                             from (select * from process_list ) as table2
                                                             where table2.process_type_id ='$processTypeId' and table2.id!='$processData->id' and table2.tracking_no like '$trackingPrefix%'
                                                        )
                                                      where process_list.id='$processData->id' and table2.id='$processData->id'");


                $processData = ProcessList::where('id', $processData->id)->first();


                $id = $processData->id;
                $ref_id = $processData->ref_id;
                $trackingNo = $processData->tracking_no;
                $desk_id = $processData->desk_id;
                $processTypeId = $processData->process_type_id;
                $status_id = $processData->status_id;
                $on_behalf_of_user = $processData->on_behalf_of_user;
                $process_desc = $processData->process_desc;
                $closed_by = $processData->closed_by;
                $locked_at = $processData->locked_at;
                $locked_by = $processData->locked_by;
                $updated_by = $processData->updated_by;


                $result = $id . ', ' . $ref_id . ', ' . $trackingNo . ', ' . $desk_id . ', ' . $processTypeId . ',' . $status_id . ', '
                    . $on_behalf_of_user . ', ' . $process_desc . ', ' . $closed_by . ', ' . $locked_at . ', ' . $locked_by . ',' . $updated_by;
//                $hashData = RSA::encrypt($result);


//                $keyPair = KeyPair::generateKeyPair(2048);
//                $secretKey = $keyPair->getPrivateKey();
//
//                $publicKey = $keyPair->getPublicKey();
                $hashData = "RWERW SDFSDFERE";

//                $previousHash = ProcessList::orderby('id', 'DESC')->skip(1)->first(['hash_value']);
                ProcessList::where('id', $id)->update(['hash_value' => $hashData]);
            }

            //Saving data to process_list table
            if ($request->get('submitInsert') == 'save') {
                if ($processlistExist->status_id == 5) {

                    ProcessList::where('ref_id', $processlistExist->id)
                        ->where('process_type_id', $this->process_type_id)->update([
                            'desk_id' => 2,
                            'status_id' => 2,
                            'tracking_no' => $processlistExist->tracking_no
                        ]);
                }
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


    /*     * ********************************************End of Controller Class************************************************* */
}
