<?php

namespace App\Modules\BoardMeting\Models;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\ProcessPath\Models\ProcessList;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Auth;

class ProcessListBMRemarks extends Model {

    protected $table = 'process_list_bm_remarks';
    protected $fillable = array(
        'id',
        'bm_process_id',
        'user_id',
        'chairman',
        'remarks',
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
    /*     * ***************************** Users Model Class ends here ************************* */
}
