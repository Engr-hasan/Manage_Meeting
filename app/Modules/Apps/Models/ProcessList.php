<?php

namespace App\Modules\Apps\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Auth;

class ProcessList extends Model {

    //
    protected $table = 'process_list';
    protected $primaryKey = 'process_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'process_id',
        'track_no',
        'reference_no',
        'company_id',
        'initiated_by',
        'closed_by',
        'desk_id',
        'status_id',
        'service_id',
        'record_id',
        'process_desc',
        'created_at',
        'updated_at',
        'updated_by'
    ];

    function update_app_for_apps($_id, $data) {
        ProcessList::where('record_id', $_id)
            ->where('service_id', 1)
            ->update($data);
    }

    /*
	 * get list of all details applications that are processed by user or in processing
	 */
    public static function getApplicationList($search_status_id = 0, $service_id = 1) {

        $userType = CommonFunction::getUserType();
        $userId = CommonFunction::getUserId();
        $desk_id = CommonFunction::getDeskId();
        if ($userType == '5x501' || $userType == '5x502') { //Applicant(Agency admin and user)

            $query = ProcessList::leftJoin('application as ap', 'ap.application_id', '=', 'process_list.record_id')
                ->where('process_list.initiated_by', '=', $userId)
                ->where('process_list.service_id', '=', $service_id)
                ->groupBy('process_list.process_id')
                ->orderBy('process_list.created_at', 'desc');

            return $query->get(['ap.applicant_name', 'ap.application_title', 'process_list.initiated_by',
                'process_list.process_id', 'process_list.record_id', 'process_list.track_no',
                'process_list.desk_id',
                'process_list.status_id', 'process_list.updated_at','process_list.created_at'
            ]);


        } elseif ($userType == '1x101') { // System Admin

            $query = ProcessList::leftJoin('application as ap', 'ap.application_id', '=', 'process_list.record_id')
                ->whereNotIn('process_list.status_id', [-1,8,23,22])
                ->where('process_list.service_id', '=', $service_id)
                ->groupBy('process_list.process_id')
                ->orderBy('process_list.created_at', 'desc');

            return $query->get(['ap.applicant_name', 'ap.application_title', 'process_list.initiated_by',
                'process_list.process_id', 'process_list.record_id', 'process_list.track_no', 'process_list.desk_id',
                'process_list.status_id', 'process_list.updated_at', 'process_list.company_id'
            ]);

        }elseif ($userType == '3x301') { // Administrative official

            $query = ProcessList::leftJoin('application as ap', 'ap.application_id', '=', 'process_list.record_id')
                ->where('process_list.desk_id', '=', $desk_id)
                ->where('process_list.service_id', '=', $service_id)
                ->whereNotIn('process_list.status_id', [-1,8,23,22])
                ->groupBy('process_list.process_id')
                ->orderBy('process_list.created_at', 'desc');
            return $query->get(['ap.applicant_name', 'ap.application_title', 'process_list.initiated_by',
                'process_list.process_id', 'process_list.record_id', 'process_list.track_no', 'process_list.desk_id',
                'process_list.status_id', 'process_list.updated_at', 'process_list.company_id'
            ]);

        } else { //For others desks

            $query = ProcessList::leftJoin('application as ap', 'ap.application_id', '=', 'process_list.record_id')
                ->where('process_list.desk_id', '=', $desk_id)
                ->where('process_list.service_id', '=', $service_id)
                ->whereNotIn('process_list.status_id', [-1,8,23,22])
                ->groupBy('process_list.process_id')
                ->orderBy('process_list.created_at', 'desc');

            return $query->get(['ap.applicant_name', 'ap.application_title', 'process_list.initiated_by',
                'process_list.process_id', 'process_list.record_id', 'process_list.track_no', 'process_list.desk_id',
                'process_list.status_id', 'process_list.updated_at', 'process_list.company_id'
            ]);

        }
    }

    /*
     * get search result
     */
    public static function getSearchResults($tracking_number, $passport_number, $nationality, $applicant_name,$status_id) {
//         dd($nationality);
        $userType = CommonFunction::getUserType();
        $userId = CommonFunction::getUserId();
        $desk_id = CommonFunction::getDeskId();

        if ($userType == '5x502' || $userType == '5x501') { //Applicant (Agency admin and user)
            return $query =  ProcessList::leftJoin('companies as c', 'c.company_id', '=', 'process_list.company_id')
                ->leftJoin('user_desk as ud', 'ud.desk_id', '=', 'process_list.desk_id')
                ->leftJoin('app_status as as1', 'as1.status_id', '=', 'process_list.status_id')
                ->leftJoin('application as ap', 'ap.application_id', '=', 'process_list.record_id')
//                ->leftJoin('area_info as ai', 'ai.area_id', '=', 'wp.ORG_DISTRICT')
                ->leftJoin('country_info as ci', 'ci.iso', '=', 'ap.correspondent_nationality')
                ->where('process_list.initiated_by', '=', $userId)
                ->where('process_list.service_id', '=', 1)
                ->where(function ($query) use ($tracking_number) {
                    if(!empty($tracking_number)){
                        $query->where('process_list.track_no', '=', $tracking_number);
                    }
                })
//                ->where(function ($query2) use ($passport_number) {
//                    if(!empty($passport_number)){
//                        $query2->where('wp.correspondent_passport', '=', $passport_number);
//                    }
//                })
                ->where(function ($query3) use ($nationality) {
                    if(!empty($nationality)){
                        $query3->where('ap.country', '=', $nationality);
                    }
                })
                ->where(function ($query4) use ($applicant_name) {
                    if(!empty($applicant_name)){
                        $query4->where('ap.applicant_name', '=', $applicant_name);
                    }
                })->where(function ($query5) use ($status_id) {
                    if(!empty($status_id)){
                        $query5->where('process_list.status_id', '=', $status_id);
                    }
                })
                ->where('as1.service_id', '=', 1)
                ->orderBy('process_list.created_at', 'desc')
                ->get(['ci.nationality','ap.applicant_name', 'ap.application_title', 'ud.desk_name', 'as1.color', 'process_list.process_id', 'process_list.record_id', 'process_list.track_no', 'c.company_name', 'as1.status_name', 'as1.status_id', 'process_list.created_at']);

        }
        else{ // all admin
            return $query =  ProcessList::leftJoin('companies as c', 'c.company_id', '=', 'process_list.company_id')
                ->leftJoin('user_desk as ud', 'ud.desk_id', '=', 'process_list.desk_id')
                ->leftJoin('app_status as as1', 'as1.status_id', '=', 'process_list.status_id')
                ->leftJoin('application as ap', 'ap.application_id', '=', 'process_list.record_id')
//                ->leftJoin('area_info as ai', 'ai.area_id', '=', 'wp.ORG_DISTRICT')
                ->leftJoin('country_info as ci', 'ci.iso', '=', 'ap.correspondent_nationality')
                ->where(function ($query) use ($tracking_number) {
                    if(!empty($tracking_number)){
                        $query->where('process_list.track_no', '=', $tracking_number);
                    }
                })
//                ->where(function ($query2) use ($passport_number) {
//                    if(!empty($passport_number)){
//                        $query2->where('wp.correspondent_passport', '=', $passport_number);
//                    }
//                })
                ->where(function ($query3) use ($nationality) {
                    if(!empty($nationality)){
                        $query3->where('ap.country', '=', $nationality);
                    }
                })
                ->where(function ($query4) use ($applicant_name) {
                    if(!empty($applicant_name)){
                        $query4->where('ap.applicant_name', '=', $applicant_name);
                    }
                })->where(function ($query5) use ($status_id) {
                    if(!empty($status_id)){
                        $query5->where('process_list.status_id', '=', $status_id);
                    }
                })
                ->where('process_list.service_id', '=', 1)
                ->where('process_list.status_id', '!=', -1)
                ->where('as1.service_id', '=', 1)
//                ->groupBy('process_list.process_id')
                ->orderBy('process_list.created_at', 'desc')
                ->get(['ci.nationality','ap.applicant_name', 'ap.application_title', 'ud.desk_name', 'as1.color', 'process_list.process_id', 'process_list.record_id', 'process_list.track_no', 'c.company_name', 'as1.status_name', 'as1.status_id', 'process_list.created_at']);

        }
    }
}
