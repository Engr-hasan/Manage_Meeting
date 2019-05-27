<?php
namespace App\Modules\Users\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SecurityProfile extends Model {

    protected $table = 'security_profile';
    protected $fillable = array(
        'id',
        'profile_name',
        'user_type',
        'user_email',
        'allowed_remote_ip',
        'week_off_days',
        'work_hour_start',
        'work_hour_end',
        'active_status',
        'created_at',
        'created_by',
        'updated_by',
    );

    public static function boot()
    {
        parent::boot();
        static::creating(function($post)
        {
            $post->created_by = Auth::user()->id;
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function($post)
        {
            $post->updated_by = CommonFunction::getUserId();
        });

    }

    /*     * ******************End of Model Class***************** */
}
