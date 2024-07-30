<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApproveQuitterRequest;
use App\Http\Requests\CreateQuitterRequest;
use App\Http\Requests\DeleteQuitterRequest;
use App\Http\Requests\ListQuitterRequest;
use App\Http\Requests\RejectQuitterRequest;
use Illuminate\Http\Request;
use App\Models\Quitter;
use App\Traits\QuitterTrait;
use App\Http\Resources\QuitterResource;
use App\Traits\LoggerTrait;

class QuitterController extends Controller
{

    use QuitterTrait,LoggerTrait;

    public function __construct(){
        $permissions = [
            'quitter_list',
            'quitter_reject',
            'quitter_accept',
            'quitter_reschedule'
        ];

        $this->middleware("permission:".implode("|",$permissions),[
            'only'=> ['list']
        ]);

        $this->middleware('permission:quitter_reject', ['only' => ['reject','delete']]);
        $this->middleware('permission:quitter_accept', ['only' => ['accept']]);
        $this->middleware('permission:quitter_reschedule', ['only' => ['reschedule']]);
    }

    public function create(CreateQuitterRequest $request){
        $fields = $request->validated();

        $quitter = Quitter::create($fields);

        $this->recordActivity('New Quitter Applied','Quitter Page Management',$this->ACTION_CREATE);

        return response()->json([
            'quitter' => $quitter,
            'message' => 'Appointment Created Successfully!'
        ]);
        if(!$quitter){
            return response()->json([
                'message'=>'Appointment Not Created'
            ]);
        }
    }
    public function list(ListQuitterRequest $request){
        $fields = $request->validated();
        $quitters = $this->searchQuitter($fields['term'],$fields['limit'],$fields['offset']);
        return response()->json([
            'quitter' =>QuitterResource::collection($quitters),
            'total' => $this->searchQuitterCount($fields['term'])
        ]);

    }

    public function delete(DeleteQuitterRequest $request){
        $fields = $request->validated();
        $quitter = Quitter::byHash($fields['id'])->delete();

        $this->recordActivity('Quitter Appointment Rejected','Quitter Page Management',$this->ACTION_DELETE);

        return response()->json([
            'message' =>'deleted'
        ]);
    }

    public function reject(RejectQuitterRequest $request){
        $fields = $request->validated();

        $quitter = Quitter::byHash($fields['id'])->update($fields);

        return response()->json([
            'quitter' => $quitter,
            'message' => 'rejected'
        ]);
    }

    public function accept(ApproveQuitterRequest $request){
        $fields = $request->validated();

        $quitter = Quitter::byHash($fields['id'])->update($fields);

        $this->recordActivity('Quitter Appointment Accepted','Quitter Page Management',$this->ACTION_UPDATE);

        return response()->json([
            'quitter' => $quitter,
            'message' => 'approved'
        ]);

    }

    public function reschedule(Request $request){
        $request->validate([
            'quitter_id' => 'required',
            'date' => 'required',
            'admin_remarks' => 'required'
        ]);

        $quitter = Quitter::byHash($request->input('quitter_id'));

        if($quitter){
            $quitter->update([
                'preferred_date' => $request->input('date'),
                'status' => 3,
                'admin_remarks' => $request->input('admin_remarks')
            ]);

            $quitter->increment('reschedule_count');
        }

        return response()->json([
            'message' => 'Appointment Date Rescheduled Succcessfully!'
        ]);
    }

    // public function listAccepted(ListQuitterRequest $request){
    //     $fields = $request->validated();
    //     $quitters = $this->searchAcceptedQuitter($fields['term'],$fields['limit'],$fields['offset']);
    //     return response()->json([
    //         'quitter' => QuitterResource::collection($quitters),
    //         'total' => $this->searchAcceptedQuitterCount($fields['term'])
    //     ]);
    // }

    // public function listRejected(ListQuitterRequest $request){
    //     $fields = $request->validated();
    //     $quitters = $this->searchRejectedQuitter($fields['term'],$fields['limit'],$fields['offset']);
    //     return response()->json([
    //         'quitter' => QuitterResource::collection($quitters),
    //         'total' => $this->searchRejectedQuitterCount($fields['term'])
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
            $quitters = $this->searchPendingQuitter($request->term,$request->limit,$request->offset);
            return response()->json([
                'message' =>  'No Pending Appointment',
                'quitter' => QuitterResource::collection($quitters),
                'total' => $this->searchPendingQuitterCount($request->term)
            ]);
        }
        if($request->status == 1){
            $quitters = $this->searchAcceptedQuitter($request->term,$request->limit,$request->offset);
            return response()->json([
                'message' =>  'No Resolved Appointment',
                'quitter' => QuitterResource::collection($quitters),
                'total' => $this->searchAcceptedQuitterCount($request->term)
            ]);
        }
        if($request->status == 2){
            $quitters = $this->searchRejectedQuitter($request->term,$request->limit,$request->offset);
            return response()->json([
                'message' =>  'No Rejected Appointment',
                'quitter' => QuitterResource::collection($quitters),
                'total' => $this->searchRejectedQuitterCount($request->term)
            ]);
        }
        if($request->status == 3){
            $quitters = $this->searchRescheduledQuitter($request->term,$request->limit,$request->offset);
            return response()->json([
                'message' =>  'No Rescheduled appointment',
                'quitter' => QuitterResource::collection($quitters),
                'total' => $this->searchRescheduledQuitterCount($request->term)
            ]);
        }
    }
}
