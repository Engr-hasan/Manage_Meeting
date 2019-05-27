<?php

namespace App\Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Libraries\CommonFunction;

class ParkInfo extends Model {

    protected $table = 'park_info';

    protected $fillable = array(
        'id',
        'park_name',
        'upazilla_name',
        'district_name',
        'park_area',
        'remarks',
        'status',
        'is_archive',
        'created_by',
        'updated_by',
    );

//    public function __construct(array $attributes = array()) {
//        $this->setRawAttributes($this->defaults, true);
//        parent::__construct($attributes);
//    }

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
                $post->updated_by = CommonFunction::getUserId();
            }
        });
    }

    /*     * *****************************  Model Class ends here ************************* */
}
