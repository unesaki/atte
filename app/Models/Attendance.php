<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;


    protected $fillable = ['user_id', 'date', 'punchIn', 'punchOut'];

    public function users() {
        return $this->belongsTo('App\Models\User', "user_id");
    }

    public function rests() {
        return $this->hasMany('App\Models\Rest');
    }

    public static function getSumTime($items)
    {
        foreach ($items as $index => $item) {
            $rests = $item->rests;
            $sum = 0;
            foreach ($rests as $rest) {
                $startTime = $rest->restIn; //休憩開始を取得
                $start_dt = new Carbon($startTime);
                $endTime = $rest->restOut; //休憩終了を取得
                $end_dt = new Carbon($endTime);
                $diff_seconds = $start_dt->diffInSeconds($end_dt);    //diffInSecondsで差分を計算
                $sum = $sum + $diff_seconds;
            }
            $start_at = new Carbon($item->punchIn);
            $end_at = new Carbon($item->punchOut);

            $diff_start_end = $start_at->diffInSeconds($end_at);
            $diff_work = $diff_start_end - $sum;

            $res_hours = floor($sum / 3600);
            $res_minutes = floor(($sum / 60) % 60);
            $res_seconds = $sum % 60;

            $work_hours = floor($diff_work / 3600);
            $work_minutes = floor(($diff_work / 60) % 60);
            $work_seconds = $diff_work % 60;

            $time_dt = Carbon::createFromTime($res_hours, $res_minutes, $res_seconds);  //時間に変換

            $time_work = Carbon::createFromTime($work_hours, $work_minutes, $work_seconds);

            $items[$index]->rest_sum = $time_dt->toTimeString();
            $items[$index]->work_time = $time_work->toTimeString();
        }

        return $items;
    }
    
}
