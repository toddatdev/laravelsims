<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PresentationTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currentTimestamp = Carbon::now();

        \App\Models\CourseContent\QSE\PresentationType::insert([
            [
                'description' => 'One Question Per Page',
                'created_at' => $currentTimestamp,
                'updated_at' => $currentTimestamp,
            ],
            [
                'description' => 'All Questions on One Page',
                'created_at' => $currentTimestamp,
                'updated_at' => $currentTimestamp,
            ],
        ]);
    }
}
