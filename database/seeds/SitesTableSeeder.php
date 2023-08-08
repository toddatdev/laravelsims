<?php

use Illuminate\Database\Seeder;

class SitesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('sites')->delete();
        
        DB::table('sites')->insert(array (
            0 => 
            array (
                'id' => 1,
                'abbrv' => 'WISER',
                'name' => 'Winter Institute for Simulaiton, Education, and Research',
                'organization_name' => 'WISER ROCKS!',
                'email' => 'wiserhelp2@upmc.edu',
                'created_at' => '2018-02-21 12:02:54',
                'updated_at' => '2018-02-23 23:54:50',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 6,
                'abbrv' => 'SimTiki',
                'name' => 'John A Burns School of Medicine Telehealth Research Institute SimTiki Simulation Center
',
                'organization_name' => 'SimTiki',
                'email' => 'help@simtiki.org',
                'created_at' => '2018-03-21 10:29:04',
                'updated_at' => '2018-03-21 10:29:04',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}