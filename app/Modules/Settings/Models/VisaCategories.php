<?php

namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class VisaCategories extends Model {

    protected $table = 'visa_categories';
    protected $fillable = array(
        'id',
        'type',
        'description',
        'process_type_id',
        'expire_after',
        'status',
        'is_archive',
    );
    protected $defaults = array(
        'is_archive' => 0
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

    /*     * ******************End of Model Class***************** */
}
