<?php namespace App\Modules\Apps\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class Service extends Model {

    protected $table = 'service_info';
    protected $fillable = [
        'id',
        'name',
        'url',
        'is_active',
    ];

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

    /*******************************End of Model Class***********************************/
}
