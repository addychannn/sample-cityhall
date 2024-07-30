<?php
namespace App\Traits;

use App\Models\Testimony;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


trait TestimonyTrait
{
        //search query
        public function searchQuery($term = '',){
            $search_keys = preg_split('/\s+/', $term, -1, PREG_SPLIT_NO_EMPTY);
            $testimonies = Testimony::with(['gallery','gallery.images'])
                            ->where(function($query) use ($search_keys){
                foreach($search_keys as $key){
                    $query->orWhere('title','ilike','%'.$key.'%')
                            ->orWhere('body', 'ilike', '%' .$key. '%')
                            ->orWhere('created_at','ilike','%'.$key.'%');
                }
            });
            return $testimonies;
        }
        
        //paginated search
        public function searchTestimony($search, $limit=5, $offset = 0, $orderBy="created_at", $order="ASC")
        {
            $testimonies = $this->searchQuery($search)
                    ->offset($offset)
                    ->limit($limit)
                    ->orderBy($orderBy, $order)
                    ->get();
    
            return $testimonies;
        }
    
        public function searchTestimoniesCount($search){
            $count = $this->searchQuery($search)->count();
            return $count;
        }

        //deleted
        public function searchDeletedTestimony($search, $limit=5, $offset = 0, $orderBy="created_at", $order="ASC")
        {
            $testimonies = $this->searchQuery($search)
                    ->onlyTrashed()
                    ->offset($offset)
                    ->limit($limit)
                    ->orderBy($orderBy, $order)
                    ->get();
    
            return $testimonies;
        }
    
        public function searchDeletedTestimoniesCount($search){
            $count = $this->searchQuery($search)
                    ->onlyTrashed()
                    ->count();
            return $count;
        }
}