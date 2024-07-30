<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Traits\PermissionsTrait;
use App\Traits\RolesTrait;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use App\Http\Resources\RolesResource;
use App\Http\Resources\PermissionsResource;

class PermissionsController extends Controller
{
    use PermissionsTrait, RolesTrait;

    public function __construct()
    {
        $this->middleware('role:Admin',
            ['only' => ['createPermission', 'updatePermission', 'permissionSearch', 'permissionDelete']]);

        $this->middleware('permission:roles_list|roles_add|roles_edit|roles_delete', ['only' => ['roleSearch', 'rolePermissionSearch']]);
        $this->middleware('permission:roles_add', ['only' => ['createRole']]);
        $this->middleware('permission:roles_edit', ['only' => ['updateRole']]);
        $this->middleware('permission:roles_delete', ['only' => ['deleteRole']]);
        
    }

    #region Permissions
    public function createPermission(Request $request){
        $request->validate([
            'name' => 'required|max:75|unique:permissions,name',
            'description' => 'max:255'
        ]);

        $permission = Permission::create([
            'name' => $request->name,
            'description' => $request->description
        ]);
        
        $this->logInfo('New Permission Created', 'Permissions', $this->ACTION_CREATE, null, $permission);

        return response([
            'message' => 'Permission created successfully',
            'data' => [
                'id' => $permission->id,
                'name' => $permission->name,
                'description' => $permission->description
            ]
        ], 201);
    }

    public function updatePermission(Request $request, $id){
        $request->validate([
            'name' => 'required|max:75|unique:permissions,name,'.$id,
            'description' => 'max:255'
        ]);

        $permission = Permission::findOrFail($id);
        $old = $permission->getOriginal();
        $permission->update($request->all());

        $this->logInfo('Permission updated', 'Permissions', $this->ACTION_UPDATE, $old, $permission->getChanges());

        return response([
            'message' => 'Permission updated successfully',
            'data' => [
                'id' => $permission->id,
                'name' => $permission->name,
                'description' => $permission->description
            ]
        ], 200);
    }

    public function permissionSearch(Request $request){
        $search = $request->search ?? "";
        $limit = $request->limit ?? 10;
        $offset = $request->offset ?? 0;
        $orderBy = $request->orderBy ?? "name";
        $order = $request->order ?? "asc";

        $permission = $this->searchPermission($search, $limit, $offset, $orderBy, $order)
            ->select(['id', 'name', 'description'])->get();
        $count = $this->searchPermissionCount($search)->first()->count;

        return response([
            'data' => $permission,
            'count' => $count
        ], 200);
    }

    public function permissionDelete($id){
        $permission = Permission::findOrFail($id);
        $permission->delete();

        $this->logWarning('Permission deleted', 'Permissions', $this->ACTION_DELETE, $permission, null);

        return response([
            'message' => 'Permission deleted successfully'
        ], 200);
    }
    #endregion

    #region Roles
    public function getPermission(){
        $permission = Permission::all();

        return response([
            'data' => $permission
        ], 200);
    }
    public function createRole(Request $request){
        $request->validate(
            [
                'name' => 'required|max:75|unique:roles,name',
                'description' => 'max:255',
                'permissions' => 'required|array'
            ]
        );

        $role = Role::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        $role->syncPermissions($request->permissions);

        $result = new RolesResource($role);
        $this->logInfo('Role created', 'Permissions (Roles)', $this->ACTION_CREATE, null, $result);
        return response([
            'message' => 'Role created successfully',
            'data' => $result,
        ], 201);
    }

    public function updateRole(Request $request, $id){
        $request->validate(
            [
                'permissions' => 'required|array'
            ]
        );

        $role = Role::findOrFail($id);
        $old = collect($role->getOriginal())->put('permissions', PermissionsResource::collection($role->permissions));

        if($role->protected != 1){
            $request->validate(
                [
                    'name' => 'required|max:75|unique:roles,name,'.$id,
                    'description' => 'max:255',
                    'permissions' => 'required|array'
                ]
            );
            $role->update([
                'name' => $request->name,
                'description' => $request->description
            ]);
        }

        $role->syncPermissions($request->permissions);
        $role->with('permissions')->get();

        $result = collect($role->getChanges())->put('permissions', PermissionsResource::collection($role->permissions));
        
        $this->logInfo('Role updated', 'Permissions (Roles)', $this->ACTION_UPDATE, $old, $result);

        return response([
            'message' => 'Role updated successfully',
            'data' => new RolesResource($role)
        ], 200);
    }

    public function roleSearch(Request $request){
        $search = $request->search ?? "";
        $limit = $request->limit ?? 10;
        $offset = $request->offset ?? 0;
        $orderBy = $request->orderBy ?? "name";
        $order = $request->order ?? "asc";

        $role = $this->searchRole($search, $limit, $offset, $orderBy, $order)
            ->with('permissions:id,name,description')
            ->select(['id', 'name', 'description', 'protected'])
            ->get();
            // ->where('name', "<>", "Admin")->get();
        $count = $this->searchRoleCount($search)
            ->first()->count;
            // ->where('name', "<>", "Admin")->first()->count;

        return response([
            'data' => RolesResource::collection($role),
            'count' => $count
        ], 200);
    }

    public function rolePermissionSearch(Request $request){
        $search = $request->search ?? "";
        // $limit = $request->limit ?? 10;
        $offset = $request->offset ?? 0;
        $orderBy = $request->orderBy ?? "name";
        $order = $request->order ?? "asc";

        $permission = $this->searchPermission($search, $offset, $orderBy, $order)
            ->select(['id', 'name', 'description'])->get();
        $count = $this->searchPermissionCount($search)->first()->count;

        return response([
            'data' => $permission,
            'count' => $count
        ], 200);
    }

    public function getRole($id){
        $role = Role::findOrFail($id);
        return response([
            'data' => new RolesResource($role)
        ], 200);
    }
    public function deleteRole($id){
        $role = Role::findOrFail($id);
        if($role->protected == 1){
            return response([
                'message' => 'Cannot delete protected role'
            ], 403);
        }
        $result = collect($role->getOriginal())->put('permissions', PermissionsResource::collection($role->permissions));
        $role->delete();

        $this->logWarning('Role deleted', 'Permissions (Roles)', $this->ACTION_DELETE, $result, null);
        return response([
            'message' => 'Role deleted successfully'
        ], 200);
    }
    #endregion
}
