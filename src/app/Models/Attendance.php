<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'workdate_id',
        'work_start',
        'work_end',
        'work_time',
        'total_rest',
        'total_work',
    ];

    // protected $dates = ['work_date', 'work_start', 'work_end'];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workdate()
    {
        return $this->belongsTo(Workdate::class);
    }

    public function rest()
    {
        return $this->hasMany(Rest::class);
    }

    // public function getDateGroup()
    // {
    // return DB::table('attendances')
    // ->leftJoin('users', 'attendances.user_id', '=', 'users.id')
    // ->select(
    // 'attendances.id',
    // 'attendances.user_id',
    // 'attendances.work_date',
    // 'attendances.work_start',
    // 'attendances.work_end',
    // 'attendances.work_time',
    // 'attendances.total_rest',
    // 'attendances.total_work',
    // 'users.name AS user_name' // ユーザー名を取得
    // )
    // ->groupBy(
    // 'attendances.work_date',
    // 'attendances.id',
    // 'attendances.user_id',
    // 'attendances.work_start',
    // 'attendances.work_end',
    // 'attendances.work_time',
    // 'attendances.total_rest',
    // 'attendances.total_work',
    // 'users.name' // ユーザー名をグループ化
    // )
    // ->orderBy('attendances.work_date')
    // ->get();
    // }
}
