<style>
  table th {
    font-size: 20px;
    padding: 20px 50px;
    border-top: solid 1px #000;
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

  .date {
    font-size: 24px;
    font-weight: bold;
    text-align: center;
    margin: 10px 0 40px 0;
  }

  button {
    border: solid 1px blue;
    background: white;
    color: blue;
    padding: 0 10px;

  }

  .border {
    border-top: solid 1px #000;
  }
</style>
@extends('layouts.app')

@section('content')

<div class="date">
  <a class="arrow" href="{!! '/attendance/' . ($num - 1) !!}">&lt;</a>
  {{ $date->format('Y-m-d') }}
  <a class="arrow" href="{{!! '/attendance/' . ($num + 1) !!}}">&gt;</a>
</div>

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
      @foreach ($array as $element)
      <span class="border"></span>
      <tr>
        <th class="name">{{ $element['name'] }}</th>
        <th class="start">{{ $element['punchIn'] }}</th>
        <th class="end">{{ $element['punchOut']}}</th>
        <th>{{ $element['restTotal']}}</th>
        <th>{{ $element['punchTotal'] }}</th>
        @endforeach

      </tr>

    </div>
  </table>
</div>

@endsection