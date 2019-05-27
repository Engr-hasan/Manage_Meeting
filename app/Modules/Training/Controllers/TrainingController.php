<?php namespace App\Modules\Training\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\Settings\Models\Agency;
use App\Modules\Settings\Models\Bank;
use App\Modules\Training\Models\EmailQueue;
use App\Modules\Training\Models\Training;
use App\Modules\Training\Models\TrainingResource;
use App\Modules\Training\Models\TrainingSchedule;
use App\Modules\Users\Models\AreaInfo;
use App\Modules\Users\Models\UsersModel;
use App\Modules\Users\Models\UserTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use App\Modules\Training\Models\TrainingParticipants;
use Maatwebsite\Excel\Excel;

class TrainingController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public $email_sender_add = 'prp@hajj.gov.bd';
	public function __construct()
	{
		if (Session::has('lang'))
			\App::setLocale(Session::get('lang'));

		set_time_limit(-1);
		$email_user = ENV('MAIL_USERNAME', 'prp@hajj.gov.bd');
		$this->email_sender_add = ENV('MAIL_FROM', $email_user);
	}

	public function index()
	{
		return view("Training::index");
	}

	public function getTrainingDetailsData()
	{
		$training_list = Training::all();
		return Datatables::of($training_list)
			->editColumn('user_types',function($training_list){
				$user_types = $training_list->user_types;
				$all_user_type = explode(',',$user_types);
				$type_name = UserTypes::whereIn('id', $all_user_type)->first([DB::raw("GROUP_CONCAT(type_name) as types")]);
				if(in_array('public',$all_user_type)){
					$type_name ='Public, '.$type_name->types;
				}else{
					$type_name = $type_name->types;
				}
				return "<span>". $type_name ."</span>";
			})
			->editColumn('description', function($training_list) {

				//return substr($training_list->description, 0, 50) . '...';
				return $training_list->description;
			})
			->addColumn('action', function ($training_list) {
				return '<a href="' . url('training/view/' . Encryption::encodeId($training_list->id)) .
				'" class="btn btn-xs btn-info"><i class="fa fa-folder-open-o"></i> Open</a>';
			})
			->removeColumn('id')
			->make(true);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		if (!ACL::getAccsessRight('Training','A'))
			die('no access right!');
		$userTypes = ['public' => 'Public'] + UserTypes::where('status','active')->lists('type_name', 'id')->all();
		$status = ['active' => 'Active','inactive' => 'Inactive'];
		return view("Training::create",compact("userTypes","status"));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		if (!ACL::getAccsessRight('Training', 'A'))
			die('no access right!');
		$this->validate($request, [
			'user_types' => 'required',
			'title' => 'required',
			'description' => 'required',
			'status' => 'required',
		]);

		$user_types = $request->get('user_types');
		$public_user = UserTypes::whereIn('id',$user_types)
			->lists('type_name', 'id')
			->all();
		$public_user = implode(",",$public_user);

		$user_types = implode(",",$user_types);

		try {
			$insert = Training::create(
				array(
					'user_types' => $user_types,
					'public_user_types' => $public_user,
					'title' => $request->get('title'),
					'description' => $request->get('description'),
					'status' => $request->get('status'),
					'created_by' => CommonFunction::getUserId()
				));

			Session::flash('success', 'Data is stored successfully!');
			return redirect('/training/view/'. Encryption::encodeId($insert->id));
		} catch (\Exception $e) {
			Session::flash('error', 'Sorry! Somthing Wrong.');
			return Redirect::back()->withInput();
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$training_id = Encryption::decodeId($id);
		$training = Training::where('id', $training_id)
			->first();
		$user_types = $training->user_types;
		$all_user_type = explode(',',$user_types);
		$type_name = UserTypes::whereIn('id', $all_user_type)->first([DB::raw("GROUP_CONCAT(type_name) as types")]);
		if(in_array('public',$all_user_type)){
			$type_name ='Public, '.$type_name->types;
		}else{
			$type_name = $type_name->types;
		}
		return view("Training::view", compact('training','type_name'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		if (!ACL::getAccsessRight('Training', 'E'))
			die('no access right!');
		$training_id = Encryption::decodeId($id);
		try
		{
			$training = Training::where('trainings.id', $training_id)
				->first();
			$userTypes = ['' => 'Select User Type','public' => 'Public'] + UserTypes::where('status','active')->lists('type_name', 'id')->all();
			$training_types = explode(",", $training->user_types);
			$select = array();
			foreach ($training_types as $training_type){
				$select[] = $training_type;
			}
			$status = ['active' => 'Active','inactive' => 'Inactive'];
			return view("Training::edit", compact('training','status','userTypes','select'));
		}
		catch (\Exception $e)
		{
			Session::flash('error', 'Sorry! Something went wrong');
			return Redirect::back();
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		if (!ACL::getAccsessRight('Training', 'E'))
			die('no access right!');
		$training_id = Encryption::decodeId($id);
		$this->validate($request, [
			'user_types' => 'required',
			'title' => 'required',
			'description' => 'required',
			'status' => 'required',
		]);

		$user_types = $request->get('user_types');

		$public_user = UserTypes::whereIn('id',$user_types)
			->lists('type_name', 'id')
			->all();
		$user_types= implode(",",$user_types);
		$public_user = implode(",",$public_user);
		try {
			Training::where('id', $training_id)->update(array(
					'user_types' => $user_types,
					'public_user_types' => $public_user,
					'title' => $request->get('title'),
					'description' => $request->get('description'),
					'status' => $request->get('status'),
					'updated_by' => CommonFunction::getUserId()
				));

			Session::flash('success', 'Data is stored successfully!');
			return redirect('/training/view/'. Encryption::encodeId($training_id));
		} catch (\Exception $e) {
			Session::flash('error', 'Sorry! Something Wrong.');
			return Redirect::back()->withInput();
		}
	}

	/*
	 * Training Resource Create
	 */
	public function resource($id)
	{
		if (!ACL::getAccsessRight('Training', 'E'))
			die('no access right!');
		$resource_types = array('1' => 'Document/ Excel/ CSV', '2' => 'PDF', '3' => 'Video');
		return view("Training::resource",compact('resource_types', 'id'));
	}

	/*
	 * Training Resource Store method
	 */
	public function storeResource($id, Request $request)
	{
		if (!ACL::getAccsessRight('Training', 'E'))
			die('no access right!');
		$this->validate($request, array(
			'resource_title' => 'required',
			'resource_type' => 'required',
			'resource_link' => 'required',
		));

		try
		{
			$training_id = Encryption::decodeId($id);
			TrainingResource::create(array(
				'training_id' => $training_id,
				'resource_title' => $request->get('resource_title'),
				'resource_type' => $request->get('resource_type'),
				'resource_link' => $request->get('resource_link'),
			));
			Session::flash('success', 'Training resource has been successfully created.');
			return Redirect::back();
		}
		catch (\Exception $e)
		{
			Session::flash('error', 'Sorry! Something went wrong.');
			return Redirect::back()->withInput();
		}
	}

	/*
	 * Training Resource Data
	 */
	public function getResourceData(Request $request)
	{
		$training_id = Encryption::decodeId($request->get('training_id'));
		$resourceDetails = TrainingResource::where('training_id',$training_id)
									->get(['id','resource_title','resource_type','resource_link','status']);
		return Datatables::of($resourceDetails)
            ->editColumn('resource_type',function($data){
				$resource_type = '';
                if ($data->resource_type == 1)
                {
                    $resource_type = 'Document/ Excel/ CSV';
                }
                elseif ($data->resource_type == 2)
                {
                    $resource_type = 'PDF';
                }
				elseif ($data->resource_type == 3)
				{
					$resource_type = 'Embedded Video';
				}
                return $resource_type;
            })
            ->addColumn('action',function($data){
				$action = '<a href="' . url('training-resource/edit/' . Encryption::encodeId($data->id)) .
					'" class="btn btn-xs btn-info"><i class="fa fa-folder-open-o"></i> Edit</a> ';
                if ($data->status == 0){
					$action .= '<a href="' . url('training-resource/publish/' . Encryption::encodeId($data->id)) .
						'" class="btn btn-xs btn-success"><i class="fa fa-chevron-circle-right"></i> Publish</a> ';
					$action .= '<a href="' . url('training-resource/remove/' . Encryption::encodeId($data->id)) .
						'" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i> Remove</a> ';
				}
				else if($data->status == 1){
					$action .= '<a href="' . url('training-resource/public/' . Encryption::encodeId($data->id)) .
						'" class="btn btn-xs btn-primary"><i class="fa fa-chevron-circle-right"></i> Public</a> ';
					$action .= '<a href="' . url('training-resource/unpublish/' . Encryption::encodeId($data->id)) .
						'" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i> Unpublish</a> ';
				}
				else if($data->status == 2){
					$action .= '<a href="' . url('training-resource/unpublish/' . Encryption::encodeId($data->id)) .
						'" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i> Unpublish</a> ';
				}
                return $action;
            })
            ->removeColumn('id')
            ->make(true);
	}

	/*
	 * Resource edit
	 */
	public function resourceEdit($id){
		if (!ACL::getAccsessRight('Training', 'E'))
			die('no access right!');
		$resource_id = Encryption::decodeId($id);
		$resource_types = array('1' => 'Document/ Excel/ CSV', '2' => 'PDF', '3' => 'Video');
		$resourceDetails = TrainingResource::where('id',$resource_id)
							->first();
		return view("Training::resource-edit",compact('resource_types', 'resourceDetails'));
	}

	/*
	 * Resource data Update
	 */
	public function resourceUpdate($id, Request $request){
		if (!ACL::getAccsessRight('Training', 'E'))
			die('no access right!');
		$resource_id = Encryption::decodeId($id);
		$this->validate($request, array(
			'resource_title' => 'required',
			'resource_type' => 'required',
			'resource_link' => 'required',
		));

		try
		{
			TrainingResource::where('id',$resource_id)
				->update(array(
				'resource_title' => $request->get('resource_title'),
				'resource_type' => $request->get('resource_type'),
				'resource_link' => $request->get('resource_link'),
			));
			Session::flash('success', 'Training resource has been successfully Updated.');
			return Redirect::back();
		}
		catch (\Exception $e)
		{
			Session::flash('error', 'Sorry! Something went wrong.'.$e->getMessage());
			return Redirect::back()->withInput();
		}
	}

	/*
	 * Publish Resource
	 */
	public function resourcePublish($id){
		if (!ACL::getAccsessRight('Training', 'E'))
			die('no access right!');
		$resource_id = Encryption::decodeId($id);
		try
		{
			TrainingResource::where('id',$resource_id)
				->update(array(
					'status' => 1
				));
			Session::flash('success', 'Training resource has been successfully Published.');
			return Redirect::back();
		}
		catch (\Exception $e)
		{
			Session::flash('error', 'Sorry! Something went wrong.'.$e->getMessage());
			return Redirect::back();
		}
	}

	/*
	 * Unpublish Resource
	 */
	public function resourceUnpublish($id){
		if (!ACL::getAccsessRight('Training', 'E'))
			die('no access right!');
		$resource_id = Encryption::decodeId($id);
		try
		{
			TrainingResource::where('id',$resource_id)
				->update(array(
					'status' => 0
				));
			Session::flash('success', 'Training resource has been successfully Unpubished.');
			return Redirect::back();
		}
		catch (\Exception $e)
		{
			Session::flash('error', 'Sorry! Something went wrong.'.$e->getMessage());
			return Redirect::back();
		}
	}

	/*
	 * Public Resource
	 */
	public function resourcePublic($id){
		if (!ACL::getAccsessRight('Training', 'E'))
			die('no access right!');
		$resource_id = Encryption::decodeId($id);
		try
		{
			TrainingResource::where('id',$resource_id)
				->update(array(
					'status' => 2
				));
			Session::flash('success', 'Training resource has been successfully published to the public end.');
			return Redirect::back();
		}
		catch (\Exception $e)
		{
			Session::flash('error', 'Sorry! Something went wrong.'.$e->getMessage());
			return Redirect::back();
		}
	}

	/*
	 * Remove Resource
	 */
	public function resourceRemove($id){
		if (!ACL::getAccsessRight('Training', 'E'))
			die('no access right!');
		$resource_id = Encryption::decodeId($id);
		try
		{
			TrainingResource::where('id',$resource_id)
				->update(array(
					'status' => -1
				));
			Session::flash('success', 'Training resource has been successfully removed.');
			return Redirect::back();
		}
		catch (\Exception $e)
		{
			Session::flash('error', 'Sorry! Something went wrong.'.$e->getMessage());
			return Redirect::back();
		}
	}

	public function getParticipantResource(Request $request)
	{
		$training_id = Encryption::decodeId($request->get('training_id'));
		$resourceDetails = TrainingResource::where('training_id',$training_id)
			->whereNotIn('status', [0, -1]) // 0 = Unpublish, -1 = Remove
			->get(['id','resource_title','resource_type','resource_link','status']);
		return Datatables::of($resourceDetails)
			->editColumn('resource_type',function($data){
				$resource_type = '';
				if ($data->resource_type == 1)
				{
					$resource_type = 'Document/ Excel/ CSV';
				}
				elseif ($data->resource_type == 2)
				{
					$resource_type = 'PDF';
				}
				elseif ($data->resource_type == 3)
				{
					$resource_type = 'Embedded Video';
				}
				return $resource_type;
			})
			->editColumn('status',function($data){
				$status = '';
				if ($data->status == 1){
					$status = '<span class="text-success">Publish</span>';
				}
				elseif ($data->status == 2){
					$status = '<span class="text-success">Public</span>';
				}
				return $status;
			})
			->addColumn('action',function($data){
				$action = '';
				if ($data->resource_type == 1){ // 1 = Doc/CSV/Excel
					$action = '<a href="'.url($data->resource_link).'" class="btn btn-xs btn-primary"><i class="fa fa-folder-open-o"></i> Open</a> ';
				}
				elseif ($data->resource_type == 2){ // 2 = PDF
					$action = '<a href="'.url($data->resource_link).'" target="_blank" class="btn btn-xs btn-primary"><i class="fa fa-folder-open-o"></i> Open</a> ';
				}
				elseif($data->resource_type == 3){ // 3 = Embedded Link
					$action = '<a href="' . url('training-resource/embedded/' . Encryption::encodeId($data->id)) .
						'" class="btn btn-xs btn-primary"><i class="fa fa-folder-open-o"></i> View Video</a> ';
				}
				return $action;
			})
			->removeColumn('id')
			->make(true);
	}

	public function embeddedResource($id)
	{
		$resource_id = Encryption::decodeId($id);
		$resourceDetail = TrainingResource::where('id',$resource_id)
							->first();

		return view("Training::embedded-resource-view",compact('resourceDetail'));
	}

	public function scheduleList()
	{
		return view("Training::schedule.list");
	}

	public function createSchedule()
	{
		if(!ACL::getAccsessRight('Training','E'))
			die('no access right!');
		$training_lists = Training::where('status', '=', 'active')
			->orderBy('title')
			->select(DB::raw('CONCAT(title," - ",public_user_types) AS title'), 'id')
			->lists('title', 'id')->all();

		$status = ['1' => 'Active','0' => 'Inactive'];
		return view("Training::schedule.create", compact('training_lists','status'));
	}

	public function storeSchedule(Request $request){
		if (!ACL::getAccsessRight('Training', 'E'))
			die('no access right');
		$this->validate($request, [
			'training_id' => 'required',
			'trainer_name' => 'required',
			'venue_name' => 'required',
			'total_seats' => 'required | numeric | min:2',
			'location' => 'required',
			'start_time' => 'required',
			'end_time' => 'required',
			'status' => 'required',
		]);

		try {
			$insert = TrainingSchedule::create(
				array(
					'training_id' => $request->get('training_id'),
					'trainer_name' => $request->get('trainer_name'),
					'venue' => $request->get('venue_name'),
					'total_seats' => $request->get('total_seats'),
					'location' => $request->get('location'),
					'start_time' => date('Y-m-d H:i:s', strtotime($request->get('start_time'))),
					'end_time' => date('Y-m-d H:i:s', strtotime($request->get('end_time'))),
					'created_by' => CommonFunction::getUserId()
				));

			Session::flash('success', 'Data is stored successfully!');
			return redirect('/training-schedule/view/'. Encryption::encodeId($insert->id));
		}
		catch (\Exception $e) {
			Session::flash('error', 'Sorry! Something Wrong.');
			return Redirect::back()->withInput();
		}
	}

	### All Training Schedule for IT admin
	public function getTrainingScheduleData()
	{
		$schedule_list = TrainingSchedule::leftJoin('trainings','training_schedule.training_id','=','trainings.id')
			->orderBy('created_at','desc')
			->get(['training_schedule.*','trainings.title as training_title', 'trainings.public_user_types as user_type']);

		return Datatables::of($schedule_list)
			->editColumn('status',function($data){
					$status = ($data->status == 1) ? 'Active' : 'Inactive';
					$class = ($data->status == 1) ? 'text-success' : 'text-danger';
					return "<span class='$class'>". $status ."</span>";
			})
			->editColumn('trainer_name',function($data){
					$trainer_name = $data->trainer_name;
					return "<span>". $trainer_name ."</span>";
			})
			->editColumn('venue_name',function($data){
					$venue_name = $data->venue;
					return "<span>". $venue_name ."</span>";
			})
			->editColumn('time', function($data) {
				$start_time = !empty($data->start_time) ? date('h:i A', strtotime($data->start_time)) : '';
				$end_time = !empty($data->end_time) ? date('h:i A', strtotime($data->end_time)) : '';

				return "<span>". $start_time ."<span> to </span>". $end_time ."</span>";
			})
			->editColumn('date', function($data) {
				$start_date = !empty($data->start_time) ? date('jS M Y', strtotime($data->start_time)) : '';
				return "<span>". $start_date ."</span>";
			})
			->addColumn('action', function ($data) {
					$return = '<a href="' . url('training-schedule/view/' . Encryption::encodeId($data->id)) .
					'" class="btn btn-xs btn-info"><i class="fa fa-folder-open-o"></i> Open</a> ';

					/*if(time() >= strtotime($data->start_time))
					{
						
						if($data->certificate !=  '') {
							$return .= '&nbsp;&nbsp;<a target="_blank" href="'.$data->certificate.'" class="btn btn-xs btn-primary download_crt" id="dl_'.Encryption::encodeId($data->id).'">&nbsp; Download Certificate</a>';
						}
						else {
							$return .= '<span id="ddl_'.Encryption::encodeId($data->id).'">&nbsp;&nbsp;<a href="javascript:void(0);" class="btn btn-xs btn-primary get_crt" id="'.Encryption::encodeId($data->id).'">&nbsp; Get Certificate</a></span>';
						}

					}*/

					return $return;
			})
			->removeColumn('id')
			->make(true);
	}
	
	### Training Material Schedule
	public function getTrainingMaterialScheduleData(Request $request)
	{
		$training_id = Encryption::decodeId($request->get('training_id'));
		 $query = TrainingSchedule::join('trainings','training_schedule.training_id','=','trainings.id')
			->leftJoin('training_participants', function($join)
			{
				$join->on('training_participants.training_schedule_id', '=', 'training_schedule.id');
				$join->on('training_participants.user_id', '=', DB::raw(Auth::user()->id));
			})
			->where('trainings.id',$training_id)
			//->where('trainings.user_types','like','%'.Auth::user()->user_type.'%')->orWhere('trainings.user_types','like','%public%')
			->orderBy('training_schedule.created_at','desc')
			->groupBy('training_schedule.id');
		if (!in_array(Auth::user()->user_type,CommonFunction::trainingAdmin())){
			$query->where('training_schedule.status',1);
		}

		$schedule_list = $query->get(['training_schedule.*','trainings.title as training_title','trainings.user_types as training_user_type','training_participants.user_id', 'training_participants.status as participant_status']);

		$participant_count  = TrainingParticipants::join('training_schedule','training_schedule.id','=','training_participants.training_schedule_id')
													->where('training_schedule.training_id',$training_id)
													->whereIn('training_participants.status',['1','2', '3'])
													->groupBy('training_schedule_id')
													->get([DB::raw('count(*) as participant'),'training_schedule.id as training_schedule_id']);
		$p_all = [];
		foreach ($participant_count as $key=> $participant){
			$p_all[$participant->training_schedule_id] = $participant->participant;
		}

		return Datatables::of($schedule_list)

			->editColumn('venue_name',function($data){
				return "<span>". $data->venue ."</span>";
			})
            ->editColumn('trainer_name',function($data){
				return "<span>". $data->trainer_name ."</span>";
			})
			->editColumn('total_seats',function($data){
				return "<span>". $data->total_seats ."</span>";
			})
			->editColumn('location',function($data){
				return "<span>". $data->location ."</span>";
			})
			->editColumn('start_date', function($data) {
				$start_date = !empty($data->start_time) ? date('jS F Y', strtotime($data->start_time)) : '';
				return $start_date;
			})
			->editColumn('time', function($data) {
				$start_time = !empty($data->start_time) ? date('h:i A', strtotime($data->start_time)) : '';
				$end_time = !empty($data->end_time) ? date('h:i A', strtotime($data->end_time)) : '';
				return "<span>". $start_time ."</span> to <br/>$end_time";
			})
			->editColumn('status',function($data){
				$status = ($data->status == 1) ? 'Active' : 'Inactive';
				$class = ($data->status == 1) ? 'text-success' : 'text-danger';
				return "<span class='$class'>". $status ."</span>";
			})
			->addColumn('action', function ($data) use ($p_all){

				$return = '<a href="' . url('training-schedule/view/' . Encryption::encodeId($data->id)) .
					'" class="btn btn-xs btn-info"><i class="fa fa-folder-open-o"></i> Open</a>';
				if(!in_array(Auth::user()->user_type, CommonFunction::trainingAdmin()))
				{
					if($data->total_seats>$p_all[$data->id]){
						if(Auth::user()->id == $data->user_id && in_array($data->participant_status, array('1','2','3','4')))
						{
							$return .= '&nbsp;&nbsp;<a href="javascript:void(0);" class="btn btn-xs btn-success">&nbsp; Applied</a>';
						}
						else
						{
							$return .= '&nbsp;&nbsp;<a href="javascript:void(0);" class="btn btn-xs btn-info applyfortraining" id="'.Encryption::encodeId($data->id).'" tid="'.Encryption::encodeId($data->training_id).'"><i class="fa fa-folder-open-o"></i> Apply</a>';
						}
					}
					else{
						$return = '<span class="text-info">Booked!!!</span>';
					}
				}

				return $return;
			})
			->removeColumn('id')
			->make(true);
	}

	public function applyForTraining(Request $request)
	{
		try
		{
			$user_type = explode('x',Auth::user()->user_type);
			if ($user_type[0] == '11'){
				$bank_id = Auth::user()->user_sub_type;
				$bank = Bank::where('id',$bank_id)->pluck('name');
			}
			else {
				$bank = null;
			}
			if ($user_type[0] == '12'){
				$agency_id = Auth::user()->user_sub_type;
				$agency = Agency::where('id','=',$agency_id)->first();
				$agency_name = $agency->name;
				$agency_license_no = $agency->license_no;
			}
			else {
				$agency_id = null;
				$agency_name = null;
				$agency_license_no = null;
			}
			$district = AreaInfo::where('area_id',Auth::user()->district)->pluck('area_nm');
			$data = array();
			$schedule_id = Encryption::decodeId($request->get('schedule_id'));

			$total_participants = TrainingParticipants::where('training_schedule_id',$schedule_id)->whereIn('status', [1,2,3,4])->count();

			$current_training_id = TrainingSchedule::where('id', $schedule_id)->pluck('training_id');
			$trainee_exist = TrainingParticipants::leftJoin('training_schedule','training_participants.training_schedule_id','=','training_schedule.id')
				->where('training_schedule.training_id',$current_training_id)
				->where('training_participants.email', Auth::user()->user_email)
				->whereIn('training_participants.status',['1,2,3,4'])
				->count();
			$scheduleData = TrainingSchedule::where('id',$schedule_id)->first(['total_seats','start_time','end_time']);
//		get the current time and date
			date_default_timezone_set('Asia/Dhaka');
			$current_time = date('Y-m-d h:i:s', time());

			if($total_participants >= $scheduleData['total_seats'])
			{
				$data = ['responseCode' => 2, 'msg' => 'All seat has been booked!!!'];
			}
			else if(strtotime($current_time) >= strtotime($scheduleData['end_time']))
			{
				$data = ['responseCode' => 3, 'msg' => 'Booking time already overed!!!'];
			}
			else if($trainee_exist > 0)
			{
				$data = ['responseCode' => 4, 'msg' => 'You have already applied to this training.!!!'];
			}
			else
			{
				if (Auth::user())
					TrainingParticipants::create([
						'training_schedule_id'=> $schedule_id,
						'user_id'=>Auth::user()->id,
						'name' => Auth::user()->user_full_name,
						'email' => Auth::user()->user_email,
						'mobile' => Auth::user()->user_phone,
						'trainee_nid' => Auth::user()->user_nid,
						'district' => $district,
						'agency_name' => $agency_name,
						'agency_license' => $agency_license_no,
						'bank' => $bank,
						'dob' => Auth::user()->user_DOB,
						'status' => 1,
					]);
				$data = ['responseCode' => 1, 'msg' => 'Your booking has been done successfully'];
			}

			return response()->json($data);
		}
		catch (\Exception $e)
		{
			$data = ['responseCode' => 0, 'msg' => 'Sorry! Something went wrong. Please try again later.'];
			return response()->json($data);
		}
	}

	public function viewSchedule($id)
	{
		$schedule_id = Encryption::decodeId($id);
		$training_schedule = TrainingSchedule::leftJoin('trainings','training_schedule.training_id','=','trainings.id')
			->where('training_schedule.id', $schedule_id)
			->first(['training_schedule.*', 'trainings.title as training_title', 'trainings.public_user_types']);
		$participant_list = $this->getNewTraineeList($schedule_id);
		return view("Training::schedule.view", compact('training_schedule', 'participant_list'));
	}

	public function editSchedule($id)
	{
		$schedule_id = Encryption::decodeId($id);
		$training_lists = Training::where('status', '=', 'active')
			->orderBy('title')
			->select(DB::raw('CONCAT(title," - ",public_user_types) AS title'), 'id')
			->lists('title', 'id')->all();


		$status = ['1' => 'Active','0' => 'Inactive'];
		$training_schedule = TrainingSchedule::where('training_schedule.id', $schedule_id)
							->first();
		return view('Training::schedule.edit',compact('training_schedule','training_lists','status'));
	}

	public function updateSchedule(Request $request, $id)
	{
		$schedule_id = Encryption::decodeId($id);

		$this->validate($request, [
			'training_id' => 'required',
			'venue_name' => 'required',
			'trainer_name' => 'required',
			'total_seats' => 'required | numeric | min:2',
			'location' => 'required',
			'start_time' => 'required',
			'end_time' => 'required',
			'status' => 'required',
		]);
		try {
			$training = Training::leftJoin('training_schedule', 'training_schedule.training_id', '=', 'trainings.id')
				->leftJoin('training_participants', 'training_participants.training_schedule_id', '=', 'training_schedule.id')
				->where('training_participants.training_schedule_id',$schedule_id)
				->first(['trainings.title','training_schedule.start_time','training_schedule.location']);

			/*
			if((strtotime($request->get('start_time')) - strtotime($training->start_time)) !=0) {
				$trainee_list = TrainingParticipants::where('training_schedule_id',$schedule_id)
					->get(['email']);
				$body_msg = trans('messages.hajj_training_schedule_change_subject')."
				<br/><br/><br/>
				".trans('messages.hajj_training_subject')." ".$training->title."<br/>"
					.trans('messages.hajj_training_date_time')." ".date('Y-m-d h:i a',strtotime($request->get('start_time')))." - ".date('Y-m-d h:i a',strtotime($request->get('end_time')))."<br/>"
					.trans('messages.hajj_training_locatione')." ".$training->location;

				$data = array(
					'header' => 'Training',
					'param' => $body_msg
				);

				foreach ($trainee_list as $trainee) {
					$email = $trainee->email;
					\Mail::send('Training::message', $data, function ($message) use ($email) {
						$message->from($this->email_sender_add, 'Hajj training system')
							->to($email)
							->subject(trans('messages.hajj_training_decline_subject'));
					});
				}
			}
			*/

			TrainingSchedule::where('id', $schedule_id)->update(
				array(
					'training_id' => $request->get('training_id'),
					'venue' => $request->get('venue_name'),
					'trainer_name' => $request->get('trainer_name'),
					'total_seats' => $request->get('total_seats'),
					'location' => $request->get('location'),
					'start_time' => date('Y-m-d H:i:s', strtotime($request->get('start_time'))),
					'end_time' => date('Y-m-d H:i:s', strtotime($request->get('end_time'))),
					'updated_by' => CommonFunction::getUserId(),
					'status'=>$request->get('status')
				));

			Session::flash('success', 'Data is stored successfully!');
			return redirect('/training-schedule/edit/'. Encryption::encodeId($schedule_id));
		}
		catch (\Exception $e) {
			Session::flash('error', 'Sorry! Something Wrong.');
			return Redirect::back()->withInput();
		}
	}


	public function trainingList()
	{
		return view("Training::material-list");
	}

	/*
	 * Trainee list download as CSV
	 */
	public function downloadTraineeList($id,Excel $excel)
	{
		$schedule_id = Encryption::decodeId($id);

		try
		{
			$participantInfo = TrainingParticipants::where('training_schedule_id',$schedule_id)
				->whereIn('status',[1,2,3])
				->get();

			$cout = 1;

			foreach ($participantInfo as $record)
			{
				$excelData[$cout]['Participant Name'] = $record->name;
				$excelData[$cout]['Email'] = $record->email;
				$excelData[$cout]['Mobile No'] = $record->mobile;
				$excelData[$cout]['District Name'] = $record->district;
				if($record->bank != "")
				{
					$excelData[$cout]['Bank Name'] = $record->bank;
				}
				else if($record->agency_name != "")
				{
					$excelData[$cout]['Agency Name'] = $record->agency_name;
				}
				if ($record->agency_license != "")
				{
					$excelData[$cout]['Agency Code'] = $record->agency_license;
				}
				$cout++;
			}


			$excel->create('trainee_' . date('YmdHis') . '_list', function($excel) use($excelData) {
				$excel->sheet('Sheetname', function($sheet) use($excelData) {
					$sheet->fromArray($excelData);
				});
			})->export('xls');
		}
		catch (\Exception $e)
		{
			Session::flash('error', 'Sorry! Something went wrong. Please try again later');
			return Redirect::back();
		}
	}

	public function getTrainingData()
	{
		$tList = Training::where('user_types','like','%'.Auth::user()->user_type.'%')
						->where('status','active')
						->orderBy('created_at','desc')->get();
		return Datatables::of($tList)
			->editColumn('title',function($data){
				return "<span>". $data->title ."</span>";
			})
			->editColumn('status',function($data){
				$status = ($data->status == 'active') ? 'Active' : 'Inactive';
				$class = ($data->status == 'active') ? 'text-success' : 'text-danger';
				return "<span class='$class'>". $status ."</span>";
			})
			->addColumn('action', function ($data) {
				return  '<a href="' . url('training/view/' . Encryption::encodeId($data->id)) .
				'" class="btn btn-xs btn-info"><i class="fa fa-folder-open-o"></i> Open</a>';
			})
			->removeColumn('id')
			->make(true);
	}
	
	public function getTraineeList(Request $request)
	{
		$schedule_id = Encryption::decodeId($request->get('schedule_id'));
		DB::statement(DB::raw('set @rownum=0'));
		$traineeList = TrainingParticipants::where('training_schedule_id',$schedule_id)
						->get([
							DB::raw('@rownum  := @rownum  + 1 AS rownum'),
							'training_participants.*'
						]);
		return Datatables::of($traineeList)
			->editColumn('serial', function($data){
				return $data->rownum;
			})
			->editColumn('organization', function($data){
				if (!empty($data->bank)){
					$organization = $data->bank;
				}
				else if (!empty($data->agency_license)){
					$organization = "<span>". $data->agency_name ."<b> (" . $data->agency_license .")</b></span>";
				}
				else{
					$organization = '';
				}
				return $organization;
			})
			->editColumn('status', function($data){
				if ($data->status == 1){
					return "<span class='text-info status'>Applied</span>";
				}
				elseif($data->status == 2){
					return "<span class='text-success status'>Verified</span>";
				}
				elseif($data->status == 3){
					return "<span class='text-success status'>Participated</span>";
				}
				elseif($data->status == 0){
					return "<span class='text-danger status'>Declined</span>";
				}
				elseif($data->status == 4){
					return "<span class='text-danger status'>Declined(Abs.)</span>";
				}
			})
			->addColumn('action', function($traineeList){
				$action = '<a href="' . url('training-participant/view/' . Encryption::encodeId($traineeList->id)) .
					'" class="btn btn-xs btn-info"><i class="fa fa-folder-open-o"></i> Open</a>';
				if ($traineeList->status == 1){
					$action .= '<span id="phase_'.Encryption::encodeId($traineeList->id).'">&nbsp;&nbsp;<a href="'.url('training/decline-from-training/' . Encryption::encodeId($traineeList->id)).'" class="btn btn-xs btn-danger" id="'.Encryption::encodeId($traineeList->id).'">&nbsp; Decline</a>';
					$action .= '&nbsp;&nbsp;<a href="'.url('training/verify-training-applicant/' . Encryption::encodeId($traineeList->id)).'" class="btn btn-xs btn-success" id="'.Encryption::encodeId($traineeList->id).'">&nbsp; Verify &nbsp;</a></span>';
				}
				else if ($traineeList->status == 2)
				{
					$action .= '<span id="phase_'.Encryption::encodeId($traineeList->id).'">&nbsp;&nbsp;<a href="javascript:void(0);"  class="btn btn-xs btn-danger absentParticipant" id="'.Encryption::encodeId($traineeList->id).'">&nbsp; Decline</a>';
					$action .= '&nbsp;&nbsp;<a href="javascript:void(0);" class="btn btn-xs btn-primary presentParticipant" id="'.Encryption::encodeId($traineeList->id).'">&nbsp; Participate &nbsp;</a></span>';
				}
				return $action;
			})
			->addColumn('check', '<input type="checkbox" name="selected_users[]" value="{{ $id }}">')
			->removeColumn('id')
			->make(true);
	}

	public function getNewTraineeList($schedule_id)
	{
		$trainee_list = TrainingParticipants::where('training_schedule_id',$schedule_id)
												->get();
		return $trainee_list;
	}

	public function getScheduleListForAssign(Request $request)
	{
		$curr_schedule_id = Encryption::decodeId($request->get('curr_schedule_id'));
		$training_id = Encryption::decodeId($request->get('training_id'));
		$schedule_list = TrainingSchedule::leftJoin('trainings','trainings.id','=','training_schedule.training_id')
										->where('training_id',$training_id)
										->where('training_schedule.id','!=',$curr_schedule_id)
										->select('training_schedule.id as schedule_id',DB::raw('CONCAT(trainings.title, " - ", training_schedule.venue) as schedule_heading'))
										->orderBy('training_schedule.id','desc')
										->get(['schedule_id','schedule_heading']);

		$data = ['responseCode' => 1, 'data' => $schedule_list];
		return response()->json($data);
	}

	public function assignSchedule(Request $request)
	{
		$traininng_id = Encryption::decodeId($request->get('training_id'));
		$curr_schedule_id = Encryption::decodeId(($request->get('curr_schedule_id')));
		$applied_schedule_id = $request->get('applied_schedule_id');
		$all_participant_id = array();
		$all_participant_id = $request->get('all_participant_id');
		$all_participant_mail = array();
		$all_participant_mail = DB::table('training_participants')
									->select('email')
									->where('training_schedule_id',$curr_schedule_id)
									->whereIn('id',$all_participant_id)
									->get();
		$total_selected_participant = count($all_participant_id);
		$participant_capacity = TrainingSchedule::where('id',$applied_schedule_id)->pluck('total_seats');
		$applied_participant = TrainingParticipants::where('training_schedule_id',$applied_schedule_id)
															->whereIn('status',['1','2'])
															->count();
		$available_seats = $participant_capacity - $applied_participant;
		if ($available_seats >= $total_selected_participant)
		{
			// Save new training participant list
			TrainingParticipants::whereIn('id',$all_participant_id)->where('status','!=',0)->update(array(
				'training_schedule_id' => $applied_schedule_id
			));

			// Mail functionality
			$body_msg = '<span style="color:#000;text-align:justify;"><b>';
			$body_msg .= 'Your applied Training Schedule has been canceled. Your new Training Schedule is: [dynamic_value]';

			$body_msg .= '</span>';
			$body_msg .= '<br/><br/><br/>Thanks<br/>';
			$body_msg .= '<b>OSS Framework</b>';
			$reg_key = 'training-schedule-change';
			$data = array(
				'header' => 'Application Update',
				'param' => $body_msg
			);


			$email_content = <<<HERE
       
           <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
					<head>
						<title>Bangladesh Economic Zones Authority</title>
						<link href='https://fonts.googleapis.com/css?family=Vollkorn' rel='stylesheet' type='text/css'>
							<style type="text/css">
								*{
									font-family: Vollkorn;
								} 
							</style>
					</head>
				
				
					<body>
						<table width="80%" style="background-color:#DFF0D8;margin:0 auto; height:50px; border-radius: 4px;">
							<thead>
								<tr>
									<td style="padding: 10px; border-bottom: 1px solid rgba(0, 102, 255, 0.21);">
										<img style="margin-left: auto; margin-right: auto; display: block;" src="http://dev-beza.eserve.org.bd/assets/images/logo_beza_single.png" width="80px"
											 alt="BEZA"/>
											<h4 style="text-align:center">
												OCPL OSS Framework
											</h4>
									</td>
								</tr>
							</thead>
							
							<tbody>
								<tr>
									<td style="margin-top: 20px; padding: 15px;">
										<!--Dear Applicant,-->
										Dear Applicant ,
										<br/><br/>
															   $body_msg
				
										<br/><br/>
									</td>
								</tr>
								<tr style="margin-top: 15px;">
									  <td style="padding: 1px; border-top: 1px solid rgba(0, 102, 255, 0.21);">
										<h5 style="text-align:center">All right reserved by OCPL OSS Framework</h5>
									</td>
								</tr>
							</tbody>  
						</table>
					</body>
				</html>
HERE;

				foreach ($all_participant_mail as $participant_mail)
				{
					$emailQueue = new EmailQueue();
					$emailQueue->secret_key = $reg_key;
					$emailQueue->email_to = $participant_mail->email;
					$emailQueue->email_cc = '';
					$emailQueue->email_content = $email_content;
					$emailQueue->save();

					\Mail::send('users::message', $data, function ($message) use ($participant_mail) {
						$message->from('no-reply@mora.gov.bd', 'OCPL OSS Framework')
							->to($participant_mail->email)
							->cc('')
							->subject('Training Schedule Cancel and New Schedule Assign Information');
					});
				}
			// Mail function end here
			
			$data = ['responseCode' => 1, 'data' => 'Successfully assigned to those participant to selected schedule.'];
			return response()->json($data);
		}
		else
		{
			$data = ['responseCode' => '0', 'data' => 'Sorry, seat capacity for the selected schedule exceeds. Remaining seats for application is '.$available_seats ];
			return response()->json($data);
		}

	}

	public function participantView(Request $request, $id)
	{
		$participant_id = Encryption::decodeId($id);
		$traineeInfo = TrainingParticipants::leftJoin('users','training_participants.user_id','=','users.id')
			->leftJoin('training_schedule','training_participants.training_schedule_id','=','training_schedule.id')
			->leftJoin('area_info as district_info','users.district','=','district_info.area_id')
			->where('training_participants.id',$participant_id)
			->first(['training_participants.*','training_schedule.start_time as training_start_time','users.user_full_name as user_full_name','users.user_email as user_email','users.user_phone as user_mobile','users.user_nid as user_nid','users.user_DOB as user_DOB','district_info.area_nm as district_name']);



		return view('Training::trainee-view', compact("traineeInfo"));
	}

	public function declineFromTraining($participant_id,Request $request)
	{
		$participant_id = Encryption::decodeId($participant_id);
		$data = array();
		try {
			TrainingParticipants::where('id', $participant_id)->update(array('status' => 0, 'updated_by' => CommonFunction::getUserId()));

            $requestData = ['participant_id'=>$participant_id,'status' => 0,'updated_by' => CommonFunction::getUserId()];
            $request->request->add($requestData);
            CommonFunction::createAuditLog('Training.decline-training',$request);

			$participant = TrainingParticipants::where('id', $participant_id)->first();
			$email = 'anamul@batworld.com';
			if($participant->user_id == 0)
			{
				$email = $participant->email;
			}
			else
			{
				$users = UsersModel::where('id', $participant->user_id)->first();
				$email = $users->user_email;
			}

			$training = Training::leftJoin('training_schedule', 'training_schedule.training_id', '=', 'trainings.id')
				->leftJoin('training_participants', 'training_participants.training_schedule_id', '=', 'training_schedule.id')
				->where('training_participants.training_schedule_id',$participant->training_schedule_id)
				->first(['trainings.title','training_schedule.start_time','training_schedule.end_time','training_schedule.location']);

			$data = ['responseCode' => 1, 'msg' => 'Participant has been successfully declined from this training','html'=>''];

			$body_msg = trans('messages.hajj_decline_msg')."
			<br/><br/><br/>
			".trans('messages.hajj_training_subject')." ".$training->title."<br/>"
			.trans('messages.hajj_training_date_time')." ".date('Y-m-d h:i a',strtotime($training->start_time))." - ".date('Y-m-d h:i a',strtotime($training->end_time))."<br/>"
			.trans('messages.hajj_training_locatione')." ".$training->location;

			$data = array(
				'header' => 'Decline training application',
				'param' => $body_msg
			);

			\Mail::send('Training::message', $data, function ($message) use ($email) {
				$message->from($this->email_sender_add, 'Hajj training system')
					->to($email)
					->subject(trans('messages.hajj_training_decline_subject'));
			});
		}
		catch (\Exception $e) {
			\Session::flash('error', 'Something went wrong [AJAX_VAR]'.$e->getMessage());
			return Redirect::back();
		}
		Session::flash('success', 'Successfully Updated Information!');
		return Redirect::back();
	}

	public function verifyTrainingApplicant($participant_id,Request $request)
	{
		if (!ACL::getAccsessRight('Training', 'E'))
		{
			$data = ['responseCode' => 0, 'msg' => 'no access right!'];
			return response()->json($data);
		}
		$participant_id = Encryption::decodeId($participant_id);
		$data = array();
		try {
			TrainingParticipants::where('id', $participant_id)->update(array('status' => 2, 'updated_by' => CommonFunction::getUserId()));

            $requestData = ['participant_id'=>$participant_id,'status' => 2,'updated_by' => CommonFunction::getUserId()];
            $request->request->add($requestData);
            CommonFunction::createAuditLog('Training.verify-participant',$request);

			$participant = TrainingParticipants::where('id', $participant_id)->first();
			$email = '';
			if($participant->user_id == 0)
			{
				$email = $participant->email;
			}
			else
			{
			  $users = UsersModel::where('id', $participant->user_id)->first();
			  $email = $users->user_email;
			}

			$training = Training::leftJoin('training_schedule', 'training_schedule.training_id', '=', 'trainings.id')
				->leftJoin('training_participants', 'training_participants.training_schedule_id', '=', 'training_schedule.id')
				->where('training_participants.training_schedule_id',$participant->training_schedule_id)
				->first(['trainings.title','training_schedule.start_time','training_schedule.end_time','training_schedule.location']);


			$action = '<span id="phase_2'.$request->get('participant_id').'">&nbsp;&nbsp;<a href="javascript:void(0);" class="btn btn-xs btn-danger absentParticipant" id="'.$request->get('participant_id').'">&nbsp; Decline</a>';
			$action .= '&nbsp;&nbsp;<a href="javascript:void(0);" class="btn btn-xs btn-primary presentParticipant" id="'.$request->get('participant_id').'">&nbsp; Participate &nbsp;</a></span>';
			$data = ['responseCode' => 1, 'msg' => 'Participant is verified for this training','html'=>$action];

			$body_msg = trans('messages.hajj_verify_msg')."
			<br/><br/><br/>
			".trans('messages.hajj_training_subject')." ".$training->title."<br/>"
				.trans('messages.hajj_training_date_time')." ".date('Y-m-d h:i a',strtotime($training->start_time))." - ".date('Y-m-d h:i a',strtotime($training->end_time))."<br/>"
				.trans('messages.hajj_training_locatione')." ".$training->location;



			$data = array(
				'header' => 'Verified training application',
				'param' => $body_msg
			);

			\Mail::send('Training::message', $data, function ($message) use ($email) {
				$message->from($this->email_sender_add, 'Hajj training system')
					->to($email)
					->subject(trans('messages.hajj_training_verify_subject'));
			});
		}
		catch (\Exception $e) {
			\Session::flash('error', 'Something went wrong [AJAX_VAR]'.$e->getMessage());
			return Redirect::back();
		}
		Session::flash('success', 'Successfully Updated Information!');
		return Redirect::back();
	}

	public function presentParticipant(Request $request)
	{
		if (!ACL::getAccsessRight('Training', 'E'))
		{
			$data = ['responseCode' => '0', 'msg' => 'no access right!'];
			return response()->json($data);
		}
		date_default_timezone_set('Asia/Dhaka');
		$participant_id = Encryption::decodeId($request->get('participant_id'));
		$trainingInfo = TrainingParticipants::join('training_schedule','training_schedule.id','=','training_participants.training_schedule_id')
						->where('training_participants.id',$participant_id)
						->first(['training_schedule.start_time']);


		if(!isset($trainingInfo->start_time) || (time() < strtotime($trainingInfo->start_time)))
		{
			$data = ['responseCode' => 0, 'msg' => 'Training has not been started yet','html'=>''];
			return response()->json($data);
		}

		TrainingParticipants::where('id', $participant_id)->update(array('status' => 3,'updated_by' => CommonFunction::getUserId()));

        $requestData = ['status' => 3,'updated_by' => CommonFunction::getUserId()];
        $request->request->add($requestData);
        CommonFunction::createAuditLog('Training.present-training',$request);

		$data = ['responseCode' => 1, 'msg' => 'Participant is attended to this training','html'=>''];
		return response()->json($data);
	}

	public function absentParticipant(Request $request)
	{
		if (!ACL::getAccsessRight('Training','E'))
		{
			$data = ['responseCode' => 0, 'msg' => 'no access right!'];
			return response()->json($data);
		}
		$participant_id = Encryption::decodeId($request->get('participant_id'));

		$trainingInfo = TrainingParticipants::join('training_schedule','training_schedule.id','=','training_participants.training_schedule_id')
			->where('training_participants.id',$participant_id)
			->first(['training_schedule.start_time']);

		if(!isset($trainingInfo->start_time) || (time() < strtotime($trainingInfo->start_time)))
		{
			$data = ['responseCode' => 0, 'msg' => 'Training has not been started yet','html'=>''];
			return response()->json($data);
		}

		TrainingParticipants::where('id', $participant_id)->update(array('status' => 4, 'updated_by' => CommonFunction::getUserId()));

        $requestData = ['status' => 4,'updated_by' => CommonFunction::getUserId()];
        $request->request->add($requestData);
        CommonFunction::createAuditLog('Training.absent-training',$request);

		$data = ['responseCode' => 1, 'msg' => 'Participant is absent in the training','html'=>''];
		return response()->json($data);
	}
	private function trainingCertApiRequest($app_id,$action='',$pdfurl='')
	{
		$pdf_type = env('TRAINING_PDF_TYPE');
		$reg_key = env('TRAINING_PDF_REG_KEY');

		//$pdf_type = 'prp.tr.cert.u';
		//$reg_key = 'p1816-t18-c518t-9693a205';



		$data = array();
		$data['data'] = array(
			'reg_key'=>$reg_key,       // Authentication key
			'pdf_type'=>$pdf_type,         // letter type
			'ref_id'=>$app_id,          //app_id
			'param'=>array(
				'app_id'=>$app_id  // app_id
			)
		);

		$data1 = json_encode($data);

		$url = '';

		if($action == "job-status")
		{
			#$url = "https://pdfservice.pilgrimdb.org:8092/api/job-status?requestData=$data1";
			#$url = "https://192.168.151.110:8093/api/job-status?requestData=$data1";
			#$url = "https://192.168.151.110:8093/api/job-status?requestData=$data1";
			$url = "{$pdfurl}api/job-status?requestData=$data1";
		}
		else if($action == "new-job")
		{
			#$url = "https://pdfservice.pilgrimdb.org:8092/api/new-job?requestData=$data1";
			#$url = "https://192.168.151.110:8093/api/new-job?requestData=$data1";
			#$url = "https://192.168.151.110:8093/api/new-job?requestData=$data1";
			$url = "{$pdfurl}api/new-job?requestData=$data1";
		}
		else
		{
			return false;
		}



		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 150);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		$response = curl_exec($ch);
		if (curl_errno($ch))
		{
			echo curl_error($ch);
			echo "\n<br />";
			$response = '';
		}
		else
		{
			curl_close($ch);
		}
		$dataResponse = json_decode($response);
		return $dataResponse;
	}

	public function ajaxCertificateLetter(Request $request)
	{
		/*
		 * API request for certificate generation will done here
		 */
		$training_participant_id = Encryption::decodeId($request->get('training_participant_id'));
		$pdfurl = $request->get('pdfurl');
		$this->trainingParCertApiRequest($training_participant_id,'new-job',$pdfurl);
		$data = ['responseCode' => 1, 'msg' => 'Certificate generation on process!!!'];
		return response()->json($data);
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
     */
	public function ajaxCertificateFeedback(Request $request)
	{
		$training_participant_id = Encryption::decodeId($request->get('training_participant_id'));
		$pdfurl = $request->get('pdfurl');
		$response = $this->trainingParCertApiRequest($training_participant_id,'job-status',$pdfurl);



		/*
		 * API request for certificate generation will done here
		 */

		if (!empty($response->response) && $response->response->status == 0)
		{
			// work next
			$data = ['responseCode' => 1, 'flag' => '2','id'=> '','certificate' => ''];
		}
		elseif(!empty($response->response) && $response->response->status == 1)
		{
			$data = ['responseCode' => 1, 'flag' => '1','id' => $request->get('training_participant_id'),'certificate' => $response->response->download_link];
			TrainingParticipants::where('id',$training_participant_id)->update(['certificate'=>$response->response->download_link]);
		}
		else
		{
			$data = ['responseCode' => 1, 'flag' => '2','id'=> '','certificate' => ''];
		}
		return response()->json($data);
	}


	public function updateDownloadPanel(Request $request)
	{
		$certificate = $request->get('certificate');
		$training_participant_id = $request->get('training_participant_id');
		$return = '';
		$return .= '&nbsp;&nbsp;<a target="_blank" href="'.$certificate.'" class="btn btn-primary download_crt" id="dl_'.$training_participant_id.'">&nbsp; Download Certificate</a>';
		$return .= '<span id="ddl_'.$training_participant_id.'">&nbsp;&nbsp;<a href="javascript:void(0);" class="btn btn-warning get_crt" id="'.$training_participant_id.'">&nbsp; Re Generate</a></span>';
		return $return;
	}


	public function ajaxTrCertificateLetter(Request $request)
	{
		/*
		 * API request for certificate generation will done here
		 */
		$training_schedule_id = Encryption::decodeId($request->get('training_schedule_id'));
		$pdfurl = $request->get('pdfurl');
		$this->trainingCertApiRequest($training_schedule_id,'new-job',$pdfurl);
		$data = ['responseCode' => 1, 'msg' => 'Certificate generation on process!!!'];
		return response()->json($data);
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
     */
	public function ajaxTrCertificateFeedback(Request $request)
	{
		$training_schedule_id = Encryption::decodeId($request->get('training_schedule_id'));
		$pdfurl = $request->get('pdfurl');
		$response = $this->trainingCertApiRequest($training_schedule_id,'job-status',$pdfurl);



		/*
		 * API request for certificate generation will done here
		 */

		if (!empty($response->response) && $response->response->status == 0)
		{
			// work next
			$data = ['responseCode' => 1, 'flag' => '2','id'=> '','certificate' => ''];
		}
		elseif(!empty($response->response) && $response->response->status == 1)
		{
			$data = ['responseCode' => 1, 'flag' => '1','id' => $request->get('training_schedule_id'),'certificate' => $response->response->download_link];
			TrainingSchedule::where('id',$training_schedule_id)->update(['certificate'=>$response->response->download_link]);
		}
		else
		{
			$data = ['responseCode' => 1, 'flag' => '2','id'=> '','certificate' => ''];
		}
		return response()->json($data);
	}


	public function updateTrDownloadPanel(Request $request)
	{
		$certificate = $request->get('certificate');
		$training_schedule_id = $request->get('training_schedule_id');
		$return = '';
		$return .= '&nbsp;&nbsp;<a target="_blank" href="'.$certificate.'" class="btn btn-danger download_crt" id="dl_'.$training_schedule_id.'">&nbsp; Download Certificate</a>';
		$return .= '<span id="ddl_'.$training_schedule_id.'">&nbsp;&nbsp;<a href="javascript:void(0);" class="btn btn-warning get_crt" id="'.$training_schedule_id.'">&nbsp; Re Generate</a></span>';
		return $return;
	}

	private function trainingParCertApiRequest($app_id,$action='',$pdfurl='')
	{
		$pdf_type = env('TRAINING_PART_PDF_TYPE');
		$reg_key = env('TRAINING_PDF_REG_KEY');

		//$pdf_type = 'prp.tr.cert.i.u';
		//$reg_key = 'p1816-t18-c518t-9693a205';



		$data = array();
		$data['data'] = array(
			'reg_key'=>$reg_key,       // Authentication key
			'pdf_type'=>$pdf_type,         // letter type
			'ref_id'=>$app_id,          //app_id
			'param'=>array(
				'app_id'=>$app_id  // app_id
			)
		);

		$data1 = json_encode($data);

		$url = '';

		if($action == "job-status")
		{
			#$url = "https://pdfservice.pilgrimdb.org:8092/api/job-status?requestData=$data1";
			#$url = "https://192.168.151.110:8093/api/job-status?requestData=$data1";
			#$url = "https://192.168.151.110:8093/api/job-status?requestData=$data1";
			$url = "{$pdfurl}api/job-status?requestData=$data1";
		}
		else if($action == "new-job")
		{
			#$url = "https://pdfservice.pilgrimdb.org:8092/api/new-job?requestData=$data1";
			#$url = "https://192.168.151.110:8093/api/new-job?requestData=$data1";
			#$url = "https://192.168.151.110:8093/api/new-job?requestData=$data1";
			$url = "{$pdfurl}api/new-job?requestData=$data1";
		}
		else
		{
			return false;
		}



		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 150);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		$response = curl_exec($ch);
		if (curl_errno($ch))
		{
			echo curl_error($ch);
			echo "\n<br />";
			$response = '';
		}
		else
		{
			curl_close($ch);
		}
		$dataResponse = json_decode($response);
		return $dataResponse;
	}

	/**
	 * Function for existing licence number should have  
	 * prefix with zero if the length < 4
     */
	public function correctionLicenceNum()
	{
		$pData = TrainingParticipants::whereNotNull('agency_license')->whereRaw("CHAR_LENGTH(agency_license) < 4")->get(['id', 'agency_license']);
		if(count($pData) == 0)
		{
			die("No license data found");
		}
		try
		{
			foreach($pData as $user)
			{
				if(strlen($user->agency_license) == 1)
				{
					$user->agency_license = "000" . $user->agency_license;
				}
				else if(strlen($user->agency_license) == 2)
				{
					$user->agency_license = "00" . $user->agency_license;
				}
				else if(strlen($user->agency_license) == 3)
				{
					$user->agency_license = "0" . $user->agency_license;
				}
				else
				{
					continue;
				}
				DB::beginTransaction();
				TrainingParticipants::where('id', $user->id)->update(['agency_license'=>$user->agency_license]);
				DB::commit();
			}
		}
		catch (\Exception $e)
		{
			
			DB::rollback();
			die($e->getMessage());
		}
		die('Done');
	}

	public function fillParticipantInfo()
	{
		try
		{
			$pData = TrainingParticipants::join('users', 'users.id', '=', 'training_participants.user_id')
				->leftJoin('area_info as d', 'users.district', '=', 'd.area_id')
				->leftJoin('area_info as t', 'users.thana', '=', 't.area_id')
				->where('training_participants.user_id', '>', 0)
				->get(
					[
						'users.id',
						'users.user_type',
						'users.user_sub_type',
						'users.bank_branch_id',
						'users.code',
						'users.user_full_name',
						'users.user_email',
						'users.user_nid',
						'users.user_DOB',
						'users.user_phone',
						'd.area_nm as district_name',
						't.area_nm as thana_name'
					]
				);

			foreach($pData as $user)
			{
				$type = explode('x',$user->user_type);
				$data = array();
				if($type[0] == '12') // Agency
				{
					$agency = Agency::where('id',$user->user_sub_type)->first(['name','license_no']);
					$data['agency_name'] = isset($agency->name) ? $agency->name : '';
					$data['agency_license'] = isset($agency->license_no) ? $agency->license_no : '';
				}
				else if($type[0] == '11') // Bank
				{
					$bank = Bank::where('id',$user->user_sub_type)->first(['name']);
					$data['bank'] = isset($bank->name) ? $bank->name : '';
				}


				$data['name'] = $user->user_full_name;
				$data['email'] = $user->user_email;
				$data['mobile'] = $user->user_phone;
				$data['trainee_nid'] = $user->user_nid;
				$data['dob'] = $user->user_DOB;
				$data['district'] = $user->district_name;
				$data['updated_at'] = date('Y-m-d H:i:s');


				DB::beginTransaction();
				TrainingParticipants::where('user_id', $user->id)->update($data);
				DB::commit();
			}
		}
		catch (\Exception $e)
		{
			DB::rollback();
			die($e->getMessage());
		}
		die('Done');
	}



	
}