<style>
    .container {
        text-align: center;
        width: 100%;
    }

    .user__name {
        font-size: 20px;
        font-weight: bold;
        margin: 30px auto;
    }

    .main__card {
        width: 80%;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
    }

    .flex__item {
        display: flex;
        justify-content: space-around;
        width: 100%;
    }

    .start,
    .end {
        background-color: #fff;
        border: none;
        font-size: 20px;
        font-weight: bold;
        padding: 60px 150px;
        margin: 20px;
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

@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('result'))
    <div class="flash_message">
        {{ session('result') }}
    </div>
    @endif
    <div class="user__name">
        {{ Auth::user()->name }}さんお疲れ様です！
    </div>
    <div class="main__card">
        <div class="flex__item flex__item-1">
            <form action="/attendance/start" method="post">
                @csrf
                <input type="submit" class="start work__start" value="勤務開始" />
            </form>
            <form action="/attendance/end" method="post">
                @csrf
                <input type="submit" class="end work__end" value="勤務終了" />
            </form>
        </div>
        <div class="flex__item flex__item-2">
            <form action="/rest/start" method="post">
                @csrf
                <input type="submit" class="start rest__start" value="休憩開始" />
            </form>
            <form action="/rest/end" method="post">
                @csrf
                <input type="submit" class="end rest__end" value="休憩終了" />
            </form>
        </div>
    </div>
    @endsection

    @section('footer')
</div>
<div class="page_bottom">Atte,inc.</div>
</div>
@endsection