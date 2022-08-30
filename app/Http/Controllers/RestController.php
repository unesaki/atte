<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\Rest;
use App\Models\Attendance;

class RestController extends Controller
{
    public function startRest()
    {
        $id = Auth::id();
        $dt = new Carbon();
        $date = $dt->toDateString();
        $time = $dt->toTimeString();

        $attendance = Attendance::where('user_id', $id)->where('date', $date)->first();
        $attendance_id = $attendance->id;

        Rest::create([
            'attendance_id' => $attendance_id,
            'restIn' => $time,
        ]);

        

        return redirect('/index')->with('result', '休憩を開始しました');
    }

    public function endRest()
    {
        $id = Auth::id();
        $dt = new Carbon();
        $date = $dt->toDateString();
        $time = $dt->toTimeString();

        $attendance = Attendance::where('user_id', $id)->where('date', $date)->first();

        $rest = $attendance->rests->whereNull("restOut")->first();
        
        Rest::where('id', $rest->id)->update(['restOut' => $time]);

        return redirect('/index')->with('result', '休憩を終了しました');
    }
}
