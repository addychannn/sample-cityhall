<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            //User Management
            [
                'name' => 'none',
                'description' => 'No Permission',
            ],
            [
                'name' => 'users_add',
                'description' => 'Add new user',
            ],
            [
                'name' => 'users_list',
                'description' => 'Show users table',
            ],
            [
                'name' => 'users_edit-profile',
                'description' => 'Update users profile',
            ],
            [
                'name' => 'users_edit-account',
                'description' => 'Update users account credentials',
            ],
            [
                'name' => 'users_edit-permission',
                'description' => 'Update users Roles and/or permissions',
            ],
            [
                'name' => 'users_change-status',
                'description' => 'Activate or deactivate users\' account',
            ],
            [
                'name' => 'users_give-direct-permissions',
                'description' => 'Give user direct permission to gain explicit access to a function',
            ],

            // User Roles
            [
                'name' => 'roles_list',
                'description' => 'Show Roles table',
            ],
            [
                'name' => 'roles_add',
                'description' => 'Add new Role',
            ],
            [
                'name' => 'roles_edit',
                'description' => 'Edit existing roles',
            ],
            [
                'name' => 'roles_delete',
                'description' => 'Delete roles (Warning! this action is permanent and non recoverable.)',
            ],

            // Account management
            // [
            //     'name' => 'self_update-profile',
            //     'description' => 'Allow users to change their profile information',
            // ],
            // [
            //     'name' => 'self_update-account',
            //     'description' => 'Allow users to change their account information',
            // ],
            // [
            //     'name' => 'self_change-password',
            //     'description' => 'Allow users to change their account password',
            // ],
            // [
            //     'name' => 'self_change-avatar',
            //     'description' => 'Allow users to change their profile image',
            // ],

            //Testimony
            [
                'name' => 'testimony_add',
                'description' => 'Allow user to create testimony',
            ],
            [
                'name' => 'testimony_edit',
                'description' => 'Allow user to edit testimony',
            ],
            [
                'name' => 'testimony_delete',
                'description' => 'Allow user to delete testimony',
            ],
            [
                'name' => 'testimony_restore',
                'description' => 'Allow user to restore deleted testimony',
            ],
            [
                'name' => 'testimony_list_deleted',
                'description' => 'Allow user to view list of deleted testimony',
            ],

            // Messages
            [
                'name' => 'message_list',
                'description' => 'Allow user to view list of testimony',
            ],
            [
                'name' => 'message_list-deleted',
                'description' => 'Allow user to view list of deleted testimony',
            ],
            [
                'name' => 'message_delete',
                'description' => 'Allow user to delete a specific message',
            ],

            // Enforcer-Room 
            [
                'name' => 'enforcer_view_submitted_forms',
                'description' => 'Allows User to view submitted forms',
            ],
            [
                'name' => 'enforcer_view_submitted_form_preview',
                'description' => 'Allows a user to view preview of a submitted form',
            ],
            [
                'name' => 'enforcer_delete_submitted_form',
                'description' => 'Allows user to delete submitted form/s',
            ],
            [
                'name' => 'enforcer_view_uploaded_forms',
                'description' => 'Allows User to view uploaded forms',
            ],
            [
                'name' => 'enforcer_view_uploaded_form_preview',
                'description' => 'Allows a user to view preview of an uploaded form',
            ],
            [
                'name' => 'enforcer_delete_uploaded_form',
                'description' => 'Allows user to delete an uploaded form',
            ],
            [
                'name' => 'enforcer_upload_new_form',
                'description' => 'Allows a user to upload new form',
            ],
            [
                'name' => 'enforcer_view_deleted_uploaded_forms_list',
                'description' => 'Allows a user to view deleted uploaded forms',
            ],
            [
                'name' => 'enforcer_restore_deleted_form',
                'description' => 'Allows a user to restore deleted form/s',
            ],

            //Report
            [
                'name' => 'report_list',
                'description' => 'Allow user to view list of reports',
            ],
            [
                'name' => 'report_reject',
                'description' => 'Allow user to reject a report',
            ],
            [
                'name' => 'report_resolve',
                'description' => 'Allow user to mark a report as resolved',
            ],

            //Quitter
            [
                'name' => 'quitter_list',
                'description' => 'Allow user to view list of quitter appointment',
            ],
            [
                'name' => 'quitter_reject',
                'description' => 'Allow user to reject a quitter appointment',
            ],
            [
                'name' => 'quitter_accept',
                'description' => 'Allow user to accept a quitter appointment',
            ],
            
            [
                'name' => 'quitter_reschedule',
                'description' => 'Allow user to reschedule a quitter appointment',
            ],

            //About
            [
                'name' => 'about_section_list',
                'description' => 'Allow user to view list of sections under the about page',
            ],
            [
                'name' => 'about_section_add',
                'description' => 'Allow user to add section/s under the about page',
            ],
            [
                'name' => 'about_section_edit',
                'description' => 'Allow user to edit section/s under the about page',
            ],
            [
                'name' => 'about_section_delete',
                'description' => 'Allow user to delete section/s under the about page',
            ],
            [
                'name' => 'about_section_visibility',
                'description' => 'Allow user to change visibility status of section/s under the about page',
            ],
            [
                'name' => 'about_section_arrange',
                'description' => 'Allow user to arrange the section/s order under the about page',
            ],

            //Event
            [
                'name' => 'event_list-deleted',
                'description' => 'Allow user to view list of deleted event/s',
            ],
            [
                'name' => 'event_add',
                'description' => 'Allow user to add event/s',
            ],

            [
                'name' => 'event_edit',
                'description' => 'Allow user to edit event/s',
            ],

            [
                'name' => 'event_delete',
                'description' => 'Allow user to delete  event/s',
            ],

            //Article
            [
                'name' => 'article_list-deleted',
                'description' => 'Allow user to view list of deleted article/s',
            ],
            [
                'name' => 'article_add',
                'description' => 'Allow user to add article/s',
            ],

            [
                'name' => 'article_edit',
                'description' => 'Allow user to edit article/s',
            ],

            [
                'name' => 'article_delete',
                'description' => 'Allow user to delete article/s',
            ],

        ];

        foreach($permissions as $permission) {
            Permission::create([
                'name' => $permission['name'],
                'description' => $permission['description'],
                'guard_name' => (bool)env('APP_AUTH_TOKEN', false) ? 'api' : 'sanctum'
            ]);
        }
    }
}
