<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['user_id', 'rest_id', 'date', 'punchIn', 'punchOut'];

    public function users() {
        return $this->belongsTo('App\Models\User', "user_id");
    }

    public function rest() {
        return $this->belongsTo('App\Models\Rest', "rest_id");
    }
}
