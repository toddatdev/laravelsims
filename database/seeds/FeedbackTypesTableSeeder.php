<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FeedbackTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currentTimestamp = Carbon::now();

        \App\Models\CourseContent\QSE\FeedbackType::insert([
            [
                'description' => 'None',
                'created_at' => $currentTimestamp,
                'updated_at' => $currentTimestamp,
            ],
            [
                'description' => 'Score Only',
                'created_at' => $currentTimestamp,
                'updated_at' => $currentTimestamp,
            ],
            [
                'description' => 'Score and Answers',
                'created_at' => $currentTimestamp,
                'updated_at' => $currentTimestamp,
            ],
        ]);
    }
}
