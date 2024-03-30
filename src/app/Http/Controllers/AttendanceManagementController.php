<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceManagementController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        return view('index', compact('user'));

        // if ($request->has('logout')) {
        // return redirect('/login');
        // }
    }

    // 勤務
    public function work_start(Request $request)
    {
        $user = Auth::user();

        $workInfo = Attendance::create([
            'user_id' => $user->id,
            'work_start' => Carbon::now(),
        ]);

        return view('index', compact('user'));
    }

    public function work_end(Request $request)
    {
        $user = Auth::user();
        $workInfo = Attendance::where('user_id', $user->id)->latest()->first();

        $workInfo->update([
            'work_end' => Carbon::now()
        ]);

        return view('index', compact('user'));
    }

    // 休憩
    public function break_start(Request $request)
    {
        $user = Auth::user();

        $breakInfo = Attendance::create([
            'user_id' => $user->id,
            'break_start' => Carbon::now(),
        ]);
        return view('index', compact('user'));
    }

    public function break_end(Request $request)
    {
        $user = Auth::user();
        $breakInfo = Attendance::where('user_id', $user->id)->latest()->first();

        $breakInfo->update([
            'break_end' => Carbon::now()
        ]);
        return view('index', compact('user'));
    }

    // 
    public function search($id)
    {
        $userInfos = Attendance::with('user')->paginate(5);

        return view('attendance', compact('userInfos'));
    }
}

// $work_start = Attendance::where('work_start')->latest()->first();
// $work_end = Attendance::where('work_end')->latest()->first();

// $work_time = $work_start->diffInSeconds($work_end)
// Attendance::create([
// 'work_time' => $work_time,
// ]);

// $hours = floor($diffInSeconds / 3600);
// $minutes = floor(($diffInSeconds % 3600) / 60);
// $seconds = $diffInSeconds % 60;

// echo "開始日時から終了日時までの時間は、" . $hours . "時間" . $minutes . "分" . $seconds . "秒です。";
