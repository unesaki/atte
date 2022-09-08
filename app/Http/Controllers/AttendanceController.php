<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Rest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index()
    {
        return view('index');
    }

    //勤務開始の処理
    public function startAttendance()
    {
        $id = Auth::id();
        $dt = new Carbon();
        $date = $dt->toDateString();
        $time = $dt->toTimeString();

        //Attendanceテーブルから、・ログイン中のuser_id・$dateの日付が合致する一番最初のレコードを取得
        $attendance = Attendance::where('user_id', $id)->where('date', $date)->first();
        

        $oldAttendance = Attendance::where('user_id', $id)->latest()->first();
        $oldAttendanceDay = "";
        $newAttendance = Carbon::today();


        //user_idとdateが存在していれば(同日に一回出勤していれば)、エラーを返す
        if (!empty($attendance->user_id) && (!empty($attendance->date))) {
            return redirect()->back()->with('result', '本日はすでに勤務を開始しています');
        }


        if ($oldAttendance) {
            $oldAttendanceDate = new Carbon($oldAttendance->punchIn);
            $oldAttendanceDay = $oldAttendanceDate->startOfDay();
        }

        //同日かつ退勤を押していない状態で出勤が押されてもエラーを返す
        if (($oldAttendanceDay == $newAttendance) && (empty($oldAttendance->punchOut))) {
            return redirect()->back()->with('result', 'すでに勤務を開始しています');
        }

        //attendanceテーブルに登録
        Attendance::create([
            'user_id' => $id,
            'date' => $date,
            'punchIn' => $time,
        ]);

        return redirect('/index')->with('result', '勤務を開始しました');
    }

    //退勤の処理
    public function endAttendance()
    {
        $id = Auth::id();
        $dt = new Carbon();
        $date = $dt->toDateString();
        $time = $dt->toTimeString();


        //Attendanceテーブルから、・ログイン中のuser_id・$dateの日付が合致するpunchOutがnullの一番最初のレコードを取得
        $attendance = Attendance::where('user_id', $id)->whereNull('punchOut')->where('date', $date)->first();


        //上記が存在すればpunchOutを更新、それ以外ではエラー文を返す
        if (!empty($attendance)) {
            $attendance->update(['punchOut' => $time]);
            return redirect('/index')->with('result', '勤務を終了しました');
        } else {
            return redirect()->back()->with('result', '勤務が開始されていないか、勤務が終了されています');
        }
    }

    public function getAttendance(Request $request)
    {

        $startTimes = DB::table('attendances')->select('punchIn', 'punchOut')->get();
        $restTimes = DB::table('rests')->select('restIn', 'restOut')->get();

        $restTotals = array();
        foreach ($restTimes as $restTime) {
            array_push(
                $restTotals,
                array(
                    'restIn' => $restTime->restIn,
                    'restOut' => $restTime->restOut,
                    'restTotal' => strtotime($restTime->restOut) - strtotime($restTime->restIn)
                )
            );
        }
        //$restTotals = DB::table('rests')->select('restOut - restIn')->get();

        //$rest_start = DB::table('rests')->select('restIn')->first();
        //$rest_end = DB::table('rests')->select('restOut')->first();

        //$rest_totals = $rest_end - $rest_start;


        return view('attendance', compact('startTimes', 'restTotals'), compact('restTimes'), compact('restTotals'));
    }

    
    
}
