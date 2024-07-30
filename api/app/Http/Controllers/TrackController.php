<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuitterResource;
use App\Http\Resources\ReportResource;
use App\Models\Report;
use App\Models\Quitter;
use Illuminate\Http\Request;

class TrackController extends Controller
{
    public function track(Request $request){
        $request->validate([
            'type' => 'required',
            'id' => 'required'
        ]);
        if($request->input('type') == 'report'){
            $result = Report::withTrashed()->byHash($request->input('id'))->get();

            if($result){
                return response()->json([
                    'result' => ReportResource::collection($result),
                    'message' => 'report found'
                ]);
            }
            else{
                return response()->json([
                    'message' =>'Report not Found'
                ]);
            }
        }

        if($request->input('type') == 'quitter appointment'){
            $result = Quitter::withTrashed()->byHash($request->input('id'))->get();

            if($result){
                return response()->json([
                    'result' => QuitterResource::collection($result),
                    'message' => 'quitter found'
                ]);
            }
            else{
                return response()->json([
                    'message' =>'Quitter Appointment not Found'
                ]);
            }
        }
    }
}
