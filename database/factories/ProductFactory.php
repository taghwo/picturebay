<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user =  User::factory(1)->create();
        return [
            'name' => $this->faker->word,
            'location' => $this->faker->sentence,
            'detail' => $this->faker->paragraph(),
            'user_id' => $user->first()->id,
            'uuid' => Str::uuid(),
            'status' => 'open',
            'created_at' => now(),
        ];
    }
}
