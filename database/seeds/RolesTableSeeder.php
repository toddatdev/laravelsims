<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('roles')->delete();
        
        \DB::table('roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'site_id' => 1,
                'name' => 'Administrator',
                'all' => 1,
                'sort' => 1,
                'created_at' => '2018-03-21 14:12:14',
                'updated_at' => '2018-03-21 14:12:14',
            ),
            1 => 
            array (
                'id' => 2,
                'site_id' => 1,
                'name' => 'Executive',
                'all' => 0,
                'sort' => 2,
                'created_at' => '2018-03-21 14:12:14',
                'updated_at' => '2018-03-21 14:12:14',
            ),
            2 => 
            array (
                'id' => 3,
                'site_id' => 1,
                'name' => 'User',
                'all' => 0,
                'sort' => 3,
                'created_at' => '2018-03-21 14:12:14',
                'updated_at' => '2018-03-21 14:12:14',
            ),
            3 => 
            array (
                'id' => 4,
                'site_id' => 1,
                'name' => 'Building Manager',
                'all' => 0,
                'sort' => 4,
                'created_at' => '2018-03-21 14:30:55',
                'updated_at' => '2018-03-21 14:30:55',
            ),
            4 => 
            array (
                'id' => 5,
                'site_id' => 1,
                'name' => 'Location Manager',
                'all' => 0,
                'sort' => 5,
                'created_at' => '2018-03-21 14:32:12',
                'updated_at' => '2018-03-21 14:32:12',
            ),
            5 => 
            array (
                'id' => 6,
                'site_id' => 1,
                'name' => 'Course Manager',
                'all' => 0,
                'sort' => 6,
                'created_at' => '2018-03-21 14:32:32',
                'updated_at' => '2018-03-21 14:32:32',
            ),
            6 => 
            array (
                'id' => 7,
                'site_id' => 1,
                'name' => 'User Manager',
                'all' => 0,
                'sort' => 7,
                'created_at' => '2018-03-21 14:33:00',
                'updated_at' => '2018-03-21 14:33:00',
            ),
            7 => 
            array (
                'id' => 8,
                'site_id' => 6,
                'name' => 'Administrator',
                'all' => 1,
                'sort' => 1,
                'created_at' => '2018-03-21 14:12:14',
                'updated_at' => '2018-03-21 14:12:14',
            ),
            8 => 
            array (
                'id' => 14,
                'site_id' => 6,
                'name' => 'Tiki Manager',
                'all' => 0,
                'sort' => 2,
                'created_at' => '2018-03-26 20:29:59',
                'updated_at' => '2018-04-24 18:29:54',
            ),
            9 => 
            array (
                'id' => 15,
                'site_id' => 6,
                'name' => 'Wahi Managers',
                'all' => 0,
                'sort' => 3,
                'created_at' => '2018-03-26 21:29:45',
                'updated_at' => '2018-04-24 18:30:00',
            ),
            10 => 
            array (
                'id' => 16,
                'site_id' => 1,
                'name' => 'Role Manager',
                'all' => 0,
                'sort' => 8,
                'created_at' => '2018-03-26 21:33:10',
                'updated_at' => '2018-05-01 19:31:28',
            ),
            11 => 
            array (
                'id' => 18,
                'site_id' => 6,
                'name' => 'Kanaka',
                'all' => 0,
                'sort' => 4,
                'created_at' => '2018-03-29 15:27:05',
                'updated_at' => '2018-03-30 18:28:00',
            ),
            12 => 
            array (
                'id' => 19,
                'site_id' => 6,
                'name' => 'Holo Ana Manager',
                'all' => 0,
                'sort' => 5,
                'created_at' => '2018-03-29 20:52:57',
                'updated_at' => '2018-04-24 18:30:07',
            ),
            13 => 
            array (
                'id' => 20,
                'site_id' => 6,
                'name' => 'Kanaka Manager',
                'all' => 0,
                'sort' => 6,
                'created_at' => '2018-03-29 21:00:46',
                'updated_at' => '2018-04-24 18:30:13',
            ),
            14 => 
            array (
                'id' => 21,
                'site_id' => 6,
                'name' => 'Kulana Manager',
                'all' => 0,
                'sort' => 7,
                'created_at' => '2018-03-29 21:05:17',
                'updated_at' => '2018-04-24 18:30:22',
            ),
            15 => 
            array (
                'id' => 22,
                'site_id' => 1,
                'name' => 'Schedule Manager',
                'all' => 0,
                'sort' => 9,
                'created_at' => '2019-04-04 14:26:19',
                'updated_at' => '2019-04-04 14:26:19',
            ),
            16 => 
            array (
                'id' => 23,
                'site_id' => 1,
                'name' => 'Schedule Requestor',
                'all' => 0,
                'sort' => 10,
                'created_at' => '2019-04-04 15:09:10',
                'updated_at' => '2019-04-04 15:09:10',
            ),
            17 => 
            array (
                'id' => 24,
                'site_id' => 1,
                'name' => 'Template Manager',
                'all' => 0,
                'sort' => 11,
                'created_at' => '2019-04-05 14:45:27',
                'updated_at' => '2019-04-05 14:45:44',
            ),
            18 => 
            array (
                'id' => 25,
                'site_id' => 1,
                'name' => 'Course Options',
                'all' => 0,
                'sort' => 12,
                'created_at' => '2019-04-05 18:40:17',
                'updated_at' => '2019-04-05 18:40:17',
            ),
            19 => 
            array (
                'id' => 26,
                'site_id' => 1,
                'name' => 'Course Categories',
                'all' => 0,
                'sort' => 13,
                'created_at' => '2019-04-05 18:40:53',
                'updated_at' => '2019-04-05 18:40:53',
            ),
            20 => 
            array (
                'id' => 27,
                'site_id' => 1,
                'name' => 'Resource Manager',
                'all' => 0,
                'sort' => 14,
                'created_at' => '2019-04-22 18:23:12',
                'updated_at' => '2019-04-22 18:23:12',
            ),
        ));
        
        
    }
}