<?php

use Illuminate\Database\Seeder;

class ContentTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('content_types')->delete();

        DB::table('content_types')->insert(array (
            0 =>
                [
                    'id' => 1,
                    'description' => 'Folder',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                ],
            1 =>
                array (
                    'id' => 2,
                    'description' => 'Page',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                ),
            2 =>
                array (
                    'id' => 3,
                    'description' => 'Video',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                ),
            3 =>
                array (
                    'id' => 4,
                    'description' => 'Doc',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                ),
            4 =>
                array (
                    'id' => 5,
                    'description' => 'Download',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                ),
            5 =>
                array (
                    'id' => 6,
                    'description' => 'Articulate',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                ),
            6 =>
                array (
                    'id' => 7,
                    'description' => 'QSE',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                )
        ));
    }
}
