<?php

use Illuminate\Database\Seeder;

class SiteOptionTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('site_option')->delete();
        
        \DB::table('site_option')->insert(array (
            0 => 
            array (
                'id' => 9,
                'site_id' => 1,
                'site_option_id' => 3,
                'value' => 'N',
                'created_at' => '2018-05-08 14:07:55',
                'updated_at' => '2018-05-08 14:07:55',
            ),
            1 => 
            array (
                'id' => 10,
                'site_id' => 1,
                'site_option_id' => 1,
            'value' => 'rgba(31,38,60, 1)',
                'created_at' => '2018-05-08 14:07:55',
                'updated_at' => '2018-05-08 14:07:55',
            ),
            2 => 
            array (
                'id' => 11,
                'site_id' => 1,
                'site_option_id' => 2,
            'value' => 'rgba(255,255,255,1)',
                'created_at' => '2018-05-08 14:07:55',
                'updated_at' => '2018-05-08 14:07:55',
            ),
            3 => 
            array (
                'id' => 12,
                'site_id' => 1,
                'site_option_id' => 5,
                'value' => 'UA-123214068-1',
                'created_at' => '2018-05-08 14:07:55',
                'updated_at' => '2018-08-01 14:41:57',
            ),
            4 => 
            array (
                'id' => 13,
                'site_id' => 1,
                'site_option_id' => 4,
                'value' => '3',
                'created_at' => '2018-05-08 14:07:55',
                'updated_at' => '2018-05-08 14:07:55',
            ),
            5 => 
            array (
                'id' => 14,
                'site_id' => 6,
                'site_option_id' => 3,
                'value' => 'N',
                'created_at' => '2018-05-08 14:09:19',
                'updated_at' => '2018-05-08 14:09:19',
            ),
            6 => 
            array (
                'id' => 15,
                'site_id' => 6,
                'site_option_id' => 1,
            'value' => 'rgba(1,41,50,1)',
                'created_at' => '2018-05-08 14:09:19',
                'updated_at' => '2018-05-08 14:09:19',
            ),
            7 => 
            array (
                'id' => 16,
                'site_id' => 6,
                'site_option_id' => 2,
            'value' => 'rgba(255,255,255,1)',
                'created_at' => '2018-05-08 14:09:19',
                'updated_at' => '2018-05-08 14:09:19',
            ),
            8 => 
            array (
                'id' => 17,
                'site_id' => 6,
                'site_option_id' => 5,
                'value' => 'UA-123214068-2',
                'created_at' => '2018-05-08 14:09:19',
                'updated_at' => '2018-08-01 15:20:31',
            ),
            9 => 
            array (
                'id' => 18,
                'site_id' => 6,
                'site_option_id' => 4,
                'value' => '18',
                'created_at' => '2018-05-08 14:09:19',
                'updated_at' => '2018-05-08 14:09:19',
            ),
            10 => 
            array (
                'id' => 19,
                'site_id' => 1,
                'site_option_id' => 6,
                'value' => '08',
                'created_at' => '2018-11-01 14:04:31',
                'updated_at' => '2018-11-01 14:04:31',
            ),
            11 => 
            array (
                'id' => 20,
                'site_id' => 1,
                'site_option_id' => 7,
                'value' => '14',
                'created_at' => '2018-11-01 14:04:31',
                'updated_at' => '2018-11-01 14:04:31',
            ),
            12 =>
                array (
                    'id' => 21,
                    'site_id' => 6,
                    'site_option_id' => 6,
                    'value' => '08',
                    'created_at' => '2018-11-01 14:04:31',
                    'updated_at' => '2018-11-01 14:04:31',
                ),
            13 =>
                array (
                    'id' => 22,
                    'site_id' => 6,
                    'site_option_id' => 7,
                    'value' => '17',
                    'created_at' => '2018-11-01 14:04:31',
                    'updated_at' => '2018-11-01 14:04:31',
                ),
        ));
        
        
    }
}