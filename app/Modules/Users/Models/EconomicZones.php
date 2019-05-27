<?php

namespace App\Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Libraries\CommonFunction;

class EconomicZones extends Model {

    protected $table = 'economic_zones';
    protected $fillable = array(
        'id',
        'name',
        'upazilla',
        'district',
        'area',
        'remarks',
    );
    
    protected $defaults = array(
        'is_locked' => 0,
        'is_archive' => 0
    );

    public function __construct(array $attributes = array()) {
        $this->setRawAttributes($this->defaults, true);
        parent::__construct($attributes);
    }

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
