<?php

use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permission_role')->delete();
        
        \DB::table('permission_role')->insert(array (
            0 => 
            array (
                'id' => 2,
                'permission_id' => 1,
                'role_id' => 4,
            ),
            1 => 
            array (
                'id' => 3,
                'permission_id' => 5,
                'role_id' => 4,
            ),
            2 => 
            array (
                'id' => 4,
                'permission_id' => 1,
                'role_id' => 5,
            ),
            3 => 
            array (
                'id' => 5,
                'permission_id' => 7,
                'role_id' => 5,
            ),
            4 => 
            array (
                'id' => 6,
                'permission_id' => 1,
                'role_id' => 6,
            ),
            5 => 
            array (
                'id' => 7,
                'permission_id' => 4,
                'role_id' => 6,
            ),
            6 => 
            array (
                'id' => 8,
                'permission_id' => 1,
                'role_id' => 7,
            ),
            7 => 
            array (
                'id' => 9,
                'permission_id' => 3,
                'role_id' => 7,
            ),
            8 => 
            array (
                'id' => 15,
                'permission_id' => 1,
                'role_id' => 14,
            ),
            9 => 
            array (
                'id' => 16,
                'permission_id' => 5,
                'role_id' => 14,
            ),
            10 => 
            array (
                'id' => 17,
                'permission_id' => 1,
                'role_id' => 15,
            ),
            11 => 
            array (
                'id' => 18,
                'permission_id' => 7,
                'role_id' => 15,
            ),
            12 => 
            array (
                'id' => 19,
                'permission_id' => 1,
                'role_id' => 19,
            ),
            13 => 
            array (
                'id' => 20,
                'permission_id' => 4,
                'role_id' => 19,
            ),
            14 => 
            array (
                'id' => 21,
                'permission_id' => 1,
                'role_id' => 20,
            ),
            15 => 
            array (
                'id' => 22,
                'permission_id' => 3,
                'role_id' => 20,
            ),
            16 => 
            array (
                'id' => 23,
                'permission_id' => 1,
                'role_id' => 21,
            ),
            17 => 
            array (
                'id' => 24,
                'permission_id' => 6,
                'role_id' => 21,
            ),
            18 => 
            array (
                'id' => 25,
                'permission_id' => 1,
                'role_id' => 16,
            ),
            19 => 
            array (
                'id' => 26,
                'permission_id' => 6,
                'role_id' => 16,
            ),
            20 => 
            array (
                'id' => 29,
                'permission_id' => 9,
                'role_id' => 22,
            ),
            21 => 
            array (
                'id' => 30,
                'permission_id' => 11,
                'role_id' => 23,
            ),
            22 => 
            array (
                'id' => 32,
                'permission_id' => 1,
                'role_id' => 24,
            ),
            23 => 
            array (
                'id' => 33,
                'permission_id' => 12,
                'role_id' => 24,
            ),
            24 => 
            array (
                'id' => 34,
                'permission_id' => 1,
                'role_id' => 25,
            ),
            25 => 
            array (
                'id' => 35,
                'permission_id' => 13,
                'role_id' => 25,
            ),
            26 => 
            array (
                'id' => 36,
                'permission_id' => 1,
                'role_id' => 26,
            ),
            27 => 
            array (
                'id' => 37,
                'permission_id' => 14,
                'role_id' => 26,
            ),
            28 => 
            array (
                'id' => 39,
                'permission_id' => 1,
                'role_id' => 27,
            ),
            29 => 
            array (
                'id' => 40,
                'permission_id' => 15,
                'role_id' => 27,
            ),
        ));
        
        
    }
}