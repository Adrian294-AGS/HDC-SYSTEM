<?php

namespace Database\Factories;

use App\Models\College;
use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Program>
 */
class ProgramFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'college_id' => College::factory(),
            'code' => strtoupper(fake()->unique()->lexify('BS????')),
            'name' => 'Bachelor of Science in '.fake()->words(2, true),
        ];
    }
}
