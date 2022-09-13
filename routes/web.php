<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\RestController;
use App\Http\Controllers\CalendarController;




Auth::routes();


Route::get('/index', [AttendanceController::class, 'index'])->name('index');


//Route::get('/attendance', [AttendanceController::class, 'getAttendance'])->name('attendance');
Route::post('/attendance/start', [AttendanceController::class, 'startAttendance'])->name('attendance.start');
Route::post('/attendance/end', [AttendanceController::class, 'endAttendance'])->name('attendance.end');


Route::post('rest/start', [RestController::class, 'startRest'])->name('rest.start');
Route::post('rest/end', [RestController::class, 'endRest'])->name('rest.end');


Route::get('attendance', [AttendanceController::class, 'getAttendance'])->name('attendance');
Route::post('attendance', [AttendanceController::class, 'getAttendance'])->name('attendance');
