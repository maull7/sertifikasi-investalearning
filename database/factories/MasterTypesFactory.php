<?php

namespace Database\Factories;

use App\Models\MasterTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MasterTypes>
 */
class MasterTypesFactory extends Factory
{
    protected $model = MasterTypes::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = [
            'Matematika Dasar',
            'Bahasa Indonesia',
            'Bahasa Inggris',
            'Pengetahuan Umum',
            'Logika',
            'TPA',
            'TIU',
            'TWK',
            'Kimia',
            'Fisika',
            'Biologi',
            'Sejarah',
            'Geografi',
            'Ekonomi',
        ];

        return [
            'name_type' => $this->faker->unique()->randomElement($types),
        ];
    }
}







