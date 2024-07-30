<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ImageTrait;
use App\Traits\ProductTrait;
use App\Http\Requests\UploadProductImageRequest;
use App\Models\Product;

class UploadImageController extends Controller
{
    use ProductTrait, ImageTrait;

    public function uploadPhoto(Request $request)
    {
        $fields = $request->validated();
				// Get the product by Hash
        $product = Product::byHash($fields['hash']);
        $gallery = $product->gallery;
        // Check if product has gallery
        if(!$gallery){
            $gallery = $product->gallery()->create([
                'name' => $product->name."'s Gallery",
            ]);
        }
				// upload
        $image = $this->uploadImage($request);
				// Manually Bind the image to the gallery by setting gallery_id to the
				// Product gallery.
        $image->gallery_id = $gallery->id;
        $image->save();

        return response()->json([
            'message' => 'Succesfully Uploaded Image',
        ]);

    }
}
