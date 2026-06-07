<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $firstNames = ['Александр', 'Иван', 'Максим', 'Дмитрий', 'Сергей', 'Андрей', 'Алексей', 'Михаил', 'Николай', 'Елена', 'Мария', 'Анна', 'Ольга', 'Татьяна', 'Наталья', 'Ирина', 'Светлана', 'Юлия'];
        $surnames = ['Иванов', 'Петров', 'Сидоров', 'Кузнецов', 'Попов', 'Васильев', 'Соколов', 'Михайлов', 'Новиков', 'Федоров', 'Морозов', 'Волков', 'Алексеев', 'Лебедев', 'Семенов'];

        return [
            'name' => $this->faker->randomElement($firstNames),
            'surname' => $this->faker->randomElement($surnames),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'phone' => '+7(9' . rand(10, 99) . ')' . rand(100, 999) . '-' . rand(10, 99) . '-' . rand(10, 99),
            'birthdate' => $this->faker->dateTimeBetween('-25 years', '-17 years')->format('Y-m-d'),
            'city' => $this->faker->randomElement(['Москва', 'Санкт-Петербург', 'Новосибирск', 'Екатеринбург', 'Казань']),
            'street' => $this->faker->streetName(),
            'house' => rand(1, 150),
            'citizenship' => 'Россия',
            'school' => 'Школа №' . rand(1, 100),
            'graduation_year' => rand(2018, 2024),
            'role' => 'user',
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
