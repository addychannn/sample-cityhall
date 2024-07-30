<?php

namespace App\Http\Controllers;

use App\Traits\ArticleTrait;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Http\Requests\CreateArticlesRequest;
use App\Http\Requests\ListArticleRequest;
use App\Http\Requests\ViewArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Requests\ShowArticleRequest;
use App\Http\Requests\DeleteArticleRequest;
use App\Http\Resources\ImageDisplayResource;
use App\Http\Resources\DeletedArticleResource;
use App\Http\Resources\ArticlesResource;
use App\Http\Requests\ImageRequest;
use App\Traits\ImageTrait;
use App\Models\Images;
use Carbon\Carbon;


class ArticleController extends Controller
{
    use ArticleTrait, ImageTrait;

    public function __construct(){
        $this->middleware('permission:article_list-deleted', ['only' => ['listDeleted','showDeleted']]);
        $this->middleware('permission:article_add', ['only' => ['create','uploadPhoto']]);
        $this->middleware('permission:article_edit', ['only' => ['update','deleteImage','uploadPhoto']]);
        $this->middleware('permission:article_delete', ['only' => ['delete']]);
    }

    public function create(CreateArticlesRequest $request){
        $fields = $request->validated();

        $article= Article::create($fields);
 
 
        if(!$article){
         return response()->json([
             'message' => 'Unable to Create article',
             
        ]);
        }
        
        return response()->json([
             
             'article'=>$article
        ]);
    } 

    public function list(ListArticleRequest $request){
        $fields = $request->validated();

        $article = $this->searchArticle($fields['term'],$fields['limit'],$fields['offset']);

        return response()->json([
            'article' => ArticlesResource::collection( $article),
            'total' => $this->searchArticleCount($fields['term'])

        ]);
    }

    

    public function listDeleted(ListArticleRequest $request){
        $fields =$request->validated();
        $deleted_articles = $this->searchDeletedArticle($fields['term'], $fields['limit'], $fields['offset']);
        return response()->json([
            'deleted_article' =>ArticlesResource::collection($deleted_articles),
            'total' =>$this->searchDeletedArticle($fields['term'])
        ]);
    }

    public function view(ViewArticleRequest $request){
        $fields = $request->validated();

        $article = Article::byHash($fields['hash'])
                ->view($fields);

                return response()->json([
                    'message' => 'view',
                    'article' => $article,
                ]);
    }
    public function update(UpdateArticleRequest $request){
        $fields = $request->validated();

        $article = Article::byHash($fields['id'])
                ->update($fields);

                return response()->json([
                    'message' => 'updated successfully',
                    'article' => $article,
                ]);
    }

    public function show(ShowArticleRequest $request){
        $fields = $request->validated();
        $article = Article::byHash($fields['hash']);
        if($article->gallery){
            $gallery = $article->gallery->images;
            return response()->json([
                'article' =>new ArticlesResource($article) ,
                'message' =>'article found',
                'gallery' => $gallery ? ImageDisplayResource::collection($gallery):null,
            ]);
        }
        else{
            return response()->json([
                'article' =>new ArticlesResource($article) ,
                'message' =>'no img'
            ]);
        }
    }

    public function showDeleted(ShowArticleRequest $request){
        $article = Article::withTrashed()->byHash($request->hash)->get();
        return response()->json([
         'article' => DeletedArticleResource::collection($article)
        ]);
        
     }

    public function delete(DeleteArticleRequest $request){
        $fields = $request->validated();
        $article = Article::byHash($fields['id'])->delete();

        $this->recordActivity('Article Deleted','Article Page Management',$this->ACTION_DELETE);


        return response()->json([
            'message' => '$article deleted succesfully',
            'article' => $article
        ]);
    }

    public function uploadPhoto(ImageRequest $request)
    {
        $fields = $request->validated();
				
        $article = Article::byHash($fields['hash']);
        $gallery = $article->gallery;
        // Check if article has gallery
        if(!$gallery){
            $gallery = $article->gallery()->create([
                'name' => $article->name."'s Gallery",
            ]);
        }
				// upload
        $image = $this->uploadImage($request);
				// Manually Bind the image to the gallery by setting gallery_id to the
				// article gallery.
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

    public function restoreArticle($id){
        $article = Article::withTrashed()->byHash($id)->restore();
        return response()->json([
            'message' => 'Article Restored Successfully!'
        ]);
    }

    

    
}
