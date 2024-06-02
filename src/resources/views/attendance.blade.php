@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('content')
<div class="pagination__date">
  <a class="pagination__date-link" href="{{ route('attendance', ['date' => \Carbon\Carbon::parse($date)->subDay()->toDateString()]) }}">&lt</a>
  <h2 class="attendance__date">{{ $date }}</h2>
  <a class="pagination__date-link" href="{{ route('attendance', ['date' => \Carbon\Carbon::parse($date)->addDay()->toDateString()]) }}">&gt</a>
</div>

<table class="attendance__table">

  <tr class="attendance__row">
    <th class="attendance__label">名前</th>
    <th class="attendance__label">勤務開始</th>
    <th class="attendance__label">勤務終了</th>
    <th class="attendance__label">休憩時間</th>
    <th class="attendance__label">勤務時間</th>
  </tr>
  @foreach($work_times as $work_time)
  <tr class="attendance__row">
    <td class="attendance__data">{{ $work_time['user'] }}</td>
    <td class="attendance__data">{{ $work_time['work_start'] }}</td>
    <td class="attendance__data">{{ $work_time['work_end'] }}</td>
    <td class="attendance__data">{{ $work_time['rest_time'] }}</td>
    <td class="attendance__data">{{ $work_time['total_time'] }}</td>
    @endforeach
  </tr>
</table>

{{ $attendances->appends(['date' => $date])->links('vendor.pagination.custom') }}



@endsection