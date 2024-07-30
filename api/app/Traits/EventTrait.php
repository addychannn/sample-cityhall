<?php

namespace App\Traits;

use App\Models\Event;
use Illuminate\Http\Request;


trait EventTrait
{
    //search query
    public function searchQuery($term = '',)
    {
        $search_keys = preg_split('/\s+/', $term, -1, PREG_SPLIT_NO_EMPTY);
        $event = Event::where(function ($query) use ($search_keys) {
            foreach ($search_keys as $key) {
                $query->where('event_title', 'ilike', '%' . $key . '%')
                    ->orWhere('description', 'ilike', '%' . $key . '%')
                    ->orWhere('start_date', 'ilike', '%' . $key . '%');
                // ->orWhere('end_date', 'ilike', '%' .$key. '%');
            }
        });

        return $event;
    }

    //paginated search

    public function searchEvent($search, $limit = 5, $offset = 0, $orderBy = "created_at", $order = "DESC")
    {
        $event = $this->searchQuery($search)
            ->offset($offset)
            ->limit($limit)
            ->orderBy($orderBy, $order)
            ->get();

        return $event;
    }

    public function searchEventCount($search)
    {
        // $count = $this->searchQuery()->count();
        $count = $this->searchQuery($search)
            ->count();
        return $count;
    }

    //deleted events
    public function searchDeletedEvent($search, $limit = 10, $offset = 0, $orderBy = "created_at", $order = "DESC")
    {
        $event = $this->searchQuery($search)
            ->onlyTrashed()
            ->offset($offset)
            ->limit($limit)
            ->orderBy($orderBy, $order)
            ->get();
        return $event;
    }

    public function searchDeletedEventCount($search)
    {
        $count = $this->searchQuery($search)
            ->onlyTrashed()
            ->count();
        return $count;
    }
}
