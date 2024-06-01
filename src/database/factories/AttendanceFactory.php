<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Attendance;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition()
    {
        $dummy_date = $this->faker->dateTimeThisMonth;
        return [
            'user_id' => $this->faker->numberBetween(1,10),
            'work_start' => $dummy_date->format('Y-m-d H:i:s'),
            'work_end' => $dummy_date->modify('+1hour')->format('Y-m-d H:i:s'),
        ];
    }
}
