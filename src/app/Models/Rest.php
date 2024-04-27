<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rest extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'break_start',
        'break_end',
        'break_time',
    ];

    public $timestamps = true;

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}
