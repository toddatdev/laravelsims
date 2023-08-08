<?php

use Illuminate\Database\Seeder;

class RoleUserTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('role_user')->delete();
        
        \DB::table('role_user')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => 1,
                'role_id' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'user_id' => 2,
                'role_id' => 1,
            ),
            2 => 
            array (
                'id' => 3,
                'user_id' => 3,
                'role_id' => 3,
            ),
            3 => 
            array (
                'id' => 4,
                'user_id' => 5,
                'role_id' => 4,
            ),
            4 => 
            array (
                'id' => 5,
                'user_id' => 6,
                'role_id' => 5,
            ),
            5 => 
            array (
                'id' => 6,
                'user_id' => 7,
                'role_id' => 6,
            ),
            6 => 
            array (
                'id' => 9,
                'user_id' => 8,
                'role_id' => 7,
            ),
            7 => 
            array (
                'id' => 10,
                'user_id' => 9,
                'role_id' => 3,
            ),
            8 => 
            array (
                'id' => 11,
                'user_id' => 2,
                'role_id' => 8,
            ),
            9 => 
            array (
                'id' => 12,
                'user_id' => 4,
                'role_id' => 8,
            ),
            10 => 
            array (
                'id' => 13,
                'user_id' => 10,
                'role_id' => 16,
            ),
            11 => 
            array (
                'id' => 16,
                'user_id' => 6,
                'role_id' => 15,
            ),
            12 => 
            array (
                'id' => 19,
                'user_id' => 12,
                'role_id' => 3,
            ),
            13 => 
            array (
                'id' => 20,
                'user_id' => 12,
                'role_id' => 7,
            ),
            14 => 
            array (
                'id' => 49,
                'user_id' => 42,
                'role_id' => 18,
            ),
            15 => 
            array (
                'id' => 50,
                'user_id' => 43,
                'role_id' => 3,
            ),
            17 => 
            array (
                'id' => 53,
                'user_id' => 8,
                'role_id' => 20,
            ),
            18 => 
            array (
                'id' => 54,
                'user_id' => 9,
                'role_id' => 14,
            ),
            19 => 
            array (
                'id' => 56,
                'user_id' => 10,
                'role_id' => 21,
            ),
            20 => 
            array (
                'id' => 57,
                'user_id' => 5,
                'role_id' => 14,
            ),
            21 => 
            array (
                'id' => 64,
                'user_id' => 44,
                'role_id' => 18,
            ),
            22 => 
            array (
                'id' => 65,
                'user_id' => 46,
                'role_id' => 18,
            ),
            23 => 
            array (
                'id' => 66,
                'user_id' => 47,
                'role_id' => 18,
            ),
            24 => 
            array (
                'id' => 67,
                'user_id' => 12,
                'role_id' => 20,
            ),
            25 => 
            array (
                'id' => 68,
                'user_id' => 4,
                'role_id' => 1,
            ),
            27 => 
            array (
                'id' => 70,
                'user_id' => 7,
                'role_id' => 19,
            ),
            28 => 
            array (
                'id' => 77,
                'user_id' => 49,
                'role_id' => 23,
            ),
            29 => 
            array (
                'id' => 78,
                'user_id' => 50,
                'role_id' => 22,
            ),
            30 => 
            array (
                'id' => 79,
                'user_id' => 51,
                'role_id' => 3,
            ),
            31 => 
            array (
                'id' => 80,
                'user_id' => 52,
                'role_id' => 24,
            ),
            32 => 
            array (
                'id' => 81,
                'user_id' => 53,
                'role_id' => 6,
            ),
            33 => 
            array (
                'id' => 84,
                'user_id' => 55,
                'role_id' => 26,
            ),
            34 => 
            array (
                'id' => 85,
                'user_id' => 54,
                'role_id' => 25,
            ),
            35 => 
            array (
                'id' => 86,
                'user_id' => 44,
                'role_id' => 3,
            ),
            36 => 
            array (
                'id' => 87,
                'user_id' => 44,
                'role_id' => 22,
            ),
            37 => 
            array (
                'id' => 88,
                'user_id' => 56,
                'role_id' => 27,
            ),
            38 =>
                array (
                    'id' => 100,
                    'user_id' => 57,
                    'role_id' => 1,
                ),
        ));
    }
}