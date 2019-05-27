<?php
namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Units extends Model {

    protected $table = 'units';
    protected $fillable = array(
        'id',
        'name',
        'is_active',
    );    
        
    protected $defaults = array(
        'is_locked' => 0,
        'is_archive' => 0
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
