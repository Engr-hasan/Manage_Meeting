<?php

namespace App\Modules\Apps\Models;

use Illuminate\Database\Eloquent\Model;

class pdfSignatureQrcode extends Model {

    protected $table = 'pdf_signature_qrcode';
    protected $fillable = array(
        'id',
        'signature',
        'qr_code',
        'service_id',
        'app_id',
        'other_significant_id',
        'desk_id',
        'user_id',
        'created_at',
        'updated_at'
    );

    public static function boot() {
        parent::boot();
    }

/*********************************************End of Model Class**********************************************/
}
