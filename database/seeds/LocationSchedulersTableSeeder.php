<?php

use Illuminate\Database\Seeder;

class LocationSchedulersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('location_schedulers')->delete();
        
        DB::table('location_schedulers')->insert(array (
            0 => 
            array (
                'id' => 7,
                'location_id' => 8,
                'user_id' => 2,
            ),
            1 => 
            array (
                'id' => 8,
                'location_id' => 8,
                'user_id' => 4,
            ),
            2 => 
            array (
                'id' => 9,
                'location_id' => 9,
                'user_id' => 2,
            ),
            3 => 
            array (
                'id' => 10,
                'location_id' => 9,
                'user_id' => 4,
            ),
            4 => 
            array (
                'id' => 5,
                'location_id' => 10,
                'user_id' => 2,
            ),
            5 => 
            array (
                'id' => 20,
                'location_id' => 11,
                'user_id' => 2,
            ),
            6 => 
            array (
                'id' => 21,
                'location_id' => 11,
                'user_id' => 4,
            ),
            7 => 
            array (
                'id' => 1,
                'location_id' => 12,
                'user_id' => 2,
            ),
            8 => 
            array (
                'id' => 2,
                'location_id' => 13,
                'user_id' => 2,
            ),
            9 => 
            array (
                'id' => 22,
                'location_id' => 14,
                'user_id' => 2,
            ),
            10 => 
            array (
                'id' => 3,
                'location_id' => 15,
                'user_id' => 2,
            ),
            11 => 
            array (
                'id' => 4,
                'location_id' => 16,
                'user_id' => 2,
            ),
            12 => 
            array (
                'id' => 18,
                'location_id' => 17,
                'user_id' => 2,
            ),
            13 => 
            array (
                'id' => 19,
                'location_id' => 18,
                'user_id' => 2,
            ),
            14 => 
            array (
                'id' => 11,
                'location_id' => 20,
                'user_id' => 4,
            ),
            15 => 
            array (
                'id' => 6,
                'location_id' => 21,
                'user_id' => 4,
            ),
            16 => 
            array (
                'id' => 12,
                'location_id' => 22,
                'user_id' => 2,
            ),
            17 => 
            array (
                'id' => 13,
                'location_id' => 23,
                'user_id' => 2,
            ),
            18 => 
            array (
                'id' => 14,
                'location_id' => 24,
                'user_id' => 2,
            ),
            19 => 
            array (
                'id' => 15,
                'location_id' => 25,
                'user_id' => 2,
            ),
            20 => 
            array (
                'id' => 16,
                'location_id' => 26,
                'user_id' => 2,
            ),
            21 => 
            array (
                'id' => 17,
                'location_id' => 26,
                'user_id' => 4,
            ),
            22 => 
            array (
                'id' => 23,
                'location_id' => 27,
                'user_id' => 2,
            ),
            23 => 
            array (
                'id' => 24,
                'location_id' => 28,
                'user_id' => 2,
            ),
            24 => 
            array (
                'id' => 25,
                'location_id' => 29,
                'user_id' => 9,
            ),
            25 => 
            array (
                'id' => 26,
                'location_id' => 30,
                'user_id' => 2,
            ),
            26 => 
            array (
                'id' => 27,
                'location_id' => 31,
                'user_id' => 9,
            ),
        ));
        
        
    }
}