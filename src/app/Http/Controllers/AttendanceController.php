<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Rest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use PhpParser\Node\Expr\Cast;

class AttendanceController extends Controller
{
  //打刻ページ
  public function index()
  {
    $user = Auth::user();
    //ボタンの活性・非活性
    $work = Attendance::where('user_id', $user->id)
      ->latest('id')
      ->first();
    $attendance_id = Attendance::where('user_id', $user->id)
      ->latest('id')
      ->first('id');

    if (!empty($attendance_id)) {
      $rest =
        Rest::where('attendance_id', $attendance_id->id)
        ->latest('id')
        ->first();
    };

    if (empty($attendance_id) || !empty($work['work_end'])) {
      //出勤ボタンを活性
      $status_work = false;
      $status_rest = false;
    } elseif (!empty($rest['rest_start']) && empty($rest['rest_end'])) {
      //休憩終了ボタンを活性
      $status_work = true;
      $status_rest = true;
    } else {
      //休憩開始と退勤ボタンを活性
      $status_work = true;
      $status_rest = false;
    };
    return view('index', compact('user', 'status_work', 'status_rest'));
  }

  // 出勤
  public function store()
  {
    $user = Auth::user();
    $today = new Carbon();
    $today_date = $today->toDateString();
    $work_start = Attendance::where('user_id', $user->id)
      ->latest('work_end')
      ->first();
    if (!empty($work_start)) {
      $start_date = Carbon::parse($work_start['work_start']);
      $date = $start_date->toDateString();
    }

    //1日1回まで
    if (!empty($work_start) && $date >= $today_date) {
      return redirect('/')->with('message', "本日は出勤済みです");
    } else {
      Attendance::create([
        'user_id' => $user->id,
        'work_start' => $today
      ]);
      return redirect('/');
    }
  }

  // 退勤
  public function update()
  {
    $user = Auth::user();
    $today = Carbon::now();

    $today_date = $today->toDateString();
    $work_start = Attendance::where('user_id', $user->id)
      ->latest('work_end')
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

  //日付別勤怠ページ
  public function attendance(Request $request)
  {
    $date = $request->input('date', Carbon::now()->toDateString());
    $attendances = Attendance::orderBy('work_start')
      ->with('user', 'rests')
      ->whereDate('work_end', $date)
      ->paginate(5);

    $work_times = $attendances->map(function ($attendance) {
      $work_start = new Carbon($attendance->work_start);
      $work_end = new Carbon($attendance->work_end);

      //休憩時間の計算
      $rest_time_seconds = $attendance->rests->reduce(function ($carry, $rest) {
        $rest_start = new Carbon($rest->rest_start);
        $rest_end = new Carbon($rest->rest_end);
        return $carry + $rest_start->diffInSeconds($rest_end);
      }, 0);

      $rest_hours = floor($rest_time_seconds / 3600);
      $rest_minutes = floor(($rest_time_seconds % 3600) / 60);
      $rest_seconds = floor($rest_time_seconds % 60);
      $rest_time = Carbon::createFromTime($rest_hours, $rest_minutes, $rest_seconds)->ToTimeString();

      //勤務時間の計算
      $total_time_seconds = $work_start->diffInSeconds($work_end) - $rest_time_seconds;
      $total_hours = floor($total_time_seconds / 3600);
      $total_minutes = floor(($total_time_seconds % 3600) / 60);
      $total_seconds = floor($total_time_seconds % 60);
      $total_time = Carbon::createFromTime($total_hours, $total_minutes, $total_seconds)->ToTimeString();

      return [
        'user' => $attendance->user->name,
        'date' => $work_start->toDateString(),
        'work_start' => $work_start->toTimeString(),
        'work_end' => $work_end->toTimeString(),
        'rest_time' => $rest_time,
        'total_time' => $total_time
      ];
    });
    return view('attendance', compact('work_times', 'date', 'attendances'));
  }

  //ユーザー一覧ページ
  public function user(Request $request)
  {
    $users_all = User::all();

    $work_starts = $users_all->map(function ($user_all) {
      $latest_work_start = $user_all->attendances()
        ->latest('work_start')->first();

      if ($latest_work_start) {
        $latest_date = new Carbon($latest_work_start->work_start);
        return [
          'name' => $user_all->name,
          'latest_date' => $latest_date->toDateString(),
        ];
      } else {
        return [
          'name' => $user_all->name,
          'latest_date' => null,
        ];
      }
    });

    $sorted_users = $work_starts->sortByDesc('latest_date');

    $per_page = 10;
    $page = $request->get('page', 1);
    $users = new LengthAwarePaginator(
      $sorted_users->forPage($page, $per_page),
      $sorted_users->count(),
      $per_page,
      $page,
      ['path' => $request->url(), 'query' => $request->query()]
    );
    return view('user', compact('users'));
  }
}
