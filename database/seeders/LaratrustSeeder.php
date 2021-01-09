<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class LaratrustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->truncateLaratrustTables();

        $config = Config::get('laratrust_seeder.roles_structure');

        if ($config === null) {
            $this->command->error("The configuration has not been published. Did you run `php artisan vendor:publish --tag=\"laratrust-seeder\"`");
            $this->command->line('');
            return false;
        }

        $mapPermission = collect(config('laratrust_seeder.permissions_map'));
        $mapPermissionName = collect(config('laratrust_seeder.permissions_map_names'));


        // Create a new role
        $role = \App\Models\Role::firstOrCreate(
            ['name' => 'superadmin'],
            [
                'display_name' => ['en' => 'super admin', 'pt-BR' => 'super administrador'],
                'description' => ['en' => 'master of the universe', 'pt-BR' => 'mestre do universo']
            ]
        );
        $permissions = [];

        $this->command->info('Creating Role ' . strtoupper('superadmin'));

        // Reading role permission modules






        $permissions[] = \App\Models\Permission::firstOrCreate(
            ['name' => 'users-create',],
            [
                'display_name' => ['en' => 'create users', 'pt-BR' => 'adicionar usuários'],
                'description' => ['en' => 'create users', 'pt-BR' => 'adicionar usuários'],
            ]
        )->id;
        $permissions[] = \App\Models\Permission::firstOrCreate(
            ['name' => 'users-update'],
            [
                'display_name' => ['en' => 'update users', 'pt-BR' => 'editar usuários'],
                'description' => ['en' => 'update users', 'pt-BR' => 'editar usuários'],
            ]
        )->id;




        // Attach all permissions to the role
        $role->permissions()->sync($permissions);
        if (Config::get('laratrust_seeder.create_users')) {
            $key = $role->name;
            $this->command->info("Creating '{$key}' user");
            // Create default user for each role
            $user = \App\Models\User::create([
                'name' => ucwords(str_replace('_', ' ', $key)),
                'email' => $key . '@app.com',
                'password' => bcrypt('password')
            ]);
            $user->attachRole($role);
        }



        //     foreach ($config as $key => $modules) {

        //         // Create a new role
        //         $role = \App\Models\Role::firstOrCreate([
        //             'name' => $key,
        //             'display_name' => ucwords(str_replace('_', ' ', $key)),
        //             'description' => ucwords(str_replace('_', ' ', $key))
        //         ]);
        //         $permissions = [];

        //         $this->command->info('Creating Role ' . strtoupper($key));

        //         // Reading role permission modules
        //         foreach ($modules as $module => $value) {

        //             foreach (explode(',', $value) as $p => $perm) {

        //                 $permissionValue = $mapPermission->get($perm);

        //                 $permissions[] = \App\Models\Permission::firstOrCreate([
        //                     'name' => $module . '-' . $permissionValue,
        //                     'display_name' => ucfirst($permissionValue) . ' ' . ucfirst($module),
        //                     'description' => ucfirst($permissionValue) . ' ' . ucfirst($module),
        //                 ])->id;

        //                 $this->command->info('Creating Permission to ' . $permissionValue . ' for ' . $module);
        //             }
        //         }

        //         // Attach all permissions to the role
        //         $role->permissions()->sync($permissions);

        //         if (Config::get('laratrust_seeder.create_users')) {
        //             $this->command->info("Creating '{$key}' user");
        //             // Create default user for each role
        //             $user = \App\Models\User::create([
        //                 'name' => ucwords(str_replace('_', ' ', $key)),
        //                 'email' => $key . '@app.com',
        //                 'password' => bcrypt('password')
        //             ]);
        //             $user->attachRole($role);
        //         }
        //     }
    }

    /**
     * Truncates all the laratrust tables and the users table
     *
     * @return  void
     */
    public function truncateLaratrustTables()
    {
        $this->command->info('Truncating User, Role and Permission tables');
        Schema::disableForeignKeyConstraints();

        DB::table('permission_role')->truncate();
        DB::table('permission_user')->truncate();
        DB::table('role_user')->truncate();

        if (Config::get('laratrust_seeder.truncate_tables')) {
            DB::table('roles')->truncate();
            DB::table('permissions')->truncate();

            if (Config::get('laratrust_seeder.create_users')) {
                $usersTable = (new \App\Models\User)->getTable();
                DB::table($usersTable)->truncate();
            }
        }

        Schema::enableForeignKeyConstraints();
    }
}
