<?php

namespace App\Http\Controllers;

use App\Models\Quitter;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function getQuitterReport(){
        $pendingQuitter=Quitter::whereStatus(0)->count();
        $approvedQuitter=Quitter::whereStatus(1)->count();
        $rejectedQuitter=Quitter::withTrashed()->whereStatus(2)->count();
        $year=Carbon::now()->format('Y');


        $pendingReport=Report::whereStatus(0)->count();
        $resolvedReport=Report::whereStatus(1)->count();
        $rejectedReport=Report::withTrashed()->whereStatus(2)->count();

        return response()->json([
            'quitterData'=>[
                'approved'=>$approvedQuitter,
                'rejected'=>$rejectedQuitter,
                'pending'=>$pendingQuitter
            ],
            'reportData'=>[
                'resolved'=>$pendingReport,
                'rejected'=>$resolvedReport,
                'pending'=>$rejectedReport
            ],
            'year'=>$year,


        ]);
    }
}
