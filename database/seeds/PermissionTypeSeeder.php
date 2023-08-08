<?php

use Illuminate\Database\Seeder;

class PermissionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('permission_type')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'name' => 'Site'
                ),
            1 =>
                array (
                    'id' => 2,
                    'name' => 'Course',
                ),
            2 =>
                array (
                    'id' => 3,
                    'name' => 'Event',
                ),
        ));
    }
}
