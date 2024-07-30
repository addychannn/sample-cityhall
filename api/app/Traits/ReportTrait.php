<?php
namespace App\Traits;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


trait ReportTrait
{
        //search query
        public function searchQuery($term = '',){
            $search_keys = preg_split('/\s+/', $term, -1, PREG_SPLIT_NO_EMPTY);
            $reports = Report::with(['gallery','gallery.images'])
                        ->where(function($query) use ($search_keys){
                foreach($search_keys as $key){
                    $query->orWhere('violator_name','ilike','%'.$key.'%')
                            ->orWhere('hash','ilike','%'.$key.'%');
                }
            });
            return $reports;
        }
        
        //paginated search
        public function searchReport($search, $limit=5, $offset = 0, $orderBy="created_at", $order="ASC")
        {
            $reports = $this->searchQuery($search)
                    // ->where('status',0)
                    ->withTrashed()
                    ->offset($offset)
                    ->limit($limit)
                    ->orderBy($orderBy, $order)
                    ->get();
    
            return $reports;
        }
    
        public function searchReportCount($search){
            $count = $this->searchQuery($search)
            // ->where('status',0)
            ->withTrashed()
            ->count();
            return $count;
        }

        //pending
        public function searchPendingReport($search, $limit=5, $offset = 0, $orderBy="created_at", $order="ASC")
        {
            $reports = $this->searchQuery($search)
                    ->where('status',0)
                    ->offset($offset)
                    ->limit($limit)
                    ->orderBy($orderBy, $order)
                    ->get();
    
            return $reports;
        }
    
        public function searchPendingReportCount($search){
            $count = $this->searchQuery($search)
            ->where('status',0)
            ->count();
            return $count;
        }

        //rejected
        public function searchRejectedReport($search, $limit=5, $offset = 0, $orderBy="created_at", $order="ASC")
        {
            $reports = $this->searchQuery($search)
                    // ->where('status',2)
                    ->onlyTrashed()
                    ->offset($offset)
                    ->limit($limit)
                    ->orderBy($orderBy, $order)
                    ->get();
    
            return $reports;
        }
    
        public function searchRejectedReportCount($search){
            $count = $this->searchQuery($search)
            ->onlyTrashed()
            ->count();
            return $count;
        }

        //resolved
        public function searchResolvedReport($search, $limit=5, $offset = 0, $orderBy="created_at", $order="ASC")
        {
            $reports = $this->searchQuery($search)
                    ->where('status',1)
                    ->offset($offset)
                    ->limit($limit)
                    ->orderBy($orderBy, $order)
                    ->get();
    
            return $reports;
        }

        public function searchResolvedReportCount($search){
            $count = $this->searchQuery($search)
            ->where('status',1)
            ->count();
            return $count;
        }
}