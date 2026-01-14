<?php

namespace Database\Factories;

use App\Models\Exams;
use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exams>
 */
class ExamsFactory extends Factory
{
    protected $model = Exams::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('now', '+1 month');
        $endDate = $this->faker->dateTimeBetween($startDate, '+2 months');

        return [
            'package_id' => Package::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(2),
            'duration' => $this->faker->numberBetween(30, 180),
            'passing_grade' => $this->faker->numberBetween(60, 85),
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }
}


