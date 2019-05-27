<?php namespace App\Modules\BoardMeting\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Apps\Models\EmailQueue;
use App\Modules\BoardMeting\Models\Agenda;

use App\Modules\BoardMeting\Models\AgendaRemarks;
use App\Modules\BoardMeting\Models\BoardMeetingProcessStatus;
use App\Modules\BoardMeting\Models\BoardMeetingDoc;
use App\Modules\BoardMeting\Models\BoardMeting;
use App\Modules\BoardMeting\Models\Committee;
use App\Modules\BoardMeting\Models\ProcessListBMRemarks;
use App\Modules\BoardMeting\Models\ProcessListBoardMeting;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use Illuminate\Http\Request;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Response as FacadeResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use League\Fractal\Resource\Collection;

use Mpdf\Mpdf;
use Symfony\Component\HttpFoundation\File\File;
use yajra\Datatables\Datatables;
use Validator;

class AgendaController extends Controller
{


    public function view($board_meeting_id)
    {
        $meeting_id = Encryption::decodeId($board_meeting_id);
        $board_meeting_data = BoardMeting::find($meeting_id);
        $pendingAgendaCount = Agenda::where('board_meting_id',$meeting_id)->where('status',0)->count();
        $chairmen = Committee::where('board_meeting_id', $meeting_id)->where('type','yes')->first();
        $document = BoardMeetingDoc::where('board_meting_id',$meeting_id)->get();
        return view('BoardMeting::agenda.agenda-list', compact('board_meeting_id','pendingAgendaCount', 'chairmen', 'board_meeting_data','document'));

    }

    public function getAgendaData(request $request)
    {
        $mode = ACL::getAccsessRight('BoardMeting', '-V-');
        $board_meting_id = Encryption::decodeId($request->get('board_meting_id'));
        $boardMeetingStatus = BoardMeting::where('id', $board_meting_id)->first(['status']);
        $chairmen = Committee::where('board_meeting_id', $board_meting_id)->where('type','yes')->first();
        $agendaList = Agenda::leftJoin('process_type', 'process_type.id', '=', 'agenda.process_type_id')
            ->leftJoin('board_meeting_process_status', 'board_meeting_process_status.id', '=', 'agenda.status')
            ->where('board_meting_id', $board_meting_id)
            ->where('agenda.is_archive', 0)
            ->orderBy('agenda.id', 'DESC')
            ->get(['agenda.id', 'agenda.name', 'description', 'process_type.name as process_type_name', 'agenda.is_active', 'agenda.created_at', 'board_meeting_process_status.status_name', 'board_meeting_process_status.panel']);

        return Datatables::of($agendaList)
            ->addColumn('action', function ($agendaList) use ($mode,$boardMeetingStatus, $chairmen) {
                if ($mode) {
                    $userType = CommonFunction::getUserType();
                    $button = ' <a href="' . url('board-meting/agenda/process/' . Encryption::encodeId($agendaList->id)) . '" class="btn btn-xs btn-primary open" ><i class="fa fa-folder-open-o"></i> View Agenda</a>';
                    if (!in_array($boardMeetingStatus->status, [5, 10])) {  //5= fixed status 10=complete
                        if (!in_array($boardMeetingStatus->status, [5, 10]) && $userType == '13x303' || (isset($chairmen) && $chairmen->user_email == Auth::user()->user_email)) {  //5= fixed status 10=complete
                            $button .= ' <a href="' . url('board-meting/agenda/edit/' . Encryption::encodeId($agendaList->id)) . '" class="btn btn-xs btn-success open" ><i class="fa fa-edit"></i> Edit</a>';
                            $button .= ' <a  onclick="deleteAgenda(' . $agendaList->id . ')" class="btn btn-xs btn-danger remove" ><i class="fa fa-times"></i></a>';
                        }
                    }
                    return $button;
                } else {
                    return '';
                }
            })
            ->editColumn('is_active', function ($agendaList) {
                if ($agendaList->status_name != '') {
                    $activate = 'class="label btn btn-' . $agendaList->panel . '" ';
                    $status_name = $agendaList->status_name;
                } else {
                    $activate = 'class="label btn btn-warning" ';
                    $status_name = 'Pending';
                }
                return '<span ' . $activate . '><b>' . $status_name . '</b></span>';
            })
            ->removeColumn('id')
            ->make(true);
    }

    public function deleteAgenda(Request $request)
    {
        $id = $request->get('agenda_id');
        Agenda::where('id', $id)->delete();;
        return response()->json(['responseCode' => 1, 'status' => 'success']);
    }

    public function downloadAgenda($board_meeting_id){
        $meeting_id = Encryption::decodeId($board_meeting_id);
        $board_meeting_data = BoardMeting::find($meeting_id);
        if($board_meeting_data->meeting_agenda_path == null){
            $sql = "select bm.location bmlocation, bm.meting_date, bm.meting_subject, bm.meting_number, agenda_process.pbmdatas, agenda.id agnid,  agenda.name, bm.id bmid
             from board_meting  bm
            left join agenda on agenda.board_meting_id = bm.id
            left join (select agenda.id as agenda_id, GROUP_CONCAT(process_list.tracking_no ,\"AAAAA\", meeting_app.task_name ,\"AAAAA\", meeting_app.task_description ,\"AAAAA\", meeting_app.remarks SEPARATOR \"@@\") as pbmdatas,pbm.agenda_id pbmagendaId from  process_list_board_meeting pbm 
            left join agenda on pbm.agenda_id = agenda.id 
            left join process_list on process_list.id = pbm.process_id
            left join process_list_board_meeting on process_list_board_meeting.process_id = process_list.id
            left join meeting_app on meeting_app.id = process_list.ref_id
            where agenda.board_meting_id = $meeting_id GROUP BY agenda_id) as agenda_process
            on agenda_process.pbmagendaId=agenda.id
            where bm.id=$meeting_id";
            $meetingInfo = \DB::select(DB::raw($sql));


            $contents = view('BoardMeting::agenda.agenda-download',compact("meetingInfo","board_meeting_data"))->render();
//return $contents;
            $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults(); // extendable default Configs
            $fontDirs = $defaultConfig['fontDir'];

            $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults(); // extendable default Fonts
            $fontData = $defaultFontConfig['fontdata'];

            $mpdf = new mPDF([
                'tempDir'       => storage_path(),
                'fontDir' => array_merge($fontDirs, [
                    public_path('fonts'), // to find like /public/fonts/SolaimanLipi.ttf
                ]),
                'fontdata' => $fontData + [
                        'solaimanlipi' => [
                            'R' => "SolaimanLipi.ttf",
                            'useOTL' => 0xFF,
                        ],
                        'nikosh' => [
                            'R' => "Nikosh.ttf",
                            'useOTL' => 0xFF,
                        ],
                        //... you can add more custom font here
                    ],
                'utf-8', // mode - default ''
                'A4', // format - A4, for example, default ''
                12, // font size - default 0
                10, // margin_left
                10, // margin right
                10, // margin top
                15, // margin bottom
                10, // margin header
                9, // margin footer
                'P',
                'default_font' => 'solaimanlipi', // default font is not mandatory, you can use in css font
            ]);

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
//           $mpdf->setFooter("<div style='margin-left: 0px'>sss</div> Page {PAGENO} of {nb}");
            $mpdf->SetHTMLFooter('
<table width="100%" style="border-top: 1px solid black">
    <tr>
        <td width="33%">'.$board_meeting_data->meting_number.'</td>
        <td width="33%" align="center">{DATE j M Y}</td>
        <td width="33%" style="text-align: right;">{PAGENO}/{nbpg}</td>
    </tr>
</table>');
//           $mpdf->SetHTMLFooter('<table width="100%" style="border-top: 0.1mm solid black;"><tr><td style="width: 50%">dddss</td><td style="width: 50%">ssd</td></tr></table>');
            $mpdf->SetDisplayMode('fullwidth');
            $mpdf->setAutoTopMargin = 'stretch';
            $mpdf->setAutoBottomMargin = 'stretch';
            $stylesheet = file_get_contents('assets/css/pdf_download_check.css');
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

            $certificateName = uniqid("meeting-agenda" . $meeting_id . "_", true);
            $pdfFilePath = $directoryName . "/" . $certificateName . '.pdf';
            $mpdf->Output($pdfFilePath, 'F'); // Saving pdf *** F for Save only, I for view only.
            BoardMeting::where('id',$meeting_id)->update([
                'meeting_agenda_path' =>$pdfFilePath,
            ]);
        }


        return \redirect()->back();

    }

    public function downloadAgendaAsDoc($board_meeting_id){

        $meeting_id = Encryption::decodeId($board_meeting_id);
        $board_meeting_data = BoardMeting::find($meeting_id);
            $sql = "select bm.location bmlocation, bm.meting_date, bm.meting_subject, bm.meting_number, agenda_process.pbmdatas, agenda.id agnid,  agenda.name, bm.id bmid
             from board_meting  bm
            left join agenda on agenda.board_meting_id = bm.id
            left join (select agenda.id as agenda_id, GROUP_CONCAT(process_list.tracking_no ,\"AAAAA\", meeting_app.task_name ,\"AAAAA\", meeting_app.task_description ,\"AAAAA\", meeting_app.remarks SEPARATOR \"@@\") as pbmdatas,pbm.agenda_id pbmagendaId from  process_list_board_meeting pbm 
            left join agenda on pbm.agenda_id = agenda.id 
            left join process_list on process_list.id = pbm.process_id
            left join process_list_board_meeting on process_list_board_meeting.process_id = process_list.id
            left join meeting_app on meeting_app.id = process_list.ref_id
            where agenda.board_meting_id = $meeting_id GROUP BY agenda_id) as agenda_process
            on agenda_process.pbmagendaId=agenda.id
            where bm.id=$meeting_id";
            $meetingInfo = \DB::select(DB::raw($sql));
            $contents = view::make('BoardMeting::agenda.agenda-download',compact("meetingInfo","board_meeting_data"))->render();
            $headers = array(
                "Content-type"=>"application/vnd.doc",
                "Expires"=>"0",
                "Cache-Control"=>"must-revalidate, post-check=0, pre-check=0",
                "Content-Disposition"=>"attachment;filename=meeting-agenda_". $meeting_id.".doc",
            );

        return response()->make($contents,200, $headers);

    }


    public function downloadAgendaMinutes($board_meeting_id){
        if(Auth::user()->signature == ''){
            Session::flash('error', 'Please upload your signature from your profile!!!');
            return \redirect()->back();
        }
        $meeting_id = Encryption::decodeId($board_meeting_id);
        $board_meeting_data = BoardMeting::find($meeting_id);
        if($board_meeting_data->meeting_minutes_path == null){
            $sql = "select bm.location bmlocation, bm.updated_at updatedAt, bm.meting_date,bm.reference_no,  bm.meting_subject, bm.meting_number, agenda_process.pbmdatas, agenda.id agnid,  agenda.name, bm.id bmid
             from board_meting bm
            left join agenda on agenda.board_meting_id = bm.id
            left join (select agenda.id as agenda_id, GROUP_CONCAT(process_list.tracking_no ,\"AAAAA\", pbm.bm_remarks ,\"AAAAA\",bmps.status_name ,\"AAAAA\" , process_list.json_object SEPARATOR \"@@\") as pbmdatas,pbm.agenda_id pbmagendaId from  process_list_board_meeting pbm 
            left join agenda on pbm.agenda_id = agenda.id 
            left join process_list on process_list.id = pbm.process_id
            left join board_meeting_process_status as bmps on bmps.id = pbm.bm_status_id

            where agenda.board_meting_id = $meeting_id  GROUP BY agenda_id) as agenda_process
            on agenda_process.pbmagendaId=agenda.id
            where bm.id=$meeting_id";
            $meetingInfo = \DB::select(DB::raw($sql));

            $getChairperson = Committee::where('board_meeting_id', $meeting_id)
//                ->where('user_email', '!=', Auth::user()->user_email)
                ->orderby('type','DESC')->get();
//            dd($getChairperson);


//            $contents = view('BoardMeting::agenda.meeting-minutes',compact("meetingInfo","board_meeting_data","getChairperson"))->render();
            $contents = view::make('BoardMeting::agenda.meeting-minutes',compact("meetingInfo","board_meeting_data","getChairperson"))->render();

            $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults(); // extendable default Configs
            $fontDirs = $defaultConfig['fontDir'];

            $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults(); // extendable default Fonts
            $fontData = $defaultFontConfig['fontdata'];

            $mpdf = new mPDF([
                'tempDir'       => storage_path(),
                'fontDir' => array_merge($fontDirs, [
                    public_path('fonts'), // to find like /public/fonts/SolaimanLipi.ttf
                ]),
                'fontdata' => $fontData + [
                        'solaimanlipi' => [
                            'R' => "SolaimanLipi.ttf",
                            'useOTL' => 0xFF,
                        ],
                        'nikosh' => [
                            'R' => "Nikosh.ttf",
                            'useOTL' => 0xFF,
                        ],
                        //... you can add more custom font here
                    ],
                'utf-8', // mode - default ''
                'A4', // format - A4, for example, default ''
                12, // font size - default 0
                10, // margin_left
                10, // margin right
                10, // margin top
                15, // margin bottom
                10, // margin header
                9, // margin footer
                'P',
                'default_font' => 'solaimanlipi', // default font is not mandatory, you can use in css font
            ]);

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
            $mpdf->SetHTMLFooter('
            <table width="100%" border="0">
             <tr>
            <td width="33%">'.$board_meeting_data->meting_number.'</td>
            <td width="33%" align="center">{DATE j M Y}</td>
            <td width="33%" style="text-align: right;">{PAGENO}/{nbpg}</td>
             </tr>
            </table>');
            $mpdf->autoLangToFont = true;
            $mpdf->SetDisplayMode('fullwidth');
            $mpdf->setAutoTopMargin = 'stretch';
            $mpdf->setAutoBottomMargin = 'stretch';
            $stylesheet = file_get_contents('assets/css/pdf_download_check.css');
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
            $certificateName = uniqid("board_minutes" .'11'."_", true);
            $pdfFilePath = $directoryName . "/" . $certificateName . '.pdf';
            $mpdf->Output($pdfFilePath, 'F'); // Saving pdf *** F for Save only, I for view only.
            BoardMeting::where('id',$meeting_id)->update([
                'meeting_minutes_path' =>$pdfFilePath,
            ]);


        }


        return \redirect()->back();

    }

    public function generateMeetingMinutesDoc($board_meeting_id){
        if(Auth::user()->signature == ''){
            Session::flash('error', 'Please upload your signature from your profile!!!');
            return \redirect()->back();
        }

        $meeting_id = Encryption::decodeId($board_meeting_id);
        $board_meeting_data = BoardMeting::find($meeting_id);
            $sql = "select bm.location bmlocation, bm.updated_at updatedAt, bm.meting_date,bm.reference_no,  bm.meting_subject, bm.meting_number, agenda_process.pbmdatas, agenda.id agnid,  agenda.name, bm.id bmid
             from board_meting bm
            left join agenda on agenda.board_meting_id = bm.id
            left join (select agenda.id as agenda_id, GROUP_CONCAT(process_list.tracking_no ,\"AAAAA\", pbm.bm_remarks ,\"AAAAA\",bmps.status_name ,\"AAAAA\" , process_list.json_object SEPARATOR \"@@\") as pbmdatas,pbm.agenda_id pbmagendaId from  process_list_board_meeting pbm 
            left join agenda on pbm.agenda_id = agenda.id 
            left join process_list on process_list.id = pbm.process_id
            left join board_meeting_process_status as bmps on bmps.id = pbm.bm_status_id

            where agenda.board_meting_id = $meeting_id  GROUP BY agenda_id) as agenda_process
            on agenda_process.pbmagendaId=agenda.id
            where bm.id=$meeting_id";
            $meetingInfo = \DB::select(DB::raw($sql));

            $getChairperson = Committee::where('board_meeting_id', $meeting_id)
                ->orderby('type','DESC')->get();

            $contents = view::make('BoardMeting::agenda.meeting-minutes',compact("meetingInfo","board_meeting_data","getChairperson"))->render();
            $headers = array(
                "Content-type"=>"application/vnd.doc",
                "Expires"=>"0",
                "Cache-Control"=>"must-revalidate, post-check=0, pre-check=0",
                "Content-Disposition"=>"attachment;filename=meeting-minutes_". $meeting_id.".doc",
            );

            return response()->make($contents,200, $headers);

    }


    public function createNewAgenda($board_meeting_id)
    {
        $board_meeting_data = BoardMeting::find(Encryption::decodeId($board_meeting_id));
        $userType = CommonFunction::getUserType();
        $process_type =  ProcessType::where('status', 1)
                ->where('process_type.active_menu_for', 'like', "%$userType%")
                ->lists('name', 'id')->all();
        $chairmen = Committee::where('board_meeting_id', Encryption::decodeId($board_meeting_id))->where('type','yes')->first();
        return view('BoardMeting::agenda.create-agenda', compact('process_type','board_meeting_data','chairmen'));
    }

    public function storeAgenda(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required',
//                'description' => 'required',
//                'is_active' => 'required'
            ]);

            DB::beginTransaction();
            $agenda_id = Agenda::create([
                'name' => $request->get('name'),
//                'description' => $request->get('description'),
                'process_type_id' => $request->get('process_type_id'),
                'board_meting_id' => Encryption::decodeId($request->get('board_meting_id')),
                'is_active' => 1
            ]);
            BoardMeting::where('id',Encryption::decodeId($request->get('board_meting_id')))
                ->update(['sequence_no'=>'3']);

            $boardMeetingDate = BoardMeting::where('id',Encryption::decodeId($request->get('board_meting_id')))->first()->meting_date;
            $process_type_name = ProcessType::where('id', $request->get('process_type_id'))->first()->name;

            $body_msg = '<span style="text-align:justify;">';
            $body_msg .= '<b>Your agenda info: <br>Agenda Name: </b> ' . $request->get('name') . '<br>
             <b>Process Type: </b>'.$process_type_name.' <br><b>Meeting Date:</b> '.date("d-M-Y", strtotime($boardMeetingDate)).'<br> <b>Description:</b>' . $request->get('description') .
                $body_msg .= '</span>';
            $body_msg .= '<br/><br/><br/>Thanks<br/>';
            $body_msg .= "<b>".env('PROJECT_NAME')." </b>";

            $header = "Agenda Information for Board Meeting";
            $param = $body_msg;
            $email_content = view("Users::message", compact('header', 'param'))->render();
            $emailQueue = new EmailQueue();
            $emailQueue->service_id = 0; // NO SERVICE ID
            $emailQueue->app_id = $agenda_id;
            $emailQueue->email_content = $email_content;
            $emailQueue->email_to = auth::user()->user_email;
            $emailQueue->sms_to =  auth::user()->user_phone;
            $emailQueue->email_subject = $header;
            $emailQueue->attachment = '';
            $emailQueue->save();

            DB::commit();
            Session::flash('success', 'Data is stored successfully!');
            return redirect('/board-meting/agenda/list/' . $request->get('board_meting_id'));
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }

    }

    public function editAgenda($id)
    {
        $agenda_id = Encryption::decodeId($id);
        $userType = CommonFunction::getUserType();
        $process_type = ['' => 'Select One'] + ProcessType::where('status', 1)
                ->where('process_type.active_menu_for', 'like', "%$userType%")
                ->lists('name', 'id')->all();

        $agendaData = Agenda::leftJoin('board_meeting_doc', 'agenda.id', '=', 'board_meeting_doc.agenda_id')
            ->where('agenda.id', $agenda_id)->first(['agenda.*','board_meeting_doc.doc_name','board_meeting_doc.file']);
        $chairmen = Committee::where('board_meeting_id', $agendaData->board_meting_id)->where('type','yes')->first();
        return view('BoardMeting::agenda.edit-agenda', compact('agendaData', 'id', 'process_type','chairmen'));
    }

    public function updateAgenda(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        try{

            DB::beginTransaction();
            $agenda_id = Encryption::decodeId($id);
            Agenda::where('id', $agenda_id)->update([
                'name' => $request->get('name'),
//                'description' => $request->get('description'),
                'process_type_id' => $request->get('process_type_id'),
            ]);
//            BoardMeting::where('id',Encryption::decodeId($request->get('board_meting_id')))
//                ->update(['sequence_no'=>'3']);

//            $attach_file = $request->file('agenda_file');
//            if ($request->hasFile('agenda_file')) {
//                foreach ($attach_file as $afile) {
//                    $fileType = $afile->getClientOriginalExtension();
//                    $getSize = $afile->getSize();
//                    if ($getSize > (1024 * 1024 * 3)) {
//                        Session::flash('error', 'File size max 3 MB');
//                        return redirect()->back();
//                    }
//                    $support_type = array('pdf','xls','xlsx','ppt','pptx','docx','doc');
//                    if (!in_array($fileType, $support_type)) {
//                        Session::flash('error', 'File type must be xls,xlsx,ppt,pptx,pdf,doc,docx format');
//                        return redirect()->back();
//                    }
//                    $original_file = $afile->getClientOriginalName();
//                    $afile->move('uploads/agenda/', time() . $original_file);
//                    $boardMeeting = BoardMeetingDoc::where('agenda_id', $agenda_id)->first();
//
//                    if ($boardMeeting == null) {
//
//                        $file = new BoardMeetingDoc();
//                        $file->file = 'uploads/agenda/' . time() . $original_file;
//                        $file->agenda_id = $agenda_id;
//                        $file->board_meting_id = Encryption::decodeId($request->get('board_meeting_id'));
//                        $file->save();
//                    } else {
//                        BoardMeetingDoc::where('agenda_id', $agenda_id)
//                            ->update([
//                                'file' => 'uploads/agenda/' . time() . $original_file,
//                            ]);
//                    }
//                }
//            }
            DB::commit();
            Session::flash('success', 'Data is Update successfully!');
            return \redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [RC-1060]');
            return redirect()->back()->withInput();
        }

    }

    public function agendaWiseProcess($agendaId)
    {
        $agenda_id = Encryption::decodeId($agendaId);
        $process_type_id = '1';
        $userType = Auth::user()->user_type;
        $ProcessType = ProcessType::whereStatus(1)
            ->where(function ($query) use ($userType) {
                $query->where('active_menu_for', 'like', "%$userType%");
            })
            ->orderBy('name')
            ->lists('name', 'id')
            ->all();
        $agendaInfo = Agenda::leftJoin('process_type', 'process_type.id', '=', 'agenda.process_type_id')
            ->leftJoin('board_meeting_process_status', 'board_meeting_process_status.id', '=', 'agenda.status')
            ->where('agenda.id', $agenda_id)
            ->first(['agenda.*', 'process_type.name as process_name',
                'board_meeting_process_status.status_name', 'board_meeting_process_status.id as status_id', 'board_meeting_process_status.panel']);

        $document = BoardMeetingDoc::leftJoin('agenda', 'agenda.id', '=', 'board_meeting_doc.agenda_id')
            ->where('board_meeting_doc.agenda_id', $agenda_id)
            ->get(['board_meeting_doc.*']);

        $appStatus = ['' => 'Select One'] + BoardMeetingProcessStatus::where('is_active', 1)
                ->where('type_id',1)//one mean agenda status
                ->lists('status_name', 'id')->all();

        $boardMeetingInfo = BoardMeting::leftJoin('board_meeting_process_status', 'board_meeting_process_status.id', '=', 'board_meting.status')
            ->where('board_meting.id', $agendaInfo->board_meting_id)->first(['board_meting.*','board_meeting_process_status.status_name','board_meeting_process_status.panel']);

        $status = BoardMeetingProcessStatus::where('type_id', 3)->lists('status_name','id')->all();

        $bm_chairman = Committee::where('board_meeting_id', $agendaInfo->board_meting_id)
            ->where('type','Yes')
            ->first();
        $getChairmensRemarksInAgenda = Agenda::where('id', $agenda_id)->pluck('remarks');
        $countAgendaRemarks = AgendaRemarks::where('agenda_id',$agenda_id)->count();
        $alreadyExistProcess = ProcessListBoardMeting::where('process_list_board_meeting.agenda_id',$agenda_id)->get();

        $chairmanRemarks = true;
        foreach ($alreadyExistProcess as $remarks)
        {
            if($remarks->bm_status_id == 0){ //chairman remarks
                $chairmanRemarks = false;
            }
        }

        Session::put('agenda_id', $agendaId);
        Session::put('board_meeting_id', Encryption::encodeId($agendaInfo->board_meting_id));

        return view('BoardMeting::agenda.agenda-process', compact('status','process_type_id', 'bm_chairman',
            'ProcessType', 'agendaId', 'agendaInfo', 'boardMeetingInfo', 'document', 'appStatus', 'getChairmensRemarksInAgenda',
            'countAgendaRemarks','alreadyExistProcess','chairmanRemarks'));
    }

    public function saveAgendaWiseBoardMeting(request $request)
    {
        $agenda_id = Encryption::decodeId($request->get('agenda_id'));
        $agendaInfo = Agenda::where('id', $agenda_id)->first(['board_meting_id']);
        if($request->get('process_list_ids')){

            foreach($request->get('process_list_ids') as $value){
                $boardMeting = new ProcessListBoardMeting();
                $boardMeting->process_id = $value;
                $boardMeting->agenda_id = $agenda_id;
                $boardMeting->board_meeting_id = $agendaInfo->board_meting_id;
                $boardMeting->is_active = 1;
                $boardMeting->save();
            }
        }else {

            $boardMeting = new ProcessListBoardMeting();
            $boardMeting->process_id = $request->get('process_list_id');
            $boardMeting->agenda_id = $agenda_id;
            $boardMeting->board_meeting_id = $agendaInfo->board_meting_id;
            $boardMeting->is_active = 1;
            $boardMeting->save();
        }
        return response()->json(['responseCode' => 1, 'status' => 'success']);
    }

    //my process
    public function agendaWiseBoardMeting(Request $request, $status = '', $desk = '')
    {
        $process_type_id = session('active_process_list');
        $list = ProcessListBoardMeting::getBoardMeeting($process_type_id, $status, $request, $desk);
        $boardMeetingStatus = BoardMeting::where('id', Encryption::decodeId($request->get('board_meeting_id')))->first(['status']);
        $status = BoardMeetingProcessStatus::where('type_id', 3)->get();
        if (count($list) > 0 && $list[0]->id != null) {
            return Datatables::of($list)
                ->addColumn('action', function ($list) use ($boardMeetingStatus,$status) {
                    $html = '';
                    if(!in_array($boardMeetingStatus->status,[6,10])) { //6 = created 10= completed
                        if ($list->bm_status_id <= 0 || $list->bm_status_id == null) {
                            $html.= '<button style="margin:15px 0px;" type="button" value="'.$list->process_list_board_id.'" class="btn btn-xs btn-info individual_action_save"><i class="fa fa-save"></i> Save</button>&nbsp;';
                        }
                    }
                    $html.= '<a  target="_blank" href="' . url($list->form_url . '/view/' . Encryption::encodeId($list->ref_id)) .'/board-meeting'. '" class="btn btn-xs btn-primary"> <i class="fa fa-folder-open"></i> Open </a>&nbsp;';
                    if(!in_array($boardMeetingStatus->status,[5,10]))  {
                        $html.='<span onclick="deleteItem(' . $list->process_list_board_id . ')" class=" btn btn-danger btn-xs"><i class="fa fa-times"></i></button>';
                    }

                    return $html;
                })
                ->editColumn('json_object', function ($list) {
                    return @getDataFromJson($list->json_object);
                })
                ->addColumn('desk', function ($list) {
                    return $list->desk_id == 0 ? 'Applicant' : $list->desk_name;
                })
                ->editColumn('updated_at', function ($list) use($status,$boardMeetingStatus) {

                    $chairman = 0;
                    $bm_chairman = Committee::where('board_meeting_id', $list->pr_board_meeting_id)
                        ->where('type','Yes')
                        ->first();
                    $html ='';
                    if(!in_array($boardMeetingStatus->status,[6,10])) { //6 = created 10= completed

                        if($list->agendaStatus == 0) {
                            $memberRemarks = CommonFunction::getMemberRemarks($list->process_list_board_id);
                            /* for members */
                            if(empty($memberRemarks)){
                                $html = "  <textarea placeholder='Write your remark here...' class='form-control remark_$list->process_list_board_id' name='remark_$list->process_list_board_id'></textarea>";
                            }else{
                                $html = "  <textarea placeholder='Write your remark here...' class='form-control remark_$list->process_list_board_id' name='remark_$list->process_list_board_id'>$memberRemarks</textarea>";
                            }

                            if ($list->bm_remarks != "") {
                                $html = "  <textarea placeholder='Write your remark here...' class='form-control hidden' name='remark_$list->process_list_board_id' >$list->bm_remarks</textarea>";
                            }
                            if (count($bm_chairman) > 0) {   /*  for chairman */
                                if (Auth::user()->user_email == $bm_chairman->user_email) {
                                    if ($list->bm_status_id <= 0 || $list->bm_status_id == null) {

                                        $html = "   <textarea placeholder='Write your remark here...' class='form-control input-sm remark_$list->process_list_board_id' name='remark_$list->process_list_board_id'>$list->bm_remarks</textarea>";
                                        $html .= "</br></br>$list->bm_status<select class='form-control input-sm status_for_$list->process_list_board_id' name='status_for_$list->process_list_board_id'>";
                                        $html .= "<option value='0'>Select Status</option>";
                                        foreach ($status as $value) {
                                            if ($list->bm_status_id == $value->id) {
                                                $selected = 'selected';
                                            } else {
                                                $selected = '';
                                            }
                                            $html .= "<option value='$value->id' $selected>$value->status_name</option>";
                                        }
                                        $html .= "</select></br>";
                                    }

                                }
                            }

                        }
                    }
                    $html .= ' <button style="margin:10px 0px;" type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#myModal" onclick="viewRemarks(' . $list->process_list_board_id . ')">All Remarks ('. $list->totalRemarks .')</button>';

                    return $html;
                })->editColumn('desk', function ($list) {
                    $html = "";
                    //if($list->bm_remarks == "" && $list->agendaStatus == 0) {
                    if ($list->bm_status_id <= 0 || $list->bm_status_id == null) {
                        $html = "<input type='checkbox' value='$list->process_list_board_id' class='checkbox' name='checkbox[$list->process_list_board_id]'>";
                    }
                    return $html;
                })
                ->removeColumn('id', 'ref_id', 'process_type_id', 'updated_by', 'closed_by', 'created_by', 'updated_by', 'desk_id', 'status_id', 'locked_by', 'ref_fields')
                ->make(true);
        } else {
            $list = ProcessType::where('id', 1)->get();//just demo data
            return Datatables::of($list)
                ->addColumn('action', function ($list) use ($status,$boardMeetingStatus) {
                    $html = '';
                    if(!in_array($boardMeetingStatus->status,[5,10]))  {
                        $html = ' <span class="btn btn-xs btn-primary processList " onclick="addMore()"> Add More <i class="fa fa-arrow-right"></i></span>';
                    }
                    return $html;
                })
                ->editColumn('json_object', function ($list) {
                    return '';
                })
                ->addColumn('desk', function ($list) {
                    return '';
                })
                ->editColumn('updated_at', function ($list) {
                    return '';
                })
                ->editColumn('tracking_no', function ($list) {
                    return '';
                })
                ->editColumn('process_name', function ($list) {
                    return '';
                })
                ->editColumn('status_name', function ($list) {
                    return '';
                })
                ->setRowAttr([
                    'color' => function($list) {
                        return 'rad';
                    },
                ])
                ->make(true);
        }
    }

    public function updateRemarks(Request $request){

        if (!empty($request->get('checkbox'))){
            foreach($request->get('checkbox') as $key=>$value){
                $chairman = 0;
                $bm_process_list = ProcessListBoardMeting::where('id', $key)->first();
                $bm_chairman = Committee::where('board_meeting_id', $bm_process_list->board_meeting_id)
                    ->where('type','Yes')
                    ->first();
                BoardMeting::where('id',$bm_process_list->board_meeting_id)->update(['sequence_no'=>5]);

                if ($request->get('bm_status_id') != ""){
                    $bm_status = $request->get('bm_status_id');
                }else{
                    $bm_status =  $request->get('status_for_'.$key);
                }

                if ($request->get('remarks') != ""){
                    $remarks = $request->get('remarks');
                }else{
                    $remarks = $request->get('remark_'.$key);
                }
                $getChairmensRemarks = ProcessListBoardMeting::where('id', $key)->pluck('bm_remarks');

                if (count($bm_chairman) > 0){
                    /* if auth user is chairmen of Board Meeting then remarks will be save on
                    * ProcessListBoardMeting table -> bm_remarks field
                    */
                    if (Auth::user()->user_email == $bm_chairman->user_email){ // on for meeting chairman
                        $chairman = 1;
                        ProcessListBoardMeting::where('id', $key)->update([
                            'bm_status_id' => $bm_status,
                            'bm_remarks' => $remarks,
                        ]);
                    }
                }

                /* if auth user is not chairmen of Board Meeting then remarks will be save on
                * process_list_bm_remarks table -> remarks field
                */
                if($getChairmensRemarks == "" || (Auth::user()->user_email == $bm_chairman->user_email)){
                    ProcessListBMRemarks::where('user_id', Auth::user()->id)->where('bm_process_id', $key)->delete();
                    $processRemark = new ProcessListBMRemarks();
                    $processRemark->bm_process_id = $key;
                    $processRemark->user_id = Auth::user()->id;
                    $processRemark->chairman = $chairman;
                    $processRemark->remarks = $remarks;
                    $processRemark->save();
                }
            }
            Session::flash('success', 'Your status has been updated!!');
        }
        else{
            Session::flash('error', 'Please select a process from process list!');
        }

        return Redirect::back();
    }

    public function getAgendaProcessRemarks(Request $request){
        $bm_process_id = $request->get('bm_process_id');
        $remarks = ProcessListBMRemarks::
        leftJoin('users', 'users.id', '=', 'process_list_bm_remarks.user_id')
            ->where('bm_process_id',$bm_process_id)
            ->get(['process_list_bm_remarks.chairman','users.user_full_name','users.user_email','users.user_pic','process_list_bm_remarks.remarks']);

        return response()->json(['responseCode' => 1, 'status' => 'success', 'data' => $remarks]);
    }

    public function getAgendaRemarks(Request $request){
        $agendaId = Encryption::decodeId($request->get('agendaId'));
        $remarks = AgendaRemarks::leftJoin('users', 'users.id', '=', 'agenda_list_remarks.user_id')
            ->where('agenda_id',$agendaId)
            ->get(['users.user_full_name','users.user_email','agenda_list_remarks.chairman', 'users.user_pic','agenda_list_remarks.remarks']);

        return response()->json(['responseCode' => 1, 'status' => 'success', 'data' => $remarks]);
    }

    public function agendaWiseProcessList(Request $request, $status = '', $desk = '')
    {
        $process_type_id = session('active_process_list');
        $status == '-1000' ? '' : $status;
        $list = ProcessListBoardMeting::getBoardMeetingList($process_type_id, $status, $request, $desk);
        $agenda_id = Encryption::decodeId($request->get('agenda_id'));
        $agenda = CommonFunction::alreadyAddedAgenda($agenda_id);
        return Datatables::of($list)
            ->addColumn('action', function ($list) use ($status, $request,$agenda,$agenda_id) {
                $html = '<a target="_blank" href="' . url($list->form_url . '/view/' . Encryption::encodeId($list->ref_id)).'/board-meeting'. '" class="btn btn-xs btn-primary"> <i class="fa fa-folder-open"></i> Open</a>  &nbsp;';

                $boardMeetingStatus = BoardMeting::where('id', Encryption::decodeId($request->get('board_meeting_id')))->first(['status']);

                if(!in_array($boardMeetingStatus->status,[5,10]))  { //5=fixed
//                    $alreadyAdd = CommonFunction::alreadyAdded($list->id, $agenda_id);
//                    if ($alreadyAdd == 1) {
//                        $html .= '<button class="btn btn-warning btn-xs">Already Added</button>';
//                    } else {
//                        $html .= '<button value="' . $list->id . '" class="add_to_board btn btn-warning btn-xs"> Add to Board Meeting </button>';
//                    }
                }
                $html .= '<button value="' . $list->id . '" class="add_to_board btn btn-warning btn-xs"> Add to Board Meeting </button>';
                return $html;
            })
            ->editColumn('json_object', function ($list) {
                return @getDataFromJson($list->json_object);
            })
            ->addColumn('desk', function ($list) {
                return $list->desk_id == 0 ? 'Applicant' : $list->desk_name;
            })->addColumn('serial', function ($list) use ($agenda,$agenda_id) {

                // if ($agenda == 1) {
//                $alreadyAdd = CommonFunction::alreadyAdded($list->id, $agenda_id);
//                if ($alreadyAdd == 1) {
//                    $html = "<input type='checkbox' disabled checked value='$list->id' class='checkbox_process_disable' name='checkbox[$list->id]'>";
//                } else {
//                    $html = "<input type='checkbox' value='$list->id' class='checkbox_process' name='checkbox[$list->id]'>";
//                }
                // } else {
                //  $html = "<input type='checkbox' value='$list->id' class='checkbox_process' name='checkbox[$list->id]'>";
                // }
                $html = "<input type='checkbox' value='$list->id' class='checkbox_process' name='checkbox[$list->id]'>";
                return $html;
            })
            ->editColumn('updated_at', function ($list) {
                return CommonFunction::updatedOn($list->updated_at);
            })
            ->removeColumn('id', 'ref_id', 'process_type_id', 'closed_by', 'created_by', 'updated_by', 'desk_id', 'status_id', 'locked_by', 'ref_fields')
            ->make(true);
    }

    public function updateProcess(request $request)
    {
        $transferType = $request->get('meeting_transfer');
        $agenda_id = Encryption::decodeId($request->get('agenda_id'));
        $board_meeting_id = Encryption::decodeId($request->get('board_meeting_id'));
        if(isset($transferType) && $transferType == 'no'){
            $this->TransferProcessList($agenda_id, $board_meeting_id);
            return \redirect('board-meting/agenda/list/'.$request->get('board_meeting_id'));
        }
        try {

            $data = Agenda::where('board_meting_id', $board_meeting_id)
                ->where('id', $agenda_id)->first();

            if ($request->get('status_id') == 3) {
//                $NextBoardMeeting = BoardMeting::where('id', '>', $data->board_meting_id)->min('id');
                $NextBoardMeeting = BoardMeting::where('meting_date', '>', date("Y-m-d"))
                    ->where('is_active', '=', 1)
                    ->orderBy('meting_date')
                    ->first();
                if ($NextBoardMeeting == null) {
                    Session::flash('error', "Roll-over is not possible, Please create a upcoming board meeting");
                    return Redirect::back();
                }
                $NextBoardMeeting = $NextBoardMeeting->id;
                $status_id = $data->status;

            } else {
                $NextBoardMeeting = $data->board_meting_id; //Current Board Meeting id
                $status_id = $request->get('status_id');
            }

            Agenda::where('id', $agenda_id)
                ->update([
                    'status' => $status_id,
//                    'remarks' => $request->get('remarks'),
                    'board_meting_id' => $NextBoardMeeting,
                    'previous_board_meeting_id' => $board_meeting_id,
                ]);
            $bm_chairman = Committee::where('board_meeting_id', $board_meeting_id)
                ->where('type','Yes')
                ->first();
            $getChairmensRemarksInAgenda = Agenda::where('id', $agenda_id)->pluck('remarks');

//            dd($agenda_id, $getChairmensRemarksInAgenda, $bm_chairman);

            if (count($bm_chairman) > 0){
                /* if auth user is chairmen of Board Meeting then remarks will be save on
                * ProcessListBoardMeting table -> bm_remarks field
                */
                if (Auth::user()->user_email == $bm_chairman->user_email){ // on for meeting chairman
                    $chairman = 1;
                    Agenda::where('id', $agenda_id)->update([
                        'remarks' => $request->get('remarks'),
                    ]);
                }
            }

            /* if auth user is not chairmen of Board Meeting then remarks will be save on
                * process_list_bm_remarks table -> remarks field
                */
//            dd($getChairmensRemarksInAgenda);
//            dd($agenda_id);
            if($getChairmensRemarksInAgenda == null || (Auth::user()->user_email == $bm_chairman->user_email)){

                $agendaRemarks = new AgendaRemarks();
                $agendaRemarks->agenda_id = $agenda_id;
                $agendaRemarks->user_id = Auth::user()->id;
                $agendaRemarks->remarks = $request->get('remarks');
                $agendaRemarks->save();
            }

            Session::flash('success', 'Your status has been updated!!');
            return Redirect::back()->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [RC-1060]');
            return redirect()->back()->withInput();
        }
    }

    protected function TransferProcessList($agenda_id, $board_meeting_id){

        DB::beginTransaction();
        $datas = ProcessListBoardMeting::leftJoin('process_list_bm_remarks', 'process_list_board_meeting.id', '=', 'process_list_bm_remarks.bm_process_id')
            ->where('agenda_id',$agenda_id)->where('board_meeting_id',$board_meeting_id)->get(['process_list_board_meeting.id']);

        foreach ($datas as $row){
            ProcessListBMRemarks::where('bm_process_id',$row->id)->delete();
        }
        ProcessListBoardMeting::where('agenda_id',$agenda_id)->where('board_meeting_id',$board_meeting_id)->delete();
        AgendaRemarks::where('agenda_id',$agenda_id)->delete();
        Agenda::where('id',$agenda_id)->delete();
        DB::commit();


    }

    public function deleteItem(Request $request)
    {
        $id = $request->get('process_list_board_id');
        ProcessListBoardMeting::where('id', $id)->delete();
        return response()->json(['responseCode' => 1, 'status' => 'success']);
    }

    public function getRollOverDate(Request $request)
    {
        $board_meeting_id = Encryption::decodeId($request->get('board_meeting_id'));
        $upComing = BoardMeting::where('meting_date', '>', date("Y-m-d"))
            ->where('is_active', '=', 1)
            ->orderBy('meting_date')
            ->first();
//        $NextBoardMeeting = BoardMeting::where('id', $upcomming)->min('meting_date');
        if($upComing != null){
            $NextBoardMeetingDate ="<label class='alert alert-success' style='font-weight: bold'>It will be transfer to the meeting date: ".date("d-M-Y", strtotime($upComing->meting_date))."  &nbsp;<label><input type='radio' name='meeting_transfer' value='yes'>Yes</label>&nbsp;<label><input name='meeting_transfer' type='radio' checked value='no'> No and forward to process list </label> </div>";
            $status = true;
        } else {
            $NextBoardMeetingDate = "<div class='alert alert-danger text-bold'style='font-weight: bold'>Roll-over is not possible, Please create a upcoming board meeting or forward to process list <label><input type='radio' name='meeting_transfer' checked value='no'>Yes</label> </div>";
            $status = false;
        }
        return response()->json(['responseCode' => 1, 'data' => $NextBoardMeetingDate, 'status' => $status]);
    }

    public function deleteBoardMeetingProcess(Request $request)
    {
        if($request->get('process_list_board_meeting_ids')){
            foreach($request->get('process_list_board_meeting_ids') as $value){
                ProcessListBoardMeting::where('id',$value)->delete();
            }
        }
        return response()->json(['responseCode' => 1, 'status' => 'success']);
    }

    protected function saveIndividualAction(Request $request)
    {
        try{
            $process_list_id = $request->get('process_list_id');
            $board_meeting_id = Encryption::decodeId($request->get('board_meeting_id'));
            $chairman = 0;
//        $bm_process_list = ProcessListBoardMeting::where('id', $process_list_id)->first();
            $bm_chairman = Committee::where('board_meeting_id', $board_meeting_id)
                ->where('type','Yes')
                ->first();

            BoardMeting::where('id',$board_meeting_id)->update(['sequence_no'=>5]);

            $remarks = $request->get('remarks');
            if (count($bm_chairman) > 0){
                if (Auth::user()->user_email == $bm_chairman->user_email){ // on for meeting chairman
                    $chairman = 1;
                    $bm_status = $request->get('bm_status_id');
                    ProcessListBoardMeting::where('id', $process_list_id)->update([
                        'bm_status_id' => $bm_status,
                        'bm_remarks' => $remarks,
                    ]);
                }
            }

            ProcessListBMRemarks::where('user_id', CommonFunction::getUserId())->where('bm_process_id', $process_list_id)->delete();
            $processRemark = new ProcessListBMRemarks();
            $processRemark->bm_process_id = $request->get('process_list_id');
            $processRemark->user_id = CommonFunction::getUserId();
            $processRemark->chairman = $chairman;
            $processRemark->remarks = $remarks;
            $processRemark->save();

            return response()->json(['responseCode' => 1, 'status' => 'success']);
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }
    }

    public function pdfview()
    {
        $areaInfo = DB::table('area_info')->get();

        $html = view("BoardMeting::bangla", compact('areaInfo'));

        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults(); // extendable default Configs
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults(); // extendable default Fonts
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new mPDF([
            'tempDir'       => storage_path(),
            'fontDir' => array_merge($fontDirs, [
                public_path('fonts'), // to find like /public/fonts/SolaimanLipi.ttf
            ]),
            'fontdata' => $fontData + [
                    'solaimanlipi' => [
                        'R' => "SolaimanLipi.ttf",
                        'useOTL' => 0xFF,
                    ],
                    'nikosh' => [
                        'R' => "Nikosh.ttf",
                        'useOTL' => 0xFF,
                    ],
                    //... you can add more custom font here
                ],
            'default_font' => 'solaimanlipi', // default font is not mandatory, you can use in css font
        ]);

        $mpdf->WriteHTML($html);
        $mpdf->Output('bangla_text.pdf','I'); exit;
    }


}
