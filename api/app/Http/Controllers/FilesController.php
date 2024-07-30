<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileResource;
use Illuminate\Http\Request;
use App\Traits\FilesTrait;
use App\Models\Files;
use Defuse\Crypto\File;
use Illuminate\Database\Eloquent\SoftDeletes;

class FilesController extends Controller
{
    use FilesTrait;

    public function __construct()
    {
        $permissions = [
            'enforcer_view_submitted_forms',
            'enforcer_view_submitted_form_preview',
            'enforcer_delete_submitted_form',
            'enforcer_view_uploaded_forms',
            'enforcer_view_uploaded_form_preview',
            'enforcer_delete_uploaded_form',
            'enforcer_upload_new_form',
            'enforcer_view_deleted_uploaded_forms_list',
            'enforcer_restore_deleted_form'
        ];

        $this->middleware("permission:" . implode("|", $permissions), [
            'only' => ['getSubmittedForms']
        ]);
        $this->middleware('permission:enforcer_view_submitted_form_preview', ['only' => ['getUploadedForm']]);
        $this->middleware('permission:enforcer_delete_submitted_form', ['only' => ['deleteForm']]);

        $this->middleware('permission:enforcer_view_uploaded_form_preview', ['only' => ['getUploadedForm']]);
        $this->middleware('permission:enforcer_delete_uploaded_form', ['only' => ['deleteForm']]);

        $this->middleware('permission:enforcer_upload_new_form', ['only' => ['uploadNewForm']]);
        // $this->middleware('permission:enforcer_view_deleted_uploaded_forms_list', ['only' => ['getDeleteForms']]);
        $this->middleware('permission:enforcer_restore_deleted_form', ['only' => ['restoreForm']]);
    }

    public function uploadNewForm(Request $request)
    {
        $is_last = $request->input('is_last');

        $request->validate([
            'uid' => 'nullable|required_if:is_last,false',
            'timestamp' => 'nullable|required_if:is_last,false',
            'file' => 'required|file|max:2048',
            'name' => 'required',
            'chunk' => 'required|integer',
            'ext' => 'required',
            'is_last' => 'required|in:1,0',
        ]);

        $chunk = $request->input('chunk');

        /**
         * Note! You can only validate the mime type on the first chunk.
         */
        if ($chunk <= 0) {
            $validatedData = $request->validate([
                'file' => 'required|file|max:2048|mimes:pdf,docx,xlsx',
            ]);
        }
        //'' folderName
        $result = $this->FileUpload($request, 'Uploaded_Files');
        if ($is_last == 1) {
            $file = Files::create([
                'name' => $result['name'],
                'file_name' => $result['file_name'],
                'path' => $result['path'],
                'ext' => $result['ext'],
                'mime' => $result['mime'],
                'size' => $result['size'],
                'hash_md5' => $result['file_hash_md5'],
                'hash_sha1' => $result['file_hash_sha1'],
                'hash_sha256' => $result['file_hash_sha256'],
                'type' => 'uploaded'
            ]);
            return response([
                "message" => "File uploaded successfully",
                "data" => $file->hashid,
            ]);
        } else {
            return response($result);
        }
    }


    public function getUploadedForms(Request $request)
    {
        $searchTerm = $request->term;
        $forms = Files::whereType('uploaded')
            ->where('name', 'ilike', '%' . $searchTerm . '%')
            ->get();

        return response()->json([
            'forms' => FileResource::collection($forms),
            //'filenames'=>FileResource::collection($forms)->pluck('name')
        ]);
    }



    public function getUploadedForm(Request $request)
    {
        $form = Files::byHash($request->id);



        return response()->json([
            'form' => new FileResource($form),
        ]);
    }

    public function getUploadedFormPreview($id)
    {
        $form = Files::byHash($id);

        return $this->preview($form);
    }


    public function deleteForm(Request $request)
    {
        Files::byHash($request->id)->delete();
    }

    public function getDeleteForms()
    {
        $forms = Files::onlyTrashed()->get();

        return response()->json([
            'forms' => FileResource::collection($forms)
        ]);
    }


    public function downloadForm(Request $request)
    {
        $form = Files::byHash($request->id);
        //return response()->json(['data'=>$form]);
        return $this->download($form);
    }

    public function submitForm(Request $request)
    {
        $is_last = $request->input('is_last');

        $request->validate([
            'uid' => 'nullable|required_if:is_last,false',
            'timestamp' => 'nullable|required_if:is_last,false',
            'file' => 'required|file|max:2048',
            'name' => 'required',
            'chunk' => 'required|integer',
            'ext' => 'required',
            'is_last' => 'required|in:1,0',
        ]);

        $chunk = $request->input('chunk');

        /**
         * Note! You can only validate the mime type on the first chunk.
         */
        if ($chunk <= 0) {
            $validatedData = $request->validate([
                'file' => 'required|file|max:2048|mimes:pdf',
            ]);
        }
        //'' folderName
        $result = $this->FileUpload($request, 'Submitted_Forms');
        if ($is_last == 1) {
            $file = Files::create([
                'name' => $result['name'],
                'file_name' => $result['file_name'],
                'path' => $result['path'],
                'ext' => $result['ext'],
                'mime' => $result['mime'],
                'size' => $result['size'],
                'hash_md5' => $result['file_hash_md5'],
                'hash_sha1' => $result['file_hash_sha1'],
                'hash_sha256' => $result['file_hash_sha256'],
                'type' => 'submitted',
                // 'submitted_by' => $result['submitted_by']
            ]);
            return response([
                "message" => "File uploaded successfully",
                "data" => $file,
            ]);
        } else {
            return response($result);
        }
    }

    public function setSubmitBy(Request $request)
    {
        $file = Files::byHash($request->hash)->update([
            'submitted_by' => $request->title
        ]);
    }


    public function getSubmittedForms(Request $request)
    {
        $searchTerm = $request->term;
        $forms = Files::whereType('submitted')
            ->where('name', 'ilike', '%' . $searchTerm . '%')
            ->get();



        return response()->json([
            'forms' => FileResource::collection($forms)
        ]);
    }

    public function restoreForm($id)
    {
        $forms = Files::withTrashed()->byHash($id)->restore();
        return response()->json([
            'message' => 'Event Restored Successfully!'
        ]);
    }
}
