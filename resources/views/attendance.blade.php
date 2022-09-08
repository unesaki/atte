<style>
  th {
    border-top: 1px solid black;
    border-bottom: 1px solid black;
    font-size: 20px;
    padding: 20px 50px;
  }

  td {
    border-bottom: 1px solid black;
  }

  table {
    border-collapse: collapse;
    margin: 0 auto;
    border-spacing: 0 8px;
    width: 90%;
  }

  .attendanceDate {
    width: 100%;
  }
</style>
@extends('layouts.app')

@section('content')

<div class="attendanceDate">
  <table>
    <div class="title">
      <tr>
        <th>名前</th>
        <th>勤務開始</th>
        <th>勤務終了</th>
        <th>休憩時間</th>
        <th>勤務時間</th>
      </tr>
    </div>

    <div class="content">
      @foreach ($startTimes as $startTime)
      <tr>
        <th class="name">{{ Auth::user()->name }}</th>
        <th class="start">{{ $startTime->punchIn }}</th>
        <th class="end">{{ $startTime->punchOut}}</th>
        @endforeach
        @foreach ($restTotals as $restTime)
        <th>{{ $restTime['restTotal']}}</th>
        @endforeach
        
      </tr>
      
    </div>
  </table>
</div>

@endsection