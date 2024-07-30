<?php

namespace App\Traits;

use App\Models\Message;
use Illuminate\Http\Request;


trait MessageTrait
{
    //search query
    public function searchQuery($term = '',)
    {
        $search_keys = preg_split('/\s+/', $term, -1, PREG_SPLIT_NO_EMPTY);
        $message = Message::where(function ($query) use ($search_keys) {
            foreach ($search_keys as $key) {
                $query->where('firstname', 'ilike', '%' . $key . '%')
                    ->orWhere('lastname', 'ilike', '%' . $key . '%')
                    ->orWhere('contact', 'ilike', '%' . $key . '%')
                    ->orWhere('email', 'ilike', '%' . $key . '%');
            }
        });

        return $message;
    }

    //paginated search

    public function searchMessage($search, $limit = 5, $offset = 0, $orderBy = "created_at", $order = "ASC")
    {
        $message = $this->searchQuery($search)
            ->offset($offset)
            ->limit($limit)
            ->orderBy($orderBy, $order)
            ->get();

        return $message;
    }

    public function searchMessageCount($search)
    {
        // $count = $this->searchQuery()->count();
        $count = $this->searchQuery($search)
            ->count();
        return $count;
    }

    //deleted messages
    public function searchDeletedMessage($search, $limit = 10, $offset = 0, $orderBy = "created_at", $order = 'DESC')
    {
        $message = $this->searchQuery($search)
            ->onlyTrashed()
            ->offset($offset)
            ->limit($limit)
            ->orderBy($orderBy, $order)
            ->get();

        return $message;
    }

    public function searchDeletedMessageCount($search)
    {
        $count = $this->searchQuery($search)
            ->onlyTrashed()
            ->count();
        return $count;
    }
}
