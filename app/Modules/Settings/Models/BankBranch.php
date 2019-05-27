<?php

namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class BankBranch extends Model {

    protected $table = 'bank_branches';
    protected $fillable = array(
        'id',
        'bank_id',
        'branch_name',
        'branch_code',
        'address',
        'manager_info',
        'is_active',
        'is_archive',
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
