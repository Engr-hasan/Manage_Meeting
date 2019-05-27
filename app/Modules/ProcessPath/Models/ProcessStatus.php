<?php namespace App\Modules\ProcessPath\Models;

use Illuminate\Database\Eloquent\Model;
class ProcessStatus extends Model {

    protected $table = 'process_status';
    protected $fillable = array(
        'id',
        'status_name',
        'process_type_id',
        'color',
        'status',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    );


}