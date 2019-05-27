<?php

namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Bank extends Model {

    protected $table = 'bank';
    protected $fillable = array(
        'id',
        'name',
        'bank_code',
        'location',
        'email',
        'phone',
        'is_active',
        'address',
        'website',
        'created_by',
        'updated_by'
    );

    public static function boot() {
        parent::boot();
        // Before update
        static::creating(function($post) {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }
/*********************************************End of Model Class**********************************************/
}
