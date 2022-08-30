@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('会員登録') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">

                            <div class="col-md-6" style="margin: 0 auto; width: 100%;">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="名前" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">


                            <div class="col-md-6" style="margin: 0 auto; width: 100%;">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="メールアドレス" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">


                            <div class="col-md-6" style="margin: 0 auto; width: 100%;">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="パスワード" name="password" required autocomplete="new-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">


                            <div class="col-md-6" style="margin: 0 auto; width: 100%;">
                                <input id="password-confirm" type="password" class="form-control" placeholder="確認用パスワード" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('会員登録') }}
                                </button>
                            </div>

                            <div style="text-align: center;">
                                <p style="margin: 2em 0 -0.2em 0; color: gray;">アカウントをお持ちの方はこちらから</p>
                                <a href="{{ route('login') }}" style=" text-decoration: none;">ログイン</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection