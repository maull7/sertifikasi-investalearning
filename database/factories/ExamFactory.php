<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exam>
 */
class ExamFactory extends Factory
{
    protected $model = Exam::class;

    public function definition(): array
    {
        return [
            'package_id' => Package::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(2),
            'duration' => $this->faker->numberBetween(30, 180),
            'passing_grade' => $this->faker->numberBetween(60, 85),
            // Kolom enum di DB: ['pretest', 'posttest']
            'type' => $this->faker->randomElement(['pretest', 'posttest']),
            'show_result_after' => $this->faker->boolean(),
        ];
    }
}

