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
      ->latest('updated_at')
      ->first();

    $attendance_id =
        Attendance::where('user_id', $user->id)
        ->latest('updated_at')
        ->first('id');

    if(empty($attendance_id) == true || empty($status_work['work_end']) == false){
      $button = 0;//出勤ボタンを活性
    } else{
      $status_rest =
        Rest::where('attendance_id', $attendance_id->id)
        ->latest('updated_at')
        ->first();
      if (empty($status_rest['rest_end']) == false && empty($status_work['work_end']) == true) {
      $button = 1;//休憩開始と退勤ボタンを活性
      } else {
        $button = 2;//休憩終了を活性
      };
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
    $work_end = new Carbon;
    Attendance::where('user_id', $user->id)
    ->latest('updated_at')
    ->first()
    ->update([
      'work_end' => $work_end
    ]);
    return redirect('/');

}
}