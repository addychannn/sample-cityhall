<?php

namespace App\Traits;

use App\Models\Product;
use Illuminate\Http\Request;


trait ProductTrait
{
    //search query
    public function searchQuery($term = '',){
        $search_keys = preg_split('/\s+/', $term, -1, PREG_SPLIT_NO_EMPTY);
        $products = Product::where(function($query) use ($search_keys){
            foreach($search_keys as $key){
                $query->where('name', 'ilike', '%' .$key.'%')
                    ->orWhere('description', 'ilike', '%' .$key. '%');
            }
        });

        return $products;
    }
        
    //paginated search

    public function searchProduct($search, $limit=5, $offset = 0, $orderBy="created_at", $order="ASC")
    {
        $products = $this->searchQuery($search)
                        ->offset($offset)
                        ->limit($limit)
                        ->orderBy($orderBy, $order)
                        ->get();

        return $products;
    }

    public function searchProductCount($search){
        $count = $this->searchQuery()->count();
        return $count;
    }
}