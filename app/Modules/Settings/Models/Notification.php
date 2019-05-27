<?php

namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Notification extends Model {

    protected $table = 'notifications';
    protected $fillable = array(
      'id','source','ref_id', 'destination','is_sent','sent_on','msg_type','template_id','response','created_by', 'updated_by'
    );

    public static function boot()
    {
        parent::boot();
        // Before update
        static::creating(function($post)
        {
            $post->created_by = Auth::user()->id;
            $post->created_by = CommonFunction::getUserId();
        });

        static::updating(function($post)
        {
            $post->updated_by = CommonFunction::getUserId();
        });

    }

}