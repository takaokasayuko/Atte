@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/verify-email.css') }}">
@endsection

@section('content')
<div class="form__content">
    <h2>メール確認済み</h2>
    <p>メールアドレスが確認されました</p>
</div>
@endsection