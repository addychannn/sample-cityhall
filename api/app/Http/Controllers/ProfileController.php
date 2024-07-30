<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;

use App\Http\Resources\UserResource;
use App\Http\Resources\AddressResource;

use App\Traits\ImageTrait;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
use App\Models\Address;
use App\Models\Images;

class ProfileController extends Controller
{
    use ImageTrait;

    public function __construct(){
        $this->middleware("permission:self_update-profile", [
            'only' => ['changeProfile']
        ]);
        $this->middleware("permission:self_update-account", [
            'only' => ['changeAccount']
        ]);
        $this->middleware("permission:self_change-password", [
            'only' => ['changePassword']
        ]);
        $this->middleware("permission:self_change-avatar", [
            'only' => ['saveAvatar', 'changeAvatar', 'deleteImage']
        ]);
    }

    public function changePassword(Request $request){
        $password_regex = '/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/';
        $request->validate([
            'current' => 'required',
            'confirm' => 'required|string|min:8|regex:'.$password_regex,
            'new' => 'required|same:confirm|string|min:8|regex:'.$password_regex,
        ]);

        $password = Hash::make($request->input('new'));
        $current = Hash::make($request->input('current'));
        $user = auth()->user();

        if(!Hash::check($request->input('current'), $user->password)){
            $this->logWarning('User failed attempt to change password. ', "Account Management", $this->ACTION_UPDATE);
            return response()->json([
                'message' => 'Current password is incorrect'
            ], 422);
        }

        $user->update([
            'password' => $password
        ]);
        $user->sendPasswordChangedNotification();
        $this->logInfo('User changed password', "Account Management", $this->ACTION_UPDATE);
        $user->tokens()->delete();
        return response([
            'message' => "Password changed successfully"
        ]);
    }

    public function saveAvatar(Request $request){
        $user = auth()->user();
        $profile = Profile::firstOrCreate(['user_id' => $user->id]);
        $image = $this->uploadImage($request, 'Avatars'.$user->username);
        $profile->image_id = $image->id;
        $profile->save();
        $profile->images()->attach($image->id);

        $result = new UserResource(User::find($user->id));
        $this->logInfo('User profile image updated', 'Account Management', $this->ACTION_UPDATE);
        return response([
            'data' => $result,
            'message' => 'Avatar updated successfully'
        ], 200);
    }

    public function changeAvatar(Request $request){
        $request->validate([
            "image" => 'required'
        ]);

        $image_id = Images::hashToId($request->input('image'));
        $user = auth()->user();
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

    public function deleteImage(Request $request){
        $request->validate([
            "image" => 'required'
        ]);
        
        $image = Images::byHashOrFail($request->input('image'));
        $user = auth()->user();
        $images = $user->profile?->images()->where('id', $image->id);
        if($images->get()->count() <= 0){
            $this->logWarning('Failed to delete user profile image. Image not found!', 'Account Management', $this->ACTION_DELETE);
            return response(['Failed to delete profile image! Image not found!'], 422);
        }

        if($image->id == $user->profile?->image?->id){
            $this->logWarning('Failed to delete user image! Image is active!', 'Account Management', $this->ACTION_DELETE);
            return response(['Failed to delete profile image! Image is still in use!'], 422);
        }

        $image->delete();

        $this->logInfo('User profile image deleted', 'Account Management', $this->ACTION_DELETE, null, $image);
        $user->refresh();
        return response([
            'data' => new UserResource($user),
            'message' => "Profile image updated successfuly!"
        ]);
    }

    public function changeProfile(Request $request){
        $request->validate([
            'first_name' => 'required|string',
            'middle_name' => 'required|string',
            'last_name' => 'required|string',
            'gender' => 'required|alpha|max:1|in:m,f',
            'birthdate' => [
                'required', 'date', 'date_format:Y-m-d',
                'before_or_equal:'.now()->format('Y-m-d'),
            ],
        ],[
            'birthdate.before_or_equal' => 'Sorry, you cannot be born in the future.'
        ]);

        $user = auth()->user();
        $old = [
            'profile' => $user->profile,
            'addresses' => $user->profile?->addresses ? AddressResource::collection($user->profile->addresses) : null,
        ];
        
        $user->profile()->updateOrCreate([
            'user_id' => $user->id
        ], [
            'first_name' => ucwords(strtolower($request->input('first_name'))),
            'middle_name' => ucwords(strtolower($request->input('middle_name'))),
            'last_name' => ucwords(strtolower($request->input('last_name'))),
            'suffix' => $request->input('suffix'),
            'birthdate' => $request->input('birthdate'),
            'gender' => $request->input('gender'),
            'nickname' => ucwords(strtolower($request->input('nickname'))) == '' ? null : ucwords(strtolower($request->input('nickname'))),
            'address' => $request->input('address')
        ]);

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
                $now = now();
                return collect($item)->put('profile_id', $user->profile->id)
                    ->put('created_at', $now)
                    ->put('updated_at', $now);
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
            //Address::upsert($addresses->toArray(), ['id'], ['profile_id', 'type_id', 'location', 'barangayCode', 'isMain']);
        }
        $user->refresh();
        $new = [
            'profile' => $user->profile,
            'addresses' => $user->profile?->addresses ? AddressResource::collection($user->profile->addresses) : null,
        ];
        $this->logInfo('User profile updated', 'User Management', $this->ACTION_UPDATE, $old, $new);

        return response([
            'data' =>  new UserResource(auth()->user()),
            'message' => 'User profile updated successfully',
        ], 200);
    }

    public function changeAccount(Request $request){
        $user = auth()->user();
        $request->validate([
            'username' => 'required|string|min:4|max:20|unique:users,username,'.$user->id,
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
        ]);

        $emailChanged = false;
        $old = $user->getOriginal();

        if($user->email == $request->email && $user->username == $request->username){
            $this->logInfo('An account update was attempted but was ignored since username and email did not change.', 'Account Management', $this->ACTION_UPDATE);
            return response([
                'message' => 'No changes were made'
            ], 422);
        }
        
        if($user->email != $request->email && $user->username != $request->username){
            $user->update([
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'email_verified_at' => null,
            ]);
            $emailChanged = true;
            $user->sendEmailVerification();
        }

        if($user->email == $request->email && $user->username != $request->username){
            $user->update([
                'username' => $request->input('username'),
            ]);
        }

        if($user->email != $request->email && $user->username == $request->username){
            $user->update([
                'email' => $request->input('email'),
                'email_verified_at' => null,
            ]);
            $emailChanged = true;
            $user->sendEmailVerification();
        }
        
        $this->logInfo('User credentials updated.', 'Account Management', $this->ACTION_UPDATE, $old, $user->getChanges());
        $user->sendAccountChangedNotification();
        
        $user->tokens()->delete();
        return response([
            'message' => 'User account updated successfully',
        ], 200);
    }
}
