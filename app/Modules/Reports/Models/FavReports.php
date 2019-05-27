<?php namespace App\Modules\Reports\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class FavReports extends Model {

    protected $table = 'custom_favorite_reports';


    protected $fillable = ['user_id', 'report_id','status'];


    public static function boot()
    {
        parent::boot();
        // Before update
        static::creating(function($post)
        {
            $post->created_by = CommonFunction::getUserId();
//            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function($post)
        {
            $post->updated_by = CommonFunction::getUserId();
        });

    }
}
