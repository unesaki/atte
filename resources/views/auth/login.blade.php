@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('ログイン') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">


                            <div class="col-md-6" style="margin: 0 auto; width: 100%;">
                                <input id="email" type="email" placeholder="メールアドレス" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">


                            <div class="col-md-6" style="margin: 0 auto; width: 100%;">
                                <input id="password" type="password" placeholder="パスワード" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>



                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary" >
                                    {{ __('ログイン') }}
                                </button>
                            </div>

                            <div style="text-align: center;">
                                <p style="margin: 2em 0 -0.2em 0; color: gray;">アカウントをお持ちでない方はこちらから</p>
                                <a href="{{ route('register') }}" style=" text-decoration: none;">会員登録</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection