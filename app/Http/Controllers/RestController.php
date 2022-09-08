<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\Rest;
use App\Models\Attendance;

class RestController extends Controller
{
    //休憩開始　　　　の処理
    public function startRest()
    {
        $id = Auth::id();
        $dt = new Carbon();
        $date = $dt->toDateString();
        $time = $dt->toTimeString();


        //Attendanceテーブルから、・ログイン中のuser_id・$dateの日付が合致する一番最初のレコードを取得
        $attendance = Rest::with('attendances')->get();
        dd('$attendance');
        

        
        

        if (!empty($attendance->user_id) && (!empty($attendance->date))) {
            return redirect()->back()->with('result', '勤務が開始されていません');
        }
        
    
        //休憩を終了せずに開始を押したらエラーを返す
        

        //Restsに登録
        Rest::create([
            'date' => $date,
            'restIn' => $time,
        ]);

        return redirect('/index')->with('result', '休憩を開始しました');
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
        $attendance_id = $attendance->id;


        //Restテーブルから、・attendance_id、・$dateの日付が合致する, resuOutがnullの一番最初のレコードを取得
        $rest = Rest::where('attendance_id', $attendance_id)->whereNull('restOut')->where('date', $date)->first();


        //上記があればrestOutを更新する。　無ければエラーを返す
        if (!empty($rest)) {
            $rest->update(['restOut' => $time]);
            return redirect('/index')->with('result', '休憩を終了しました');
        } else {
            return redirect()->back()->with('result', '休憩が開始されていないか、勤務が終了されています');
        }
    }
}

/**
 * やること①
 * 一番最初、２２行目のidがない状態だとエラーとなってしまうため、idがなければ「勤務を開始してください」のエラー文章がリダイレクトで表示されるようにする
 * 
 * やること②
 * user_idが１ではない人が休憩を開始できない。
 * 
 * やること③
 */