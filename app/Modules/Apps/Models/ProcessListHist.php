<?php

namespace App\Modules\Apps\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\CommonFunction;

class ProcessListHist extends Model {

    protected $table = 'process_list_hist';
    protected $fillable = [
        'id',
        'process_id',
        'company_id',
        'process_type',
        'ref_id',
        'desk_id',
        'status_id',
        'process_desc',
        'json_object',
        'on_behalf_of_user',
        'locked_by',
        'locked_at',
        'closed_by',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    public static function boot() {
        parent::boot();
        // Before update
        static::creating(function($post) {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }


    public function updateProcess($ref_id, $data, $process_type_id) {
        ProcessList::where('ref_id', $ref_id)
            ->where('process_type', $process_type_id)
            ->update($data);
    }





    /*     * *****************************End of Model Function********************************* */
}
