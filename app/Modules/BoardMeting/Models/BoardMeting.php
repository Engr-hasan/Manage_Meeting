<?php

namespace App\Modules\BoardMeting\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Auth;

class BoardMeting extends Model {

    protected $table = 'board_meting';
    protected $fillable = array(
        'id',
        'meting_number',
        'meting_subject',
        'reference_no',
        'sequence_no',
        'meting_date',
        'agenda_ending_date',
        'meeting_agenda_path',
        'meeting_minutes_path',
        'location',
        'org_name',
        'org_address',
        'notice_details',
        'meting_notice',
        'is_active',
        'status',
        'is_archive',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    );

    public static function getList(){

        $userType = CommonFunction::getUserType();

        if ($userType != '13x303') {
            $boardMeeting = BoardMeting::leftJoin('agenda', 'board_meting.id', '=', 'agenda.board_meting_id')
                ->leftJoin('board_meeting_process_status as bms', 'board_meting.status', '=', 'bms.id')
                ->leftJoin('boared_meeting_committee as bmc', 'board_meting.id', '=', 'bmc.board_meeting_id')
                ->where('board_meting.is_active', 1)
                ->whereNotIn('board_meting.status', [10, 11])//10 = complete status 11=
                ->where('bmc.user_email', Auth::user()->user_email) //access for the users
                ->groupBy('board_meting.id')
                ->orderBy('board_meting.id', 'DESC')
                ->get([DB::raw('GROUP_CONCAT(agenda.name, ",", agenda.id , ",", agenda.created_at , ",", agenda.status , ",", agenda.previous_board_meeting_id separator "##") AS agenda_info'),
                    'board_meting.id', 'board_meting.meting_number', 'board_meting.meting_date',
                    'board_meting.location as area_nm', 'board_meting.is_active as status', 'board_meting.created_at','board_meting.status as board_meeting_status','board_meting.meeting_agenda_path',
                    'bms.status_name', 'bms.panel', 'bmc.user_email']);
        }else{ // for board meeting admin
            $boardMeeting = BoardMeting::leftJoin('agenda', 'board_meting.id', '=', 'agenda.board_meting_id')
                ->leftJoin('board_meeting_process_status as bms', 'board_meting.status', '=', 'bms.id')
                ->where('board_meting.is_active', 1)
                ->whereNotIn('board_meting.status', [10, 11])//10 = complete status 11=
                ->groupBy('board_meting.id')
                ->orderBy('board_meting.id', 'DESC')
                ->get([DB::raw('GROUP_CONCAT(agenda.name, ",", agenda.id , ",", agenda.created_at , ",", agenda.status , ",", agenda.previous_board_meeting_id separator "##") AS agenda_info'),
                    'board_meting.id', 'board_meting.meting_number', 'board_meting.meting_date',
                    'board_meting.location as area_nm', 'board_meting.is_active as status', 'board_meting.created_at','board_meting.status as board_meeting_status','board_meting.meeting_agenda_path',
                    'bms.status_name', 'bms.panel']);
        }
        return $boardMeeting;
    }
    public static function getCompleteList(){
//        $Committee = Committee::all();
//        $CommitteeUserEmail= [];
//        foreach($Committee as $data){
//            $CommitteeUserEmail[] = $data->user_email;
//        }
        $boardMeetingComplete = BoardMeting::leftJoin('agenda', 'board_meting.id', '=', 'agenda.board_meting_id')
            ->leftJoin('board_meeting_process_status as bms', 'board_meting.status', '=', 'bms.id')
            ->leftJoin('boared_meeting_committee as bmc', 'board_meting.id', '=', 'bmc.board_meeting_id')
            ->where('board_meting.is_active',1)
            ->whereIn('board_meting.status',[10,11]) //10 = complete
            ->where('bmc.user_email', Auth::user()->user_email) //access for the users
            ->groupBy('board_meting.id')
            ->orderBy('board_meting.id','DESC')
            ->get([DB::raw('GROUP_CONCAT(agenda.name, ",", agenda.id , ",", agenda.created_at , ",", agenda.status , ",", agenda.previous_board_meeting_id separator "##") AS agenda_info'),
                'board_meting.id','board_meting.meeting_minutes_path','board_meting.meeting_agenda_path','board_meting.status as board_meeting_status','board_meting.meting_number','board_meting.meting_date',
                'board_meting.location as area_nm','board_meting.is_active as status',
                'board_meting.created_at','bms.status_name','bms.panel','bmc.user_email']);

        return $boardMeetingComplete;
    }



    public static function boot() {
        parent::boot();
        // Before update
        static::creating(function($post) {
            if (Auth::guest()) {
                $post->created_by = 0;
                $post->updated_by = 0;
            } else {
                $post->created_by = Auth::user()->id;
                $post->updated_by = Auth::user()->id;
            }
        });

        static::updating(function($post) {
            if (Auth::guest()) {
                $post->updated_by = 0;
            } else {
                $post->updated_by = Auth::user()->id;
            }
        });
    }
    /*     * ***************************** Users Model Class ends here ************************* */
}
