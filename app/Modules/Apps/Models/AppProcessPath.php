<?php

namespace App\Modules\apps\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class AppProcessPath extends Model {

    protected $table = 'app_process_path';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'process_type',
        'status_to',
        'status_from',
        'desk_to',
        'FILE_ATTACHMENT',
        'email',
        'desk_from',
        'color',
        'created_at',
        'updated_at',
        'service_id'
    ];

    function insert_method($data) {
        DB::table($this->table)
            ->insert($data);
    }

    function update_method($_id, $data) {
        DB::table($this->table)
            ->where('process_id', $_id)
            ->update($data);
    }

    /**************************************End of Model Class*******************************************/
}
