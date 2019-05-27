<?php namespace App\Modules\Dashboard\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Libraries\CommonFunction;

class Dashboard extends Model {

    protected $table = 'dashboard_object';
    protected $fillable = [
        'db_obj_title',
        'db_obj_caption',
        'db_user_id',
        'db_obj_type',
        'db_obj_para1',
        'db_obj_para2',
        'db_obj_status',
        'db_obj_sort',
        'db_user_type',
        'updated_by',
    ];


    public static function boot()
    {
        parent::boot();
        // Before update
        static::creating(function($post)
        {
            //$post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function($post)
        {
            $post->updated_by = CommonFunction::getUserId();
        });

    }

    public function getWidget()
    {
        $widgetData = '';
        $data = Dashboard::where('db_obj_type','widget')
            ->Where(function($query)
            {
             $query->where('db_user_type','LIKE','%'.Auth::user()->user_type.'%')
                 ->orWhere('db_user_type',0)
                 ->orWhere('db_user_id',Auth::user()->id);
            })
            ->orderBy('db_user_id','desc')
            ->orderBy('db_user_type','desc')
            ->first();

        if($data) {
            //user_type like {$user_type} and user_sub_type={$user_sub_type}
            $users_type = Auth::user()->user_type;
            $type = explode('x', $users_type);
            $data['user_type'] = "'"."$type[0]x" . substr($type[1], 0, 2) ."_'";
            $data['user_sub_type'] = Auth::user()->user_sub_type==''? 0 : Auth::user()->user_sub_type;
            $data['district'] = Auth::user()->district;
            $data['sess_user_id'] = Auth::user()->id;
            $data['sess_user_type'] = Auth::user()->user_type;
            $data['sess_user_sub_type'] = Auth::user()->user_sub_type;
            //$reportHelper = new ReportHelper();
            //$sql = $reportHelper->ConvParaEx($data->db_obj_para1,$data);
            $sql = CommonFunction::ConvParaEx($data->db_obj_para1,$data);

            try
            {
                $widgetData = DB::select(DB::raw($sql));

            }catch(\Illuminate\Database\QueryException $e) {

                $widgetData = array();
            }
        }
        return $widgetData;
    }

    public function getDates2()
    {
        return null;
        $list = DB::select(DB::raw("SELECT tab2.Agency,format(tab1.Pilgrims,0) as Pilgrims,format(Quota,0) as Quota,StartDate,EndDate FROM (
                    SELECT is_govt AS Agency, COUNT(id) AS Pilgrims FROM pilgrims WHERE is_archived=0 AND serial_no>0 GROUP BY is_govt
                    ) tab1 RIGHT JOIN (
                    SELECT CASE WHEN caption='PRE_REG_GOVT_PERIOD' THEN 'Government' ELSE 'Private' END Agency,VALUE AS StartDate,value2 AS EndDate,value3 AS Quota
                    FROM configuration WHERE caption IN('PRE_REG_GOVT_PERIOD','PRE_REG_PRIVATE_PERIOD')
                    ) tab2 ON tab2.Agency=tab1.Agency"));
        return $list;
    }

    public function getDashboardObject()
    {
        $data = Dashboard::where('db_obj_type','LIST')
            ->where('db_user_type','LIKE','%'.Auth::user()->user_type.'%')
            ->get();
        return $data;
    }

/********************End of Model Class*****************/
}
