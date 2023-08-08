<?php

use Illuminate\Database\Seeder;

class ViewerTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('viewer_types')->delete();

        DB::table('viewer_types')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'description' => 'Instructor',
                    'display_order' => '1',
                    'permission_ids' => '39,42,43',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                    ),
            1 =>
                array (
                    'id' => 2,
                    'description' => 'Learner',
                    'display_order' => '0',
                    'permission_ids' => '38,40,41',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                )
        ));
    }
}
