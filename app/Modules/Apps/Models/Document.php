<?php

namespace App\Modules\apps\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\CommonFunction;
use Illuminate\Support\Facades\Auth;

class Document extends Model {

    protected $table = 'document';
    protected $fillable = [
        'service_id',
        'app_id',
        'doc_id',
        'doc_name',
        'doc_file'
    ];

    public static function boot() {
        parent::boot();
        // Before update
        static::creating(function($post) {
            $post->created_by = Auth::user()->id;
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }

    /**********************************End of Model Class**************************************/
}
