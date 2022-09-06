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

    public function startAttendance()
    {
        $id = Auth::id();

        $dt = new Carbon();
        $date = $dt->toDateString();
        $time = $dt->toTimeString();

        $oldAttendance = Attendance::where('user_id', $id)->latest()->first();

        $oldAttendanceDay = "";

        if ($oldAttendance) {
            $oldAttendanceDate = new Carbon($oldAttendance->punchIn);
            $oldAttendanceDay = $oldAttendanceDate->startOfDay();
        }
        $newAttendance = Carbon::today();

        if (($oldAttendanceDay == $newAttendance) && (empty($oldAttendance->punchOut))) {
            return redirect()->back()->with('result', 'すでに勤務を開始しています');
        }


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
        $date = $dt->toDateString();
        $time = $dt->toTimeString();

        $attendance = Attendance::where('user_id', $id)->whereNull('punchOut')->where('date', $date)->first();

        //dd($attendance);


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
