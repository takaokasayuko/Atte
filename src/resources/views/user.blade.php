@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user.css') }}">
@endsection

@section('content')

<table class="attendance__table">

  <tr class="attendance__row">
    <th class="attendance__label">番号</th>
    <th class="attendance__label">名前</th>
    <th class="attendance__label">最終出勤日</th>
    <th class="attendance__label"></th>
  </tr>
  @foreach($users as $user)
  <tr class="attendance__row">
    <td class="attendance__data">{{ $users->firstItem() + $loop->index }}</td>
    <td class="attendance__data">{{ $user['name'] }}</td>
    <td class="attendance__data">{{ $user['latest_date'] }}</td>
    <td class="attendance__data"><a class="attendance__data-btn" href="{{ route('user.detail', ['user' => $user['user_id']]) }}">詳細</a></td>
    @endforeach
  </tr>
</table>

{{ $users->links('vendor.pagination.custom') }}

@endsection