<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rest extends Model
{
    use HasFactory;

    protected $fillable = [
        'date', 'restIn', 'restOut',
    ];

    public function attendances() {
        return $this->hasMany('App\Models\Attendance');
    }

}


