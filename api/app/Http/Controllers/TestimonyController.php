<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTestimonyRequest;
use App\Http\Requests\DeleteTestimonyRequest;
use App\Http\Requests\ListTestimonyRequest;
use App\Http\Requests\ShowTestimonyRequest;
use App\Http\Requests\UpdateTestimonyRequest;
use App\Http\Requests\UploadTestimonyImageRequest;
use App\Http\Resources\DeletedTestimonyResource;
use App\Http\Resources\ImageDisplayResource;
use App\Http\Resources\TestimonyResource;
use App\Models\Images;
use App\Models\Testimony;
use App\Traits\ImageTrait;
use App\Traits\TestimonyTrait;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TestimonyController extends Controller
{
    use ImageTrait, TestimonyTrait;

    public function __construct(){
        $this->middleware('permission:testimony_add', ['only' => ['create','uploadPhoto']]);
        $this->middleware('permission:testimony_edit', ['only' => ['update','uploadPhoto','deleteImage']]);
        $this->middleware('permission:testimony_delete', ['only' => ['delete']]);
        $this->middleware('permission:testimony_restore', ['only' => ['restoreTestimony']]);
        $this->middleware('permission:testimony_list_deleted', ['only' => ['listDeleted','showDeleted']]);
    }

    public function create(CreateTestimonyRequest $request){
        $fields = $request->validated();
        $testimony = Testimony::create($fields);

        $this->recordActivity('New Testimonial Added','Testimony Page Management',$this->ACTION_CREATE);

        return response()->json([
            'testimony' => $testimony,
            'message' => 'testimony created'
        ]);
    }

    public function uploadPhoto(UploadTestimonyImageRequest $request){
        $fields = $request->validated();
        $testimony = Testimony::byHash($fields['hash']);
        $gallery = $testimony->gallery;


        //check if testimony has gallery
        if(!$gallery){
            $gallery = $testimony->gallery()->create([
                'name' => $testimony->hash." 's Gallery",
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

    public function list(ListTestimonyRequest $request){
        $fields = $request->validated();
        $testimonies = $this->searchTestimony($fields['term'],$fields['limit'],$fields['offset']);
        return response()->json([
            'testimonies' => TestimonyResource::collection($testimonies),
            'total' => $this->searchTestimoniesCount($fields['term'])
        ]);
    }

    public function show(ShowTestimonyRequest $request){
        $fields = $request->validated();
        $testimony = Testimony::byHash($fields['hash']);
        return response()->json([
            'testimony' =>new TestimonyResource($testimony) ,
            'message' =>'testimony found',
        ]);
    }

    public function showDeleted(Request $request){
        $testimony = Testimony::withTrashed()->byHash($request->hash)->get();
        return response()->json([
            'testimony' => DeletedTestimonyResource::collection($testimony)
        ]);
    }

    public function update(UpdateTestimonyRequest $request){
        $fields = $request->validated();
        $testimony = Testimony::byHash($fields['id'])->update($fields);

        $this->recordActivity('"'.$fields['title'].'"Testimony Details Updated','Testimony Page Management',$this->ACTION_CREATE);

        return response()->json([
            'testimony' => $testimony,
            'message' => 'updated'
        ]);
    }

    public function delete(DeleteTestimonyRequest $request){
        $fields = $request->validated();
        $testimony = Testimony::byHash($fields['id'])->delete();

        $this->recordActivity('Testimony Deleted','Testimony Page Management',$this->ACTION_DELETE);

        return response()->json([
            'message' => 'deleted',
            'testimony' => $testimony
        ]);
    }

    public function deleteImage(Request $request){
        $image = Images::byHash($request->id)->delete();
        return response()->json([
            'image' => $image,
            'message'=> 'deleted img'
        ]);
    }

    public function listDeleted(ListTestimonyRequest $request){
        $fields = $request->validated();
        $testimonies = $this->searchDeletedTestimony($fields['term'],$fields['limit'],$fields['offset']);
        return response()->json([
            'testimonies' => TestimonyResource::collection($testimonies),
            'total' => $this->searchDeletedTestimoniesCount($fields['term'])
        ]);
    }

    public function restoreTestimony($id){
        $testimony = Testimony::withTrashed()->byHash($id)->restore();
        return response()->json([
            'message' => 'Testimony Restored Successfully!'
        ]);
    }
}
