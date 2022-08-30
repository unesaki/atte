<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Rest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;


class AttendanceController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function startAttendance () 
    {
        $id = Auth::id();

        $dt = new Carbon();
        $date = $dt->toDateString();
        $time = $dt->toTimeString();
        
        
        Attendance::create([
            'user_id' => $id,
            'date' => $date,
            'punchIn' => $time,
        ]);


        return redirect('/index')->with('result', '勤務を開始しました');
    }

    public function endAttendance() 
    {
        $id = Auth::id();
        $dt = new Carbon();
        $time = $dt->toTimeString();

        Attendance::where('user_id', $id)->update(['punchOut' => $time]);

        return redirect('/index')->with('result', '勤務を終了しました');
    }
    public function getAttendance(Request $request)
    {
        return view('attendance');
    }
}
