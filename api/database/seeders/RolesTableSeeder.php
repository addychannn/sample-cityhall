<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'Admin',
                'protected' => 1,
                'description'=> 'A person with "Full/Unrestricted" access to admin features.',
                'permissions' => [],
            ],
            [
                'name' => 'Moderator',
                'protected' => 1,
                'description'=> 'A person with access to some admin features.',
                'permissions' => [
                    'users_add',
                    'users_list',
                    'users_edit-profile',
                    'users_edit-account',
                    'users_edit-permission',
                    'users_change-status',
                    'users_give-direct-permissions',

                    'roles_list',
                    'roles_add',
                    'roles_edit',
                    'roles_delete',

                    'testimony_add',
                    'testimony_edit',
                    'testimony_delete',
                    'testimony_restore',
                    'testimony_list_deleted',


                    'message_list',
                    'message_list-deleted',
                    'message_delete',

                    'enforcer_view_submitted_forms',
                    'enforcer_view_submitted_form_preview',
                    'enforcer_delete_submitted_form',
                    'enforcer_view_uploaded_forms',
                    'enforcer_view_uploaded_form_preview',
                    'enforcer_delete_uploaded_form',
                    'enforcer_upload_new_form',
                    'enforcer_view_deleted_uploaded_forms_list',
                    'enforcer_restore_deleted_form',
                    
                    'report_list',
                    'report_reject',
                    'report_resolve',

                    'quitter_list',
                    'quitter_reject',
                    'quitter_accept',
                    'quitter_reschedule',

                    'about_section_list',
                    'about_section_add',
                    'about_section_edit',
                    'about_section_delete',
                    'about_section_visibility',
                    'about_section_arrange',

                    'event_list-deleted',
                    'event_add',
                    'event_edit',
                    'event_delete',

                    'article_list-deleted',
                    'article_add',
                    'article_edit',
                    'article_delete',
                ],
            ],
            [
                'name' => 'User',
                'protected' => 1,
                'description'=> 'A person with limited access to system features.',
                'permissions' => [
                    // 'self_change-password',
                    // 'self_change-avatar',
                    // 'self_update-profile',
                    // 'self_update-account',

                    'users_edit-profile',
                    'users_edit-account',
                    'about_section_list',
                    'enforcer_view_uploaded_forms',
                    'enforcer_view_uploaded_form_preview'
                ],
            ],
        ];

        foreach($roles as $role) {
            $roleName = Role::create([
                'name' => $role['name'],
                'protected' => $role['protected'],
                'description' => $role['description'],
                'guard_name' => (bool)env('APP_AUTH_TOKEN', false) ? 'api' : 'sanctum'
            ]);
            $roleName->syncPermissions($role['permissions']);
        }
    }
}
