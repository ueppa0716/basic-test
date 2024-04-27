<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_date',
    ];

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }
}
