<?php

namespace App\Modules\ProcessPath\Models;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\BoardMeting\Models\ProcessListBoardMeting;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ProcessList extends Model {

    //
    protected $table = 'process_list';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'ref_id',
        'company_id',
        'park_id',
        'tracking_no',
        'json_object',
        'desk_id',
        'process_type_id',
        'status_id',
        'priority',
        'on_behalf_of_user',
        'process_desc',
        'closed_by',
        'locked_by',
        'locked_at',
        'is_active',
        'created_at',
        'created_by',
        'updated_at',
        'hash_value',
        'updated_by'
    ];

    public static function boot() {
        parent::boot();
        static::creating(function($post) {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }


    function update_app_for_apps($_id, $data) {
        ProcessList::where('record_id', $_id)
            ->where('service_id', 1)
            ->update($data);
    }

    public static function getApplicationList($process_type_id = 0, $status = 0, $request, $desk)
    {
        $userType = CommonFunction::getUserType();
        $company_id = Auth::user()->user_sub_type;
        $userDeskIds = CommonFunction::getUserDeskIds();

        $user_id = CommonFunction::getUserId();

        $userParkIds = CommonFunction::getUserParkIds();
        $delegatedUserDeskParkIds = CommonFunction::getDelegatedUserDeskParkIds();


        $query = ProcessList::leftJoin('user_desk', 'process_list.desk_id', '=', 'user_desk.id')
            ->leftjoin('process_status', function ($on) {
                $on->on('process_list.status_id', '=', 'process_status.id')
                    ->on('process_list.process_type_id', '=', 'process_status.process_type_id', 'and');
            })
            ->leftJoin('process_type', 'process_list.process_type_id', '=', 'process_type.id')
            ->where('process_type.active_menu_for', 'like', "%$userType%");

        if ($userType == '1x101' || $userType == '2x202') { // System Admin
            $query->whereNotIn('process_list.status_id', [-1, 5]);

        } elseif ($userType == '5x505' || $userType == '13x303') { // General User 13x303= board admin
            $query->whereIn('process_list.company_id', explode(',', $company_id));
        }
        elseif ($userType == '4x404'){

            $query->whereIn('process_list.company_id', explode(',', $company_id));
        }
        else {
            if ($desk == 'my-desk') { //Condition applied for my-desk data only

                $query->where(function ($query1) use ($userDeskIds, $userParkIds) {
                    $query1->whereIn('process_list.desk_id', $userDeskIds)
                        ->whereIn('process_list.park_id', $userParkIds)
                        ->where('process_list.desk_id', '!=', 0)
                        ->whereNotIn('process_list.status_id', [-1, 5]);
                });
            }

            else if($desk == 'desk_user_application'){
                $query->where(function ($query1) use ($user_id){
                    $query1->where('process_list.created_by', '=', $user_id);
                });
            }
            else if ($desk == 'my-delg-desk') {

                if (empty($delegatedUserDeskParkIds)) {
                        $query->where('process_list.desk_id', 555555);
                } else {
                $i = 0;
                    foreach ($delegatedUserDeskParkIds as $data) {
                        $queryInc = '$query' . $i;

                        if ($i == 0) {
                            $query->where(function ($queryInc) use ($data) {
                                $queryInc->whereIn('process_list.desk_id', $data['desk_ids'])
                                    ->whereIn('process_list.park_id', $data['park_ids']);
                            });
                        } else {
                            $query->orWhere(function ($queryInc) use ($data) {
                                $queryInc->whereIn('process_list.desk_id', $data['desk_ids'])
                                    ->whereIn('process_list.park_id', $data['park_ids']);
                            });
                        }
                        $i++;
                    }
                }
            }

        }

        if ($request->has('process_search')) { //work for search parameter
            $query->search($request); //calling of scopeSearch function

        } else {
            if ($process_type_id) {
                $query->where('process_list.process_type_id', $process_type_id);
            }
            $from = Carbon::now();
            $to = Carbon::now();
            $from->subMonths(3); //maximum 3month data selection by default
            $query->whereBetween('process_list.created_at', [$from, $to]);
        }

//        $query->orderBy('process_list.priority', 'desc');
        $query->orderBy('process_list.id', 'desc')->distinct();
        return $query->select([
            'process_list.id',
            'process_list.ref_id',
            'process_list.tracking_no',
            'json_object',
            'process_list.desk_id',
            'process_list.process_type_id',
            'process_list.status_id',
            'process_list.priority',
            'process_list.process_desc',
            'process_list.updated_at',
            'process_list.updated_by',
            'process_list.locked_by',
            'process_list.locked_at',
            'user_desk.desk_name',
            'process_status.status_name',
            'process_type.name as process_name',
            'process_type.form_url'
        ]);

    }


    public function scopeSearch($query, $request)
    {

        if($request->has('search_date')){
            $from = Carbon::parse($request->get('search_date'));
            $to = Carbon::parse($request->get('search_date'));
        }else {
            $from = Carbon::now();
            $to = Carbon::now();
        }
        switch ($request->get('search_time')){
            case 30:
                $from->subMonth(); $to->addMonth();
                break;
            case 15:
                $from->subWeeks(2); $to->addWeeks(2);
                break;
            case 7:
                $from->subWeek(); $to->addWeek();
                break;
            case 1:
                $from->subDay(); $to->addDay();
                break;
            default:
//                $from->subDays($request->get('search_time'));
//                $to->addDays($request->get('search_time'));
        }
        if($request->has('search_date')) {
            $query->whereBetween('process_list.created_at', [$from, $to]); //date time wise search
        }
        if(strlen($request->get('search_text')) > 2) { //for search text data
            $query->where('process_list.json_object', 'like', '%' . $request->get('search_text') . '%');
        }
        if($request->get('search_type')>0) {
            $query->where('process_list.process_type_id', $request->get('search_type'));
            $query->where('process_list.status_id', '!=',-1);
        }
        if($request->has('search_status')) {
            $query->wherein('process_list.status_id', explode(",",$request->get('search_status')));
        }

        return $query;
    }

}
