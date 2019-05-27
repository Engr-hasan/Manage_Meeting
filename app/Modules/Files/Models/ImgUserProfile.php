<?php

namespace App\Modules\Files\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Auth;

class ImgUserProfile extends Model {

    protected $table = 'img_user_profile';
    protected $fillable = array(
        'id',
        'ref_id',
        'details',
        'created_by',
        'updated_by'
    );

    public static function boot()
    {
        parent::boot();
        // Before update
        static::creating(function($post)
        {
            if(isset(Auth::user()->id)){
                $post->created_by = Auth::user()->id;
            }else{
                $post->created_by = 0;
            }
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function($post)
        {
            $post->updated_by = CommonFunction::getUserId();
        });

    }

    /************************ Users Model Class ends here ****************************/
}
