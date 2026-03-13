<?php

namespace Database\Factories;

use App\Models\MasterType;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subject>
 */
class SubjectFactory extends Factory
{
    protected $model = Subject::class;

    public function definition(): array
    {
        return [
            // Akan diisi eksplisit di seeder; default null supaya tidak memicu factory MasterType.
            'master_type_id' => null,
            'name' => $this->faker->unique()->words(3, true),
            'code' => strtoupper($this->faker->bothify('SUB-###')),
            'description' => $this->faker->sentence(8),
        ];
    }
}

