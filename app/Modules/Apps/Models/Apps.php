<?php namespace App\Modules\Apps\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Apps extends Model {
    protected $primaryKey = 'application_id';
	protected $table = "application";
    protected $fillable = array(
        'application_id',
        'tracking_number',
        'applicant_type',
        'applicant_name',
        'applicant_father_name',
        'applicant_mother_name',
        'applicant_spouse_name',
        'permanent_address',
        'present_address',
        'country',
        'correspondent_nationality',
        'agency_id',
        'application_title',
        'initiated_by',
        'closed_by',
        'on_behalf_of',
        'created_at',
        'created_by',
        'updated_at',
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
                $post->created_by = Auth::user()->id;
                $post->updated_by = Auth::user()->id;
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

    function update_method($app_id, $data) {
        DB::table($this->table)
            ->where('application_id', $app_id)
            ->update($data);
    }

}
