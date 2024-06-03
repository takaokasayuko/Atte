<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Rest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RestController extends Controller
{
  //休憩開始
  public function store()
  {
    $user = Auth::user();
    $today = Carbon::now();

    $today_date = $today->toDateString();
    $work_start = Attendance::where('user_id', $user->id)
      ->latest('id')
      ->first();
    $start_date = Carbon::parse($work_start['work_start']);
    $date = $start_date->toDateString();

    //出勤日と休憩開始日が異なる場合
    while ($date < $today_date) {
      //退勤時間を登録
      $end_time = '23:59:59';
      $end_date_string = $date . ' ' . $end_time;
      $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $end_date_string);
      Attendance::where('user_id', $user->id)
        ->latest('id')
        ->first()
        ->update([
          'work_end' => $end_date
        ]);

      //翌日に出勤時間を登録
      $start_time = '00:00:00';
      $date = $start_date->addDay()->toDateString();
      $next_date_string = $date . ' ' . $start_time;
      $work_start = Carbon::createFromFormat('Y-m-d H:i:s', $next_date_string);
      Attendance::create([
        'user_id' => $user->id,
        'work_start' => $work_start
      ]);
    };
    $attendance_id = Attendance::where('user_id', $user->id)
      ->latest('id')
      ->first('id');
    Rest::create([
      'attendance_id' => $attendance_id['id'],
      'rest_start' => $today
    ]);
    return redirect('/');
  }

  //休憩終了
  public function update()
  {
    $user = Auth::user();
    $attendance_id = Attendance::where('user_id', $user->id)
      ->latest('id')
      ->first();

    $today = Carbon::now();
    $today_date = $today->toDateString();
    $rest_start = Rest::where('attendance_id', $attendance_id->id)
      ->latest('id')
      ->first();

    $start_date = Carbon::parse($rest_start['rest_start']);
    $date = $start_date->toDateString();

    //休憩の開始日と終了日が異なる場合
    while ($date < $today_date) {
      //休憩終了時間と退勤時間を登録
      $end_time = '23:59:59';
      $end_date_string = $date . ' ' . $end_time;
      $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $end_date_string);

      Rest::where('attendance_id', $attendance_id->id)
        ->latest('id')
        ->first()
        ->update([
          'rest_end' => $end_date
        ]);

      Attendance::where('user_id', $user->id)
        ->latest('id')
        ->first()
        ->update([
          'work_end' => $end_date
        ]);

      //翌日に出勤時間と休憩開始時間を登録
      $start_time = '00:00:00';
      $date = $start_date->addDay()->toDateString();
      $next_date_string = $date . ' ' . $start_time;
      $work_start = Carbon::createFromFormat('Y-m-d H:i:s', $next_date_string);
      Attendance::create([
        'user_id' => $user->id,
        'work_start' => $work_start
      ]);

      $attendance_id = Attendance::where('user_id', $user->id)
        ->latest('id')
        ->first();
      Rest::create([
        'attendance_id' => $attendance_id->id,
        'rest_start' => $work_start
      ]);
    };

    Rest::where('attendance_id', $attendance_id->id)
      ->latest('id')
      ->first()
      ->update([
        'rest_end' => $today
      ]);
    return redirect('/');
  }
}
