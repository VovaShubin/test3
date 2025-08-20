<?php

namespace Database\Factories;

use App\Models\SupportRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SupportRequest>
 */
class SupportRequestFactory extends Factory
{
    protected $model = SupportRequest::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'status' => $this->faker->randomElement([SupportRequest::STATUS_ACTIVE, SupportRequest::STATUS_RESOLVED]),
            'message' => $this->faker->paragraph(),
            'comment' => $this->faker->optional()->sentence(),
            'created_at' => $this->faker->dateTimeBetween('-30 days'),
            'updated_at' => now(),
        ];
    }
}


