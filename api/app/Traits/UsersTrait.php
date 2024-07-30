<?php

namespace App\Traits;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Profile;

trait UsersTrait
{
    use LikeToggleTrait;

    public function searchUserQuery($search= "", $column = "username"){
        $searchKeys = preg_split('/\s+/', $search ?? "", -1, PREG_SPLIT_NO_EMPTY);
        $user = User::where(function ($query) use ($searchKeys, $column) {
            foreach ($searchKeys as $key) {
                $query->when($column != 'name', function ($qq) use ($key, $column){
                        $qq->orWhere($column, self::LikeToggle(), '%' . $key . '%')
                        ->orWhere('username','ilike','%'.$key.'%');
                    })
                    ->when($column == 'name', function ($qq) use ($key) {
                        $qq->orWhere(function($q) use ($key){
                            $q->whereRelation('profile', 'first_name', self::LikeToggle(), '%' . $key . '%');
                        })
                        ->orWhere(function($q) use ($key){
                            $q->whereRelation('profile', 'middle_name', self::LikeToggle(), '%' . $key . '%');
                        })
                        ->orWhere(function($q) use ($key){
                            $q->whereRelation('profile', 'last_name', self::LikeToggle(), '%' . $key . '%');
                        })
                        ->orWhere('username','ilike','%'.$key.'%');
                    });
            }
        });
        return $user;
    }

    public function searchUser($search, $limit = 10, $offset = 0, $orderBy = "username", $order = "asc", $column = "username"){
        $user = $this->searchUserQuery($search, $column)
            ->when($orderBy == 'name', function($query) use ($order){
                $query->orderBy(
                    Profile::select('first_name')
                    ->whereColumn('profiles.user_id', 'users.id'),
                $order)->orderBy(
                    Profile::select('middle_name')
                    ->whereColumn('profiles.user_id', 'users.id'),
                $order)->orderBy(
                    Profile::select('last_name')
                    ->whereColumn('profiles.user_id', 'users.id'),
                $order);
            })
            ->when($orderBy != 'name', function($query) use ($orderBy, $order){
                $query->orderBy($orderBy, $order);
            })
            ->offset($offset)->limit($limit)
            ->with('profile')
            ->with('roles')
            //->with('gender')
            ->with('permissions')
            ->with('profile.addresses')
            ->with('profile.addresses.barangay')
            ->with('profile.addresses.type')
            ->with('profile.image')
            ->with('profile.images');
        return $user;
    }

    public function searchUserCount($search, $column = "username"){
        $user = $this->searchUserQuery($search, $column)
            ->selectRaw('count(*) as count');
        return $user;
    }
}