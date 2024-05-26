<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Rest;
use App\Models\Workdate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceManagementController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $workInfo = Attendance::where('user_id', $user->id)->latest()->first();
        $breakInfo = null;

        if ($workInfo) {
            $breakInfo = Rest::where('attendance_id', $workInfo->id)->latest()->first();
        }

        return view('index', compact('user', 'workInfo', 'breakInfo'));
    }



    // 勤務開始
    public function work_start(Request $request)
    {
        $user = Auth::user();

        // 今日の勤務日を取得するか新しく作成する
        $workDate = Workdate::firstOrCreate(
            ['work_date' => Carbon::today()]
        );

        // ユーザーの直近の勤務情報を取得する
        $workInfo = Attendance::where('user_id', $user->id)->latest()->first();

        if ($workInfo && ($workInfo->work_start) && empty($workInfo->work_end)) {
            return redirect()->back()->with('status', '今日は既に仕事を開始しています。');
        } else {
            $workInfo = Attendance::create([
                'user_id' => $user->id,
                'workdate_id' => $workDate->id,
                'work_start' => Carbon::now(),
            ]);
        }

        // // ユーザーの今日の勤務情報がない場合は作成する
        // if (!$workInfo) {
        //     $workInfo = Attendance::create([
        //         'user_id' => $user->id,
        //         'workdate_id' => $workDate->id,
        //         'work_start' => Carbon::now(),
        //     ]);
        // } elseif (($workInfo->work_start) && (!$workInfo->work_end)) {
        //     return redirect()->back()->with('status', '今日は既に仕事を開始しています。');
        //     // } elseif (($workInfo->work_start) && ($workInfo->work_end)) {
        //     // return redirect()->back()->with('status', '今日は既に勤務終了しています。');
        // }

        return redirect()->back();
    }



    // 勤務終了
    public function work_end(Request $request)
    {
        $user = Auth::user();
        $workInfo = Attendance::where('user_id', $user->id)->latest()->first();

        if ($workInfo->work_end) {
            return redirect()->back()->with('status', '今日は既に勤務終了しています。');
        }

        // 休憩開始しておりかつ休憩終了していないレコードを取得
        $breakInfo = Rest::where('attendance_id', $workInfo->id)
            ->whereNotNull('break_start')
            ->whereNull('break_end')
            ->first();

        // $today = Carbon::today()->format('Y-m-d');
        // $breakStartDay = Carbon::parse($breakInfo->break_start)->format('Y-m-d');

        if (!empty($breakInfo)) {
            return redirect()->back()->with('status', '先に休憩終了してください。');
        }

        // if (!empty($breakInfo) && ($breakStartDay == $today)) {
        // return redirect()->back()->with('status', '先に休憩終了してください。');
        // }

        // $workStart = Carbon::parse($workInfo->work_start);
        // $workStartDay = Workdate::where('id', $workInfo->workdate_id)->latest()->first();
        // $today = Carbon::today();

        $workEnd = Carbon::now();
        $workInfo->update(['work_end' => $workEnd]);
        $workEnd = Carbon::parse($workInfo->work_end);
        $diffWork = $workEnd->diff($workInfo->work_start);
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

        return redirect()->back();

        // // 勤務開始の日付と今日の日付が異なる場合
        // if (empty($workInfo->work_end) && ($today != $workStartDay->work_date)) {
        //     // 前日のWorkdateに終業時間を設定
        //     $workInfo->update(['work_end' => Carbon::createFromTimeString('23:59:59')]);
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

        // // 新しいWorkdateを作成
        // $workDate = Workdate::firstOrCreate(['work_date' => $today]);

        // // 新しいWorkdateでAttendanceを作成
        // $workInfo = Attendance::create([
        //     'user_id' => $user->id,
        //     'workdate_id' => $workDate->id,
        //     'work_start' => Carbon::createFromTimeString('00:00:00'),
        //     // 'work_end' => Carbon::now()
        // ]);

        //     $workInfo = Attendance::where('user_id', $user->id)->latest()->first();
        //     $workStart = Carbon::parse($workInfo->work_start);
        //     $workEnd = Carbon::now();

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
        // }
        // // 勤務開始の日付と今日の日付が同じ場合
        // elseif (empty($workInfo->work_end) && ($today == $workStartDay->work_date)) {
        //     // 当日のWorkdateでAttendanceを更新
        //     $workEnd = Carbon::now();
        //     $workInfo->update(['work_end' => $workEnd]);

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
        // }

        // return redirect()->back();
    }



    // 休憩開始
    public function break_start(Request $request)
    {
        $user = Auth::user();
        $attendance = Attendance::where('user_id', $user->id)->latest()->first();
        $breakInfo = Rest::where('attendance_id', $attendance->id)
            ->whereNotNull('break_start')
            ->whereNull('break_end')
            ->first();

        $workStartDay = Workdate::where('id', $attendance->workdate_id)->latest()->first();
        $today = Carbon::today();


        if (($today != $workStartDay->work_date)) {
            return redirect()->back()->with('status', '今日の勤怠入力がありません。');
        }

        if ($breakInfo) {
            return redirect()->back()->with('status', '休憩中です。');
        }

        // if ($attendance->work_end) {
        // return redirect()->back()->with('status', '今日は既に勤務終了しています。');
        // }

        if (empty($attendance->work_start)) {
            return redirect()->back()->with('status', '今日は仕事を開始していません。');
        }

        $breakInfo = Rest::create([
            'attendance_id' => $attendance->id,
            'break_start' => Carbon::now(),
        ]);

        return redirect()->back();
    }



    // 休憩終了
    public function break_end(Request $request)
    {
        $user = Auth::user();
        $attendance = Attendance::where('user_id', $user->id)->latest()->first();
        $breakInfo = Rest::where('attendance_id', $attendance->id)->latest()->first();

        if ($breakInfo->break_end) {
            return redirect()->back()->with('status', '休憩中ではありません。');
        }

        $breakEnd = Carbon::now();
        $breakInfo->update(['break_end' => $breakEnd]);
        $diff = $breakEnd->diff($breakInfo->break_start);
        $breakTime = $diff->format('%H:%I:%S');
        $breakInfo->update([
            'break_time' => $breakTime
        ]);
        $totalRest = Rest::where('attendance_id', $attendance->id)
            ->whereDate('break_start', Carbon::today())
            ->sum('break_time');
        $attendance->update([
            'total_rest' => $totalRest
        ]);

        return redirect()->back();
    }

    // $breakStart = Carbon::parse($breakInfo->break_start);
    // $breakEnd = Carbon::now();
    // $diff = $breakEnd->diff($breakStart);
    // $today = Carbon::today()->format('Y-m-d');
    // $breakStartDay = Carbon::parse($breakInfo->break_start)->format('Y-m-d');

    // // 休憩開始の日付と今日の日付が異なる場合
    // if (empty($breakInfo->break_end) && ($breakStartDay != $today)) {
    //     // 前日のWorkdateに終業時間を設定
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

    // $workInfo->update(['work_end' => Carbon::createFromTimeString('23:59:59')]);
    // $workStart = Carbon::parse($workInfo->work_start);
    // $workEnd = Carbon::parse($workInfo->work_end);

    // $diffWork = $workEnd->diff($workStart);
    // $workTime = $diffWork->format('%H:%I:%S');

    // $workInfo->update([
    // 'work_time' => $workTime,
    // ]);

    // if (!empty($workInfo->total_rest)) {
    // $totalRest = Carbon::parse($workInfo->total_rest);
    // } else {
    // $totalRest = Carbon::createFromTimeString('00:00:00');
    // }

    // $workTime = Carbon::parse($workInfo->work_time);

    // $diffTotal = $workTime->diff($totalRest);
    // $totalWork = $diffTotal->format('%H:%I:%S');

    // $workInfo->update([
    //     'total_work' => $totalWork
    // ]);

    // // 新しいWorkdateを作成
    // $workDate = Workdate::firstOrCreate(['work_date' => $today]);

    // // 新しいWorkdateで00:00:00から勤務開始状態に
    // $workInfo = Attendance::create([
    //     'user_id' => $user->id,
    //     'workdate_id' => $workDate->id,
    //     'work_start' => Carbon::createFromTimeString('00:00:00'),
    // ]);

    // $attendance =
    //     Attendance::where('user_id', $user->id)->latest()->first();

    // // 新しいWorkdateで00:00:00から現在時刻まで休憩カウント
    // $breakInfo = Rest::create([
    //     'attendance_id' => $attendance->id,
    //     'break_start' =>
    //     Carbon::createFromTimeString('00:00:00'),
    //     'break_end' => Carbon::now(),
    // ]);

    // $breakTime = $diff->format('%H:%I:%S');

    // $breakInfo->update([
    //     'break_time' => $breakTime
    // ]);

    // $breakStartDay = Carbon::parse($breakInfo->break_start);
    // $totalRest = Rest::where('attendance_id', $attendance->id)
    //     ->whereDate('break_start', $breakStartDay)
    //     ->sum('break_time');

    // $attendance->update([
    //     'total_rest' => $totalRest
    // ]);

    // return redirect()->back();
    // }
    //     // 休憩開始の日付と今日の日付が同じ場合
    //     elseif (empty($breakInfo->break_end) && ($breakStartDay == $today)) {
    //         $breakEnd = Carbon::now();
    //         $breakInfo->update(['break_end' => $breakEnd]);
    //     }

    //     $breakTime = $diff->format('%H:%I:%S');

    //     $breakInfo->update([
    //         'break_time' => $breakTime
    //     ]);

    //     $totalRest = Rest::where('attendance_id', $attendance->id)
    //         ->whereDate('break_start', Carbon::today())
    //         ->sum('break_time');

    //     $attendance->update([
    //         'total_rest' => $totalRest
    //     ]);

    //     return redirect()->back();
    // }



    // 日付一覧表示
    public function search(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today()->format('Y-m-d');
        $date = $request->input('date');

        // フォームから送信された日付がない場合は、今日の日付を使用
        if (!$date) {
            $date = $today;
        }

        // "<"をクリックした場合、前日の日付を取得
        if ($request->has('prev')) {
            $date = Carbon::parse($date)->subDay()->format('Y-m-d');
        }

        // ">"をクリックした場合、次の日の日付を取得
        if ($request->has('next')) {
            $date = Carbon::parse($date)->addDay()->format('Y-m-d');
        }

        // 勤怠情報を取得
        $workDates = Workdate::whereDate('work_date', '=', $date)->get();

        // 勤怠情報が見つからない場合は、リダイレクトしてメッセージを表示
        if ($workDates->isEmpty()) {
            return view('attendance', compact('date'));
            // return redirect('/attendance')->with('status', $date . 'に勤怠実績はありません。');
        }

        // 勤怠情報が見つかった場合は、その日付の最初の勤怠情報を使用
        $workDate = $workDates->first();
        $userInfos = Attendance::with(['workdate', 'user'])
            ->where('workdate_id', $workDate->id)
            ->paginate(5);

        // ビューに日付と勤怠情報を渡して表示
        return view('attendance', compact('userInfos', 'workDate', 'date'));
    }


    // $userInfos = Workdate::with(['attendance.user'])
    // ->paginate(1);

    // $userInfos = Attendance::with(['workdate', 'user'])
    // ->orderBy('workdate_id', 'asc')
    // ->paginate(5, ['*'], 'page');


    // $userInfos = Attendance::with(['workdate', 'user'])
    // ->whereIn('workdate_id', function ($query) {
    // $query->select('workdate_id')
    // ->from('attendances')
    // ->groupBy('workdate_id');
    // })
    // ->paginate(5);

    // $workDates = Workdate::with(['attendance.user'])
    // ->paginate(1);

    // $userInfos = $attendances->merge($workDates);

    // $workDate->setRelation('attendance', $workDate->attendance()->paginate(5));

    // return view('attendance', compact('userInfos'));


    public function user(Request $request)
    {
        $users = User::paginate(5);

        return view('user', compact('users'));
    }
}
