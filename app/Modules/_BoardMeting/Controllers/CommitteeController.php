<?php namespace App\Modules\BoardMeting\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Apps\Models\EmailQueue;
use App\Modules\BoardMeting\Models\Agenda;
use App\Modules\BoardMeting\Models\BoardMeting;
use App\Modules\BoardMeting\Models\Committee;
use App\Modules\BoardMeting\Models\ProcessListBoardMeting;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\Settings\Models\Notice;
use App\Modules\Users\Models\Users;
use App\Modules\Users\Models\UserTypes;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Mpdf\Mpdf;
use yajra\Datatables\Datatables;
use Validator;

class CommitteeController extends Controller
{

    public function index($board_meeting_id)
    {
        $usersType = UserTypes::where('status','active')->where('id', '4x404')->lists('type_name','id')->all();
        $board_meeting_data = BoardMeting::find(Encryption::decodeId($board_meeting_id));
        $users = Users::orderBy('user_full_name', 'ASC')->lists('user_full_name', 'id')->all();

        $user_list = Users::where('user_type', '=', '4x404')
            ->Where('user_status', '=', 'active')
            ->Where('id', '!=', Auth::user()->id)
            ->lists('user_full_name', 'id')->all();
        return view('BoardMeting::committee.create-committee', compact('user_list','board_meeting_id', 'users', 'board_meeting_data','usersType'));
    }

    public function memberEdit($member_id)
    {
        $committee_data = Committee::find(Encryption::decodeId($member_id));
        $board_meeting_data = BoardMeting::find($committee_data->board_meeting_id);

        return view('BoardMeting::committee.edit-committee', compact('member_id', 'committee_data', 'board_meeting_data'));
    }

    public function getCommitteeList(Request $request)
    {
        $userType = $request->get('user_type');
        $result = Users::where('user_type', '=', $userType)
            ->Where('user_status', '=', 'active')
            ->Where('id', '!=', Auth::user()->id)
            ->get(['user_full_name', 'id']);
        echo json_encode($result);
    }

    public function storeCommittee(Request $request)
    {

        $this->validate($request, [
            'board_meeting_id' => 'required',
            'user_email' => 'required',
            'user_name' => 'required'
        ]);

        $board_meeting_id = Encryption::decodeId($request->get('board_meeting_id'));
        $alreadyExist = Committee::where('board_meeting_id', $board_meeting_id)
            ->where('user_email', $request->get('user_email'))
            ->count();

        if ($alreadyExist <= 0) {
            if ($request->get('type') == "Yes") {

                $alreadyExistCo = Committee::where('board_meeting_id', $board_meeting_id)
                    ->where('type', $request->get('type'))
                    ->count();
                if ($alreadyExistCo >= 1) {
                    Session::flash('error', 'chairperson already added for this meeting!');
                    return redirect()->back();
                }
            }
            Committee::create([
                'board_meeting_id' => $board_meeting_id,
                'user_email' => $request->get('user_email'),
                'user_name' => $request->get('user_name'),
                'user_mobile' => $request->get('user_mobile'),
                'organization' => $request->get('organization'),
                'user_phone' => $request->get('user_phone'),
                //'type' => $request->get('type'),
                'designation' => $request->get('designation')
            ]);
           // $sequence_no = BoardMeting::where('id',$board_meeting_id)->first(['sequence_no'=>'2']);


            /////// user store

            $user_info = Users::where("user_email", $request->get('user_email'))->count();

            if ($user_info <= 0) { //Already exist checking
                $token_no = hash('SHA256', "-" . $request->get('user_email') . "-");
                $encrypted_token = Encryption::encodeId($token_no);

                if (Auth::user()->user_type == '1x101') {     //System admin
                    $desk_id = '';
                    //$desk_id = $request->get('desk_id');
                    $user_type = $request->get('user_type');
                } else {
                    $desk_id = Auth::user()->desk_id;
                    $user_type = Auth::user()->user_type;
                }

                $user_sub_type = 0;


                $data = array(
                    'user_full_name' => $request->get('user_name'),

                    'user_phone' => $request->get('user_mobile'),
                    'user_email' => $request->get('user_email'),
                    'user_hash' => $encrypted_token,
                    'user_sub_type' => $user_sub_type,
                    'user_type' => $user_type,
                    //'eco_zone_id' => $request->get('eco_zone_id'),
                    'desk_id' => $desk_id,
                    'user_status' => 'active',
                    'is_approved' => 1,
                    'user_agreement' => 0,
                    'first_login' => 0,
                    'user_verification' => 'no',
                    'user_hash_expire_time' => new Carbon('+6 hours')
                );
                Users::create($data);

                $email = $request->get('user_email');
                $user_phone = $request->get('user_mobile');
                $verify_link = 'users/verify-created-user/' . ($encrypted_token);

                $body_msg = "OCPL thanks you for requesting to open an account in our system.<br/>
                              Click the following link to confirm your e-mail account.
                            <br/> <a href='" . url($verify_link) . "'>Verify the e-mail address you have provided earlier</a>";

                $params = array([
                    'emailYes' => '1',
                    'emailTemplate' => 'Users::message',
                    'emailBody' => $body_msg,
                    'emailSubject' => 'Verify your email address',
                    'emailHeader' => 'Verify your email address',
                    'emailAdd' => $email,
                    'mobileNo' => $user_phone,
                    'smsYes' => '0',
                    'smsBody' => '',
                ]);
                CommonFunction::sendMessageFromSystem($params);
            }

            Session::flash('success', 'Committee assign successfully!');
            return redirect()->back();
            //////////// end user store

        } else {

            Session::flash('error', 'This member already added for this meeting!');
            return redirect()->back()->withInput();

        }

    }

    public function updateMember(Request $request)
    {

        $this->validate($request, [
            'board_meeting_id' => 'required',
            'member_id' => 'required',
            'user_name' => 'required'
        ]);

        $board_meeting_id = Encryption::decodeId($request->get('board_meeting_id'));
        $member_id = Encryption::decodeId($request->get('member_id'));

        Committee::where('id',$member_id)->where('board_meeting_id',$board_meeting_id)
            ->update([
                'user_name'=>$request->get('user_name'),
                'user_mobile'=>$request->get('user_mobile'),
                'organization'=>$request->get('organization'),
                'user_phone'=>$request->get('user_phone'),
                'designation'=>$request->get('designation'),
            ]);
//        $committee->user_name = $request->get('user_name');
//        $committee->user_mobile = $request->get('user_mobile');
//        $committee->organization = $request->get('organization');
//        $committee->user_phone = $request->get('user_phone');
//        $committee->designation = $request->get('designation');
//        $committee->update();
        Session::flash('success', 'Committee update successfully!');
        return redirect()->back();


    }

    public function getData(Request $request)
    {
        $mode = ACL::getAccsessRight('BoardMeting', '-V-');
        $board_meting_id = Encryption::decodeId($request->get('board_meting_id'));
        $list = Committee::where('board_meeting_id', $board_meting_id)
            ->skip(1)->take(200)->get();
        $boardMeetingStatus = BoardMeting::where('id', $board_meting_id)->first(['status']);
        return Datatables::of($list)
            ->addColumn('action', function ($list) use ($mode, $boardMeetingStatus) {
                if ($mode) {
                    $id = $list->id;
                    $userType = CommonFunction::getUserType();
                    $button = '';
                    if (!in_array($boardMeetingStatus->status, [5, 10]) && $userType =='13x303') { //5= fixed status
                        $button = ' <a href="' . url('board-meting/committee/member-edit/' . Encryption::encodeId($list->id)) . '" class="btn btn-xs btn-success open" ><i class="fa fa-edit"></i> Edit </a> ';
                        $button .= '<button onclick="deleteMember(' . $id . ')" class=" btn btn-danger btn-xs"><i class="fa fa-times"></i></button> &nbsp;';
                    }

                    return $button;
                } else {
                    return '';
                }
            })
            ->removeColumn('id')
            ->make(true);
    }

    public function deleteMember(Request $request)
    {
        $id = $request->get('member_id');
        Committee::where('id', $id)->delete();;
        return response()->json(['responseCode' => 1, 'status' => 'success']);
    }

    public function getUserInfo(Request $request)
    {
        $id = $request->get('member_id');
        $userInfo = User::find($id);
        return response()->json(['responseCode' => 1, 'status' => 'success', 'data' => $userInfo]);
    }


    public function view($board_meting_id)
    {
        return view('BoardMeting::agenda.agenda-list')->with('board_meting_id', $board_meting_id);

    }

    public function checkChairpersonType(Request $request)
    {
        $chairperson_type = $request->get('chairperson_type');
        $board_meeting_id = Encryption::decodeId(($request->get('board_meeting_id')));
        $alreadyExist = 0;
        if ($chairperson_type == 'Yes') {
            $alreadyExist = Committee::where('board_meeting_id', $board_meeting_id)
                ->where('type', $chairperson_type)
                ->count();
        }
        return response()->json(['responseCode' => 1, 'status' => $alreadyExist]);
    }

    public function chairmanChoice($board_meeting_id)
    {
        return view('BoardMeting::committee.chairman-choice')->with('board_meeting_id', $board_meeting_id);
    }

    public function getDataForChairmanChoice(Request $request)
    {
        $mode = ACL::getAccsessRight('BoardMeting', '-A-');
        $board_meting_id = Encryption::decodeId($request->get('board_meting_id'));
        $list = Committee::where('board_meeting_id', $board_meting_id)->skip(1)->take(200)->get();
        $boardMeetingStatus = BoardMeting::where('id', $board_meting_id)->first(['status']);
        return Datatables::of($list)
            ->addColumn('action', function ($list) use ($mode, $boardMeetingStatus) {
                if ($mode) {
                    $button = '';
                    if (!in_array($boardMeetingStatus->status, [5, 10])) { //5= fixed status 10=approved
                        if ($list->type == 'Yes') {
                            $checked = 'checked';
                        } else {
                            $checked = '';
                        }
                        $button = ' <label  style="cursor: pointer"><input style="cursor: pointer" type="checkbox"  ' .$checked . '  name="cb1" value="' . Encryption::encodeId($list->id) . '" class="chairman_selected" /> Chairperson</label> &nbsp;';
                    }
                    return $button;
                } else {
                    return '';
                }
            })
            ->removeColumn('id')
            ->make(true);
    }

    public function saveChairpersonChoice(Request $request)
    {
        if (!ACL::getAccsessRight('BoardMeting', '-A-'))
            abort(401, 'No access right!');

        try {
            $committeeId = Encryption::decodeId($request->get('committee_id'));
            $boardMeetingId = Encryption::decodeId($request->get('board_meeting_id'));

            Committee::where('board_meeting_id', $boardMeetingId)
                ->where('type', 'Yes')
                ->update(['type' => 'No']);

            Committee::where('id', $committeeId)->update([
                'type' => 'Yes'
            ]);
            return response()->json(['responseCode' => 1, 'status' => 'Chairperson choice successfully']);

        } catch (Exception $e) {
            return response()->json(['responseCode' => 0, 'status' => 'Something Was wrong!!']);
        }
    }

    public function noticeGenerate($board_meeting_id)
    {

        $meeting_id = Encryption::decodeId($board_meeting_id);
        $meetingInfo = BoardMeting::where('id', $meeting_id)->first();
        $committeeInfo = Committee::where('board_meeting_id', $meeting_id)->where('user_email','!=', Auth::user()->user_email)
            ->get([
                'user_email',
                'user_name',
                'designation',
                'organization'
            ]);

        $contBoardMeetingChairperson = Committee::where('board_meeting_id', $meeting_id)->where('type','Yes')->count();

        if($contBoardMeetingChairperson == 0){
            Session::flash('error', 'Please select a chairperson!');
            return redirect()->back();
        }

        $contents = view('BoardMeting::notice.notice-html', compact('meetingInfo','committeeInfo'))->render();
//        return $contents;
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
        $mpdf->SetTitle("Board Meeting Automation System");
        $mpdf->SetSubject("Subject");
        $mpdf->SetAuthor("Business Automation Limited");
        $mpdf->autoScriptToLang = true;
        $mpdf->baseScript = 1;
        $mpdf->autoVietnamese = true;
        $mpdf->autoArabic = true;

        $mpdf->autoLangToFont = true;
        $mpdf->setFooter("Page {PAGENO} of {nb}");
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
        $certificateName = uniqid("board_meeting_" . $meetingInfo->meting_number . "_", true);
        $pdfFilePath = $directoryName . "/" . $certificateName . '.pdf';
        $mpdf->Output($pdfFilePath, 'F'); // Saving pdf *** F for Save only, I for view only.

//        $pdfFilePath = 'uploads/board_meeting_werwer_5a66ca8e467978.93350888.pdf';
//        dd($meeting_id);
        BoardMeting::where('id',$meeting_id)->update([
           'meting_notice' =>$pdfFilePath,
           'sequence_no' => 4
        ]);

        return redirect('/board-meting/committee/notice/view/' . $board_meeting_id);
        //$mpdf->Output('test' . '.pdf', 'I');   // Saving pdf "F" for Save only, "I" for view only.

    }

    public function notice($board_meeting_id)
    {
        $board_id = Encryption::decodeId($board_meeting_id);
        $board_meeting_data = BoardMeting::where('id',$board_id)->first();
        return view('BoardMeting::notice.notice',compact('board_meeting_data','board_meeting_id'));

    }

    public function noticePublish($board_meeting_id)
    {
        if (!ACL::getAccsessRight('BoardMeting', '-A-'))
            abort(401, 'No access right!');
        try{
        $board_id = Encryption::decodeId($board_meeting_id);
        $notice = BoardMeting::where('id',$board_id)->first(['meting_notice','notice_publish','meting_number','meting_notice','meting_subject','meting_date','location']);
//        dd($notice->meting_date);
        $meetingDate = date("d M Y", strtotime($notice->meting_date));
        $meetingTime = date("h:i a", strtotime($notice->meting_date));
        $meetingLocation = $notice->location;
        $meetingSubject = $notice->meting_subject;
        DB::beginTransaction();

        BoardMeting::where('id',$board_id)->update(['notice_publish'=>1,'sequence_no'=> 4]);
        Notice::create([
            'heading' => $notice->meting_number,
            'details' => URL::to('/').'/'.$notice->meting_notice,
            'status' => 'public',
            'is_active' => '1',
            'prefix' => 'board-meeting',
        ]);

        $committee = Committee::where('board_meeting_id',$board_id)->get(['user_email']);
        foreach ($committee as $committeeEmail){
            $userInfo = Users::where('user_email',$committeeEmail->user_email)->first();

            $body_msg = '<span style="color:black;text-align:justify;">';
            $body_msg .= $userInfo->user_full_name.'<br>
            '.$userInfo->designation.'<br><br><b>Subject: '.$meetingSubject.'</b><br><br>We would like to request you, please attend the following meeting schedule<br><br>Date:'.$meetingDate.'<br>Time:'.$meetingTime.'<br>Location:'.$meetingLocation.'<br><br>We look forward to seeing you at our meeting.';
            $body_msg .= '</span>';
            $body_msg .= '<br/><br/>Thank You<br/>';
//            $body_msg .= '<b>'.env('PROJECT_NAME').'</b>';

            $header = "Meeting Information for Board Meeting";
            $param = $body_msg;
            $email_content = view("Users::message", compact('header', 'param'))->render();
            $emailQueue = new EmailQueue();
            $emailQueue->service_id = 0; // NO SERVICE ID
            $emailQueue->app_id = 0;
            $emailQueue->email_content = $email_content;
            $emailQueue->email_to =$committeeEmail->user_email;
            $emailQueue->sms_to = '';
            $emailQueue->email_subject = $header;
            $emailQueue->attachment =  URL::to('/').'/'.$notice->meting_notice;
            $emailQueue->save();

        }
            DB::commit();
            Session::flash('success', 'Board meeting notice publish successfully!');
            return redirect('/board-meting/lists/');

        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [RC-1060]');
            return redirect()->back();
        }
    }

    public function noticeRePublish($board_meeting_id, $second_time='')
    {
        if (!ACL::getAccsessRight('BoardMeting', '-A-'))
            abort(401, 'No access right!');

        try{
            $board_id = Encryption::decodeId($board_meeting_id);
            $notice = BoardMeting::where('id',$board_id)->first(['meting_notice','notice_publish','meting_number','meting_notice','meting_subject','meting_date','location']);
            $meetingDate = date("d M Y", strtotime($notice->meting_date));
            $meetingTime = date("h:i a", strtotime($notice->meting_date));
            $meetingLocation = $notice->location;
            $meetingSubject = $notice->meting_subject;

            DB::beginTransaction();
            BoardMeting::where('id',$board_id)->update(['notice_publish'=>1,'sequence_no'=> 4]);
            Notice::create([
                'heading' => $notice->meting_number,
                'details' => URL::to('/').'/'.$notice->meting_notice,
                'status' => 'public',
                'is_active' => '1',
                'prefix' => 'board-meeting',
            ]);
            DB::commit();
            Session::flash('success', 'Board meeting notice publish successfully!');
            return redirect('/board-meting/lists/');

        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [RC-1060]');
            return redirect()->back();
        }
    }
}
