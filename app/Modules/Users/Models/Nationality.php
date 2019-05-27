<?php

namespace App\Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Nationality extends Model {

    protected $table = 'nationality';
    protected $fillable = array(
        'id',
        'nationality'
    );

    /************************ Users Model Class ends here ****************************/
}
