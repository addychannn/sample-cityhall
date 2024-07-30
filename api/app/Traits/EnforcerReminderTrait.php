<?php

namespace App\Traits;

use App\Models\Enforcer;
use Illuminate\Http\Request;

trait EnforcerReminderTrait
{
    public function searchQuery($term = '',){
        $search_keys = preg_split('/\s+/', $term, -1, PREG_SPLIT_NO_EMPTY);
        $products = Enforcer::where(function($query) use ($search_keys){
            foreach($search_keys as $key){
                $query->where('title', 'ilike', '%' .$key.'%');
            }
        });

        return $products;
    }

    public function searchReminder($search, $limit=5, $offset = 0, $orderBy="created_at", $order="ASC")
    {
        $products = $this->searchQuery($search)
                        ->offset($offset)
                        ->limit($limit)
                        ->orderBy($orderBy, $order)
                        ->get();

        return $products;
    }

    public function searchReminderCount($search){
        $count = $this->searchQuery()->count();
        return $count;
    }
}
