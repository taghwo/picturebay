<?php

namespace Database\Factories;

use App\Models\PhotographerRequest;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PhotographerRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PhotographerRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'photographer_id' => User::factory(1)->create()->first()->id,
            'product_id' => Product::factory(1)->create()->first()->id,
            'created_at' => now(),
        ];
    }
}
