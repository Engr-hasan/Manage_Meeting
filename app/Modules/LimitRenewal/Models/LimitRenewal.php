<?php

namespace App\Modules\LimitRenewal\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LimitRenewal extends Model {

    protected $table = 'limit_renewal';
    protected $fillable = array(
        'id',
        'date_of_submission',
        'date_of_approval',
        'date_of_approval',
        'company_name',
        'phone_number',
        'mobile_number',
        'address',
        'membership_no',
        'Name_and_designation',
        'name',
        'email',
        'business_nature',
        'estimated_online_transaction',
        'refile_increment',
        'download_as_pdf',
        'bank_id',
        'branch_id',
        'certificate',
        'acceptTerms',
        'is_draft',
        'is_archive',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    );

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

    /**
     * @return application list
     */


    public static function getApplicatoinList($process_type_id = '')
    {
        $userType = CommonFunction::getUserType();
        $userDeskIds = CommonFunction::getUserDeskIds();
        $company_id = CommonFunction::getUserSubTypeWithZero();
        $query = P2ProcessList::leftJoin('space_allocation as apps', 'apps.id', '=', 'p2_process_list.ref_id')
            ->leftJoin('user_desk', 'user_desk.id', '=', 'p2_process_list.desk_id')
            ->leftJoin('p2_process_status as ps', function ($join) use($process_type_id) {
                $join->on('ps.id', '=', 'p2_process_list.process_status_id');
                $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
            })

            ->leftJoin('park_info as pi', 'pi.id', '=','p2_process_list.park_id');
        if ($userType == '5x505') { //Applicant
            $query->where('p2_process_list.company_id', '=', $company_id);
        } elseif ($userType == '1x101' || $userType == '2x202') { // System Admin and IT help desk
            $query->whereNotIn('p2_process_list.process_status_id', [-1, 8, 22])
                ->where('p2_process_list.process_status_id', 23); // 23 is payment accepted
        } elseif ($userType == '3x303' && in_array(10,$userDeskIds)) { // Security Viewers
            $query->where('p2_process_list.process_status_id', 23)// 23 is payment accepted
            ->whereIn('p2_process_list.process_status_id', [30, 31]); // 30 = custom verified, 31 = Issued Gatepass (Full)
        } elseif ($userType == '9x909' && in_array(8,$userDeskIds)) { // Customs Viewers
            $query->where('p2_process_list.process_status_id', 23)// 23 is payment accepted
            ->whereIn('p2_process_list.process_status_id', [21, 30]); // 21 = approved and sent to custom, 30 = custom verified
        } else { // for system admin
            $query->whereIn('p2_process_list.desk_id', $userDeskIds)
                ->whereNotIn('p2_process_list.process_status_id', [-1, 8, 22]);
        }
        $result= $query->where('p2_process_list.process_type_id', $process_type_id)
            ->orderBy('p2_process_list.updated_at', 'desc')
            ->get([
                'p2_process_list.desk_id',
                'p2_process_list.process_status_id',
                'p2_process_list.tracking_no',
                'p2_process_list.locked_by',
                'p2_process_list.locked_at',
                'p2_process_list.ref_id',
                'p2_process_list.tracking_no',
                'user_desk.desk_name',
                'ps.status_name',
                'ps.color',
                'pi.id as parkId',
                'apps.*',
            ]);
        return $result;
    }

    /**
     * @return status wise application list
     */
    public static function getApplicationListByStatus($process_type_id, $status_id) {
        $userType = CommonFunction::getUserType();
        $company_id = CommonFunction::getUserSubTypeWithZero();
        $query = P2ProcessList::leftJoin('space_allocation as apps', 'apps.id', '=', 'p2_process_list.ref_id')
            ->leftJoin('user_desk', 'user_desk.id', '=', 'p2_process_list.desk_id')
            ->leftJoin('p2_process_status as ps', function ($join) use($process_type_id) {
                $join->on('ps.id', '=', 'p2_process_list.process_status_id');
                $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
            })
            ->leftJoin('park_info as pi', 'pi.id', '=','p2_process_list.park_id')
            ->where('p2_process_list.process_type_id', '=', $process_type_id)
            ->where('p2_process_list.process_status_id', '=', $status_id);

        if ($userType == '5x505' || $userType == '6x606') { //Applicant
            $query->where('p2_process_list.company_id', $company_id);
        }  else { //For others desks
            $query->whereNotIn('p2_process_list.process_status_id', [-1]);
        }
        $result= $query->orderBy('apps.created_at', 'DESC')
            ->get([
                'p2_process_list.desk_id',
                'p2_process_list.process_status_id',
                'p2_process_list.tracking_no',
                'p2_process_list.locked_by',
                'p2_process_list.locked_at',
                'p2_process_list.ref_id',
                'p2_process_list.tracking_no',
                'user_desk.desk_name',
                'ps.status_name',
                'ps.color',
                'pi.id as parkId',
                'apps.*',
            ]);
        return $result;
    }

    function update_method($app_id, $data) {
        DB::table($this->table)
            ->where('id', $app_id)
            ->update($data);
    }


    function duplicate_certificate($agency_id,$data){
        DB::table($this->table)
            ->where('agency_id',$agency_id)
            ->where('status_id',25)
            ->where('certificate_en','!=',1)
            ->where('certificate_bn','!=',1)
            ->orderby('created_at','desc')
            ->update($data);
    }

    /*     * *****************************End of Model Class********************************** */
}
