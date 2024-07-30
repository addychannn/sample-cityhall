<?php

namespace App\Http\Controllers;

use App\Models\Enforcer;
use App\Models\Quitter;
use App\Models\Report;
use App\Traits\EnforcerReminderTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnforcerController extends Controller
{
    //
    use EnforcerReminderTrait;

    public function getReminders(Request $request){
        $reminders = Enforcer::all();

        $reminders = $this->searchReminder($request->term,$request->limit,$request->offset);

        return response()->json([
            'data'=>$reminders,
            'total'=>$this->searchReminderCount($request->term)
        ]);
    }

    public function addNewReminder(Request $request){

        Enforcer::create([
            'title'=>$request->title,
            'content'=>$request->content
        ]);
    }

    public function getSpecificReminder(Request $request){

        $reminder = Enforcer::find($request->id);

        return response()->json([
            'reminder'=>$reminder
        ]);
    }

    public function editSpecificReminder(Request $request){

        $reminder = Enforcer::find($request->id)->update([
            'title'=>$request->title,
            'content'=>$request->content
        ]);

    }

    public function deleteSpecificReminder(Request $request){

        Enforcer::find($request->id)->delete();

    }


}
