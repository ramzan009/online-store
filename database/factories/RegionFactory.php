<?php

namespace Database\Factories;

use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Region>
 */
class RegionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Region::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->city . ' ' . $this->faker->unique()->numerify('###'),
            'slug' => $this->faker->unique()->slug(2),
            'parent_id' => null,
        ];
    }
}
