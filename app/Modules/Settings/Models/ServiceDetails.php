<?php

namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class ServiceDetails extends Model {

    protected $table = 'service_details';
    protected $fillable = array(
      'title','description','login_page_details','process_type_id', 'terms_and_conditions', 'status','is_archive','created_by','created_at','updated_by','updated_at'
    );

}