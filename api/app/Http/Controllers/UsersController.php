<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetUserByIdRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use App\Models\User;
use App\Models\Profile;
use App\Models\Address;
use App\Models\AccountStatusHistory;
use App\Models\Images;

use App\Traits\UsersTrait;
use App\Traits\ImageTrait;
use App\Traits\AddressTrait;

use App\Http\Resources\UserResource;
use App\Http\Resources\AddressResource;
use App\Http\Resources\MainAddressResource;
use App\Http\Resources\RolesResource;
use App\Http\Resources\PermissionsResource;
use App\Http\Resources\ProfilesResource;
use App\Models\Gender;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    use UsersTrait, ImageTrait, AddressTrait;

    public function __construct(){
        $permissions = [
            'users_add',
            'users_list',
            'users_edit-profile',
            'users_edit-account',
            'users_edit-permission',
            'users_change-status',
        ];

        $this->middleware("permission:".implode("|", $permissions), [
            'only' => ['userSearch', 'getUserByUsername', 'getUserStatusHistory']
        ]);

        $this->middleware('permission:users_add|users_edit-permission', ['only' => ['getRoles']]);
        $this->middleware('permission:users_add', ['only' => ['addUser']]);
        $this->middleware('permission:users_edit-profile', ['only' => ['updateUserProfile','createOrUpdateUserProfile', 'setAddressAsMain', 'updateAddress', 'deleteAddress', 'saveAvatar', 'changeAvatar', 'deleteAvatar']]);
        $this->middleware('permission:users_edit-account', ['only' => ['updateAccount', 'updatePassword']]);
        $this->middleware('permission:users_change-status', ['only' => ['toggleUserActive']]);
        $this->middleware('permission:users_edit-permission', ['only' => ['setPermissions']]);
    }

    public function userSearch(Request $request){
        $search = $request->input('search');
        $limit = $request->input('limit', 10);
        $offset = $request->input('offset', 0);
        $orderBy = $request->input('orderBy', 'username');
        $order = $request->input('order', 'asc');
        $column = $request->input('column', 'name');
        $role = $request->input('role', null);

        $auth = auth()->user();
        $isSuperAdmin = $auth->hasRole('Admin');

        $user = $this->searchUser($search, $limit, $offset, $orderBy, $order,$column)
            ->when($role != null, function ($q) use ($role){
                $q->role($role);
            })
            ->when(!$isSuperAdmin, function($query) use ($auth){
                return $query->whereRelation('roles', 'name', '<>', 'Admin')
                    ->orDoesntHave('roles');
            })
            // ->where('id', '<>', $auth->id)
            ->get();

        $count = $this->searchUserCount($search,$column)
            ->when($role != null, function ($q) use ($role){
                $q->role($role);
            })
            ->when(!$isSuperAdmin, function($query) use ($auth){
                return $query->whereRelation('roles', 'name', '<>', 'Admin')
                    ->orDoesntHave('roles');
            })
            // ->where('id', '<>', $auth->id)
            ->first()->count;

        $auth = auth()->user();
        $isSuperAdmin = $auth->hasRole('Admin');

        $roles = Role::when(!$isSuperAdmin, function($query){
            return $query->where('name', '<>', 'Admin');
        })->get(['id', 'name']);

        return response([
            'count' => $count,
            'data' => UserResource::collection($user),
            'roles' => $roles,
            'logged_user' => $auth
        ], 200);
    }

    public function getUserByUsername($username){
        $user = User::where('username', $username)->first();
        return response([
            'data' => new UserResource($user)
        ], 200);
    }

    public function getUserById($id){
        $user = User::byHash($id);

        if($user->profile){
            $main = $user->profile->addresses->where('isMain',true);
            if($main){
                return response()->json([
                    'data' => new UserResource($user),
                    'main_address' => MainAddressResource::collection($main)
                ], 200); 
            }
            return response()->json([
                'data' => new UserResource($user)
            ], 200); 
        }
        else{
            return response()->json([
                'data' => new UserResource($user)
            ], 200); 
        }
        
    }

    public function getGenders(){
        $genders = Gender::all();
        return response([
            'data' => $genders
        ]);
    }
    
    public function getUserStatusHistory($id){
        $history = AccountStatusHistory::where('user_id', $id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        return response([
            'data' => $history
        ], 200);
    }

    public function getPermissions(){
        $permissions = Permission::get(['id', 'name', 'description']);
        return response([
            'data' => $permissions
        ], 200);
    }

    public function getRoles(Request $request){
        $includePermissions = $request->input('p', false);

        $auth = auth()->user();
        $isSuperAdmin = $auth->hasRole('Admin');

        $roles = Role::when(!$isSuperAdmin, function($query){
            return $query->where('name', '<>', 'Admin');
        });

        if($includePermissions){
            $roles = RolesResource::collection($roles->with('permissions')->get());
            return response([
                'roles' => $roles,
                'permissions' => Permission::orderBy('name', 'ASC')->get(['id', 'name', 'description']),
            ], 200);
        }
        return response([
            'data' => $roles->get(['id', 'name']),
        ], 200);
    }

    public function addUser(Request $request){
        $request->validate([
            'username' => 'required|max:75|unique:users,username',
            'password' => 'required|min:8',
            'email' => 'nullable|email|max:255|unique:users,email',
            'role' => 'nullable|exists:roles,id',
        ]);

        $role = Role::where('name', 'User')->first();
        if($request->input('role')){
            $role = Role::find($request->input('role'));
        }
        
        $auth = auth()->user();
        if($role->name == 'Admin' && !$auth->hasRole('Admin')){
            $this->logError('An unauthorized attempted to create a "Super Admin" Account!', 'User Management', $this->ACTION_CREATE, null, [$request->all(), $role]);
            return response([
                'message' => "Unauthorized action detected!"
            ], 403);
        }

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'email' => $request->email,
        ]);

        Profile::create([
            'user_id' => $user->id
        ]);
        
        $user->markEmailAsVerified(); // Mark email as verified regardless of if email exists since account is created by admin  admin / authorized user.
        
        $user->assignRole($role);

        $result = new UserResource($user);

        $this->logInfo('New "'.$role->name.'" created', 'User Management', $this->ACTION_CREATE, null, $result);

        return response([
            'message' => 'User created successfully',
            'data' => $result
        ], 201);
    }

    public function updateUserProfile(Request $request, $id){
        $request->validate([
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'required|string',
            'suffix' => 'nullable',
            'nickname' => 'nullable',
            'gender' => 'required|alpha|max:1|in:m,f',
            'birthdate' => [
                'required', 'date', 'date_format:Y-m-d',
                'before_or_equal:'.now()->format('Y-m-d'),
            ],
            'address' => ''
        ],[
            'birthdate.before_or_equal' => 'Sorry, you cannot be born in the future.'
        ]);

        $user = User::byHashOrFail($id);
        $old = [
            'profile' => $user->profile,
            'addresses' => $user->profile?->addresses ? AddressResource::collection($user->profile->addresses) : null,
        ];
        // $old = json_decode(json_encode(new ProfileResource($user->profile)));
        $user->profile()->updateOrCreate([
            'user_id' => $user->id
        ], [
            'first_name' => ucwords(strtolower($request->input('first_name'))),
            'middle_name' => ucwords(strtolower($request->input('middle_name'))),
            'last_name' => ucwords(strtolower($request->input('last_name'))),
            'suffix' => $request->input('suffix'),
            'birthdate' => $request->input('birthdate'),
            'gender' => $request->input('gender'),
            'nickname' => $request->input('nickname') ? ucwords(strtolower($request->input('nickname'))) : null,
            'address' => $request->input('address'),
        ]);

        // if($request->file('file')){
        //     $this->SaveAvatar($request);
        // }

        $user->refresh();

        if($request->addresses && count($request->addresses) > 0){
            $tmp = [];
            $hasMain = false;
            foreach($request->addresses as $address){
                if($address['isMain'] == true) $hasMain = true;
                $tmp[] = $address;
            }
            if(!$hasMain){
                $tmp[0]['isMain'] = true;
            }
            $addresses = collect($tmp)->map(function ($item, $key) use ($user){
                return collect($item)->put('profile_id', $user->profile->id)
                    ->put('created_at', now())
                    ->put('updated_at', now());
            });

            $updates = $addresses->filter(function ($value, $key) {
                return $value['id'] != null;
            });
            foreach($updates->toArray() as $update){
                $toUpdate = Address::where('id', $update['id'])->first();
                $toUpdate->update([
                    'type_id' => $update['type_id'],
                    'location' => $update['location'],
                    'barangayCode' => $update['barangayCode'],
                    'isMain' => $update['isMain'],
                ]);
            }
            $creates = $addresses->filter(function ($value, $key) {
                return $value['id'] == null;
            })->map(function ($item, $key){
                return $item->except(['id']);
            });
            Address::insert($creates->toArray());
            // Address::upsert($addresses->toArray(), ['id'], ['profile_id', 'type_id', 'location', 'barangayCode', 'isMain']);
        }

        $user->refresh();

        $new = [
            'profile' => $user->profile,
            'addresses' => $user->profile?->addresses ? AddressResource::collection($user->profile->addresses) : null,
        ];
        $this->logInfo('User profile updated', 'User Management', $this->ACTION_UPDATE, $old, $new);

        return response([
            'data' => new UserResource($user),
            'message' => 'User profile updated successfully',
        ], 200);
    }

    public function createOrUpdateUserProfile(Request $request, $id){
        $request->validate([
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'required|string',
            'suffix' => 'nullable',
            'nickname' => 'nullable',
            'gender' => 'required',
            'birthdate' => [
                'required', 'date',
                'before_or_equal:'.now()
            ],
            'location' => 'nullable',
            'barangay_code' => 'nullable'
        ],[
            'birthdate.before_or_equal' => 'Sorry, you cannot be born in the future.'
        ]);

        $user = User::byHash($id)->profile;
        // $old = [
        //     'profile' => $user,
        //     'addresses' => $user?->addresses ? AddressResource::collection($user->profile->addresses) : null,
        // ];

        if($user){
            $user->update([
                'first_name' => ucwords(strtolower($request->input('first_name'))),
                'middle_name' => ucwords(strtolower($request->input('middle_name'))),
                'last_name' => ucwords(strtolower($request->input('last_name'))),
                'suffix' => $request->input('suffix'),
                'birthdate' => $request->input('birthdate'),
                'gender_id' => $request->input('gender'),
                'nickname' => $request->input('nickname') ? ucwords(strtolower($request->input('nickname'))) : null
            ]);
        }
        else{
            $user = User::byHash($id);
            Profile::create([
                'user_id' => $user->id
            ]);

            $user->profile->update([
                'first_name' => ucwords(strtolower($request->input('first_name'))),
                'middle_name' => ucwords(strtolower($request->input('middle_name'))),
                'last_name' => ucwords(strtolower($request->input('last_name'))),
                'suffix' => $request->input('suffix'),
                'birthdate' => $request->input('birthdate'),
                'gender_id' => $request->input('gender'),
                'nickname' => $request->input('nickname') ? ucwords(strtolower($request->input('nickname'))) : null
            ]);
        }

        if($request->input('location') || $request->input('barangay_code')){
            Address::create([
                'profile_id' => $user->id,
                'type_id' => 1,
                'location' => $request->input('location'),
                'barangayCode' => $request->input('barangay_code'),
                'isMain' => false
            ]);
        }

        $user->refresh();
        // $new = [
        //     'profile' => $user->profile,
        //     'addresses' => $user->profile?->addresses ? AddressResource::collection($user->profile->addresses) : null,
        // ];

        $this->logInfo('User profile updated', 'User Management', $this->ACTION_UPDATE);
    }

    public function setAddressAsMain(Request $request,$id){
        $request->validate([
            'isMain' => 'required'
        ]);

        $addresses = User::byHash($id)->profile->addresses;
        $total = $addresses->count();

        for($ctr=0; $ctr<$total; $ctr++){
            $address = $addresses[$ctr];
            if($address['id'] == $request->input('isMain')){
                $address->update(['isMain'=>true]);
            }
            else{
                $address->update(['isMain'=>false]);
            }
        }

        return response()->json([
            'message' => 'Address set to main residence successfully'
        ]);
    }

    public function updateAddress(Request $request){
        $fields = $request->validate([
            'id' => 'required',
            'location' => 'required',
            'barangay_code' => 'required'
        ]);

        Address::find($fields['id'])->update([
            'location'=>$fields['location'],
            'barangayCode'=>$fields['barangay_code']
        ]);
        
        return response()->json([
            'message' => 'Address Updated Successfully'
        ]);
    }

    public function deleteAddress(Request $request){
        $request->validate([
            'id' => 'required'
        ]);

        Address::find($request->input('id'))->delete();

        return response()->json([
            'message' => 'address deleted'
        ]);
    }

    public function saveAvatar(Request $request, $id){
        $user = User::byHashOrFail($id);
        $image = $this->uploadImage($request, 'Avatars'.$user->username);
        $profile = $user->profile()->updateOrCreate([
            'user_id' => $user->id
        ],[
            'image_id' => $image->id,
        ]);
        $profile->images()->attach($image->id);

        $this->logInfo('User account image updated', 'User Management', $this->ACTION_UPDATE);
        return response([
            'data' => new UserResource($user),
            'message' => 'Avatar updated successfully'
        ], 200);
    }

    public function changeAvatar(Request $request, $id){
        $request->validate([
            "selected_id" => 'required'
        ]);

        $user = User::byHashOrFail($id);
        $image_id = Images::hashToId($request->input('selected_id'));
        $image = $user->profile?->images()->where('id', $image_id);
        $old = $user->profile?->getOriginal();
        if($image->get()->count() <= 0){
            $this->logWarning('Failed to update user profile image. Image not found!', 'Account Management', $this->ACTION_UPDATE);
            return response(['Failed to update profile image! Image not found!'], 422);
        }
        $user->profile?->update([
            'image_id' => $image->first()->id,
        ]);
        $new = $user->profile?->getChanges();
        $this->logInfo('User profile image updated', 'Account Management', $this->ACTION_UPDATE, $old, $new);
        $user->refresh();
        return response([
            'data' => new UserResource($user),
            'message' => "Profile image updated successfuly!"
        ]);
    }

    public function deleteAvatar(Request $request,$id){
        $request->validate([
            "image" => 'required'
        ]);

        $user = User::byHashOrFail($id);
        $image = Images::byHashOrFail($request->input('image'));
        $images = $user->profile?->images()->where('id', $image->id);
        if($images->get()->count() <= 0){
            $this->logWarning('Failed to delete user profile image. Image not found!', 'Account Management', $this->ACTION_DELETE);
            return response(['Failed to delete profile image! Image not found!'], 422);
        }

        if($image->id == $user->profile?->image?->id){
            $this->logWarning('Failed to delete user image! Image is active!', 'Account Management', $this->ACTION_DELETE);
            // return response(['Failed to delete profile image! Image is still in use!'], 422);
        }
        else{
            $image->delete();

            $this->logInfo('User profile image deleted', 'Account Management', $this->ACTION_DELETE, null, $image);
            $user->refresh();
            return response([
                'data' => new UserResource($user),
                'message' => "Image deleted successfully!"
            ]);
        }

       
    }

    public function updateAccount(Request $request, $id){
        $user = User::byHashOrFail($id);
        $old = $user->getOriginal();
        $request->validate([
            'username' => 'required|string|unique:users,username,'.$user->id,
            'email' => 'nullable|string|email|unique:users,email,'.$user->id,
        ]);

        $requireVerification = $request->input('requireVerification', false);

        if($user->username != $request->input('username') || $user->email != $request->input('email')){
            if($user->username != $request->input('username')){
                $user->username = $request->input('username');
            }
            if($user->email != $request->input('email')){
                $user->email = $request->input('email');
                $user->email_verified_at = $requireVerification ? null : now();
            }
            $user->save();
        }

        $user->sendAdminAccountChangedNotification();

        if($requireVerification){
            $user->sendEmailVerification();
        }

        $this->logInfo('User account updated', 'User Management', $this->ACTION_UPDATE,$old, $user->getChanges());
        //logout user
        $user->tokens()->delete();

        return response([
            'data' => new UserResource($user),
            'message' => 'Account updated successfully'
        ], 200);
    }

    public function updatePassword(Request $request, $id){
        $request->validate([
            'password' => 'required|string|min:8',
            'notify' => 'boolean',
        ]);

        $user = User::byHashOrFail($id);
        $user->password = Hash::make($request->input('password'));
        $user->save();

        if($request->input('notify') == "true" && $user->email != null){
            $user->sendAdminPasswordChangedNotification($request->input('password'));
        }

        $result = new UserResource($user);
        $this->logInfo('User password updated', 'User Management', $this->ACTION_UPDATE);

        //logout user
        $user->tokens()->delete();

        return response([
            'data' => $result,
            'message' => 'Password updated successfully'
        ], 200);
    }

    public function toggleUserActive(Request $request, $id){
        $user = User::byHashOrFail($id);
        $old = $user->getOriginal();
        $disabled = false;
        if($user->disabled_at){
            $user->disabled_at = null;
        }else{
            $user->disabled_at = now();
            $disabled = true;
        }
        $user->save();
        $name = $request->user()->username;
        if($request->user()->profile != null){
            $name = $request->user()->profile->first_name . ' ' . $request->user()->profile->last_name;
        }
        $status = $disabled ? 'Disabled' : 'Enabled';
        $status .= " by";
        if(trim($name) != ''){
            $status .= " ".$name;
        }
        if($user->email != null){
            $status .= " (".$request->user()->email.")";
        }else{
            $status .= " (".$request->user()->username.")";
        }

        // $history = AccountStatusHistory::create([
        //     'user_id' => $user->id,
        //     'status' => $status,
        //     'reason' => $request->input('remarks')
        // ]); $history

        $this->logInfo('User status updated', 'User Management', $this->ACTION_UPDATE, $old, [$user->getChanges()]);
        return response([
            'message' => 'User status updated successfully'
        ], 200);
    }

    public function verifyEmail(Request $request, $email){
        $user = User::where('email', $email)->first();
        if($user){
            $user->update([
                'email_verified_at' => now(),
            ]);
            $this->logInfo("Email verification successfull ($email)", "User Management", $this->ACTION_UPDATE, $user->getOriginal());
            return response([
                'message' => 'Email verified successfully'
            ], 200);
        }else{
            $this->logWarning("Failed to verify email address ($email)", "User Management", $this->ACTION_UPDATE, $user->getOriginal());
            return response([
                'message' => 'Email not found'
            ], 422);
        }
    }

    public function getUserPermissions($id){
        $user = User::byHashOrFail($id);

        if(count($user->getDirectPermissions()) != 0){
            $permissions = $user->getAllPermissions()->pluck('id');
        }
        else{
            $permissions = $user->getPermissionsViaRoles()->pluck('id');
        }

        $indirect_permission = $user->getPermissionsViaRoles()->pluck('id');
        
        return response()->json([
            'permissions' => $permissions,
            'indirect_permission' => $indirect_permission
        ]);
    }

    public function setPermissions(Request $request, $id){
        $request->validate([
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',

            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);
        $user = User::byHash($id);
        $old = [
            "roles" => RolesResource::collection($user->roles),
            "Direct Permissions" => PermissionsResource::collection($user->getDirectPermissions()),
        ];
        
        foreach($request->input('params.roles') as $role){
            if($role != 1){
                $indirect_permission = $user->getPermissionsViaRoles()->pluck('id')->toArray();
                $result = array_diff($request->input('params.permissions'),$indirect_permission);

                if(auth()->user()->can('users_give-direct-permissions'))
                {      
                    $user->syncPermissions($result);
                }
                // $user->syncRoles($request->input('roles'));
            }
        }

        if(count($request->input('params.roles')) <= 0 && count($request->input('params.permissions')) <= 0){
            $user->syncPermissions('none');
        }

        $new = [
            "roles" => RolesResource::collection($user->roles),
            "Direct Permissions" => PermissionsResource::collection($user->getDirectPermissions()),
        ];

        $permissions = $user->getAllPermissions();
        
        $this->logInfo('User permissions updated', 'User Management', $this->ACTION_UPDATE, $old, $new);
        return response([
            'message' => 'User permissions updated successfully!',
            'data' => new UserResource($user),
            'all_permission' => $permissions,
        ], 200);
    }


}
