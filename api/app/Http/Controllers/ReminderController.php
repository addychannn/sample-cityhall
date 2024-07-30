<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reminder;

class ReminderController extends Controller
{
    public function getReminderList(){
      
        $reminder = Reminder::all();
        return response()->json(['data'=>$reminder]);
    }
}
