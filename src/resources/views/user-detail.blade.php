@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user.css') }}">
@endsection

@section('content')
<div class="pagination__date">
  <a class="pagination__date-link" href="{{ route('user.detail', ['user' => $user->id, 'month' => Carbon\Carbon::parse($month)->subMonthNoOverflow()->format('Y-m')]) }}">&lt</a>
  <h2 class="attendance__date">
    <span class="attendance__month">{{ $month }}</span>
    <span class="attendance__month">{{ $user['name'] }}</span>
  </h2>
  <a class="pagination__date-link" href="{{ route('user.detail', ['user' => $user->id, 'month' => Carbon\Carbon::parse($month)->addMonthNoOverflow()->format('Y-m')]) }}">&gt</a>
</div>

<table class="attendance__table">

  <tr class="attendance__row">
    <th class="attendance__label">日付</th>
    <th class="attendance__label">勤務開始</th>
    <th class="attendance__label">勤務終了</th>
    <th class="attendance__label">休憩時間</th>
    <th class="attendance__label">勤務時間</th>
  </tr>

  @foreach($dates as $date => $attendance)
  <tr class="attendance__row">
    <td class="attendance__data">{{ \Carbon\Carbon::parse($date)->day }}日</td>
    @if(empty($attendance))
    <td class="attendance__data">-</td>
    <td class="attendance__data">-</td>
    <td class="attendance__data">-</td>
    <td class="attendance__data">-</td>
    @else
    <td class="attendance__data">{{ $attendance['work_start'] }}</td>
    <td class="attendance__data">{{ $attendance['work_end'] }}</td>
    <td class="attendance__data">{{ $attendance['rest_time'] }}</td>
    <td class="attendance__data">{{ $attendance['total_time'] }}</td>
    @endif
    @endforeach
  </tr>
  <tr class="attendance__row">
    <td colspan="4" class="total__label">1ヶ月の勤務時間</td>
    <td class="total__label">{{ $month_work_time }}</td>
  </tr>

</table>

@endsection