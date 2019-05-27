<?php namespace App\Modules\Apps\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\Apps\Models\Apps;
use App\Modules\Apps\Models\DocInfo;
use App\Modules\Apps\Models\Document;
use App\Modules\Apps\Models\EmailQueue;
use App\Modules\Apps\Models\ProcessDoc;
use App\Modules\Apps\Models\SmsQueue;
use App\Modules\Apps\Models\Status;
use App\Modules\Users\Models\AreaInfo;
use App\Modules\Apps\Models\ProcessList;
use App\Modules\Users\Models\Countries;
use App\Modules\Users\Models\UserDesk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use yajra\Datatables\Datatables;

class AppsController extends Controller {

	protected $service_id;

	public function __construct() {
		$this->service_id = 1; // 1 is for apps process
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$appsInfo = ProcessList::getApplicationList();
		$appStatus = Status::where('service_id', 1)->get();
		$statusList = array();
		foreach ($appStatus as $k => $v) {
			$statusList[$v->status_id] = $v->status_name;
			$statusList[$v->status_id . 'color'] = $v->color;
		}
		$statusList[-1] = "Draft";
		$statusList['-1' . 'color'] = '#AA0000';
		$desks = UserDesk::all();
		$deskList = array();
		foreach ($desks as $k => $v) {
			$deskList[$v->desk_id] = $v->desk_name;
		}
		$status = Status::where('service_id', 1)->orderBy('status_name', 'ASC')->lists('status_name', 'status_id')->all();
		$nationality = Countries::orderby('nationality')->where('nationality', '!=', '')->lists('nationality', 'iso');
		return view("Apps::app-list", compact('appsInfo', 'appStatus', 'statusList', 'desks', 'deskList', 'status', 'nationality'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function createApplication()
	{
		$authUserId = CommonFunction::getUserId();
		$statusArr = array(8, 22, '-1'); //8 is Discard, 22 is Rejected Application and -1 is draft
		$alreadyExistApplicant = Apps::leftJoin('process_list', 'process_list.record_id', '=', 'application.application_id')
			->where('process_list.service_id', $this->service_id)
			->whereNotIn('process_list.status_id', $statusArr)
			->where('application.created_by', $authUserId)
			->first();
		$document = DocInfo::where('service_id', $this->service_id)->orderBy('doc_name')->get();
//		if ($alreadyExistApplicant) {
//			Session::flash('error', "You have already applied for the application! Your tracking no is : " . $alreadyExistApplicant->tracking_number);
//			return redirect()->back();
//		}
		if ($alreadyExistApplicant) {
			$clr_document = Document::where('app_id', $alreadyExistApplicant->id)->get();
			foreach ($clr_document as $documents) {
				$clrDocuments[$documents->doc_id]['doucument_id'] = $documents->id;
				$clrDocuments[$documents->doc_id]['file'] = $documents->doc_file;
			}
		} else {
			$clrDocuments = [];
		}
		$agency = ['' => 'Select One'] + ['1' => 'Sarkar Agency', '2' => 'Mijbah Habib Travel Agency', '3' => 'Sandip Travel Agency'];
		return view("Apps::application-form", compact('agency', 'alreadyExistApplicant', 'document', 'clrDocuments'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function appStore(Request $request, Apps $apps, ProcessList $processlist)
	{
		try{
			$authUserId = CommonFunction::getUserId();
			$statusArr = array(5, 8, 22, '-1'); // 5 is shortfall, 8 is Discard, 22 is Rejected Application and -1 is draft
			$alreadyExistApplicant = Apps::leftJoin('process_list', function($join) {
				$join->on('process_list.record_id', '=', 'application.application_id');
				$join->on('process_list.service_id', '=', DB::raw($this->service_id));
			})
				->where('process_list.service_id', $this->service_id)
				->whereNotIn('process_list.status_id', $statusArr)
				->where('application.created_by', $authUserId)
				->first();
//			if ($alreadyExistApplicant) {
//				Session::flash('error', "You have already submitted Application! Your tracking no is : " . $alreadyExistApplicant->tracking_number);
//				return redirect()->back();
//			}
			$app_id = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');
			$alreadyExistApplicant = Apps::where('created_by', $authUserId)->where('application_id', $app_id)->orderBy('application_id', 'ASC')->first();

//		if ($alreadyExistApplicant) {
//			if (!ACL::getAccsessRight('application', 'E', $app_id))
//				die('You have no access right! Please contact with system admin if you have any query.');
//			$apps = $alreadyExistApplicant;
//		}else {
//			if (!ACL::getAccsessRight('application', 'A'))
//				die('You have no access right! Please contact with system admin if you have any query11.');
//		}

			if (!empty($request->get('sv_draft')))
				$draft = 1;
			else
				$draft = 0;

			$apps->application_title = $request->get('application_title');
			$apps->applicant_name = $request->get('applicant_name');
			$apps->applicant_type = Auth::user()->user_type;
			$apps->applicant_father_name = $request->get('applicant_father_name');
			$apps->applicant_mother_name = $request->get('applicant_mother_name');
			$apps->agency_id = $request->get('agency_id');
			$apps->present_address = $request->get('present_address');
			$apps->permanent_address = $request->get('permanent_address');
			$apps->status_id = 1;
			$apps->is_draft = $draft;
			$apps->created_by = CommonFunction::getUserId();

			$apps->save();

			///For Tracking ID generating and update
			if (!$alreadyExistApplicant) {
				$tracking_number = 'AP-' . date("dMY") . $this->service_id . str_pad($apps->application_id, 6, '0', STR_PAD_LEFT);
				$apps->tracking_number = $tracking_number;
				$apps->save();
			} else {
				//for testing purpose
				$tracking_number = 'AP-' . date("dMY") . $this->service_id . str_pad($apps->application_id, 6, '0', STR_PAD_LEFT);
				$apps->tracking_number = $tracking_number;
				$apps->save();
				//$tracking_number = $apps->tracking_number;
				//testing purpose//
			}
			$data = $request->all();
			### document store in document table
			$doc_row = DocInfo::where('service_id', 1) // 1 is for Apps
			->get(['doc_id', 'doc_name']);
			//dd($request->get('validate_field_'));
			///Start file uploading
			if (isset($doc_row)) {
				foreach ($doc_row as $docs) {
					if ($request->get('validate_field_' . $docs->doc_id) != '') {
						$documnent_id = $docs->doc_id;
						if ($request->get('document_id_' . $docs->doc_id) == '') {
							Document::create([
								'service_id' => 1, // 1 is for Apps
								'app_id' => $apps->application_id,
								'doc_id' => $documnent_id,
								'doc_name' => $request->get('doc_name_' . $docs->doc_id),
								'doc_file' => $request->get('validate_field_' . $docs->doc_id)
							]);
						} else {
							$documentId = $request->get('document_id_' . $docs->doc_id);
							Document::where('id', $documentId)->update([
								'service_id' => 1, // 1 is for Apps
								'app_id' => $apps->application_id,
								'doc_id' => $documnent_id,
								'doc_name' => $request->get('doc_name_' . $docs->doc_id),
								'doc_file' => $request->get('validate_field_' . $docs->doc_id)
							]);
						}
					}
				}
			}
			///End file uploading
			/*
		 	* save data to process_list table
		 	*/
			$processlistExist = ProcessList::where('record_id', $apps->application_id)->where('service_id', $this->service_id)->first();
			//dd($processlistExist);
			$deskId = 0;
			if (!empty($request->get('sv_draft'))) {
				$statusId = -1;
				$deskId = 0;
			} else {
				$statusId = 1;
				$deskId = 3; // 3 is RD1
			}

			if (count($processlistExist) < 1) {
				$process_list_insert = ProcessList::create([
					'track_no' => $tracking_number,
					'reference_no' => '',
					'company_id' => '',
					'service_id' => 1,
					'initiated_by' => CommonFunction::getUserId(),
					'closed_by' => 0,
					'status_id' => $statusId,
					'desk_id' => $deskId,
					'record_id' => $apps->application_id,
					'process_desc' => '',
					'updated_by' => CommonFunction::getUserId()
				]);
			} else {
				if ($processlistExist->status_id > -1)
					$statusId = 10;  /// Re-submit

				$processlistUpdate = array(
					'service_id' => 1,
					'status_id' => $statusId,
					'desk_id' => $deskId
				);
				$processlist->update_app_for_apps($apps->application_id, $processlistUpdate);
			}

			Session::flash('success', "Your application has been submitted with tracking no: <strong>" . $tracking_number . "</strong>");
			return redirect('application');
		}
		catch (\Exception $e) {
			dd($e->getMessage());
			Session::flash('error', 'Sorry! Something went wrong, Please try again.');
			return Redirect::back()->withInput();
		}
	}

	/*
	 * Application form view
	 */
	public function appFormView($id) {
		try{
			$app_id = Encryption::decodeId($id);
			$form_data = Apps::where('application_id', $app_id)->first(['status_id']);
			$authUserId = CommonFunction::getUserId();
			$applicationInfo = Apps::where('application_id', $app_id)->first();
			$agency =['1' => 'Sarkar Agency', '2' => 'Mijbah Habib Travel Agency', '3' => 'Sandip Travel Agency'];
			$process_history = DB::select(DB::raw("select `process_list_hist`.`desk_id`,`as`.`status_name`,
                                `process_list_hist`.`process_id`, 
                                if(`process_list_hist`.`desk_id`=0,\"-\",`ud`.`desk_name`) `deskname`,
                                `users`.`user_full_name`, 
                                `process_list_hist`.`updated_by`, 
                                `process_list_hist`.`status_id`, 
                                `process_list_hist`.`process_desc`,
                                `process_list_hist`.`process_desc`, 
                                `process_list_hist`.`record_id`, 
                                `process_list_hist`.`created_at` ,
                                group_concat(`pd`.`file`) as files
                                from `process_list_hist`
                                left join `user_desk` as `ud` on `process_list_hist`.`desk_id` = `ud`.`desk_id`
                                left join `users` on `process_list_hist`.`updated_by` = `users`.`id`
                                left join `process_documents` as `pd` on `process_list_hist`.`record_id` = `pd`.`app_id` and `process_list_hist`.`desk_id` = `pd`.`desk_id` and `process_list_hist`.`status_id` = `pd`.`status_id`
                                left join `app_status` as `as` on `process_list_hist`.`status_id` = `as`.`status_id`
                                where `process_list_hist`.`record_id`  = '$app_id'
                                and `process_list_hist`.`status_id` != -1
                    group by `process_list_hist`.`record_id`,`process_list_hist`.`desk_id`, `process_list_hist`.`status_id`
                    order by process_list_hist.created_at desc
                    "));
			$document = DocInfo::where('service_id', $this->service_id)->orderBy('doc_name')->get();
			$clr_document = Document::where('app_id', $app_id)->get();
			foreach ($clr_document as $documents) {
				$clrDocuments[$documents->doc_id]['doucument_id'] = $documents->id;
				$clrDocuments[$documents->doc_id]['file'] = $documents->doc_file;
			}
			return view('Apps::apps-view', compact('applicationInfo', 'agency', 'process_history', 'document', 'clrDocuments'));
		}
		catch (\Exception $e){
			dd($e->getMessage());
		}
	}

	/*
	 * function of uploading document of an application
	 */
	public function uploadDocument() {
		return View::make('Apps::ajaxUploadFile');
	}

	/*
	 * preview the full application form
	 */
	public function preview()
	{
		return view("Apps::preview");
	}

	/*
	 * ajax load data on select a distinct application
	 */
	public function ajaxRequest($param, Request $request)
	{
		$data = ['responseCode' => 0];
		$statusId = $request->get('id');
		$curr_app_id = $request->get('curr_app_id');

		$current_service_id = $request->get('current_service_id');

		$processType = 1;

		if ($param == 'process') {

			$processInfo = ProcessList::where('record_id', $curr_app_id)->where('service_id', $this->service_id)->first();
			//Set from any application desk_id and status_id. Not login user desk id
			$statusFrom = $processInfo->status_id; //$request->get('status_from');
			$deskId = $processInfo->desk_id; //Auth::user()->desk_id;

			$verifiedInfo = Apps::where('application_id', $curr_app_id)->first();
			$sql = "SELECT DGN.desk_id, DGN.desk_name
                        FROM user_desk DGN
                        WHERE
                        find_in_set(DGN.desk_id,
                        (SELECT desk_to FROM app_process_path APP WHERE APP.desk_from LIKE '%$deskId%'
                            AND APP.status_from = '$statusFrom' AND APP.status_to REGEXP '^([0-9]*[,]+)*$statusId([,]+[,0-9]*)*$')) ";

			// Get all applications' id
			// If not verified, give them a message that without verification, application can't be updated
			// adding leading zero for like condition
			$statusId_wz = sprintf("%02d", $statusId);

			//echo $sql;exit;
			$deskList = \DB::select(DB::raw($sql));
			$list = array();
			foreach ($deskList as $k => $v) {

				$tmpDeskId = $v->desk_id;
				$list[$tmpDeskId] = $v->desk_name; //. '( ' . $v->user_full_name . ' )';
			}
			$fileattach_flug = "SELECT APP.id, APP.FILE_ATTACHMENT FROM app_process_path APP WHERE APP.desk_from LIKE '%$deskId%'
            AND APP.status_from = '$statusFrom' AND APP.status_to LIKE '%$statusId%' limit 1";

			$fileattach_flug_data = \DB::select(DB::raw($fileattach_flug));

			$data = ['responseCode' => 1, 'data' => $list, 'status_to' => $statusId, 'status_from' => $statusFrom, 'desk_from' => $deskId,
				'file_attach' => $fileattach_flug_data[0]->FILE_ATTACHMENT];
		}elseif ($param == 'load-status-list') {
			$statusId = $request->get('curr_status_id');
			$delegate = $request->get('delegate');

			if (empty($delegate)) {
//                $user_id = Users::where('delegate_to_user_id', Auth::user()->id)->pluck('delegate_by_user_id');
//                $deskId = Users::where('id', $user_id)->pluck('desk_id');
				$deskId = Auth::user()->desk_id;
				$cond = "AND desk_from LIKE '%$deskId%'";
			} else {
				$cond = '';
			}
			$processInfo = ProcessList::where('record_id', $curr_app_id)->where('service_id', $this->service_id)->first();
			$statusFrom = $processInfo->status_id; //$request->get('status_from');
			$verifiedInfo = Apps::where('application_id', $curr_app_id)->first();
			$sql = "SELECT APS.status_id, APS.status_name
                        FROM app_status APS
                        WHERE
                        find_in_set(APS.status_id,
                        (SELECT GROUP_CONCAT(status_to) FROM app_process_path APP WHERE APP.status_from = '$statusId' $cond))
                        AND APS.service_id = 1
                        order by APS.status_name";

			$statusList = \DB::select(DB::raw($sql));
			if ($statusFrom == 9 && ($verifiedInfo->sb_gk_verification_status == 0 && $verifiedInfo->nsi_gk_verification_status == 0)) {
				$data = ['responseCode' => 5, 'data' => ''];
			} else {
				$data = ['responseCode' => 1, 'data' => $statusList];
			}
		}
		return response()->json($data);
	}

	/*
	 * after process button click, update the Process_list table
	 */
	public function updateBatch(Request $request, ProcessList $process_model, Apps $apps) {
		try{


			//dd($request->all());
			$deskFrom = Auth::user()->desk_id;
			$remarks = $request->get('remarks');
			$apps_id = $request->get('application');
			$desk_id = $request->get('desk_id');
			$status_id = $request->get('status_id');
			$attach_file = $request->file('attach_file');
			$service_id = 1;
			$onbehalf = $request->get('on_behalf_of');
			$on_behalf_of = 0;
			if (!empty($onbehalf)) {
				$on_behalf_of = $request->get('on_behalf_of');
			}
			foreach ($apps_id as $app_id) {

				if ($request->hasFile('attach_file')) {
					foreach ($attach_file as $afile) {
						$original_file = $afile->getClientOriginalName();
						$afile->move('uploads/', time() . $original_file);
						$file = new ProcessDoc;
						$file->app_id = $app_id;
						$file->desk_id = $desk_id;
						$file->status_id = $status_id;
						$file->file = 'uploads/' . time() . $original_file;
						$file->save();
					}
				}

				$appInfo = ProcessList::where('record_id', $app_id)->where('service_id', '=', $service_id)->first();
				$status_from = $appInfo->status_id;
				$deskFrom = $appInfo->desk_id;
				if (empty($desk_id)) {
					$whereCond = "select * from app_process_path where status_from = '$status_from' AND desk_from = '$deskFrom'
                        AND status_to REGEXP '^([0-9]*[,]+)*$status_id([,]+[,0-9]*)*$'";

					$processPath = DB::select(DB::raw($whereCond));
					if ($processPath[0]->desk_to == '0')  // Sent to Applicant
						$desk_id = 0;
					if ($processPath[0]->desk_to == '-1')   // Keep in same desk
						$desk_id = $deskFrom;
				}

				$app_data = array(
					'status_id' => $status_id,
					'remarks' => $remarks,
					'updated_at' => date('y-m-d H:i:s'),
					'updated_by' => Auth::user()->id
				);

				$info_data = array(
					'desk_id' => $desk_id,
					'status_id' => $status_id,
					'process_desc' => $remarks,
					'updated_by' => Auth::user()->id,
					'on_behalf_of_desk' => $deskFrom
				);
				if ($status_id == 8 || $status_id == 14) {
					$info_data['closed_by'] = Auth::user()->id;
				}

				$process_model->update_app_for_apps($app_id, $info_data);

				$apps->update_method($app_id, $app_data);
				Apps::where('application_id', $app_id)->update(['status_id' => $status_id, 'remarks' => $remarks]);

				$process_data = ProcessList::where('record_id', $app_id)->where('service_id', '=', $service_id)->first();

//            Notification
				$body_msg = '<span style="color:#000;text-align:justify;"><b>';


				if ($status_id == 18) {

//              Send to sb nsi

					$fetched_email_address_arr = "'" . CommonFunction::getFieldName($process_data->initiated_by, 'id', 'user_email', 'users') . "'";
					$fetched_phone_number_arr = "'" . CommonFunction::getFieldName($process_data->initiated_by, 'id', 'user_phone', 'users') . "'";
					$email_content = "OCPL BASE Email Test";
					$sms_content = "OCPL BASE SMS TEST";
					EmailQueue::create([
						'app_id' => $app_id,
						'service_id' => 1,
						'email_content' => $email_content,
						'email_to' => $fetched_email_address_arr,
						'email_cc' => 'shahin@batworld.com',
						'secret_key' => '',
						'pdf_type' => '',
						'sms_content' => $sms_content,
						'sms_to' => $fetched_phone_number_arr
					]);
					

				}
			}
			//         for previous and present status
			$appStatus = Status::get();
			$statusList = array();
			foreach ($appStatus as $k => $v) {
				$statusList[$v->status_id] = $v->status_name;
			}

			Session::flash('success', "Application status updated Previous status: $statusList[$status_from] || Present Status: $statusList[$status_id]");
			return redirect()->back();
		}
		catch (\Exception $e) {
			dd($e->getMessage());
			Session::flash('error', 'Sorry, something went wrong. Please try again later.');
			return redirect()->back();
		}
	}

	/*
	 * function for advance search
	 */
	public function searchResult(Request $request) {
		$tracking_number = $request->get('tracking_number');
		$passport_number = $request->get('passport_number');
		$nationality = $request->get('nationality');
		$organization = $request->get('organization');
		$applicant_name = $request->get('applicant_name');
		$status_id = $request->get('status_id');

		$getList = ProcessList::getSearchResults($tracking_number, $passport_number, $nationality, $applicant_name, $status_id);
		$_type = Auth::user()->user_type;
		$user_type = explode('x', $_type)[0];
		$desk_id = Auth::user()->desk_id;

		$areaList = AreaInfo::lists('area_nm', 'area_id');
		$resultList = [2 => 'No Objection', 3 => 'Objection', 4 => 'Black Listed'];
		$view = View::make('Apps::search-result', compact('getList', 'resultList', 'user_type', 'desk_id', 'areaList'));
		$contents = $view->render();
		$data = ['responseCode' => 1, 'data' => $contents];
		return response()->json($data);
	}
}
