<?php

namespace Database\Factories;

use App\Models\PhotographerRequest;
use App\Models\PhotoShoot;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PhotoShootFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PhotoShoot::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        if (!file_exists(public_path('storage/faker'))) {
            mkdir(public_path('storage/faker'), (int)777, true);
        }

        $image = $this->faker->image(public_path('storage/faker'), 1200, 600, null, false);
        return [
            'hq_file_path' => "faker/".$image,
            'thumbnail_file_path' => "faker/". $image,
            'name' =>  $image,
            'status' => rand(0, 1),
            'photographer_request_id' => PhotographerRequest::factory()->create()->first()->id,
            'created_at' => now(),
        ];
    }
}
