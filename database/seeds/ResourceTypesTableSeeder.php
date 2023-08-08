<?php

use Illuminate\Database\Seeder;

class ResourceTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('resource_types')->delete();
        
        DB::table('resource_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'abbrv' => 'Room',
                'display_order' => 1,
                'created_at' => '2018-05-31 12:00:00',
                'updated_at' => '2018-05-31 12:00:00',
            ),
            1 => 
            array (
                'id' => 2,
                'abbrv' => 'Equipment',
                'display_order' => 2,
                'created_at' => '2018-05-31 12:00:00',
                'updated_at' => '2018-05-31 12:00:00',
            ),
            2 => 
            array (
                'id' => 3,
                'abbrv' => 'Personnel',
                'display_order' => 3,
                'created_at' => '2018-05-31 12:00:00',
                'updated_at' => '2018-05-31 12:00:00',
            ),
        ));
        
        
    }
}