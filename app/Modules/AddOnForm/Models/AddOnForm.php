<?php

namespace App\Modules\AddOnForm\Models;

use App\Libraries\Encryption;
use App\Modules\PilgrimRegRequest\Models\PaymentRequest;
use App\Modules\ProcessPath\Models\ProcessList;
use Illuminate\Database\Eloquent\Model;

class AddOnForm extends Model {


    public function processAddOnForm($request)
    {
        $process_list_id = Encryption::decodeId($request->get('process_list_id'));
        $process = ProcessList::where('id',$process_list_id)->first();
        $status_id = $request->get('status_id');

        $status = false;

        switch ($process->process_type_id) {
            case 13: // Pilgrim registration

                if($status_id == '3')
                {
                    $status = $this->savePilgrimRequestApproveForm($request,$process);
                }
                else
                {
                    $status = false;
                }
                break;
            case 14: // Agency Activation

                if($status_id == '2')
                {
                    $status = AgencyRenewalForm::licenceRenewalDataUpdate($request,$process);
                }
                else if($status_id == '3')
                {
                    $status = AgencyRenewalForm::licenceRenewalDataUpdate($request,$process);
                }
                break;
        }

        return $status;
    }


    private function savePilgrimRequestApproveForm($request,$process)
    {
        $voucher_url = $request->get('voucher_url');
        return PaymentRequest::where('id',$process->ref_id)->update(['voucher_url' => $voucher_url]);
    }
}
