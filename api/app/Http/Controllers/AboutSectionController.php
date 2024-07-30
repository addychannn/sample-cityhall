<?php

namespace App\Http\Controllers;

use App\Http\Resources\ImageDisplayResource;
use App\Models\AboutSection;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use App\Http\Requests\CreateSectionRequest;
use App\Http\Requests\DeleteSectionRequest;
use App\Http\Requests\UpdateSectionDetailsRequest;
use App\Http\Requests\UploadAboutPhotoRequest;
use App\Http\Resources\AboutSectionResource;
use App\Models\Enforcer;
use App\Models\Gallery;
use App\Models\Images;
use App\Traits\AboutSectionTrait;
use App\Traits\LoggerTrait;
use Illuminate\Auth\Events\Validated;
use PhpParser\Node\Expr\Cast\Object_;

class AboutSectionController extends Controller
{
    use ImageTrait,LoggerTrait,AboutSectionTrait;

    public function __construct(){
        $permissions = [
            'about_section_list',
            'about_section_add',
            'about_section_edit',
            'about_section_delete',
            'about_section_visibility',
            'about_section_arrange',
        ];
        $this->middleware("permission:".implode("|",$permissions),[
            'only'=> ['getSectionList']
        ]);
        $this->middleware('permission:about_section_add', ['only' => ['createSection','uploadPhoto']]);
        $this->middleware('permission:about_section_edit', ['only' => ['updateSection','getSection','deleteImages','uploadPhoto']]);
        $this->middleware('permission:about_section_delete', ['only' => ['deleteSection']]);
        $this->middleware('permission:about_section_visibility', ['only' => ['showInPublic']]);
        $this->middleware('permission:about_section_arrange', ['only' => ['arrangeSections']]);
    }

    public function getSectionList(Request $request){
        $list = AboutSection::with('gallery.images')->get();

        $list = $this->searchSection($request->term,$request->limit,$request->offset);


        return response()->json([
            'data'=>$list,
            'total'=>$this->searchSectionCount($request->term)
        ]);
    }

    public function createSection(CreateSectionRequest $request){
        $fields=$request->validated();

        $section=AboutSection::create($fields);

        $this->recordActivity('New Section"'.$fields['title'].'" created','About Page Management',$this->ACTION_CREATE);

        return response()->json([
            'section' => $section,
            'message'=> 'Success'
        ]);
    }

    public function uploadPhoto(UploadAboutPhotoRequest $request)
    {
        $fields = $request->validated();
				// Get the product by Hash
        $section = AboutSection::byHash($fields['hash']);
        $gallery = $section->gallery;
        // Check if product has gallery
        if(!$gallery){
            $gallery = $section->gallery()->create([
                'name' => $section->title."'s Gallery",
            ]);
        }
				// upload
        $image = $this->uploadImage($request);
				// Manually Bind the image to the gallery by setting gallery_id to the
				// Product gallery.
        $image->gallery_id = $gallery->id;
        $image->save();

        $this->recordActivity('Photos Added to "'.$section->title.'Section"','About Page Management',$this->ACTION_UPDATE);

        return response()->json([
            'message' => 'Succesfully Uploaded Image',
        ]);

    }

    public function getSection(Request $request){
        $section  = AboutSection::byHash($request->hash);
        if($section->gallery){
            $gallery = $section->gallery->images;
            return response()->json([
                'message'=>'section fetched',
                "data"=>$section,
                'gallery'=>ImageDisplayResource::collection($gallery)
            ]);
        }
        else{
            return response()->json([
                'message'=>'section fetched',
                "data"=>$section,
            ]);
        }

    }

    public function updateSection(UpdateSectionDetailsRequest $request)
    {
        $fields = $request->validated();
        $section = AboutSection::byHash($request->hash)->update($fields);

        $this->recordActivity('Changes Made on "'.$fields['title'].'" Section Details','About Page Management',$this->ACTION_UPDATE);

        return response()->json([
            'message'=>'Updated Successfully',
            'data'=>$section
        ]);


    }

    public function deleteImages(Request $request){
        foreach($request->hash as $hash ){
            Images::byHash($hash)->delete();
        }

        $this->recordActivity('Images Deleted at About Page','About Page Management',$this->ACTION_DELETE);

        return response()->json([
            'message'=>"Success"
        ]);
    }

    public function showInPublic(Request $request){
        $section = AboutSection::byHash($request->hash);
        $section->show=!$section->show;
        $section->save();

        $show=$section->show;

        $this->recordActivity('Section"'.$section->title.'" Visibility Changed','About Page Management',$this->ACTION_UPDATE);

        return response()->json([
            'message'=>$show==true ? 'Section Now Visible on Public' : 'Section Hidden on Public',
            'section'=>$section
        ]);
    }

    public function deleteSection(DeleteSectionRequest $request){
        $field = $request->validated();

        // $images = AboutSection::byHash($field['hash'])->gallery->images;
        // $gallery = AboutSection::byHash($field['hash'])->gallery;
        $section = AboutSection::byHash($field['hash']);


        if($section->gallery){
            $images=$section->gallery->images;

            //delete all images in gallery
            foreach($images as $image){
                 $image->delete();
            }

            //delete the gallery of the section after deleting images
            $gallery=$section->gallery;
            $gallery->delete();

            $section->delete();

            $this->recordActivity('Section"'.$section->title.'" Deleted','About Page Management',$this->ACTION_DELETE);

            return response()->json([
                'message'=>'Successful Delete',

            ]);
        }
        else{
            $section->delete();
            return response()->json([
            'message'=>'Successful Delete']);
        }

    }

    public function arrangeSections(Request $request){
        $new = $request->sections;
        $hash='';

        for($i=0;$i<count($new);$i++){
                $hash=$new[$i]['hash'];
                $change = AboutSection::byHash($hash);
                $change->position=$i+1;
                $change->save();
        }

        $sections = AboutSection::all()->sortBy('position');

        $this->recordActivity('About Page Sections Rearranged','About Page Management',$this->ACTION_UPDATE);

        return response()->json([
            'message'=>'Success',
            'sections'=>$sections,
        ]);
    }

    public function sectionList(){
        $sections = AboutSection::with('gallery.images')->where('show',true)->orderBy('position')->get();

        return response()->json([
            'data'=>AboutSectionResource::collection($sections),
        ]);
    }

    public function getImages(){
        $sections = AboutSection::with('gallery.images')->whereHas('gallery')->orderBy('position')->get();
        $sections = AboutSectionResource::collection($sections);

        return response()->json([
            'data'=>$sections,
        ]);
        }


        public function test(){
            $enforcer=Enforcer::all();
            return response()->json(['data'=>$enforcer]);
        }

}



