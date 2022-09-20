<style>
  table th {
    font-size: 20px;
    padding: 20px 50px;
    border-top: solid 1px #000;
  }

  td {
    border-bottom: 1px solid black;
  }

  tr {
    border: solid 1px gray;
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
    font-size: 26px;
    font-weight: bold;
    text-align: center;
    margin: 20px 0;
  }

  .border {
    border-top: solid 1px #000;
  }

  .d-flex {
    margin-top: 10px;
  }

  .page_bottom {
    width: 100%;
    height: 50px;
    background-color: #fff;
    position: absolute;
    bottom: 0;
    margin: 0 auto;
    text-align: center;
    line-height: 50px;
    font-weight: bold;
  }
</style>
@extends('layouts.header')

@section('content')

  <div class="date">
    <a class="arrow" href="{!! '/attendance/' . ($num - 1) !!}">&lt;</a>
    {{ $date }}
    <a class="arrow" href="{!! '/attendance/' . ($num + 1) !!}">&gt;</a>
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
      @foreach ($pageData as $element)
      
      <tr>
        <th>{{ $element['name'] }}</th>
        <th>{{ $element['punchIn'] }}</th>
        <th>{{ $element['punchOut']}}</th>
        <th>{{ $element['restTotal']}}</th>
        <th>{{ $element['punchTotal'] }}</th>
        @endforeach
      </tr>

    </div>
  </table>
</div>
<div class="d-flex justify-content-center">
  {{ $pageData->links('pagination::bootstrap-4') }}
</div>
@endsection

@section('footer')
</div>
<div class="page_bottom">Atte,inc.</div>
</div>
@endsection