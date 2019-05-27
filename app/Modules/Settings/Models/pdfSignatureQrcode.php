<?php namespace App\Modules\Settings\Models;

use Illuminate\Database\Eloquent\Model;


class pdfSignatureQrcode extends Model {

    protected $table = 'pdf_signature_qrcode';
    protected $fillable = array(
        'id',
        'signature',
        'qr_code',
        'app_id',
        'service_id',
        'user_id'
    );

    public static function boot()
    {
        parent::boot();
    }
    
    

}
