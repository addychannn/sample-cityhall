<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateReportRequest;
use App\Http\Requests\DeleteReportRequest;
use App\Http\Requests\ListReportRequest;
use App\Http\Requests\RejectReportRequest;
use App\Http\Requests\ResolveReportRequest;
use App\Http\Requests\UploadReportImageRequest;
use App\Http\Requests\ViewReportImageRequest;
use App\Http\Resources\ImageDisplayResource;
use App\Http\Resources\ReportResource;
use App\Models\Address\Barangay;
use Illuminate\Http\Request;
use App\Models\Report;
use App\Traits\ImageTrait;
use App\Traits\ReportTrait;
use Carbon\Carbon;

class ReportController extends Controller
{
    use  ReportTrait, ImageTrait;

    public function __construct(){
        $permissions = [
            'report_list',
            'report_reject',
            'report_resolve',
        ];
        $this->middleware("permission:".implode("|",$permissions),[
            'only'=> ['list']
        ]);
        // $this->middleware('permission:report_list', ['only' => ['list']]);
        $this->middleware('permission:report_reject', ['only' => ['reject','delete']]);
        $this->middleware('permission:report_resolve', ['only' => ['resolve']]);
    }

    public function create(CreateReportRequest $request){
        $fields = $request->validated();
        $report = Report::create($fields);

        $this->recordActivity('New Report Submitted','Report Page Management',$this->ACTION_CREATE);

        return response()->json([
            'report' => $report,
            'message' => 'Sending your report...'
        ]);
        if(!$report){
            return response()->json([
                'message' => 'Report Not Created.'
            ]);
        }
    }

    public function uploadPhoto(UploadReportImageRequest $request){
        $fields = $request->validated();
        $report = Report::byHash($fields['hash']);
        $gallery = $report->gallery;

        //check if report has gallery
        if(!$gallery){
            $gallery = $report->gallery()->create([
                'name' => $report->hash." 's Gallery",
            ]);
        }

        //upload
        $image = $this->uploadImage($request);
        $image->gallery_id = $gallery->id;
        $image->save();

        return response()->json([
            'message' => 'image uploaded'
        ]);
    }

    public function list(ListReportRequest $request){
        $fields = $request->validated();
        $reports = $this->searchReport($fields['term'],$fields['limit'],$fields['offset']);
        return response()->json([
            'report' => ReportResource::collection($reports),
            'total' => $this->searchReportCount($fields['term'])
        ]);
    }

    public function reject(RejectReportRequest $request){
        $fields = $request->validated();
        $report = Report::byHash($fields['id'])->update($fields);

        return response()->json([
            'report' => $report
        ]);
    }

    public function delete(DeleteReportRequest $request){
        $fields = $request->validated();

        $report = Report::byHash($fields['id'])->delete();

        $this->recordActivity('Report Rejected','Report Page Management',$this->ACTION_UPDATE);

        return response()->json([
            'report' => $report,
            'message' => 'report deleted'
        ]);
    }

    public function resolve(ResolveReportRequest $request){
        $fields = $request->validated();
        $report = Report::byHash($fields['id'])->update($fields);

        $this->recordActivity('Report Resolved','Report Page Management',$this->ACTION_UPDATE);

        return response()->json([
            'report' => $report,
            'message' => 'resolved report'
        ]);
    }

    // public function listReject(ListReportRequest $request){
    //     $fields = $request->validated();
    //     $rejected_reports = $this->searchRejectedReport($fields['term'], $fields['limit'], $fields['offset']);
    //     return response()->json([
    //         'rejected_report' => ReportResource::collection($rejected_reports) ,
    //         'total' => $this->searchRejectedReportCount($fields['term'])
    //     ]);
    // }
    // public function listResolve(ListReportRequest $request){
    //     $fields = $request->validated();
    //     $resolved_reports = $this->searchResolvedReport($fields['term'], $fields['limit'], $fields['offset']);
    //     return response()->json([
    //         'resolved_report' => ReportResource::collection($resolved_reports) ,
    //         'total' => $this->searchResolvedReportCount($fields['term'])
    //     ]);
    // }
    public function filterData(Request $request){
        $request->validate([
            'status' => 'nullable',
            'term' => 'nullable',
            'offset' => 'required|numeric',
            'limit' =>'required|numeric',
        ]);

        if($request->status == 0){
            // $reports = Report::where('status',0)->get();
            $reports = $this->searchPendingReport($request->term, $request->limit, $request->offset);
            return response()->json([
                'message' => 'No Pending Report',
                'data' => ReportResource::collection($reports),
                'total' => $this->searchPendingReportCount($request->term)
            ]);
        }
        if($request->status == 1){
            // $reports = Report::where('status',1)->get();
            $reports = $this->searchResolvedReport($request->term, $request->limit, $request->offset);
            return response()->json([
                'message' => 'No Resolved Report',
                'data' => ReportResource::collection($reports),
                'total' => $this->searchResolvedReportCount($request->term)
            ]);
        }
        if($request->status == 2){
            // $reports = Report::withTrashed()->where('status',2)->get();
            $reports = $this->searchRejectedReport($request->term, $request->limit, $request->offset);
            return response()->json([
                'message' => 'No Rejected Report',
                'data' => ReportResource::collection($reports),
                'total' => $this->searchRejectedReportCount($request->term)
            ]);
        }
    }
}
