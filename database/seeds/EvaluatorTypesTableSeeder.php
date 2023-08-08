<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EvaluatorTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currentTimestamp = Carbon::now();

        \App\Models\CourseContent\QSE\EvaluatorType::insert([
            [
                'description' => 'Learner',
                'created_at' => $currentTimestamp,
                'updated_at' => $currentTimestamp,
            ],
            [
                'description' => 'Instructor',
                'created_at' => $currentTimestamp,
                'updated_at' => $currentTimestamp,
            ],
        ]);
    }
}
