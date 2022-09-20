<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Rest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        if (empty(Auth::user()->name)) {
            return view('auth/login');
        } else {
        return view('index');
        }
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

        return redirect('/')->with('result', '勤務を開始しました');
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

        $attendanceId = Attendance::where('user_id', $id)->where('date', $date)->first();

        if (empty($attendance->id)) {
            return redirect()->back()->with('result', '勤務が開始されていません');
        } else {
            $attendance_id = $attendanceId->id;
        }

        $rest_id = Rest::where('rest_id');


        $oldRest = Rest::where('attendance_id', $attendance_id)->latest()->first();
        $oldRestDay = "";

        $newRest = Carbon::today();
        if ($oldRest) {
            $oldRestDate = new Carbon($oldRest->restIn);
            $oldRestDay = $oldRestDate->startOfDay();
        }

        //休憩を終了せずに開始を押したらエラーを返す
        if (($oldRestDay == $newRest) && (empty($oldRest->restOut))) {
            return redirect()->back()->with('result', '休憩が終了されていません');
        }

        //上記が存在すればpunchOutを更新、それ以外ではエラー文を返す
        if (!empty($attendance)) {
            $attendance->update(['punchOut' => $time, 'rest_id' => $rest_id]);
            return redirect('/')->with('result', '勤務を終了しました');
        } else {
            return redirect()->back()->with('result', '勤務が開始されていないか、勤務が終了されています');
        }
    }

    public function getAttendance(Request $request, $num)
    {
        $id = Auth::id();
        //日付の表示
        $dt = new Carbon();
        $date = $dt->toDateString();

        $day = $num;
        $date = date("Y-m-d", strtotime($date .' ' . $day . 'day'));
        
        

        //Attendanceテーブルから、$dateの日付が合致するレコードを全て取得
        $data = Attendance::where('date', $date)->get();
        
        $array = array();
        
        foreach($data as $element) {
            $restData = Rest::where('attendance_id', $element->id)->get();
            $restTotal = 0;
            foreach($restData as $restElement) {
                $restTotal += strtotime($restElement->restOut) - strtotime($restElement->restIn);
            }
            $restSec = $restTotal % 60;
            $restMin = ($restTotal - $restSec) / 60;
            $restHouAbout = $restMin % 60;
            $restHou = ($restMin - $restHouAbout) / 60;
            $restTotal = (sprintf("%02d", $restHou).":".sprintf("%02d", $restHouAbout).":".sprintf("%02d", $restSec));
            
            $punchData = Attendance::where('id', $element->id)->get();
            $punchTotal = 0;
            foreach ($punchData as $punchElement) {
                $punchTotal = strtotime($punchElement->punchOut) - strtotime($punchElement->punchIn);
            }
            
            $punchSec = $punchTotal % 60;
            $punchMin = ($punchTotal - $punchSec) / 60;
            $punchHouAbout = $punchMin % 60;
            $punchHou = ($punchMin - $punchHouAbout) / 60;
            $punchTotalNoRest = (sprintf("%02d", $punchHou) . ":" . sprintf("%02d", $punchHouAbout) . ":" . sprintf("%02d", $punchSec));
            $punchTotalRest = strtotime($punchTotalNoRest) - strtotime($restTotal);
            $punchSec1 = $punchTotalRest % 60;
            $punchMin1 = ($punchTotalRest - $punchSec1) / 60;
            $punchHouAbout1 = $punchMin1 % 60;
            $punchHou1 = ($punchMin1 - $punchHouAbout) / 60;
            $punchTotal = (sprintf("%02d", $punchHou1) . ":" . sprintf("%02d", $punchHouAbout1) . ":" . sprintf("%02d", $punchSec1));
            
            $user = User::find($element->user_id);

            
            array_push(
                $array,
                array(
                    'name' => $user->name,
                    'punchIn' => $element->punchIn,
                    'punchOut' => $element->punchOut,
                    'restTotal' => $restTotal,
                    'punchTotal' => $punchTotal,
                    )
                );
            }
            $coll = collect($array);
            $pageData = $this->paginate($coll, 5, null, ['path'=>'/attendance/'. $num]);

            
                
            return view('attendance')->with([
                "date" => $date,
                "data" => $data,
                "array" => $array,
                "pageData" => $pageData,
                "num" => $num,
            ]);
        }
        private function paginate($items, $perPage = 5, $page = null, $options = []) {
            $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
        }
}
