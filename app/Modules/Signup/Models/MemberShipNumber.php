<?php namespace App\Modules\Signup\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Auth;

class MemberShipNumber extends Model {

    protected $table = 'membership_number';
    protected $fillable = array(
        'type',
        'membership_no',
        'user_id',
        'is_active',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    );

    public static function boot() {
        parent::boot();
        // Before update
        static::creating(function($post) {
            if (Auth::guest()) {
                $post->created_by = 0;
                $post->updated_by = 0;
            } else {
                $post->created_by = CommonFunction::getUserId();
                $post->updated_by = CommonFunction::getUserId();
            }
        });

        static::updating(function($post) {
            if (Auth::guest()) {
                $post->updated_by = 0;
            } else {
                $post->updated_by = Auth::user()->id;
            }
        });
    }


    /*     * ***************************** Users Model Class ends here ************************* */
}
