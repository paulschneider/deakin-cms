<?php
namespace App\Console\Commands;

use DB;
use Cache;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Console\Command;

class PermissionsBuild extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'permissions:build';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset and hydrate the permissions tables.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        // Uncomment the below to wipe the table clean before populating
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        DB::table('role_user')->truncate();
        DB::table('roles')->truncate();
        DB::table('permission_role')->truncate();
        DB::table('permissions')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        // Setup Roles.
        $super               = new Role;
        $super->name         = "super";
        $super->display_name = "Super";
        $super->description  = "Super administrators";
        $super->save();

        $admin               = new Role;
        $admin->name         = "admin";
        $admin->display_name = "Admin";
        $admin->description  = "Normal administrators";
        $admin->save();

        $user_admin               = new Role;
        $user_admin->name         = "user_admin";
        $user_admin->display_name = "User Admin";
        $user_admin->description  = "Ability to create and edit users";
        $user_admin->save();

        // Used to assign who can do what.
        $new_roles = ['super' => $super->id, 'admin' => $admin->id, 'user_admin' => $user_admin->id];

        $supers = ['al.munnings@iconinc.com.au'];
        $admins = [
            'fabrian@iconinc.com.au',
            'dan.gregson@iconinc.com.au',
            'chris@iconinc.com.au',
        ];

        // Supers
        $users = User::whereIn('email', $supers)->get();
        foreach ($users as $user) {
            $user->attachRole($super);
        }

        // Admins
        $users = User::whereIn('email', $admins)->get();
        foreach ($users as $user) {
            $user->attachRole($admin);
        }

        // Setup individual permissions.

        $this->comment('Build basic permissions.');
        $permissions = [
            'view.admin' => ['display' => 'Admin Interface', 'roles' => ['super', 'admin', 'user_admin']],
        ];

        // Menus
        $this->comment('Build permissions for menus.');
        $permissions['menu.admin'] = ['display' => 'Menu: admin', 'roles' => ['super', 'admin']];

        // Add in all the admin resources (controllers)
        // Use the name of the controller MINUS the Contorller part.
        $resources = [
            'acl', 'aliases', 'attachments', 'blocks',
            'links', 'menus', 'pages',
            'terms', 'users', 'vocabularies',
            'cron', 'configurations',
            'submissions', 'banners', 'articles',
            'icons',
            'profiles', 'submissions', 'credentials',
        ];

        /*
         * Give admin and suepr all permissions
         */

        foreach ($resources as $resource) {
            $permissions['admin.' . $resource . '.get']    = ['display' => 'Admin GET ' . $resource, 'roles' => ['super', 'admin']];
            $permissions['admin.' . $resource . '.patch']  = ['display' => 'Admin PATCH ' . $resource, 'roles' => ['super', 'admin']];
            $permissions['admin.' . $resource . '.post']   = ['display' => 'Admin POST ' . $resource, 'roles' => ['super', 'admin']];
            $permissions['admin.' . $resource . '.delete'] = ['display' => 'Admin DELETE ' . $resource, 'roles' => ['super', 'admin']];

            $this->comment('Build resource permissions for admin.' . $resource);
        }

        /*
         * Deep actions into controllers
         *
         * REMEMBER: strtolower() on $permissions key.
         */

        $this->comment('Build custom permissions.');

        // Home
        $permissions['admin.home.get.index'] = ['display' => 'Admin GET HomeController@index', 'roles' => ['super', 'admin']];

        // Icons
        $permissions['admin.icons.get.iframe']      = ['display' => 'Admin Icons Iframe GET', 'roles' => ['super', 'admin']];
        $permissions['admin.icons.get.fontawesome'] = ['display' => 'Admin Icons FontAwesome GET', 'roles' => ['super', 'admin']];
        $permissions['admin.icons.post.wysiwyg']    = ['display' => 'Admin Icons WYSIWYG GET', 'roles' => ['super', 'admin']];

        // Users
        $permissions['admin.users.get.me']              = ['display' => 'Admin edit own account', 'roles' => ['super', 'admin', 'user_admin']];
        $permissions['admin.users.patch.updateme']      = ['display' => 'Admin update own account', 'roles' => ['super', 'admin', 'user_admin']];
        $permissions['admin.users.get.resetpassword']   = ['display' => 'Admin reset someones password', 'roles' => ['super', 'user_admin']];
        $permissions['admin.users.get.resetactivation'] = ['display' => 'Admin reset someones password', 'roles' => ['super', 'user_admin']];
        $permissions['admin.users.roles']               = ['display' => 'Admin change user roles', 'roles' => ['super', 'user_admin']];
        $permissions['admin.users.roles.any']           = ['display' => 'Admin change ANY user roles', 'roles' => ['super']];
        $permissions['admin.users.deactivate']          = ['display' => 'Admin deactivate an account', 'roles' => ['super']];

        foreach ($new_roles as $new_role => $role_id) {
            $permissions['admin.users.assign_role.' . $role_id] = ['display' => 'Admin assign ' . $new_role . ' role', 'roles' => ['super']];
        }

        // Settings
        $permissions['admin.configurations.get.sitesettings']      = ['display' => 'Admin GET site settings', 'roles' => ['super', 'admin']];
        $permissions['admin.configurations.post.savesitesettings'] = ['display' => 'Admin POST site settings', 'roles' => ['super', 'admin']];
        $permissions['admin.configurations.get.clearcaches']       = ['display' => 'Admin GET clear caches', 'roles' => ['super', 'admin']];

        /*
         * Remove admin from some stuff.
         * Make super admin only.
         */

        $super_only = [
            'admin.acl.get',
            'admin.acl.patch',
            'admin.acl.post',
            'admin.acl.delete',

            //'admin.blocks.get',
            //'admin.blocks.patch',
            'admin.blocks.post',
            'admin.blocks.delete',

            'admin.configurations.get',
            'admin.configurations.patch',
            'admin.configurations.post',
            'admin.configurations.delete',

            'admin.icons.get',
            'admin.icons.patch',
            'admin.icons.post',
            'admin.icons.delete',

            'menu.admin',
        ];

        foreach ($super_only as $only) {
            $permissions[$only]['roles'] = ['super'];
        }

        /*
         * ALlow user admins and supers to edit users.
         */
        $user_admin_only = [
            'admin.users.get',
            'admin.users.patch',
            'admin.users.post',
            'admin.users.delete',
            'admin.users.assign_role.' . $new_roles['admin'],
            'admin.users.assign_role.' . $new_roles['user_admin'],
        ];

        foreach ($user_admin_only as $only) {
            $permissions[$only]['roles'] = ['super', 'user_admin'];
        }

        /*
         *
         * Conventions are simple.
         * Controller namespace . resource name . request method
         *
         * EG admin.pages.post
         *    frontend.pages.post
         *
         */

        $setting = [];

        foreach ($permissions as $key => $options) {
            $permission               = new Permission;
            $permission->name         = strtolower($key);
            $permission->display_name = $options['display'];
            $permission->save();

            foreach ($options['roles'] as $role) {
                $setting[$role][] = $permission->id;
            }
        }

        // Attach the permissions (equivilent to sync())
        foreach ($setting as $role => $pids) {
            ${$role}->attachPermissions($pids);
            $this->comment('Attaching ' . $role . ' permissions to ' . implode(', ', $pids));
        }

        Cache::flush(['menus', 'acl']);
    }
}
