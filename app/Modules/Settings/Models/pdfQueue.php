<?php namespace App\Modules\Settings\Models;

use Illuminate\Database\Eloquent\Model;


class pdfQueue extends Model {

    protected $table = 'pdf_queue';
    protected $fillable = array(
        'pdf_type',
        'app_id',
        'service_id',
        'secret_key'
    );

    public static function boot()
    {
        parent::boot();
    }
    
    

}
