@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('content')
<div class="form__content">
  <h2 class="form__heading">ログイン</h2>
  <form class="form" action="/login" method="post">
    @csrf

    <div class="form__group">
      <div class="form__group-content">
        <input class="form__input" type="text" name="email" id="email" placeholder="メールアドレス" value="{{ old('email') }}">
      </div>
      <div class="form__error">
        @error('email')
        {{ $message }}
        @enderror
      </div>
    </div>

    <div class="form__group">
      <div class="form__group-content">
        <input class="form__input" type="password" name="password" id="password" placeholder="パスワード">
      </div>
      <div class="form__error">
        @error('password')
        {{ $message }}
        @enderror
      </div>
    </div>

    <div class="form__button">
      <button class="form__button-submit">ログイン</button>
    </div>

    <div class="link__group">
      <p class="link__group-text">
        アカウントをお持ちでない方はこちらから
      </p>
      <a class="link__button-submit" href="/register">会員登録</a>
    </div>

  </form>

</div>
@endsection