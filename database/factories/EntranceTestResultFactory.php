<?php

namespace Database\Factories;

use App\Models\EntranceTestResult;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EntranceTestResult>
 */
class EntranceTestResultFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $attributes = [
            'student_id' => User::factory()->student(),
            'exam_date' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
        ];

        foreach (array_keys(EntranceTestResult::subtests()) as $key) {
            $score = fake()->numberBetween(5, 16);
            $attributes["{$key}_score"] = $score;
            $attributes["{$key}_descriptor"] = EntranceTestResult::suggestDescriptor($score);
        }

        $quotient = fake()->numberBetween(80, 125);

        return $attributes + [
            'quotient' => $quotient,
            'quotient_descriptor' => EntranceTestResult::suggestDescriptor($quotient, 'quotient'),
        ];
    }
}
