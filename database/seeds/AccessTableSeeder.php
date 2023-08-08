<?php

use Illuminate\Database\Seeder;
use Database\DisableForeignKeys;

/**
 * Class AccessTableSeeder.
 */
class AccessTableSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        //Changed this to User*s*TableSeeder to match the Class made by iseed
        $this->call(UsersTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        //I changed this from UserRoleSeeder to RoleUserTableSeeder -jl 2018-03-23 
        // $this->call(UserRoleSeeder::class);
        $this->call(RoleUserTableSeeder::class);

        $this->call(PermissionTableSeeder::class);
        //I changed this from PermissionRoleSeeder to PermissionRoleTableSeeder -jl 2018-03-23 
        // $this->call(UserRoleSeeder::class);
        $this->call(PermissionRoleTableSeeder::class);

        $this->enableForeignKeys();
    }
}
