<?php

namespace App\Traits;

use App\Models\AboutSection;
use Illuminate\Http\Request;

trait AboutSectionTrait
{
    //search query
    public function searchQuery($term = '',)
    {
        $search_keys = preg_split('/\s+/', $term, -1, PREG_SPLIT_NO_EMPTY);
        $sections = AboutSection::where(function ($query) use ($search_keys) {
            foreach ($search_keys as $key) {
                $query->where('title', 'ilike', '%' . $key . '%');
            }
        });

        return $sections;
    }

    //paginated search

    public function searchSection($search, $limit = 5, $offset = 0, $orderBy = "created_at", $order = "ASC")
    {
        $sections = $this->searchQuery($search)
            ->offset($offset)
            ->limit($limit)
            ->orderBy($orderBy, $order)
            ->get();

        return $sections;
    }

    public function searchSectionCount($search)
    {
        // $count = $this->searchQuery()->count();
        $count = $this->searchQuery($search)
            ->count();
        return $count;
    }
}
