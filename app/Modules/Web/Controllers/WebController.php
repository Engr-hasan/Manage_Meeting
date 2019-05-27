<?php namespace App\Modules\Web\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Libraries\Encryption;
use App\Modules\Dashboard\Models\DashboardObjectDynamic;
use App\Modules\Settings\Models\Bank;
use App\Modules\Training\Models\TrainingParticipants;
use App\Modules\Training\Models\TrainingResource;
use App\Modules\Training\Models\TrainingSchedule;
use App\Modules\Users\Models\AreaInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view("Web::index");
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

	public static function getDashboardObjectsJson($key){
		$data = array('responseCode'=>0,'data'=>'');
		$response = '';
		$object_details = DashboardObjectDynamic::where('key',$key)->first();
		if($object_details) {
			if ($object_details->updated_at && $object_details->updated_at > '0') {
				$extended_time = Carbon::parse($object_details->updated_at)->addSeconds($object_details->time_limit);
			}else{
				$extended_time = Carbon::now();
			}

			if($extended_time<=Carbon::now()){ // limited time over
				$sql = $object_details->query;
				$response = json_encode(DB::select(DB::raw($sql)));
				DashboardObjectDynamic::where('key',$key)->update([
					'response'=>$response,
					'updated_at'=>Carbon::now()
				]);
			}else{
				// limited time is not over
				$response = $object_details->response;
			};
			$return_response = json_encode(array(
				'key'=>$key,
				'title'=>$object_details->title,
				'layout'=>$object_details->layout,
				'json'=>json_decode($response)
			));
			return $return_response;
		}else{
			return null;
		}
	}

	public function loadDashboardObjectsChart($key){
		$result = json_decode($this->getDashboardObjectsJson($key));
		if($result!=null){
			if($key=='PRE_HALNAGAT'){
				return view('public_home.report-dashboard-object', compact('result'));
			}elseif($key=='REG_HALNAGAT'){
				return view('public_home.report-dashboard-object', compact('result'));
			}
		}else{
			return 'Data not found.';
		}
	}


	public function getTrainingPublicSchedule(Request $request)
	{
		$training_id = Encryption::decodeId($request->get('training_id'));
		if(!$training_id)
		{
			return 'Invalid data!!!';
		}
		date_default_timezone_set('Asia/Dhaka');
		$date = date('Y-m-d H:i:s', time());
		$schedule_list = TrainingSchedule::where('training_id',$training_id)
			->leftJoin('trainings','training_schedule.training_id','=','trainings.id')
			->leftJoin('training_participants as tp', function($query){
				$query->on('tp.training_schedule_id','=','training_schedule.id');
				$query->whereIn('tp.status',[1,2,3]);
			})
//			->where('training_schedule.end_time', '>', $date)
			->where('training_schedule.status','1')
			->groupBy('training_schedule.id')
			->orderBy('training_schedule.start_time','asc')
			->get([DB::raw('count(tp.id) as total_participant'),'training_schedule.total_seats','training_schedule.id','training_schedule.trainer_name','training_schedule.location','training_schedule.start_time','training_schedule.end_time','trainings.title as training_title','trainings.public_user_types as public_user_types']);
		$training_resource = TrainingResource::where('training_id',$training_id)
			->where('status','=',2)
			->where('is_deleted',0)
			->get();
		return view('Training::public-training.schedule', compact('schedule_list','training_resource'));

	}

	public function applyForm(Request $request)
	{
		$schedule_id = Encryption::decodeId($request->get('schedule_id'));
		$training_info = TrainingSchedule::leftJoin('trainings','trainings.id','=','training_schedule.training_id')
			->where('training_schedule.id',$schedule_id)->first(['trainings.public_user_types','location','trainings.title as title', 'training_schedule.total_seats as total_seats', 'trainings.user_types as user_types', 'training_schedule.start_time as start_time','training_schedule.end_time as end_time']);
		$user_type = explode(",",$training_info->user_types);
		$total_participants = TrainingParticipants::where('training_schedule_id',$schedule_id)->whereIn('status',[1,2,3,4])->count();
		$scheduleData = TrainingSchedule::where('id',$schedule_id)->first(['total_seats','start_time']);
		//		get the current time and date


		$info['total_applied'] = TrainingParticipants::where('training_schedule_id',$schedule_id)->whereIn('status',[1,2,3,4])->count();
		$info['total_verified'] = TrainingParticipants::where('training_schedule_id',$schedule_id)->whereIn('status',[2,3,4])->count();
		$districts = AreaInfo::where('area_type', 2)->orderBy('area_nm', 'ASC')->lists('area_nm', 'area_nm')->all();
		$bank = Bank::orderBy('id', 'desc')->lists('name', 'name')->all();




		date_default_timezone_set('Asia/Dhaka');
		$current_time = date('Y-m-d h:i:s', time());
		if(!$schedule_id)
		{
			return response()->json(['responseCode' => 2, 'public_html' => '','msg' => 'Invalid data!!!']);
		}
		else if($total_participants >= $scheduleData['total_seats'])
		{
			return response()->json(['responseCode' => 2, 'public_html' => '','msg' => 'Sorry All seat has been booked!!!']);
		}
		else if(strtotime($current_time) >= strtotime($scheduleData['start_time']))
		{
			return response()->json(['responseCode' => 2, 'public_html' => '','msg' => 'Sorry Booking time already over!!!']);
		}
		$public_html = strval(view('Training::public-training.apply',compact('schedule_id','training_info','user_type','info', 'districts', 'bank')));
		return response()->json(['responseCode' => 1, 'public_html' => $public_html,'msg' => 'Sorry All seat has been booked!!!']);
	}

	public function applyPublicTraining(Request $request)
	{
		$schedule_id = Encryption::decodeId($request->get('schedule_id'));
		$training_info = TrainingSchedule::leftJoin('trainings','trainings.id','=','training_schedule.training_id')
			->where('training_schedule.id',$schedule_id)->first(['trainings.user_types as user_types','training_schedule.total_seats','training_schedule.end_time']);
		$user_type = explode(",",$training_info->user_types);
		$rules = [
			'name' => 'required',
			'email' => 'required|email',
			'phone' => 'required|min:11|max:14',
			'district' => 'required',
			'trainee_nid' => 'digits_between:10,17|required|numeric',
			'dob' => 'required',
			'g-recaptcha-response' => 'required'
		];
		if (array_intersect($user_type, CommonFunction::bankUser())) {
			$rules['bank'] = 'required';
		}

		$agency_name = '';
		if (array_intersect($user_type, CommonFunction::agencyUser()))
		{
			$agency_name = Agency::where('license_no','=',$request->get('agency_license'))->where('is_active',1)->pluck('name');
			if ($agency_name == null){
				return response()->json(['responseCode' => 4, 'msg' =>'Agency licence that you inserted is not valid or agency is inactive!!!']);
			}
			$rules['agency_license'] = 'required|numeric|digits:4';
		}


		$validator = Validator::make($request->all(),$rules);
		$total_participants = TrainingParticipants::where('training_schedule_id',$schedule_id)->whereIn('status',[1,2,3,4])->count();
		$current_training_id = TrainingSchedule::where('id', $schedule_id)->pluck('training_id');


		$trainee_exist = TrainingParticipants::leftJoin('training_schedule','training_participants.training_schedule_id','=','training_schedule.id')
			->where('training_schedule.training_id',$current_training_id)
			->where('training_participants.email',$request->get('email'))
			->whereIn('training_participants.status',['1,2,3,4'])
			->count();
		date_default_timezone_set('Asia/Dhaka');
		$date = date('Y-m-d H:i:s', time());
		if($date >= $training_info->end_time)
		{
			return response()->json(['responseCode' => 6, 'msg' =>'Sorry, The Training is already over!!!']);
		}
		if($total_participants >= $training_info->total_seats)
		{
			return response()->json(['responseCode' => 5, 'msg' =>'Sorry, All seat has been booked!!!']);
		}
		else if($validator->fails()) {
			return response()->json(['responseCode' => 2, 'msg' =>'Please insert all information carefully.']);
		}
		else if ($trainee_exist > 0) {
			return response()->json(['responseCode' => 3, 'msg' => 'You have already applied to this training.']);
		}

		try{

			DB::beginTransaction();

			TrainingParticipants::create(
				array(
					'training_schedule_id' => $schedule_id,
					'name' => $request->get('name'),
					'email' => $request->get('email'),
					'mobile' => $request->get('phone'),
					'district' => $request->get('district'),
					'trainee_nid' => $request->get('trainee_nid'),
					'bank' => $request->get('bank'),
					'agency_license' => $request->get('agency_license'),
					'agency_name' => $agency_name,
					'dob' => date('Y-m-d',strtotime($request->get('dob'))),
				));
			DB::commit();
			$data = ['responseCode' => 1, 'msg' => '<div class="row well"><span class="text-success text-center"><strong>Thank You. You are successfully registered in this Training.</strong></span></div>'];

		}
		catch (Exception $e) {
			DB::rollback();
			$data = ['responseCode' => 0, 'msg' => '<span class="text-success"><b>Something was wrong. Please try again.<span class="text-success"><b>'];
		}
		return response()->json($data);
	}

//    Public Training Video Resource
	public function publicTrainingVideo($id)
	{
		$resource_id = Encryption::decodeId($id);
		$resourceDetail = TrainingResource::where('id',$resource_id)
			->first();
		return view("Web::training-public-video", compact('resourceDetail'));
	}

}
