<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QSEAnswerTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('qse_answer_types')->delete();

        $answerTypes = [
            [
                'id' => 1,
                'abbrv' => 'Multiple Choice Checkboxes',
                'description' => 'Answers will be displayed in checkbox control',
                'has_response' => 1,
                'input_type_id' => 1,
                'created_at' => '2020-12-09 00:00:00',
                'updated_at' => '2020-12-09 00:00:00',
            ],
            [
                'id' => 2,
                'abbrv' => 'Single Choice Radio Buttons',
                'description' => 'Answers wil be displayed in a radio control',
                'has_response' => 1,
                'input_type_id' => 2,
                'created_at' => '2020-12-09 00:00:00',
                'updated_at' => '2020-12-09 00:00:00',
            ],
            [
                'id' => 3,
                'abbrv' => 'Single Choice Dropdown',
                'description' => 'Answers will be displayed in select list, only one answer can be selected',
                'has_response' => 1,
                'input_type_id' => 7,
                'created_at' => '2020-12-09 00:00:00',
                'updated_at' => '2020-12-09 00:00:00',
            ],
            [
                'id' => 4,
                'abbrv' => 'Written Response',
                'description' => 'Free form answer text',
                'has_response' => 1,
                'input_type_id' => 3,
                'created_at' => '2020-12-09 00:00:00',
                'updated_at' => '2020-12-09 00:00:00',
            ],
            [
                'id' => 5,
                'abbrv' => 'Header',
                'description' => 'Free form answer text',
                'has_response' => 0,
                'input_type_id' => 3,
                'created_at' => '2020-12-09 00:00:00',
                'updated_at' => '2020-12-09 00:00:00',
            ],
            [
                'id' => 6,
                'abbrv' => 'Sub Header',
                'description' => 'Free form answer text',
                'has_response' => 0,
                'input_type_id' => 3,
                'created_at' => '2020-12-09 00:00:00',
                'updated_at' => '2020-12-09 00:00:00',
            ],
            [
                'id' => 7,
                'abbrv' => 'Likert Scale',
                'description' => 'Radio with inputs and color scale',
                'has_response' => 1,
                'input_type_id' => 2,
                'created_at' => '2020-12-09 00:00:00',
                'updated_at' => '2020-12-09 00:00:00',
            ],
            [
                'id' => 8,
                'abbrv' => 'Slider',
                'description' => 'Slider',
                'has_response' => 1,
                'input_type_id' => 12,
                'created_at' => '2020-12-09 00:00:00',
                'updated_at' => '2020-12-09 00:00:00',
            ],
            [
                'id' => 9,
                'abbrv' => 'Multiple Choice Dropdown',
                'description' => 'Select dropdown with multiple choice',
                'has_response' => 1,
                'input_type_id' => 11,
                'created_at' => '2020-12-09 00:00:00',
                'updated_at' => '2020-12-09 00:00:00',
            ],
            [
                'id' => 10,
                'abbrv' => 'Yes or No',
                'description' => 'Answer will be in select list only Yes/No',
                'has_response' => 1,
                'input_type_id' => 7,
                'created_at' => '2020-12-09 00:00:00',
                'updated_at' => '2020-12-09 00:00:00',
            ],
        ];

        DB::table('qse_answer_types')->insert($answerTypes);
    }
}