<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\createEvent;
use App\Http\Requests\DeleteEventRequest;
use App\Http\Requests\ListEventRequest;
use App\Http\Requests\ShowEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Requests\UploadEventImageRequest;
use App\Http\Requests\ViewEventImageRequest;
use App\Http\Resources\EventResource;
use App\Http\Resources\DeletedEventResource;
use App\Models\Event;
use App\Models\Images;
use App\Http\Resources\ImageDisplayResource;
use App\Traits\EventTrait;
use App\Http\Requests\ImageRequest;
use App\Traits\ImageTrait;
use Carbon\Carbon;

class EventController extends Controller
{
    use EventTrait, ImageTrait;

    public function __construct(){
        $this->middleware('permission:event_list-deleted', ['only' => ['listDeleted','showDeleted']]);
        $this->middleware('permission:event_add', ['only' => ['createEvent','uploadPhoto']]);
        $this->middleware('permission:event_edit', ['only' => ['update','deleteImage','uploadPhoto']]);
        $this->middleware('permission:event_delete', ['only' => ['delete']]);
    }

    public function createEvent(createEvent $request){
        $fields = $request->validated();

        $event= Event::create($fields);

        $this->recordActivity('New Event Added','Events Page Management',$this->ACTION_CREATE);

        if(!$event){
         return response()->json([
             'message' => 'Unable to Create Event',

        ]);
        }

        return response()->json([
             // 'hash' => $product->hash,
             'event'=>$event
        ]);
    }

    public function update(UpdateEventRequest $request){
        $fields = $request->validated();

        $event = Event::byHash($fields['id'])
                ->update($fields);

                $this->recordActivity('Event Updated','Events Page Management',$this->ACTION_CREATE);

                return response()->json([
                    'message' => 'updated successfully',
                    'event' => $event,
                ]);
    }

    public function list(ListEventRequest $request){
        $fields = $request->validated();

        $event = $this->searchEvent($fields['term'],$fields['limit'],$fields['offset']);

        return response()->json([
            'event' => EventResource::collection($event),
            'total' => $this->searchEventCount($fields['term'])

        ]);
    }

    public function listDeleted(ListEventRequest $request){
        $fields = $request->validated();
        $deleted_events= $this->searchDeletedEvent($fields['term'], $fields['limit'], $fields['offset']);
        return response()->json([
            'deleted_event' =>EventResource::collection($deleted_events),
            'total' => $this->searchDeletedEvent($fields['term'])
        ]);
    }

    public function showDeleted(ShowEventRequest $request){
       $event = Event::withTrashed()->byHash($request->hash)->get();
       return response()->json([
        'event' => DeletedEventResource::collection($event)
       ]);
       
    }

    public function show(ShowEventRequest $request){
        $fields = $request->validated();
        $event = Event::byHash($fields['hash']);
        if($event->gallery){
            $gallery = $event->gallery->images;
            return response()->json([
                'event' =>new EventResource($event) ,
                'message' =>'event found',
                'gallery' => $gallery ? ImageDisplayResource::collection($gallery):null,
            ]);
        }
        else{
            return response()->json([
                'event' =>new EventResource($event) ,
                
                'message' =>'no img'
            ]);
        }
       
    }

    public function delete(DeleteEventRequest $request){
        $fields = $request->validated();
        $event = Event::byHash($fields['id'])->delete();

        $this->recordActivity('Event Deleted','Events Page Management',$this->ACTION_DELETE);


        return response()->json([
            'message' => 'event deleted succesfully',
            'event' => $event
        ]);
    }

    public function uploadPhoto(UploadEventImageRequest $request)
    {
        $fields = $request->validated();
				
        $event = Event::byHash($fields['hash']);
        $gallery = $event->gallery;
        // Check if event has gallery
        if(!$gallery){
            $gallery = $event->gallery()->create([
                'name' => $event->hash."'s Gallery",
            ]);
        }
				// upload
        $image = $this->uploadImage($request);
				// Manually Bind the image to the gallery by setting gallery_id to the
				// event gallery.
        $image->gallery_id = $gallery->id;
        $image->save();

        return response()->json([
            'message' => 'Succesfully Uploaded Image',
        ]);

    }
    public function deleteImage(Request $request){
        $image = Images::byHash($request->id)->delete();
        return response()->json([
            'image' => $image,
            'message'=> 'deleted img'
        ]);
    }

    public function restoreEvent($id){
        $event = Event::withTrashed()->byHash($id)->restore();
        return response()->json([
            'message' => 'Event Restored Successfully!'
        ]);
    }

}
