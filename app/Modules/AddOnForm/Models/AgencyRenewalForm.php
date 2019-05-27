<?php

namespace App\Modules\AddOnForm\Models;

use App\Libraries\Encryption;
use App\Modules\AgencyRenewal\Models\AgencyInfoDraft;
use App\Modules\ProcessPath\Models\ProcessList;
use Illuminate\Database\Eloquent\Model;

class AgencyRenewalForm extends Model {


    public static function getAgencyRenewalFormData($process_list_id)
    {
        $ref_id = ProcessList::where('id',$process_list_id)->pluck('ref_id');
        $renewFormData = AgencyInfoDraft::where('id',$ref_id)->first(['id','license_effective_date','license_expired_date']);
        return $renewFormData;
    }

    public static function licenceRenewalDataUpdate($request,$process)
    {
        $agency_renew_frm_id = Encryption::decodeId($request->get('agency_renew_frm_id'));
        $license_effective_date = date('Y-m-d',strtotime($request->get('license_effective_date')));
        $license_expire_date = date('Y-m-d',strtotime($request->get('license_expired_date')));
        $frm_update = AgencyInfoDraft::where('id',$agency_renew_frm_id)->update([
            'license_effective_date'=>$license_effective_date,
            'license_expired_date'=>$license_expire_date,
        ]);
        return $frm_update;
    }
}
