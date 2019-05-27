<?php namespace App\Modules\Apps\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\CommonFunction;
use Illuminate\Support\Facades\Auth;


class ProcessDoc extends Model {

    protected $table = 'process_documents';
    protected $fillable = array(
        'id',
        'service_id',
        'ref_id',
        'desk_id',
        'status_id',
        'file',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    );

    public static function boot()
    {
        parent::boot();
        // Before update
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

}
