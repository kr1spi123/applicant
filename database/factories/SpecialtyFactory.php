<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Specialty>
 */
class SpecialtyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->jobTitle(),
            'code' => $this->faker->numerify('##.##.##'),
            'duration' => '3 года 10 месяцев',
            'budget_places' => $this->faker->numberBetween(10, 50),
            'description' => $this->faker->paragraph(),
            'qualification' => 'Technician',
            'cost_full_time' => $this->faker->numberBetween(50000, 80000),
            'cost_part_time' => null,
            'cost_distance' => null,
        ];
    }
}
