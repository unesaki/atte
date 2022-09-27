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
    public function startRest()
    {
        $id = Auth::id();
        $dt = new Carbon();
        $date = $dt->toDateString();
        $time = $dt->toTimeString();

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

        if (!empty($noPunchIn)) {
            return redirect()->back()->with('result', '勤務が開始されていません');
        }
        
        $newRest = Carbon::today();
        if ($oldRest) {
            $oldRestDate = new Carbon($oldRest->restIn);
            $oldRestDay = $oldRestDate->startOfDay();
        }

        if (($oldRestDay == $newRest) && (empty($oldRest->restOut))) {
            return redirect()->back()->with('result', '既に休憩を開始しています');
        }
        
        $rest = Attendance::where('user_id', $id)->whereNull('punchOut')->where('date', $date)->first();
        if (empty($rest)) {
            return redirect()->back()->with('result', '勤務が終了しています');
        }

        Rest::create([
            'attendance_id' => $attendance_id,
            'date' => $date,
            'restIn' => $time,
        ]);

        return redirect('/')->with('result', '休憩を開始しました');
    }
        

    public function endRest()
    {
        $id = Auth::id();
        $dt = new Carbon();
        $date = $dt->toDateString();
        $time = $dt->toTimeString();
        $attendance = Attendance::where('user_id', $id)->where('date', $date)->first();

        if (empty($attendance->id)) {
            return redirect()->back()->with('result', '勤務が開始されていません');
        } else {
            $attendance_id = $attendance->id;
        }

        $rest = Rest::where('attendance_id', $attendance_id)->whereNull('restOut')->where('date', $date)->first();

        if (!empty($rest)) {
            $rest->update(['restOut' => $time]);
            return redirect('/')->with('result', '休憩を終了しました');
        } else {
            return redirect()->back()->with('result', '休憩が開始されていないか、勤務が終了されています');
        }
    }    
}
