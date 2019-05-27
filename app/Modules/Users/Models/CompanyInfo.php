<?php

namespace App\Modules\Users\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CompanyInfo extends Model {

    protected $table = 'company_info';
    protected $fillable = array(
        'id',
        'company_name',
        'division',
        'district',
        'thana',
        'company_status',
        'is_approved',
        'is_archive',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
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

    /************************ Countries Model Class ends here ****************************/
}
