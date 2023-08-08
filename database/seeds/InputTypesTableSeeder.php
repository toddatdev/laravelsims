<?php

use Illuminate\Database\Seeder;

class InputTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('input_types')->delete();

        $inputTypes = [
            [
                'id' => 1,
                'description' => 'checkbox',
                'created_at' => '2018-10-29 17:21:25',
                'updated_at' => '2018-10-29 17:21:25',
            ],
            [
                'id' => 2,
                'description' => 'radio',
                'created_at' => '2018-10-29 17:21:25',
                'updated_at' => '2018-10-29 17:21:25',
            ],
            [
                'id' => 3,
                'description' => 'text',
                'created_at' => '2018-10-29 17:21:25',
                'updated_at' => '2018-10-29 17:21:25',
            ],
            [
                'id' => 4,
                'description' => 'color',
                'created_at' => '2018-10-29 17:21:25',
                'updated_at' => '2018-10-29 17:21:25',
            ],
            [
                'id' => 5,
                'description' => 'date',
                'created_at' => '2018-10-29 17:21:25',
                'updated_at' => '2018-10-29 17:21:25',
            ],
            [
                'id' => 6,
                'description' => 'number',
                'created_at' => '2018-10-29 17:21:25',
                'updated_at' => '2018-10-29 17:21:25',
            ],
            [
                'id' => 7,
                'description' => 'select',
                'created_at' => '2018-10-29 17:21:25',
                'updated_at' => NULL,
            ],
            [
                'id' => 8,
                'description' => 'minute_ba_start',
                'created_at' => '2018-10-29 17:21:25',
                'updated_at' => NULL,
            ],
            [
                'id' => 9,
                'description' => 'minute',
                'created_at' => '2018-10-29 17:21:25',
                'updated_at' => NULL,
            ],
            [
                'id' => 10,
                'description' => 'minute_ba_end',
                'created_at' => '2018-10-29 17:21:25',
                'updated_at' => NULL,
            ],
            [
                'id' => 11,
                'description' => 'select_multiple',
                'created_at' => '2018-10-29 17:21:25',
                'updated_at' => NULL,
            ],
            [
                'id' => 12,
                'description' => 'slider',
                'created_at' => '2018-10-29 17:21:25',
                'updated_at' => NULL,
            ],
        ];

        foreach ($inputTypes as $inputType) {
            \DB::table('input_types')->updateOrInsert($inputType, $inputType);
        }
    }
}