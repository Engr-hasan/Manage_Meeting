<?php

namespace App\Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Libraries\CommonFunction;

class Delegation extends Model {

    protected $table = 'delegate_history';
    protected $fillable = array(
        'delegate_id',
        'delegate_id_back',
        'delegate_form_user',
        'delegate_to_user_id',
        'delegate_by_user_id',
        'status',
        'remarks'
    );

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

    //************************ Model Class ends here ****************************/
}
