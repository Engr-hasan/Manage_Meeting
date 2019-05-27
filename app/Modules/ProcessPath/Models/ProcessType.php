<?php namespace App\Modules\ProcessPath\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class ProcessType extends Model {

    protected $table = 'process_type';
    protected $fillable = array(
        'id',
        'name',
        'status',
        'final_status',
        'type_key',
        'active_menu_for',
        'menu_name',
        'form_url',
        'process_key',
        'ref_fields'
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

    /*     * *****************************End of Model Class********************************** */
}

