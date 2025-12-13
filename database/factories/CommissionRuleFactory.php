<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CommissionRuleOrigin;
use App\Models\CommissionRuleDestination;

class CommissionRuleFactory extends Factory
{
    public function definition()
    {
        return [
            'rate_value' => $this->faker->randomFloat(2, 1, 100),
            'rate_type' => $this->faker->randomElement(['percentage', 'flat']),
        ];
    }

    public function hasOrigins($count = 3)
    {
        return $this->has(CommissionRuleOrigin::factory()->count($count), 'origins');
    }

    public function hasDestinations($count = 3)
    {
        return $this->has(CommissionRuleDestination::factory()->count($count), 'destinations');
    }
}