<?php

namespace App\Http\Controllers;

use App\Models\Product;

use App\Traits\ProductTrait;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Requests\ListProductRequest;
use App\Http\Requests\DeleteProductRequest;
use App\Http\Requests\ShowProductRequest;
use App\Http\Requests\ImageRequest;
use App\Traits\ImageTrait;


use Illuminate\Http\Request;



class ProductController extends Controller
{
    use ProductTrait, ImageTrait;

    public function create(CreateProductRequest $request){
        $fields = $request->validated();

       $product= Product::create($fields);


       if(!$product){
        return response()->json([
            'message' => 'Unable to Create Product',
            
       ]);
       }
       
       return response()->json([
            // 'hash' => $product->hash,
            'product'=>$product
       ]);
    }

    public function update(UpdateProductRequest $request){
        $fields = $request->validated();

        $product = Product::byHash($fields['id'])
                ->update($fields);

                return response()->json([
                    'message' => 'updated successfully',
                    'product' => $product,
                ]);
    }
    public function list(ListProductRequest $request){
        $fields = $request->validated();

        $products = $this->searchProduct($fields['term'],$fields['limit'],$fields['offset']);

        return response()->json([
            'product' => $products,
            'total' => $this->searchProductCount($fields['term'])

        ]);
    }
    public function delete(DeleteProductRequest $request){
        $fields = $request->validated();
        $product = Product::byHash($fields['id'])->delete();
        return response()->json([
            'message' => 'Product deleted successfully',
            'product' => $product
        ]);

    }

    public function show(ShowProductRequest $request){
        $fields = $request->validated();

        $product = Product::byHash($fields['hash']);

        return response()->json([
            'message' => 'Successfully found product',
            'product' => $product
        ]);
    }

    public function uploadPhoto(ImageRequest $request)
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

    

