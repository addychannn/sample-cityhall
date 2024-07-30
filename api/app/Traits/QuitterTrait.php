<?php
namespace App\Traits;

use App\Models\Quitter;
use Illuminate\Http\Request;


trait QuitterTrait
{
    //search query
    public function searchQuery($term = '',){
        $search_keys = preg_split('/\s+/', $term, -1, PREG_SPLIT_NO_EMPTY);
        $quitters = Quitter::where(function($query) use ($search_keys){
            foreach($search_keys as $key){
                $query->where('hash', 'ilike', '%' .$key.'%')
                    ->orWhere('first_name', 'ilike', '%' .$key. '%')
                    ->orWhere('middle_name', 'ilike', '%' .$key. '%')
                    ->orWhere('last_name', 'ilike', '%' .$key. '%')
                    ->orWhere('suffix', 'ilike', '%' .$key. '%');
            }
        });

        return $quitters;
    }
        
    //paginated search
    public function searchQuitter($search, $limit=5, $offset = 0, $orderBy="created_at", $order="ASC")
    {
        $quitters = $this->searchQuery($search)
                // ->where('status',0)
                ->withTrashed()
                ->offset($offset)
                ->limit($limit)
                ->orderBy($orderBy, $order)
                ->get();

        return $quitters;
    }

    public function searchQuitterCount($search){
        $count = $this->searchQuery($search)
                ->withTrashed()
                ->count();
        return $count;
    }

    //pending
    public function searchPendingQuitter($search, $limit=5, $offset = 0, $orderBy="created_at", $order="ASC"){
        $quitters = $this->searchQuery($search)
        ->where('status',0)
        ->offset($offset)
        ->limit($limit)
        ->orderBy($orderBy, $order)
        ->get();

        return $quitters;
    }
    public function searchPendingQuitterCount($search){
        $count = $this->searchQuery($search)
                ->where('status',0)
                ->count();
        return $count;
    }

    //accepted
    public function searchAcceptedQuitter($search, $limit=5, $offset = 0, $orderBy="created_at", $order="ASC"){
        $quitters = $this->searchQuery($search)
        ->where('status',1)
        ->offset($offset)
        ->limit($limit)
        ->orderBy($orderBy, $order)
        ->get();

        return $quitters;
    }
    public function searchAcceptedQuitterCount($search){
        $count = $this->searchQuery($search)
                ->where('status',1)
                ->count();
        return $count;
    }

    //rejected
    public function searchRejectedQuitter($search, $limit=5, $offset = 0, $orderBy="created_at", $order="ASC"){
        $quitters = $this->searchQuery($search)
        ->onlyTrashed()
        ->offset($offset)
        ->limit($limit)
        ->orderBy($orderBy, $order)
        ->get();

        return $quitters;
    }
    public function searchRejectedQuitterCount($search){
        $count = $this->searchQuery($search)
                ->onlyTrashed()
                ->count();
        return $count;
    }

    //reshceduled
    public function searchRescheduledQuitter($search, $limit=5, $offset = 0, $orderBy="created_at", $order="ASC"){
        $quitters = $this->searchQuery($search)
        ->where('status',3)
        ->offset($offset)
        ->limit($limit)
        ->orderBy($orderBy, $order)
        ->get();

        return $quitters;
    }
    public function searchRescheduledQuitterCount($search){
        $count = $this->searchQuery($search)
                ->where('status',3)
                ->count();
        return $count;
    }
}