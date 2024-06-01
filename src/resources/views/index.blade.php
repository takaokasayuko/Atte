@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')


<div class="attendance__content">
  <div class="attendance__heading">
    <h2 class="attendance__heading-name">{{ $user['name'] }}さんお疲れ様です！</h2>
  </div>

  @if(session('message'))
  <div class="error__message">
    {{ session('message') }}
  </div>
  @endif

  <div class="attendance__group">
    <div class="attendance__button">
      <form class="form__work-start" action="/work_start" method="post">
        @csrf
        @if(!$status_work && !$status_rest)
        <button class="attendance__button-submit">勤務開始</button>
        @else
        <button disabled class="attendance__button-submit">勤務開始</button>
        @endif
      </form>
    </div>

    <div class="attendance__button">
      <form class="form__work-end" action="/work_end" method="post">
        @csrf
        @if($status_work && !$status_rest)
        <button class="attendance__button-submit">勤務終了</button>
        @else
        <button disabled class="attendance__button-submit">勤務終了</button>
        @endif

      </form>
    </div>

    <div class="attendance__button">
      <form class="form__rest-start" action="/rest_start" method="post">
        @csrf
        @if($status_work && !$status_rest)
        <button class="attendance__button-submit">休憩開始</button>
        @else
        <button disabled class="attendance__button-submit">休憩開始</button>
        @endif

      </form>
    </div>

    <div class="attendance__button">
      <form class="form__rest-end" action="/rest_end" method="post">
        @csrf
        @if($status_work && $status_rest)
        <button class="attendance__button-submit">休憩終了</button>
        @else
        <button disabled class="attendance__button-submit">休憩終了</button>
        @endif

      </form>
    </div>
  </div>

</div>

@endsection