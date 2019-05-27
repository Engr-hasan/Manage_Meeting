<?php

namespace App\Modules\apps\Models;

use Illuminate\Database\Eloquent\Model;

class pdfQueue extends Model {

    protected $table = 'pdf_queue';
    protected $fillable = [
        'id',
        'pdf_type',
        'app_id',
        'other_significant_id',
        'service_id',
        'attachment',
        'secret_key',
        'status',
        'created_at',
        'updated_at'
    ];

    public static function boot() {
        parent::boot();
    }

//***************************************End of Class****************************************************
}
