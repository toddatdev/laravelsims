<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ActivationTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currentTimestamp = Carbon::now();

        \App\Models\CourseContent\QSE\ActivationType::insert([
            [
                'description' => 'Manual',
                'created_at' => $currentTimestamp,
                'updated_at' => $currentTimestamp,
            ],
            [
                'description' => 'Automatic',
                'created_at' => $currentTimestamp,
                'updated_at' => $currentTimestamp,
            ],
        ]);
    }
}
