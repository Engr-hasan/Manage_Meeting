<?php
namespace App\Modules\Support\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackTopics extends Model {
    protected $table = "feedback_topics";
    protected $fillable = array(
        'id',
        'name'
    );
}