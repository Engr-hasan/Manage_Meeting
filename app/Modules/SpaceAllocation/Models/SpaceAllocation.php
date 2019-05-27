<?php

namespace App\Modules\SpaceAllocation\Models;

use App\Libraries\CommonFunction;
use App\Modules\Apps\Models\P2ProcessList;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SpaceAllocation extends Model {

    protected $table = 'space_allocation';
    protected $fillable = array(
        'id',
        'company_id',
        'bill_id',
        'bill_month',
        'payment_status_id',
        'applicant_name',
        'country_type',
        'country',
        'state',
        'province',
        'division_id',
        'district_id',
        'address_line1',
        'address_line2',
        'post_code',
        'phone_no',
        'fax_no',
        'email',
        'website',
        'identification_type',
        'nid',
        'passport',
        'vat_reg_no',
        'tin_reg_no',
        'park_id',
        'company_name',
        'company_logo',
        'type_of_business_service',
        'organization_type',
        'industry_type',
        'industry_cat_id',
        'eia_cer_file',
        'eia_cer_exist',
        'construction_start',
        'construction_end',
        'construction_duration',
        'cod_date',
        'fa_lc_to',
        'fa_fc_to',
        'fa_tc_to',
        'equity',
        'local_loan',
        'foreign_loan',
        'total_loan',
        'auth_capital_to',
        'paid_capital_to',
        'ext_borrow_to',
        'equity_loan_ratio',
        'paid_cap_amount',
        'paid_cap_nature',
        'paid_cap_percentage',
        'agreed_land',
        'sfb_plot_address',
        'remarks',
        'product_name',
        'product_usage',
        'manufacture_process',
        'project_cost',
        'mp_year_1',
        'for_man_1',
        'for_skill_1',
        'for_unskill_1',
        'for_total_1',
        'loc_man_1',
        'loc_skill_1',
        'loc_unskill_1',
        'loc_total_1',
        'gr_total_1',
        'mp_year_2',
        'for_man_2',
        'for_skill_2',
        'for_unskill_2',
        'for_total_2',
        'loc_man_2',
        'loc_skill_2',
        'loc_unskill_2',
        'loc_total_2',
        'gr_total_2',
        'mp_year_3',
        'for_man_3',
        'for_skill_3',
        'for_unskill_3',
        'for_total_3',
        'loc_man_3',
        'loc_skill_3',
        'loc_unskill_3',
        'loc_total_3',
        'gr_total_3',
        'mp_year_4',
        'for_man_4',
        'for_skill_4',
        'for_unskill_4',
        'for_total_4',
        'loc_man_4',
        'loc_skill_4',
        'loc_unskill_4',
        'loc_total_4',
        'gr_total_4',
        'mp_year_5',
        'for_man_5',
        'for_skill_5',
        'for_unskill_5',
        'for_total_5',
        'loc_man_5',
        'loc_skill_5',
        'loc_unskill_5',
        'loc_total_5',
        'gr_total_5',
        'sales_export',
        'sales_exp_oriented',
        'sales_domestic',
        'sales_total',
        'land_ini',
        'land_reg',
        'power_ini',
        'power_reg',
        'gas_ini',
        'gas_reg',
        'po_no',
        'po_date',
        'po_bank_id',
        'po_bank_branch_id',
        'po_ammount',
        'po_file',
        'water_ini',
        'water_reg',
        'etp_ini',
        'etp_reg',
        'is_draft',
        'is_locked',
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
