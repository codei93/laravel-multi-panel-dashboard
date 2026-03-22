<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TripFactory extends Factory
{
    protected $model = Trip::class;

    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);

        return [
            'customer_id' => Customer::factory(),
            'title' => ucwords($title),
            'slug' => Str::slug($title),
            'total_price' => fake()->randomFloat(2, 100, 5000),
        ];
    }
}
