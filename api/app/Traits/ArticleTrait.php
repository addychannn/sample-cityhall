<?php

namespace App\Traits;

use App\Models\Article;
use Illuminate\Http\Request;


trait ArticleTrait
{
    //search query
    public function searchQuery($term = '',)
    {
        $search_keys = preg_split('/\s+/', $term, -1, PREG_SPLIT_NO_EMPTY);
        $article = Article::where(function ($query) use ($search_keys) {
            foreach ($search_keys as $key) {
                $query->where('articleTitle', 'ilike', '%' . $key . '%')
                    ->orWhere('description', 'ilike', '%' . $key . '%')
                    ->orWhere('created_at', 'ilike', '%' . $key . '%');
            }
        });

        return $article;
    }

    //paginated search

    public function searchArticle($search, $limit = 5, $offset = 0, $orderBy = "created_at", $order = "DESC")
    {
        $article = $this->searchQuery($search)
            ->offset($offset)
            ->limit($limit)
            ->orderBy($orderBy, $order)
            ->get();

        return $article;
    }

    public function searchArticleCount($search)
    {
        // $count = $this->searchQuery()->count();
        $count = $this->searchQuery($search)
            ->count();
        return $count;
    }

    // deleted articles
    public function searchDeletedArticle($search, $limit = 10, $offset = 0, $orderBy = "created_at", $order = "ASC")
    {
        $article = $this->searchQuery($search)
            ->onlyTrashed()
            ->offset($offset)
            ->limit($limit)
            ->orderBy($orderBy, $order)
            ->get();
        return $article;
    }

    public function searchDeletedArticleCount($search)
    {
        $count = $this->searchQuery($search)
            ->onlyTrashed()
            ->count();
        return $count;
    }
}
