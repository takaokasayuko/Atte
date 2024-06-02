@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
<div class="form__content">
  <h2 class="form__heading">会員登録</h2>
  <form class="form" action="/register" method="post">
    @csrf

    <div class="form__group">
      <div class="form__group-content">
        <input class="form__input" type="text" name="name" id="name" placeholder="名前" value="{{ old('name') }}">
      </div>
      <div class="form__error">
        @error('name')
        {{ $message }}
        @enderror
      </div>
    </div>

    <div class="form__group">
      <div class="form__group-content">
        <input class="form__input" type="email" name="email" id="email" placeholder="メールアドレス" value="{{ old('email') }}">
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

    <div class="form__group">
      <div class="form__group-content">
        <input class="form__input" type="password" name="password_confirmation" placeholder="確認用パスワード">
      </div>
    </div>

    <div class="form__button">
      <button class="form__button-submit">会員登録</button>
    </div>

    <div class="link__group">
      <p class="link__group-text">
        アカウントをお持ちの方はこちらから
      </p>
      <a class="link__button-submit" href="/login">ログイン</a>
    </div>


  </form>

</div>
@endsection