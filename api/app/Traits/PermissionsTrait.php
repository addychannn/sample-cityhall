<?php

namespace App\Traits;
use Illuminate\Http\Request;

use Spatie\Permission\Models\Permission;

trait PermissionsTrait
{
    use LikeToggleTrait;
    
    public function searchPermissionQuery($search = ""){
        $searchKeys = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);
        $permission = Permission::where(function ($query) use ($searchKeys) {
            foreach ($searchKeys as $key) {
                $query->orWhere('name', self::LikeToggle(), '%' . $key . '%');
                $query->orWhere('description', self::LikeToggle(), '%' . $key . '%');
            }
        });
        return $permission;
    }

    public function searchPermission($search = "",  $offset = 0, $orderBy = "id", $order = "asc"){
        $permission = $this->searchPermissionQuery($search)
            ->orderBy($orderBy, $order)->offset($offset);
            // ->limit($limit);
        return $permission;
    }

    public function searchPermissionCount($search = ""){
        $permission = $this->searchPermissionQuery($search)
            ->selectRaw('count(*) as count');
        return $permission;
    }
}