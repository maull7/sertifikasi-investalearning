<?php

namespace Database\Factories;

use App\Models\BankQuestions;
use App\Models\MasterTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BankQuestions>
 */
class BankQuestionsFactory extends Factory
{
    protected $model = BankQuestions::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type_id' => MasterTypes::factory(),
            'question' => '<p>' . $this->faker->paragraph(3) . '</p>',
            'question_image' => null,
            'solution' => '<p>' . $this->faker->paragraph(2) . '</p>',
            'explanation' => '<p>' . $this->faker->paragraph(2) . '</p>',
            'option_a' => $this->faker->sentence(3),
            'option_b' => $this->faker->sentence(3),
            'option_c' => $this->faker->sentence(3),
            'option_d' => $this->faker->sentence(3),
            'answer' => $this->faker->randomElement(['a', 'b', 'c', 'd']),
        ];
    }
}


