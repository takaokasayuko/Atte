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
        $rest_start = new Carbon;
        $attendance_id =
        Attendance::where('user_id', $user->id)
        ->latest('updated_at')
        ->first('id');
        Rest::create([
            'attendance_id' => $attendance_id['id'],
            'rest_start' => $rest_start
        ]);

        return redirect('/');

    }

    //休憩終了
    public function update()
    {
        $user = Auth::user();
       
        $attendance_id =
        Attendance::where('user_id', $user->id)
        ->latest('updated_at')
        ->first('id');
        $rest_end = new Carbon;
    
        Rest::where('attendance_id', $attendance_id->id)
        ->latest('updated_at')
        ->first()
        ->update([
            'rest_end' => $rest_end
        ]);

        return redirect('/');

    }
}
