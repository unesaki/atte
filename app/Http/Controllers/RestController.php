<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\Rest;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;

class RestController extends Controller
{
    //休憩開始　　　　の処理
    public function startRest()
    {
        $id = Auth::id();
        $dt = new Carbon();
        $date = $dt->toDateString();
        $time = $dt->toTimeString();


        //Attendanceテーブルから、・ログイン中のuser_id、・$dateの日付が合致する一番最初のレコードを取得
        $attendance = Attendance::where('user_id', $id)->where('date', $date)->first();

        if (empty($attendance->id)) {
            return redirect()->back()->with('result', '勤務が開始されていません');
        } else {
            $attendance_id = $attendance->id;
        }

        $noPunchIn =
        Attendance::where('user_id', $id)->where('date', $date)->whereNull('punchIn')->first();
        

        $oldRest = Rest::where('attendance_id', $attendance_id)->latest()->first();
        $oldRestDay = "";

        //勤務が開始されていないとエラーを返す
        if (!empty($noPunchIn)) {
            return redirect()->back()->with('result', '勤務が開始されていません');
        }
        
        $newRest = Carbon::today();
        if ($oldRest) {
            $oldRestDate = new Carbon($oldRest->restIn);
            $oldRestDay = $oldRestDate->startOfDay();
        }

        //休憩を終了せずに開始を押したらエラーを返す
        if (($oldRestDay == $newRest) && (empty($oldRest->restOut))) {
            return redirect()->back()->with('result', '既に休憩を開始しています');
        }
        
        $rest = Attendance::where('user_id', $id)->whereNull('punchOut')->where('date', $date)->first();
        if (empty($rest)) {
            return redirect()->back()->with('result', '勤務が終了しています');
        }

        //Restsに登録
        Rest::create([
            'attendance_id' => $attendance_id,
            'date' => $date,
            'restIn' => $time,
        ]);

        return redirect('/')->with('result', '休憩を開始しました');
    }
        
    //休憩終了　　　　　の処理
    public function endRest()
    {
        $id = Auth::id();
        $dt = new Carbon();
        $date = $dt->toDateString();
        $time = $dt->toTimeString();


        //Attendanceテーブルから、・ログイン中のuser_id、・$dateの日付が合致する一番最初のレコードを取得
        $attendance = Attendance::where('user_id', $id)->where('date', $date)->first();

        if (empty($attendance->id)) {
            return redirect()->back()->with('result', '勤務が開始されていません');
        } else {
            $attendance_id = $attendance->id;
        }


        //Restテーブルから、・attendance_id、・$dateの日付が合致する, resuOutがnullの一番最初のレコードを取得
        $rest = Rest::where('attendance_id', $attendance_id)->whereNull('restOut')->where('date', $date)->first();


        //上記があればrestOutを更新する。　無ければエラーを返す
        if (!empty($rest)) {
            $rest->update(['restOut' => $time]);
            return redirect('/')->with('result', '休憩を終了しました');
        } else {
            return redirect()->back()->with('result', '休憩が開始されていないか、勤務が終了されています');
        }
    }

    
}

/**
 * 
 * やること③
 */