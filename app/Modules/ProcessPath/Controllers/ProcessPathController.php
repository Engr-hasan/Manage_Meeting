<?php namespace App\Modules\ProcessPath\Controllers;

use App\Http\Controllers\Controller;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Modules\Apps\Models\ProcessDoc;
use App\Modules\ProcessPath\Models\BoardMeting;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessStatus;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\Users\Models\Users;
use App\Modules\Users\Models\UsersModel;
use App\Modules\SpaceAllocation\Controllers\GeneralApps;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use yajra\Datatables\Datatables;
use \ParagonIE\EasyRSA\KeyPair;
use \ParagonIE\EasyRSA\EasyRSA;

class ProcessPathController extends Controller
{

    public function __construct() {
        if (Session::has('lang'))
            App::setLocale(Session::get('lang'));
    }

    /**
     * Show application list
     * @param string $id
     * @param string $processStatus
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processListById( $id='',$processStatus='')
    {
        $process_type_id = $id != '' ? Encryption::decodeId($id) : 0;

        if(! session()->has('active_process_list')){
            session()->set('active_process_list',$process_type_id);
        }
        $userType = Auth::user()->user_type;
        $ProcessType = ProcessType::whereStatus(1)
            ->where(function ($query) use ($userType) {
                $query->where('active_menu_for', 'like', "%$userType%");
            })
            ->orderBy('name')
            ->lists('name','id')
            ->all();

        $process_info = ProcessType::where('id', $process_type_id)->first(['form_url','name']);
        $processStatus = null;

        $status = ['' => 'Select one'] + ProcessStatus::where('process_type_id', $process_type_id != 0 ? $process_type_id : -1)  // -1 means this service not available
                ->where('id', '!=',-1)
                ->lists('status_name', 'id')->all();

        $searchTimeLine = [
            ''=>'select One',
            '1' => '1 Day',
            '7' => '1 Week',
            '15' => '2 Weeks',
            '30' => '1 Month',
        ];

        return view("ProcessPath::common-list", compact('status', 'ProcessType', 'processStatus','searchTimeLine','process_type_id','process_info'));
    }

    public function setProcessType(Request $request)  //ajax set process type
    {
        session()->set('active_process_list',$request->get('data'));
        return 'success';
    }

    public function getList(Request $request, $status = '', $desk = '')
    {
        $process_type_id = session('active_process_list');
        $status == '-1000' ? '' : $status;
        $list = ProcessList::getApplicationList($process_type_id, $status, $request, $desk);
        return Datatables::of($list)
            ->addColumn('action', function ($list) use ($status) {
                if ($list->locked_by > 0
                    && Carbon::createFromFormat('Y-m-d H:i:s', $list->locked_at)->diffInMinutes() < 3 and $list->locked_by != Auth::user()->id
                    ) {
                    $locked_by_user = UsersModel::where('id', $list->locked_by)->pluck('user_full_name');
                    $html= '<img width="20" src="' . url('/assets/images/Lock-icon_2.png') . '"/>' .
                           '<a onclick="return confirm(' . "'The record locked by $locked_by_user, would you like to force unlock?'" . ')" 
                            target="_blank" href="' . url($list->form_url.'/view/' . Encryption::encodeId($list->ref_id)) . '" 
                            class="btn btn-xs btn-primary"> Open</a> &nbsp; ';

                } else {
                    $html= '<a target="_blank" href="' . url($list->form_url.'/view/' . Encryption::encodeId($list->ref_id)). '" class="btn btn-xs btn-primary"> <i class="fa fa-folder-open"></i> Open</a>  &nbsp;';
                }
                return $html;
            })
            ->editColumn('json_object', function ($list) {
                    return @getDataFromJson($list->json_object);
            })
            ->addColumn('desk', function ($list) {
                return $list->desk_id == 0 ? 'Applicant' : $list->desk_name;
            })
            ->editColumn('updated_at', function ($list) {
                return CommonFunction::updatedOn($list->updated_at);
            })
            ->removeColumn('id', 'ref_id', 'process_type_id', 'updated_by', 'closed_by', 'created_by', 'updated_by','desk_id','status_id','locked_by','ref_fields')
            ->setRowAttr([
                'style' => function($list){
                    $color = '';
                    if($list->priority==1) {
                        $color.= 'background:#f000';
                    }elseif ($list->priority==2) {
                        $color.= '    background: -webkit-linear-gradient(left, rgba(220,251,199,1) 0%, rgba(220,251,199,1) 80%, rgba(255,255,255,1) 100%);';
                    }elseif ($list->priority==3) {
                        $color.= '    background: -webkit-linear-gradient(left, rgba(255,251,199,1) 0%, rgba(255,251,199,1) 40%, rgba(255,251,199,1) 80%, rgba(255,255,255,1) 100%);';
                    }
                    return $color;
                }
            ])
            ->make(true);
    }


    public function getDeskByStatus(Request $request)
    {
        $process_list_id = Encryption::decodeId($request->get('process_list_id'));
        $status_from = Encryption::decodeId($request->get('status_from'));


        $processInfo = ProcessList::where('id', $process_list_id)->first([
            'process_type_id', 'desk_id', 'ref_id'
        ]);
        $statusId = $request->get('statusId');
        $sql = "SELECT DGN.id, DGN.desk_name
                        FROM user_desk DGN WHERE
                        find_in_set(DGN.id,
                        (SELECT desk_to FROM process_path APP
                         where APP.desk_from LIKE '%$processInfo->desk_id%'
                            AND APP.status_from = '$status_from'
                            AND APP.process_type_id = '$processInfo->process_type_id'
                            AND APP.status_to REGEXP '^([0-9]*[,]+)*$statusId([,]+[,0-9]*)*$')) ";

        $deskList = \DB::select(DB::raw($sql));



        $list = array();
        foreach ($deskList as $k => $v) {
            $tmpDeskId = $v->id;
            $list[$tmpDeskId] = $v->desk_name;
        }

        $fileRemarkData = "SELECT APP.id, APP.file_attachment,APP.remarks
                                   FROM process_path APP
                                   WHERE APP.desk_from LIKE '%$processInfo->desk_id%'
                                   AND APP.status_from = '$status_from'
                                
                                   AND APP.process_type_id = '$processInfo->process_type_id'
                                   AND APP.status_to REGEXP '^([0-9]*[,]+)*$statusId([,]+[,0-9]*)*$'  limit 1";

        $fileRemarkData = \DB::select(DB::raw($fileRemarkData));
        $applicable_desk = $list;

        $processTypeFinalStatus = ProcessType::where('id',$processInfo->process_type_id)->first(['final_status']);
        $finalStatus = explode(",",$processTypeFinalStatus->final_status);
        $pinNumber = '';
        if (in_array($statusId, $finalStatus)) {  //checking final status
            $result = CommonFunction::requestPinNumber();
            if($result == true)
               $pinNumber = 1;
        }

        $html = $this->requestFormContent($statusId, $processInfo->process_type_id, $processInfo->ref_id);
        ///Get form elements using form-id
        /// You will call addOnform Function by passing form-id
        /// extra-submit-form.blade.php
        /// get html
        /// set it in ajax return data
        $data = ['responseCode' => 1, 'data' => $applicable_desk, 'html'=>$html, 'remarks' => $fileRemarkData[0]->remarks,
                'file_attachment' => $fileRemarkData[0]->file_attachment,'pin_number'=>$pinNumber];
        return response()->json($data);
    }


    public function updateProcess(Request $request)
    {

        $rules = [
            'status_id' => 'required',
        ];
        if ($request->get('is_remarks_required') == 1) {
            $rules['remarks'] = 'required';
        }
        if ($request->get('is_file_required') == 1) {
            $rules['attach_file'] = 'requiredarray';
        }

        if (isset($request->pin_number)){
            if ($request->get('pin_number') == '') {
                \Session::flash('error', "Pin number Field Is Required");
                return redirect()->back();
//            $rules['pin_number'] = 'required';
            }
        }


        $customMessages = [
            'status_id.required' => 'Apply Status Field Is Required',
            'remarks.required' => 'Remarks Field Is Required',
            'attach_file.requiredarray' => 'Attach File Field Is Required',
//            'pin_number.required' => 'Pin number Field Is Required',
        ];
        $this->validate($request, $rules, $customMessages);
        try {

            if (isset($request->pin_number)){
                $security_code = trim($request->get('pin_number'));
                $user_id = CommonFunction::getUserId();
                $pin_number = $security_code . '-' . $user_id;
                $encrypted_pin = Encryption::encode($pin_number);
                $count = Users::where('id', $user_id)->where(['pin_number' => $encrypted_pin])->count();
                if ($count <= 0) {
                    \Session::flash('error', "Security Code doesn't match.");
                    return redirect()->back();
                }
            }

            DB::beginTransaction();
            $process_list_id = Encryption::decodeId($request->get('process_list_id'));
            $existProcessInfo = ProcessList::where('id', $process_list_id)
                ->first([
                    'id',
                    'ref_id',
                    'process_type_id',
                    'status_id',
                    'desk_id',
                    'updated_at',
                    'tracking_no'
                ]);
            $process_type_id = Encryption::encodeId($existProcessInfo->process_type_id);

            // Verify Process Path
            $statusID = $request->get('status_id');
            $deskID = $request->get('desk_id') == '' ? 0 : $request->get('desk_id');
            $process_path_count = DB::select(DB::raw("select count(*) from process_path
                                        where status_from = $existProcessInfo->status_id
                                        AND desk_from = $existProcessInfo->desk_id
                                        AND desk_to = $deskID
                                        AND status_to REGEXP '^([0-9]*[,]+)*$statusID([,]+[,0-9]*)*$'"));
            if ($process_path_count == 0) {
                Session::flash('error', 'Sorry, invalid process request.');
                return redirect('process/list/' . $process_type_id);
            }


            // if data verification is true
            if ($request->data_verification == Encryption::encode(UtilFunction::processVerifyData($existProcessInfo))) {
                // On Behalf of desk id

                $on_behalf_of_user = 0;

                $my_desk_ids = CommonFunction::getUserDeskIds();

//                if (!in_array($existProcessInfo->desk_id,$my_desk_ids)) {
//                    $on_behalf_of_user = Encryption::decodeId($request->get('on_behalf_user_id'));
//                }
                $on_behalf_of_user = 0;

                if ($request->hasFile('attach_file')) {
                    $attach_file = $request->file('attach_file');
                    foreach ($attach_file as $afile) {

                        $original_file = $afile->getClientOriginalName();
                        $afile->move('uploads/', time() . $original_file);
                        $file = new ProcessDoc;
                        $file->process_type_id = $existProcessInfo->process_type_id;
                        $file->ref_id = $process_list_id;
                        $file->desk_id = $request->get('desk_id');
                        $file->status_id = $request->get('status_id');
                        $file->file = 'uploads/' . time() . $original_file;
                        $file->save();
                    }
                }

                // Updating process list
                $status_from = $existProcessInfo->status_id;
                $deskFrom = $existProcessInfo->desk_id;

                if (empty($deskID)) {
                    $whereCond = "select * from process_path where status_from = '$status_from' AND desk_from = '$deskFrom'
                        AND status_to REGEXP '^([0-9]*[,]+)*$statusID([,]+[,0-9]*)*$'";

                    $processPath = DB::select(DB::raw($whereCond));
                    if ($processPath[0]->desk_to == '0')  // Sent to Applicant
                        $deskID = 0;
                    if ($processPath[0]->desk_to == '-1')   // Keep in same desk
                        $deskID = $deskFrom;
                }

                $processData=[
                    'desk_id' => $deskID,
                    'status_id' => $request->get('status_id'),
                    'priority' => $request->get('priority'),
                    'process_desc' => $request->get('remarks'),
                    'on_behalf_of_user' => $on_behalf_of_user,
                    'updated_by' => Auth::user()->id
                ];

                $processTypeFinalStatus=ProcessType::where('id',$existProcessInfo->process_type_id)->first(['final_status']);
                $finalStatus=explode(",",$processTypeFinalStatus->final_status);
                $closed_by=0;
                if (in_array($statusID, $finalStatus)) {  //checking final status and current status are same ??
                    $closed_by = $processData['closed_by']=CommonFunction::getUserId();
                }

                $processData['locked_by']=0;
                $processData['locked_at']='0000-00-00 00:00:00';


                $id = $existProcessInfo->id;
                $ref_id = $existProcessInfo->ref_id;
                $trackingNo = $existProcessInfo->tracking_no;
                $desk_id = $deskID;
                $processTypeId = $existProcessInfo->process_type_id;
                $status_id = $request->get('status_id');
                $on_behalf_of_users = $on_behalf_of_user;
                $process_desc = $request->get('remarks');
                $closed_byy = $closed_by;
                $locked_at = '0000-00-00 00:00:00';
                $locked_by = 0;
                $updated_by = Auth::user()->id;
                $result = $id . ', ' .$ref_id . ', ' .$trackingNo. ', ' . $desk_id. ', ' .$processTypeId .','. $status_id. ', '
                    . $on_behalf_of_users. ', ' . $process_desc. ', ' . $closed_byy. ', ' . $locked_at. ', ' . $locked_by.','.$updated_by;


                $keyPair = KeyPair::generateKeyPair(2048);

                $publicKey = $keyPair->getPublicKey();

                $hashData = EasyRSA::encrypt($result, $publicKey);
                $previousHash = $existProcessInfo->hash_value;

                $processData['previous_hash']=$previousHash;
                $processData['hash_value']=$hashData;
                ProcessList::where('id', $existProcessInfo->id)->update($processData);



                DB::commit();

                /**
                 * callback functionality
                 * like AD form info save in application
                 */
                session()->set('requestData',$request->all());
                $url = $this->processAddOnForm($existProcessInfo->id, $statusID);
                if($url!=""){
                    \Session::flash('success', 'Process has been updated.');
                    return \redirect($url);
                }


                \Session::flash('success', 'Process has been updated successfully.');
            } else {
                \Session::flash('error', 'Sorry, Data has been updated by another user.');
            }

            //Check call back url

            return redirect('process/list/' . $process_type_id);

        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            Session::flash('error', 'Sorry, something went wrong.');
            return redirect()->back();
        }
    }
    public function requestFormContent($CurrentStatusId, $process_type_id, $ref_id)
    {
//        $process_list_id = Encryption::decodeId($request->get('process_list_id'));
//        $process_type_id = Encryption::decodeId($request->get('process_type_id'));
//          $request_status_id = $request->get('request_status_id');

        $form_id = ProcessStatus::where('process_type_id',$process_type_id)->where('id',$CurrentStatusId)->pluck('form_id');

        if($form_id == 'AddOnForm/desk_from')
        {
            $appInfo = ProcessList::leftJoin('space_allocation as apps', 'apps.id', '=', 'process_list.ref_id')
                ->where('process_list.ref_id', $ref_id)
                ->where('process_list.process_type_id',  $process_type_id)
                ->first([
                    'process_list.company_id',
                    'process_list.desk_id',
                    'process_list.park_id',
                    'process_list.tracking_no',
                    'process_list.status_id',
                    'process_list.locked_by',
                    'process_list.locked_at',
                    'apps.*',
                ]);
            $public_html = strval(view("ProcessPath::{$form_id}",compact('form_id', 'process_type_id','appInfo')));
        }elseif ($form_id == 'AddOnForm/general-desk-from'){
            $appInfo = ProcessList::leftJoin('ga_master as apps', 'apps.id', '=', 'process_list.ref_id')
                ->where('process_list.ref_id', $ref_id)
                ->where('process_list.process_type_id',  $process_type_id)
                ->first([
                    'process_list.company_id',
                    'process_list.desk_id',
                    'process_list.park_id',
                    'process_list.tracking_no',
                    'process_list.status_id',
                    'process_list.locked_by',
                    'process_list.locked_at',
                    'apps.service_name',
                ]);

            $public_html = strval(view("ProcessPath::{$form_id}",compact('form_id', 'process_type_id','appInfo')));
        }
        else
        {
            $public_html = '';
        }
        return $public_html;
    }

    public function processAddOnForm($process_list_id, $status_id)
    {
        $process = ProcessList::where('id',$process_list_id)->first();
        $toUrl = ProcessStatus::where('process_type_id',$process->process_type_id)->where('id',$status_id)->pluck('to_url');

        switch ($process->process_type_id) {
            case 1: // Space Allocation
                if (in_array($status_id,['3','12','20']))  //Update desk AddOn form information with mail
                {
                    $url = $toUrl.'/'.Encryption::encodeId($process->ref_id).'/'.Encryption::encodeId($process->process_type_id);

                }
                elseif($status_id == '25') //Certificate generate
                {
                    $url = $toUrl.'/'.Encryption::encodeId($process->ref_id).'/'.Encryption::encodeId($process->process_type_id);

                }
                else
                {
                    $url= '';
                }
                break;
            case 2: // General apps

                if (in_array($status_id,['5','25']))  //Update desk AddOn form information with mail
                {
                    $url = $toUrl.'/'.Encryption::encodeId($process->ref_id).'/'.Encryption::encodeId($process->process_type_id);
                }
                else
                {
                    $url= '';
                }
                break;
            case 8: // Loan locator

                if (in_array($status_id,['5','25']))  //Update desk AddOn form information with mail
                {
                    $url = $toUrl.'/'.Encryption::encodeId($process->ref_id).'/'.Encryption::encodeId($process->process_type_id);
                }
                else
                {
                    $url= '';
                }
                break;
            case 3: // CB

                if (in_array($status_id,['25']))  //Update desk AddOn form information with mail
                {
                    $url = $toUrl.'/'.Encryption::encodeId($process->ref_id).'/'.Encryption::encodeId($process->process_type_id);
                }
                else
                {
                    $url= '';
                }
                break;

            case 4: // CB

                if (in_array($status_id,['25']))  //Update desk AddOn form information with mail
                {
                    $url = $toUrl.'/'.Encryption::encodeId($process->ref_id).'/'.Encryption::encodeId($process->process_type_id);
                }
                else
                {
                    $url= '';
                }
                break;
        }

        return $url;
    }



    /**
     * Check application validity for application process
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkApplicationValidity(Request $request)
    {
        $process_list_id = Encryption::decodeId($request->get('process_list_id'));
        $existProcessInfo = ProcessList::where('id', $process_list_id)
            ->first([
                'id',
                'ref_id',
                'process_type_id',
                'status_id',
                'desk_id',
                'updated_at'
            ]);

        if ($request->data_verification == Encryption::encode(UtilFunction::processVerifyData($existProcessInfo))) {
            return response()->json(array('responseCode' => 1));
        }
        return response()->json(array('responseCode' => 0));
    }


    /**
     * Load status list
     * @param $param
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxRequest($param, Request $request)
    {
        try {
            $data = ['responseCode' => 0];
            $application_id = Encryption::decodeId($request->get('application_id'));
            $process_list_id = Encryption::decodeId($request->get('process_list_id'));
            $appInfo = ProcessList::where('id', $process_list_id)->first(
                [
                    'process_type_id',
                    'id as process_list_id',
                    'status_id',
                    'ref_id',
                    'id',
                    'json_object',
                    'desk_id',
                    'updated_at'
                ]);
            $statusFrom = $appInfo->status_id; // current process status
            $deskId = $appInfo->desk_id; // Current desk id
            $process_type_id = $appInfo->process_type_id; // Current desk id

            DB::beginTransaction();

           if ($param == 'load-status-list') {


                $sql = "SELECT APS.id, APS.status_name
                        FROM process_status APS
                        WHERE find_in_set(APS.id,
                        (SELECT GROUP_CONCAT(status_to) FROM process_path APP
                        WHERE APP.status_from = '$statusFrom' AND APP.desk_from = '$deskId'  AND APP.process_type_id = '$process_type_id'))
                        AND APS.process_type_id = '$process_type_id'
                        order by APS.status_name";
                $statusList = \DB::select(DB::raw($sql));

               $priority = DB::table('process_priority')->where('is_active','=', 1)->lists('name','id');

                $data = ['responseCode' => 1, 'data' => $statusList,'priority'=>$priority];
            }
            DB::commit();
            return response()->json($data);
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }
    }
}
