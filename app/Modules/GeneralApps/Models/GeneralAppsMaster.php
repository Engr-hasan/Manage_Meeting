<?php

namespace App\Modules\GeneralApps\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class GeneralAppsMaster extends Model {

    protected $table = 'ga_master';
    protected $fillable = array(
        'company_name',
        'date_of_submission',
        'date_of_approval',
        'tracking_id',
        'company_reg_no',
        'office_district',
        'officce_police_station',
        'office_post_office',
        'office_post_code',
        'office_house_flat_road',
        'office_telephone',
        'office_mobile',
        'office_fax',
        'office_email',
        'factory_district',
        'factory_police_statuion',
        'factory_post_office',
        'factory_post_code',
        'factory_mouza_no',
        'factory_house_flat_road',
        'factory_telephone',
        'factory_mobile',
        'factory_fax',
        'factory_email',
        'chairman_name',
        'chairman_designation',
        'chairman_country',
        'chairman_district',
        'chairman_police_station',
        'chairman_post_code',
        'chairman_house_flat_road',
        'chairman_telephone',
        'chairman_mobile',
        'chairman_fax',
        'chairman_email',
        'industry_type',
        'local_executive',
        'local_supporting_staff',
        'local_total',
        'foreign_executive',
        'foreign_supporting_staff',
        'foreign_total',
        'ratio_local',
        'ratio_foreign',
        'electricity',
        'gas',
        'telephone',
        'road',
        'water',
        'drainage',
        'tin_no',
        'authorized_name',
        'acceptTerms',
        'authorized_address',
        'authorized_email',
        'authorized_mobile',
        'po_no',
        'po_date',
        'po_bank_id',
        'po_bank_branch_id',
        'po_ammount',
        'po_file',
        'certificate',
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
    /*     * *****************************End of Model Class********************************** */
}
