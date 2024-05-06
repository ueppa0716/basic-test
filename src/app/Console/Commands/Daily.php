<?php

namespace App\Console\Commands;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Rest;
use App\Models\Workdate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\AttendanceManagementController;

class Daily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:Daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '日を跨いだ時点で翌日の出勤操作に切り替える';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Request $request)
    {

        $today = Carbon::today()->format('Y-m-d');
        $breakInfos = Rest::where('break_end', null)->get();
        $workInfos = Attendance::where('work_end', null)->get();

        if ($breakInfos->isNotEmpty()) {
            foreach ($breakInfos as $breakInfo) {
                $breakStartDay = Carbon::parse($breakInfo->break_start)->format('Y-m-d');
                $breakStart = Carbon::parse($breakInfo->break_start);
                $breakInfo->update(['break_end' => Carbon::createFromTimeString('23:59:59')->setDate($breakStart->year, $breakStart->month, $breakStart->day)]);
                $breakEnd = Carbon::parse($breakInfo->break_end);
                $diff = $breakEnd->diff($breakStart);
                $breakTime = $diff->format('%H:%I:%S');
                $breakInfo->update([
                    'break_time' => $breakTime
                ]);
                $breakStartDay = Carbon::parse($breakInfo->break_start);
                $attendance = Attendance::where('id', $breakInfo->attendance_id)->first();
                $totalRest = Rest::where('attendance_id', $attendance->id)
                    ->whereDate('break_start', $breakStartDay)
                    ->sum('break_time');
                $attendance->update([
                    'total_rest' => $totalRest
                ]);
            }
        }

        if ($workInfos->isNotEmpty()) {
            foreach ($workInfos as $workInfo) {
                $workStart = Carbon::parse($workInfo->work_start);
                $workInfo->update(['work_end' => Carbon::createFromTimeString('23:59:59')->setDate($workStart->year, $workStart->month, $workStart->day)]);
                $workEnd = Carbon::parse($workInfo->work_end);
                $diffWork = $workEnd->diff($workStart);
                $workTime = $diffWork->format('%H:%I:%S');
                $workInfo->update([
                    'work_time' => $workTime,
                ]);
                if (!empty($workInfo->total_rest)) {
                    $totalRest = Carbon::parse($workInfo->total_rest);
                } else {
                    $totalRest = Carbon::createFromTimeString('00:00:00');
                }
                $workTime = Carbon::parse($workInfo->work_time);
                $diffTotal = $workTime->diff($totalRest);
                $totalWork = $diffTotal->format('%H:%I:%S');
                $workInfo->update([
                    'total_work' => $totalWork
                ]);
            }
        }

        return redirect()->back()->with('status', "日付が変わりました。本日は" . $today . 'です。');


        // $attendance = Attendance::where('user_id', $user->id)->latest()->first();
        // $breakInfo = Rest::where('attendance_id', $attendance->id)->latest()->first();

        // $today = Carbon::today()->format('Y-m-d');
        // $breakStartDay = Carbon::parse($breakInfo->break_start)->format('Y-m-d');
        // $workStartDay = Carbon::parse($attendance->work_start)->format('Y-m-d');
        // $breakStart = Carbon::parse($breakInfo->break_start);
        // $breakEnd = Carbon::now();
        // $diff = $breakEnd->diff($breakStart);

        // // 休憩中に日付をまたいだ場合
        // if (empty($breakInfo->break_end) && ($breakStartDay != $today)) {
        //     // app('App\Http\Controllers\AttendanceManagementController')->break_end();
        //     $breakInfo->update(['break_end' => Carbon::createFromTimeString('23:59:59')->setDate($breakStart->year, $breakStart->month, $breakStart->day)]);
        //     $breakTime = $diff->format('%H:%I:%S');
        //     $breakInfo->update([
        //         'break_time' => $breakTime
        //     ]);
        //     $breakStartDay = Carbon::parse($breakInfo->break_start);
        //     $totalRest = Rest::where('attendance_id', $attendance->id)
        //         ->whereDate('break_start', $breakStartDay)
        //         ->sum('break_time');
        //     $attendance->update([
        //         'total_rest' => $totalRest
        //     ]);
        //     // app('App\Http\Controllers\AttendanceManagementController')->work_end();
        //     $workInfo = Attendance::where('user_id', $user->id)->latest()->first();
        //     $workInfo->update(['work_end' => Carbon::createFromTimeString('23:59:59')]);
        //     $workStart = Carbon::parse($workInfo->work_start);
        //     $workEnd = Carbon::parse($workInfo->work_end);
        //     $diffWork = $workEnd->diff($workStart);
        //     $workTime = $diffWork->format('%H:%I:%S');
        //     $workInfo->update([
        //         'work_time' => $workTime,
        //     ]);
        //     if (!empty($workInfo->total_rest)) {
        //         $totalRest = Carbon::parse($workInfo->total_rest);
        //     } else {
        //         $totalRest = Carbon::createFromTimeString('00:00:00');
        //     }
        //     $workTime = Carbon::parse($workInfo->work_time);
        //     $diffTotal = $workTime->diff($totalRest);
        //     $totalWork = $diffTotal->format('%H:%I:%S');
        //     $workInfo->update([
        //         'total_work' => $totalWork
        //     ]);
        //     // app('App\Http\Controllers\AttendanceManagementController')->work_start();
        //     $workDate = Workdate::firstOrCreate(
        //         ['work_date' => Carbon::today()]
        //     );
        //     $workInfo = Attendance::create([
        //         'user_id' => $user->id,
        //         'workdate_id' => $workDate->id,
        //         'work_start' => Carbon::now(),
        //     ]);
        //     // app('App\Http\Controllers\AttendanceManagementController')->break_start();
        //     $attendance = Attendance::where('user_id', $user->id)->latest()->first();
        //     $breakInfo = Rest::create([
        //         'attendance_id' => $attendance->id,
        //         'break_start' => Carbon::now(),
        //     ]);
        // } elseif (empty($attendance->work_end) && ($workStartDay != $today)) {
        //     // app('App\Http\Controllers\AttendanceManagementController')->work_end();
        //     $workInfo = Attendance::where('user_id', $user->id)->latest()->first();
        //     $workInfo->update(['work_end' => Carbon::createFromTimeString('23:59:59')]);
        //     $workStart = Carbon::parse($workInfo->work_start);
        //     $workEnd = Carbon::parse($workInfo->work_end);
        //     $diffWork = $workEnd->diff($workStart);
        //     $workTime = $diffWork->format('%H:%I:%S');
        //     $workInfo->update([
        //         'work_time' => $workTime,
        //     ]);
        //     if (!empty($workInfo->total_rest)) {
        //         $totalRest = Carbon::parse($workInfo->total_rest);
        //     } else {
        //         $totalRest = Carbon::createFromTimeString('00:00:00');
        //     }
        //     $workTime = Carbon::parse($workInfo->work_time);
        //     $diffTotal = $workTime->diff($totalRest);
        //     $totalWork = $diffTotal->format('%H:%I:%S');
        //     $workInfo->update([
        //         'total_work' => $totalWork
        //     ]);
        //     // app('App\Http\Controllers\AttendanceManagementController')->work_start();
        //     $workDate = Workdate::firstOrCreate(
        //         ['work_date' => Carbon::today()]
        //     );
        //     $workInfo = Attendance::create([
        //         'user_id' => $user->id,
        //         'workdate_id' => $workDate->id,
        //         'work_start' => Carbon::now(),
        //     ]);
        // }
    }
}
