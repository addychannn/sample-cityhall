<?php

namespace App\Http\Controllers;

use App\Traits\MessageTrait;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Http\Requests\CreateMessageRequest;
use App\Http\Requests\ListMessageRequest;
use App\Http\Requests\ShowMessageRequest;
use App\Http\Requests\DeleteMessageRequest;
use App\Http\Resources\MessageResource;

use App\Http\Requests\ImageRequest;
use App\Http\Requests\ListEventRequest;
use App\Traits\ImageTrait;

class MessageController extends Controller
{
    use MessageTrait, ImageTrait;

    public function __construct(){
        $permissions = [
            'message_list',
            'message_delete',
            'message_list-deleted'
        ];
        $this->middleware("permission:".implode("|",$permissions),[
            'only'=> ['list']
        ]);
        $this->middleware('permission:message_delete', ['only' => ['delete']]);
        $this->middleware('permission:message_list-deleted', ['only' => ['getDeletedMessages']]);
    }

    public function create(CreateMessageRequest $request){
        $fields = $request->validated();

        $message= Message::create($fields);

        $this->recordActivity('New Message Received','Messages Page Management',$this->ACTION_CREATE);

        if(!$message){
         return response()->json([
             'message' => 'Unable to Create message',

        ]);
        }

        return response()->json([

             'message'=>$message,
             'alert'=>'Your message will be reviewed momentarily'
        ]);
    }

    public function list(ListMessageRequest $request){
        $fields = $request->validated();

        $message = $this->searchMessage($fields['term'],$fields['limit'],$fields['offset']);

        return response()->json([
            'message' => MessageResource::collection($message),
            'total' => $this->searchMessageCount($fields['term']),
            // 'message' => $message,

        ]);
    }


     public function show(Request $request){

        $message = Message::withTrashed()->byHash($request->hash)->get();

        return response()->json([
            'message'=> $message
        ]);

    }


    public function delete(DeleteMessageRequest $request){
        $fields = $request->validated();

        $message = Message::byHash($fields['id'])->delete();

        //$this->recordActivity('Message Deleted','Messages Page Management',$this->ACTION_DELETE);

        return response()->json([
            //'message' => $message,
            'response' => 'message deleted'
        ]);
    }

    public function getDeletedMessages(){
        $data = Message::onlyTrashed()->get();
        return response()->json([
            'data'=> $data
        ]);
    }

    public function restoreMessage($id){
        $message = Message::withTrashed()->byHash($id)->restore();
        return response()->json([
            'message' => 'Message Restored Successfully!'
        ]);
    }



}
