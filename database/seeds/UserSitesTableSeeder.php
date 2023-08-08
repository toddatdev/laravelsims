<?php

use Illuminate\Database\Seeder;

class UserSitesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('user_sites')->delete();
        
        \DB::table('user_sites')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => 2,
                'site_id' => 6,
                'created_at' => '2018-03-23 16:01:31',
                'updated_at' => '2018-03-23 16:01:31',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'user_id' => 2,
                'site_id' => 1,
                'created_at' => '2018-03-26 20:02:26',
                'updated_at' => '2018-03-26 20:02:26',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'user_id' => 10,
                'site_id' => 1,
                'created_at' => '2018-03-26 21:34:39',
                'updated_at' => '2018-03-26 21:34:39',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'user_id' => 5,
                'site_id' => 1,
                'created_at' => '2018-03-26 21:36:32',
                'updated_at' => '2018-03-26 21:36:32',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'user_id' => 6,
                'site_id' => 1,
                'created_at' => '2018-03-26 21:37:28',
                'updated_at' => '2018-03-26 21:37:28',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'user_id' => 5,
                'site_id' => 6,
                'created_at' => '2018-03-28 19:16:25',
                'updated_at' => '2018-03-28 19:16:25',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'user_id' => 6,
                'site_id' => 6,
                'created_at' => '2018-03-28 19:17:39',
                'updated_at' => '2018-03-28 19:17:39',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'user_id' => 4,
                'site_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'user_id' => 9,
                'site_id' => 6,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'user_id' => 1,
                'site_id' => 6,
                'created_at' => '2018-03-28 20:24:54',
                'updated_at' => '2018-03-28 20:24:54',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'user_id' => 12,
                'site_id' => 1,
                'created_at' => '2018-03-29 14:50:23',
                'updated_at' => '2018-03-29 14:50:23',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'user_id' => 42,
                'site_id' => 6,
                'created_at' => '2018-03-29 19:41:49',
                'updated_at' => '2018-03-29 19:41:49',
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'user_id' => 43,
                'site_id' => 1,
                'created_at' => '2018-03-29 19:59:53',
                'updated_at' => '2018-03-29 19:59:53',
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'user_id' => 7,
                'site_id' => 6,
                'created_at' => '2018-03-29 20:47:48',
                'updated_at' => '2018-03-29 20:47:48',
                'deleted_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'user_id' => 10,
                'site_id' => 6,
                'created_at' => '2018-03-29 20:49:55',
                'updated_at' => '2018-03-29 20:49:55',
                'deleted_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'user_id' => 8,
                'site_id' => 6,
                'created_at' => '2018-03-29 20:59:40',
                'updated_at' => '2018-03-29 20:59:40',
                'deleted_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'user_id' => 44,
                'site_id' => 6,
                'created_at' => '2018-03-29 21:03:25',
                'updated_at' => '2018-03-29 21:03:25',
                'deleted_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'user_id' => 8,
                'site_id' => 1,
                'created_at' => '2018-03-29 21:43:57',
                'updated_at' => '2018-03-29 21:43:57',
                'deleted_at' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'user_id' => 45,
                'site_id' => 6,
                'created_at' => '2018-03-30 16:30:31',
                'updated_at' => '2018-03-30 16:30:31',
                'deleted_at' => NULL,
            ),
            19 => 
            array (
                'id' => 21,
                'user_id' => 44,
                'site_id' => 1,
                'created_at' => '2018-03-30 18:21:34',
                'updated_at' => '2018-03-30 18:21:34',
                'deleted_at' => NULL,
            ),
            20 => 
            array (
                'id' => 22,
                'user_id' => 12,
                'site_id' => 6,
                'created_at' => '2018-04-24 18:52:20',
                'updated_at' => '2018-04-24 18:52:20',
                'deleted_at' => NULL,
            ),
            21 => 
            array (
                'id' => 23,
                'user_id' => 47,
                'site_id' => 6,
                'created_at' => '2018-04-24 20:06:38',
                'updated_at' => '2018-04-24 20:06:38',
                'deleted_at' => NULL,
            ),
            22 => 
            array (
                'id' => 24,
                'user_id' => 4,
                'site_id' => 6,
                'created_at' => '2018-05-01 15:35:54',
                'updated_at' => '2018-05-01 15:35:54',
                'deleted_at' => NULL,
            ),
            23 => 
            array (
                'id' => 25,
                'user_id' => 1,
                'site_id' => 1,
                'created_at' => '2018-07-25 12:58:01',
                'updated_at' => '2018-07-25 12:58:01',
                'deleted_at' => NULL,
            ),
            24 => 
            array (
                'id' => 26,
                'user_id' => 3,
                'site_id' => 1,
                'created_at' => '2018-10-10 01:54:42',
                'updated_at' => '2018-10-10 01:54:42',
                'deleted_at' => NULL,
            ),
            25 => 
            array (
                'id' => 27,
                'user_id' => 3,
                'site_id' => 6,
                'created_at' => '2018-10-10 13:55:24',
                'updated_at' => '2018-10-10 13:55:24',
                'deleted_at' => NULL,
            ),
            26 => 
            array (
                'id' => 28,
                'user_id' => 49,
                'site_id' => 1,
                'created_at' => '2019-04-04 16:03:24',
                'updated_at' => '2019-04-04 16:03:24',
                'deleted_at' => NULL,
            ),
            27 => 
            array (
                'id' => 29,
                'user_id' => 50,
                'site_id' => 1,
                'created_at' => '2019-04-04 16:04:00',
                'updated_at' => '2019-04-04 16:04:00',
                'deleted_at' => NULL,
            ),
            28 => 
            array (
                'id' => 32,
                'user_id' => 51,
                'site_id' => 1,
                'created_at' => '2019-04-05 16:04:00',
                'updated_at' => '2019-04-05 16:04:00',
                'deleted_at' => NULL,
            ),
            29 => 
            array (
                'id' => 33,
                'user_id' => 52,
                'site_id' => 1,
                'created_at' => '2019-04-05 16:04:00',
                'updated_at' => '2019-04-05 16:04:00',
                'deleted_at' => NULL,
            ),
            30 => 
            array (
                'id' => 34,
                'user_id' => 53,
                'site_id' => 1,
                'created_at' => '2019-04-05 16:21:14',
                'updated_at' => '2019-04-05 16:21:14',
                'deleted_at' => NULL,
            ),
            31 => 
            array (
                'id' => 35,
                'user_id' => 54,
                'site_id' => 1,
                'created_at' => '2019-04-05 18:39:37',
                'updated_at' => '2019-04-05 18:39:37',
                'deleted_at' => NULL,
            ),
            32 => 
            array (
                'id' => 36,
                'user_id' => 55,
                'site_id' => 1,
                'created_at' => '2019-04-05 18:42:18',
                'updated_at' => '2019-04-05 18:42:18',
                'deleted_at' => NULL,
            ),
            33 => 
            array (
                'id' => 37,
                'user_id' => 56,
                'site_id' => 1,
                'created_at' => '2019-04-22 18:24:08',
                'updated_at' => '2019-04-22 18:24:08',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}