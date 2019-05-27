<?php namespace App\Modules\BoardMeting\Controllers;

use App\ActionInformation;
use App\Http\Controllers\Controller;


use App\Modules\Apps\Models\EmailQueue;
use App\Modules\BoardMeting\Models\Agenda;
use App\Modules\BoardMeting\Models\BoardMeetingDoc;
use App\Modules\BoardMeting\Models\BoardMeting;
use App\Modules\BoardMeting\Models\Committee;
use App\Modules\BoardMeting\Models\ProcessListBoardMeting;
use App\Modules\Faq\Models\Faq;
use App\Modules\Files\Controllers\FilesController;
use App\Modules\ProcessPath\Models\Desk;
use App\Modules\ProcessPath\Models\UserDesk;
use App\Modules\projectClearance\Models\Agency;
use App\Modules\Settings\Models\Notice;
use App\Modules\Users\Models\Countries;
use App\Modules\Users\Models\Delegation;
use App\Modules\Users\Models\EconomicZones;
use App\Modules\Users\Models\FailedLogin;
use App\Modules\Users\Models\Notification;
use App\Modules\Users\Models\ParkInfo;
use App\Modules\Users\Models\UserLogs;
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

class BoardMetingController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view("BoardMeting::list");
    }

    /*
     * user's list for system admin
     */
    public function lists()
    {
        $shareDoc = BoardMeetingDoc::where('is_active',1)
            ->where('ctg_id', 2)
            ->orderBy('id','DESC')
            ->get(['id','doc_name','tag', 'created_at']);

        $notice = Notice::where('is_active',1)
            ->where('prefix','=', 'board-meeting')
            ->orderBy('id','DESC')
            ->get();

        return view('BoardMeting::list',compact('shareDoc','notice'));
    }

    /*
     *Board Meting details information by ajax request
     */
    public function getRowDetailsData()
    {
        $mode = ACL::getAccsessRight('BoardMeting', '-V-');
        $boardMeting = BoardMeting::getList();

        return Datatables::of($boardMeting)
            ->addColumn('action', function ($boardMeting) use ($mode) {
                if ($mode) {
                    $button ="";
                    if(in_array($boardMeting->board_meeting_status,[5,10,11])) {
                        if ($boardMeting->meeting_agenda_path == null){
                            if (CommonFunction::getUserType() == '13x303') { //13X303= board admin
                                $button .= '<a href="' . url('/board-meting/agenda/download/' . Encryption::encodeId($boardMeting->id)) . '" class="btn btn-xs btn-warning "><i class="fa fa-download"></i> Generate Agenda </a><br> ';
                            }
                        }else{
                            $button.= '<a href="' . url($boardMeting->meeting_agenda_path) . '"  download="" class="btn btn-xs btn-warning "><i class="fa fa-download"></i> Draft Meeting Minutes</a><br> ';
//                            $button .= '<a href="' . url('/board-meting/agenda/doc-download/' . Encryption::encodeId($boardMeting->id)) . '" class="btn btn-xs btn-danger "><i class="fa fa-download" aria-hidden="true"></i></i> Download Agenda DOC </a><br> ';
                        }


                    }
                    return $button;
                } else {
                    return '';
                }
            })
            ->editColumn('meting_date', function ($boardMeting)  use ($mode)  {
                $upComing = BoardMeting::where('meting_date', '>', date("Y-m-d"))
                    ->orderBy('meting_date')
                    ->first();
                if (!empty($upComing) && $upComing->id == $boardMeting->id)
                {
                    $newItem = '<img src="/assets/images/upcoming.png" style="margin-top: 0px;width: 100px" alt=" " class="img-responsive">';
                }else{
                    $newItem = '';
                }

                $html = '<a href="' . url('board-meting/agenda/list/' . Encryption::encodeId($boardMeting->id)) . '" class="hover-item" style="text-decoration: none">
          
                    <div class="panel  hover-item" style="margin-top: 10px; border: 1px solid #86bb86">
                        <div class="panel-heading" >
                            <div class="row">
                                <div class="col-xs-2">
                                    <div class="h5" style="margin-top:0;margin-bottom:0;font-size: 15px;text-align:right">
                                       </div>
                                         <div style="position: absolute">
                        ' . $newItem . '
                    </div>
                                </div>
                                <div class="col-xs-10 text-right">
                                     Meeting No. '.$boardMeting->meting_number.'
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 text-right">
                                    <div style="font-size: 12px;color: gray">
                                        <br>
                                      Location. '.$boardMeting->area_nm.'
                                    </div>
                                </div>
                            </div>
                        </div>
                            <div class="panel-footer" style="padding:  5px; background: linear-gradient(to bottom, #eeeeee 0%,#cccccc 100%);">
                              
                                <span class="text-center">&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-calendar" aria-hidden="true"></i> '.date("d M Y h:i a", strtotime($boardMeting->meting_date)).'</span>
                                <span class="pull-right"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>
                                <div class="clearfix"></div>
                            </div>

                    </div>
               
                </a>';
                return $html;

            })
            ->editColumn('agenda_info', function ($boardMeting) {

                $agenda_data = explode("##",$boardMeting->agenda_info);
                $userType = CommonFunction::getUserType();
                $button = '';
                if($userType == '13x303' && empty($boardMeting->agenda_info)) {
                    $button.= "<a href='/board-meting/agenda/create-new-agenda/.".Encryption::encodeId($boardMeting->id)." 'style='margin-top: 10px; ' class='btn btn-md btn-default btn-block'><i class='fa fa-plus'></i> Add Agenda</a> ";
                }
                $i=0;
                foreach ($agenda_data as $value) {
                    $i++;
                    $row = explode(",", $value);
                    if (!empty($row[0])) {

                        if($row[3] == 0 && $row[4] == 0){
                            $newItem = '<img src="/assets/images/newitem.png" style="width: 13%; margin-top: -3px" alt=" " class="img-responsive">';
                        }else{
                            $newItem = '';
                        }

                        $button.= '
                <a class="hover-item" style="text-decoration: none" href="#">
                 <div class="panel panel-default hover-item" style="           
                    margin-top: 2px; border: 1px solid #86bb86">
                    <div style="position: absolute">
                        ' . $newItem . '
                    </div>
                    <div>
                    <div class="pull-right" style="margin: 15px 15px 0px 0px;"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>
                    <div class="panel-heading" style="border-left: 5px solid #31708f">
                        <div class="col-md-offset-2"><span style="margin-top: 20px;">' . $row[0] . '</span><br>&nbsp;</div>
                    </div>
                
                    </div>
                    
                 </div>
                </a>';

                    }
                }
                return  $button ;

            })
            ->editColumn('status', function ($boardMeting) {
                    $activate = 'style="color:white" class=" btn-xs  label-'.$boardMeting->panel.'" ';
                    $status_name = $boardMeting->status_name;

                return '<span ' . $activate . '><b>' . $status_name . '</b></span>';
            })
            ->removeColumn('id')
            ->make(true);
    }
    public function view($board_meting_id){
        return view('BoardMeting::agenda-list')->with('board_meting_id', $board_meting_id);

    }

    public function newBoardMeting(){
        return view('BoardMeting::create-board-meting');
    }

    public function editBM($bm_id){

        $bm_data = BoardMeting::find(Encryption::decodeId($bm_id));
        return view('BoardMeting::edit-board-meting', compact('bm_data'));
    }

    private function _dateTimeConvartFromDateTimePicker($requestDateTime){
        list($day, $month, $year, $hour, $minute, $dayType) = preg_split('/[\/\s:]+/', $requestDateTime);
        if($hour == 12 && $dayType == "pm"){
            $dayType = "am"; // for 12 PM
        }elseif($hour == 12 && $dayType == "am"){
            $hour = "00";
//                $dayType = "pm";
            // for 12 AM
        }
        $convertDateTime =  $d1me = $year . '-' . $month. '-' .  $day . ' ' . ($dayType == "pm"?$hour+12: $hour) . ":" . $minute . ":00";
        $time = explode(" ", $convertDateTime);
        $ConvertMysqlFormat =  date('Y-m-d', strtotime($convertDateTime))." ".$time[1];
        return $ConvertMysqlFormat; // dataType in DB is datetime
    }
    public function storeMeeting(Request $request){
        try {

            DB::beginTransaction();
            $boardMeeting = new BoardMeting();
            $boardMeeting->meting_date = $this->_dateTimeConvartFromDateTimePicker($request->get('meting_date'));;
            $boardMeeting->agenda_ending_date = $this->_dateTimeConvartFromDateTimePicker($request->get('meting_ending_date'));
            $boardMeeting->meting_subject = $request->get('meeting_subject');
            $boardMeeting->meting_number = $request->get('meting_number');
            $boardMeeting->location = $request->get('location');
            $boardMeeting->org_name = $request->get('organization');
            $boardMeeting->org_address = $request->get('organization_address');
            $boardMeeting->notice_details = $request->get('notice_details');
            $boardMeeting->sequence_no = 2; //for first step
            $boardMeeting->is_active = 1;
            $boardMeeting->status = 6;
            $boardMeeting->save();

//            $agendaInfo = new Agenda();
//            $agendaInfo->board_meting_id = $boardMeeting->id;
//            $agendaInfo->is_active = 1;
//            $agendaInfo->save();

            $Committee = new Committee();
            $Committee->board_meeting_id = $boardMeeting->id;
            $Committee->user_name = Auth::user()->user_full_name;
            $Committee->user_email = Auth::user()->user_email;
            $Committee->user_mobile = Auth::user()->user_phone;
            $Committee->designation = Auth::user()->designation;
            $Committee->type = 'No';
            $Committee->save();
            DB::commit();

            Session::flash('success', 'Board Meeting Successfully Added!');

            return redirect('/board-meting/agenda/create-new-agenda/'.Encryption::encodeId($boardMeeting->id));
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [UC5102]');
            return Redirect::back()->withInput();
        }

    }

    public function updateMeeting(Request $request){

        $boardMeeting = BoardMeting::findOrNew(Encryption::decodeId($request->get('bm_id')));
        $boardMeeting->meting_date =  $boardMeeting->meting_date = $this->_dateTimeConvartFromDateTimePicker($request->get('meting_date'));
        $boardMeeting->agenda_ending_date = $this->_dateTimeConvartFromDateTimePicker($request->get('meting_ending_date'));
        $boardMeeting->meting_subject = $request->get('meeting_subject');
        $boardMeeting->meting_number = $request->get('meting_number');
        $boardMeeting->location = $request->get('location');
        $boardMeeting->location = $request->get('location');
        $boardMeeting->org_name = $request->get('organization');
        $boardMeeting->org_address = $request->get('organization_address');
        $boardMeeting->notice_details = $request->get('notice_details');
        $boardMeeting->is_active = 1;
        $boardMeeting->status = 6;
        $boardMeeting->sequence_no = 2;
        $boardMeeting->save();
        Session::flash('success', 'Board Meeting Successfully Update!');
        return redirect('/board-meting/agenda/create-new-agenda/'.$request->get('bm_id'));
//        return redirect('/board-meting/committee/member-edit/'.$request->get('bm_id'));
//        return redirect('/board-meting/lists/');

    }

    public function checkNumber(Request $request){
        try {
            $meeting_number = $request->get('meeting_number');
            $if_existed_number = BoardMeting::where('meting_number', $meeting_number)->count();

            if ($if_existed_number > 0){
                return $if_existed_number;
            }else{
                return $if_existed_number;
            }

        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [UC5102]');
            return Redirect::back()->withInput();
        }
    }

    public function createShareDocument(){
        return view('BoardMeting::create-share-document');
    }

    public function storeShareDocument(Request $request){

        $this->validate($request, [
            'doc_name' => 'required',
            'attachment' => 'required',
            'tag' => 'required',
        ]);

        $attach_file = $request->file('attachment');
        if ($request->hasFile('attachment')) {
                $fileType = $attach_file->getClientOriginalExtension();
                $getSize = $attach_file->getSize();
                if ($getSize > (1024 * 1024 * 3)) {
                    Session::flash('error', 'File size max 3 MB');
                    return redirect()->back();
                }
                $support_type = array('pdf','xls','xlsx','ppt','pptx','docx','doc');
                if (!in_array($fileType, $support_type)) {
                    Session::flash('error', 'File type must be xls,xlsx,ppt,pptx,pdf,doc,docx format');
                    return redirect()->back();
                }

                $original_file = $attach_file->getClientOriginalName();
                $attach_file->move('uploads/boardMeeting/', time() . $original_file);
            }

        BoardMeetingDoc::create([
            'doc_name'=> $request->get('doc_name'),
            'file' => 'uploads/boardMeeting/' . time() . $original_file,
            'tag'=> $request->get('tag'),
            'ctg_id'=> 2,
            'is_active'=> 1,
        ]);
        Session::flash('success', 'Share Document Successfully Added!');
        return Redirect::back();

    }

    public function getShareDocument(){
        $mode = ACL::getAccsessRight('BoardMeting', '-V-');
        $boardMeting = BoardMeetingDoc::getList();

        return Datatables::of($boardMeting)
            ->make(true);
    }

    public function viewShareDocument($id){
        $shareDocumentId = Encryption::decodeId($id);
        $doc = BoardMeetingDoc::where('id',$shareDocumentId)->first();
        return view('BoardMeting::view-share-document',compact('doc'));
    }

    public function viewNews($id){
        $news_id = Encryption::decodeId($id);
        $news = Notice::where('id',$news_id)->first();
        return view('BoardMeting::view-news',compact('news'));
    }

    public function fixedMeeting(Request $request){
        $board_id = Encryption::decodeId($request->get('board_meeting_id'));
        $agenda=new AgendaController();
        $responsedata=$agenda->downloadAgenda($request->get('board_meeting_id'));
        BoardMeting::where('id',$board_id)
            ->update([
                'status'=> 5 //5= fixed board-meeting status and ty
            ]);
        return response()->json(['responseCode' => 1, 'status' => 'success']);
    }

    public function completeMeeting(Request $request){
        $board_id = Encryption::decodeId($request->get('board_meeting_id'));
        $ref_no = $request->get('ref_no');
        BoardMeting::where('id',$board_id)
            ->update([
                'status'=> 10, //10= complete board-meeting status
                'sequence_no'=> 6,
                'reference_no'=> $ref_no,

            ]);
        return response()->json(['responseCode' => 1, 'status' => 'success']);
    }

    public function getCompleteMeeting(){
        $mode = ACL::getAccsessRight('BoardMeting', '-V-');
        $boardMeting = BoardMeting::getCompleteList();

        return Datatables::of($boardMeting)
            ->addColumn('action', function ($boardMeting) use ($mode) {
                if ($mode) {
                    $button = '';
                    $getChairpersonEmail = CommonFunction::checkChairperson($boardMeting->id);
                    if (CommonFunction::getUserType() == '13x303' && $boardMeting->board_meeting_status == 10 && $boardMeting->meeting_minutes_path !=''){ //13X303= board admin
//                            $button.= ' <button  class="btn btn-xs btn-primary publish_complete_meeting" value="'.Encryption::encodeId($boardMeting->id).'" ><i class="fa fa-bell-o"></i> Publish </button> ';
                    }
                    if(in_array($boardMeting->board_meeting_status,[5,10,11])) {
                        if ($boardMeting->meeting_agenda_path == null){
                            if (CommonFunction::getUserType() == '13x303') { //13X303= board admin
                                $button .= '<a  href="' . url('/board-meting/agenda/download/' . Encryption::encodeId($boardMeting->id)) . '" class="btn btn-xs btn-warning "><i class="fa fa-download"></i> Generate Agenda </a><br> ';
                            }
                        }else{
                            $button.= '<a style="margin: 2px" href="' . url($boardMeting->meeting_agenda_path) . '"  download="" class="btn btn-xs btn-warning "><i class="fa fa-download"></i> Download Draft Meeting Minutes</a><br> ';
//                            $button .= '<a href="' . url('/board-meting/agenda/doc-download/' . Encryption::encodeId($boardMeting->id)) . '" class="btn btn-xs btn-danger "><i class="fa fa-download" aria-hidden="true"></i></i> Download Agenda DOC </a><br> ';
                        }

                        if ($boardMeting->meeting_minutes_path == null){
                            if (CommonFunction::getUserType() == '13x303' || (isset($getChairpersonEmail) && $getChairpersonEmail == Auth::user()->user_email && $boardMeting->board_meeting_status == 10)) { //13X303= board admin
                                $button .= '<a href="' . url('/board-meting/agenda/minutes/download/' . Encryption::encodeId($boardMeting->id)) . '" class="btn btn-xs btn-danger "><i class="fa fa-refresh" aria-hidden="true"></i></i> Generate meeting minutes </a><br> ';
                            }
                        }else{
                            $button.= '<a href="' . url($boardMeting->meeting_minutes_path) . '"  download="" class="btn btn-xs btn-info "><i class="fa fa-download"></i> Meeting minutes download pdf </a><br> ';
//                            $button .= '<a href="' . url('/board-meting/agenda/minutes/doc-download/' . Encryption::encodeId($boardMeting->id)) . '" class="btn btn-xs btn-success "><i class="fa fa-download"></i> meeting minutes download doc </a><br> ';
                        }


                    }
                    return $button;
                } else {
                    return '';
                }
            })
            ->editColumn('meting_date', function ($boardMeting)  use ($mode)  {
                $html = '<a href="' . url('board-meting/agenda/list/' . Encryption::encodeId($boardMeting->id)) . '" class="hover-item" style="text-decoration: none">

                    <div class="panel  hover-item" style="margin-top: 10px; border: 1px solid #86bb86">
                        <div class="panel-heading" >
                            <div class="row">
                                <div class="col-xs-2">
                                    <div class="h5" style="margin-top:0;margin-bottom:0;font-size: 15px;text-align:right">
                                       </div>
                                         <div style="position: absolute">

                    </div>
                                </div>
                                <div class="col-xs-10 text-right">
                                     Meeting No. '.$boardMeting->meting_number.'
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 text-right">
                                    <div style="font-size: 12px;color: gray">
                                        <br>
                                      Location. '.$boardMeting->area_nm.'
                                    </div>
                                </div>
                            </div>
                        </div>
                            <div class="panel-footer" style="padding:  5px; background: linear-gradient(to bottom, #eeeeee 0%,#cccccc 100%);">

                                <span class="text-center">&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-calendar" aria-hidden="true"></i> '.date("d M Y h:i a", strtotime($boardMeting->meting_date)).'</span>
                                <span class="pull-right"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>
                                <div class="clearfix"></div>
                            </div>

                    </div>

                </a>';
                return $html;

            })
            ->editColumn('agenda_info', function ($boardMeting) {

                $agenda_data = explode("##",$boardMeting->agenda_info);
                $button="";
                $i=0;
                foreach ($agenda_data as $value) {
                    $i++;
                    $row = explode(",", $value);
                    if (!empty($row[0])) {
                        $button .= '
                <a class="hover-item" style="text-decoration: none" href="#">
                 <div class="panel panel-default hover-item" style="
                    margin-top: 2px; border: 1px solid #86bb86">
                    <div style="position: absolute">
                    </div>
                    <div>
                    <div class="pull-right" style="margin: 15px 15px 0px 0px;"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>
                    <div class="panel-heading" style="border-left: 5px solid #31708f">
                        <div class="col-md-offset-2"><span style="margin-top: 20px;">' . $row[0] . '</span><br>&nbsp;</div>
                    </div>

                    </div>

                 </div>
                </a>';

                    }
                }
                return  $button ;

            })
            ->editColumn('status', function ($boardMeting) {
                $activate = 'style="color:white;" class="  btn-xs  label-'.$boardMeting->panel.'" ';
                $status_name = $boardMeting->status_name;

                return '<span ' . $activate . '><b>' . $status_name . '</b></span>';
            })
            ->removeColumn('id')
            ->make(true);
    }

    public function completeMeetingPublish(Request $request)
    {
        BoardMeting::where('id',Encryption::decodeId($request->get('board_meeting_id')))
            ->update(['status'=>11]);
        return response()->json(['responseCode' => 1, 'status' => 'success']);
    }

    public function generateDocx()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();


        $section = $phpWord->addSection();
        $header = $section->addHeader();
//
        $header->addText('This document has a header with just one image.', array('align'=>'right'));
//        $table->addText('This is the header.');
//        $table->addCell(4500)->addImage('_earth.jpg', array('width'=>50, 'height'=>50, 'align'=>'right'));
// Add footer
        $footer = $section->createFooter();
        $footer->addPreserveText('Page {PAGE} of {NUMPAGES}.', array('align'=>'center'));
        // Adding Text element with font customized using explicitly created font style object...
        $fontStyle = new \PhpOffice\PhpWord\Style\Font();
        $fontStyle->setBold(true);
        $fontStyle->setName('Tahoma');
        $fontStyle->setSize(20);
        $section->addImage("http://itsolutionstuff.com/frontTheme/images/logo.png");
        $myTextElement = $section->addText('"Believe you can and you\'re halfway there." (Theodor Roosevelt)');
        $myTextElement->setFontStyle($fontStyle);
        $sectionStyle = $section->getStyle();
        $sectionStyle->setMarginRight(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(2));
        $header = $section->addHeader();


// Saving the document as OOXML file...
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('helloWorld.docx');
        try {
            $objWriter->save(storage_path('helloWorld.docx'));
        } catch (Exception $e) {
        }


        return response()->download(storage_path('helloWorld.docx'));
        dd(55);

    }

}
