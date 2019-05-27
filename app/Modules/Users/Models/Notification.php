<?php

namespace App\Modules\Users\Models;

use App\Libraries\CommonFunction;
use App\Libraries\ReportHelper;
use App\Modules\Settings\Models\Bank;
use App\Modules\Settings\Models\Configuration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model {

    protected $table = 'notifications';
    protected $fillable = array(
         'source',
         'ref_id',
         'destination',
         'status',
         'is_sent',
         'msg_type',
         'template_id',
         'priority',
         'created_by',
         'updated_by'
    );

    public static function boot()
    {
        parent::boot();
        // Before update
        static::creating(function($post)
        {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function($post)
        {
            $post->updated_by = Auth::user()->id;
        });

    }

    /**
     * @param $pilgrim_id
     */
    public function sendRegSMS($pilgrim_id){
        $pilgrim = Pilgrim::find($pilgrim_id);
        if($pilgrim->is_govt=='Private'){
            $template = Template::where('caption','REG_SMS_P')->where('status',1)->first();
        }else{
            $template = Template::where('caption','REG_SMS_G')->where('status',1)->first();
        }


        if($template==null)
        {
            return false;
        }
        $template = $template->toArray();



        $data = [
            'token_no' => $pilgrim->tracking_no,
            'pilgrim' => CommonFunction::getName4SMS($pilgrim->full_name_english),
            'birth_date' => CommonFunction::changeDateFormat($pilgrim->birth_date),
            'validity' => CommonFunction::changeDateFormat(Configuration::getLastDate($pilgrim->is_govt))
        ];
        //$reportHelper = new ReportHelper();
        $smsData['source'] = CommonFunction::ConvParaEx($template['details'], $data);

        $smsData['destination'] = $pilgrim->mobile;
        $smsData['msg_type'] = 'SMS';
        $smsData['ref_id'] = $pilgrim->id;
        $smsData['is_sent'] = 0;
        $smsData['template_id'] = $template['id'];

        Notification::create($smsData);
    }


    public function sendPaidSMS($pilgrim_id,$bank_id){
        $pilgrim = Pilgrim::findOrFail($pilgrim_id);
        
        // getting payment or pilgrims info
        $bank_name = Bank::find($bank_id);
        if($pilgrim->is_govt=='Private'){
            $template = Template::where('caption','PAID_SMS_P')->where('status',1)->first();
        }else{
            $template = Template::where('caption','PAID_SMS_G')->where('status',1)->first();
        }

        if($template==null)
        {
            return false;
        }
        $template = $template->toArray();



        if($pilgrim->is_govt == 'Government'){
            $serial_no = 'G-'.$pilgrim->serial_no;
        }elseif($pilgrim->is_govt == 'Private'){
            $serial_no = 'NG-'.$pilgrim->serial_no;
        }else{
            $serial_no = '';
        }
        $data = [
            'token_no' => $pilgrim->tracking_no,
            'bank_name' => $bank_name->name,
            'serial_no' => $serial_no,
            'pilgrim' => CommonFunction::getName4SMS($pilgrim->full_name_english),
        ];
        //$reportHelper = new ReportHelper();
        $smsData['source'] = CommonFunction::ConvParaEx($template['details'], $data);

        $smsData['destination'] = $pilgrim->mobile;
        $smsData['msg_type'] = 'SMS';
        $smsData['ref_id'] = $pilgrim->id;
        $smsData['is_sent'] = 0;
        $smsData['template_id'] = $template['id'];

        Notification::create($smsData);
    }

    public function sendRegPaidSMS($pilgrim_id,$bank_id)
    {
        $pilgrim = Pilgrim::findOrFail($pilgrim_id);
        // getting payment or pilgrims info
        $bank_name = Bank::find($bank_id);

        if($pilgrim->is_govt=='Private'){
            $template = Template::where('caption','REG_PAID_SMS_P')->where('status',1)->first();
        }else{
            $template = Template::where('caption','REG_PAID_SMS_G')->where('status',1)->first();
        }

        if($template==null)
        {
            return false;
        }
        $template = $template->toArray();



        $data = [
            'token_no' => $pilgrim->tracking_no,
            'bank_name' => $bank_name->name,
            'pilgrim' => CommonFunction::getName4SMS($pilgrim->full_name_english),
        ];
        $reportHelper = new ReportHelper();
        $smsData['source'] = CommonFunction::ConvParaEx($template['details'], $data);

        $smsData['destination'] = $pilgrim->mobile;
        $smsData['msg_type'] = 'SMS';
        $smsData['ref_id'] = $pilgrim->id;
        $smsData['is_sent'] = 0;
        $smsData['template_id'] = $template['id'];
        Notification::create($smsData);
    }

    public function sendSecondStepSMS($code){

        $smsData['source'] = 'Your OSS Framework verification code: '.$code;

        $smsData['destination'] = Auth::user()->user_phone;
        $smsData['msg_type'] = 'SMS';
        $smsData['ref_id'] = Auth::user()->id;
        $smsData['is_sent'] = 0;
        $smsData['template_id'] = 0;
        $smsData['priority'] = 9;

        Notification::create($smsData);
    }

    /**
     * @param $pilgrim_id
     * @param string $type
     */
    public static function sendRefundSMS($pilgrim_id, $type = 'REFUNDED'){
        $pilgrim = Pilgrim::find($pilgrim_id);

        if($type == 'REFUNDED'){
            $template = Template::where('caption', 'REFUNDED')->where('status',1)->first();
        }else {
            $template = Template::where('caption', 'REFUND_REQ')->where('status',1)->first();
        }

        if($template==null)
        {
            return false;
        }
        $template = $template->toArray();

        $data = [
            'token_no' => $pilgrim->tracking_no,
            'pilgrim' => CommonFunction::getName4SMS($pilgrim->full_name_english),
        ];
        //$reportHelper = new ReportHelper();
        $smsData['source'] = CommonFunction::ConvParaEx($template['details'], $data);

        $smsData['destination'] = $pilgrim->mobile;
        $smsData['msg_type'] = 'SMS';
        $smsData['ref_id'] = $pilgrim->id;
        $smsData['is_sent'] = 0;
        $smsData['template_id'] = $template['id'];

        Notification::create($smsData);
    }

    public static function resendSMS($id)
    {
        $sms = Notification::findOrFail($id);
        Notification::create([
            'source' => $sms->source,
            'ref_id' => $sms->ref_id,
            'destination' => $sms->destination,
            'is_sent' => 0,
            'sent_on' => $sms->sent_on,
            'no_of_try' => $sms->no_of_try,
            'msg_type' => $sms->msg_type,
            'template_id' => $sms->template_id
        ]);
    }

    public static function sendCustomMessage($message,$destination,$msg_type,$ref_id,$priority)
    {
        Notification::create([
            'source' => $message,
            'destination' => $destination,
            'msg_type' => $msg_type,
            'ref_id' => $ref_id,
            'is_sent' => 0,
            'priority' => $priority
        ]);
    }

    /*     * ******************End of Model Class***************** */
}
