<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Rest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class AttendanceController extends Controller
{
  public function index()
  {
    $user = Auth::user();

    //ボタンの活性・非活性
    $status_work = Attendance::where('user_id', $user->id)
      ->latest('id')
      ->first();
    $attendance_id = Attendance::where('user_id', $user->id)
      ->latest('id')
      ->first('id');

    if (!empty($attendance_id)) {
      $status_rest =
        Rest::where('attendance_id', $attendance_id->id)
        ->latest('id')
        ->first();
    };



    if (empty($attendance_id) == true || empty($status_work['work_end']) == false) {
      $button = 0; //出勤ボタンを活性
    } elseif (empty($status_rest['rest_start']) == false && empty($status_rest['rest_end']) == true) {
      $button = 2; //休憩終了ボタンを活性
    } else {
      $button = 1; //休憩開始と退勤ボタンを活性
    };

    return view('index', compact('user', 'button'));
  }

  // 出勤
  public function store()
  {
    $user_id = Auth::id();
    $work_start = new Carbon();

    Attendance::create([
      'user_id' => $user_id,
      'work_start' => $work_start
    ]);
    return redirect('/');
  }

  // 退勤
  public function update()
  {
    $user = Auth::user();

    $today = Carbon::now();
    $today_date = $today->toDateString();
    $work_start =
      Attendance::where('user_id', $user->id)
      ->latest('id')
      ->first();
    $start_date = Carbon::parse($work_start['work_start']);
    $date = $start_date->toDateString();

    //出勤日と退勤日が異なる場合
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

    Attendance::where('user_id', $user->id)
      ->latest('id')
      ->first()
      ->update([
        'work_end' => $today
      ]);

    return redirect('/');
  }
}
