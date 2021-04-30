<?php

namespace Tests\Feature;

use Illuminate\Support\Str;
use App\Models\User;
use App\Repositories\Contracts\ProductContract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use WithFaker;
    protected $product;
    protected $data;
    protected function Setup():void
    {
        parent::setUp();
        $this->product = Mockery::mock(ProductContract::class);
        $user =  User::factory(1)->create();
        $this->data = [
            'name' => $this->faker->word,
            'location' => $this->faker->sentence,
            'detail' => $this->faker->paragraph(),
            'user_id' => $user->first()->id,
            'uuid' => Str::uuid(),
            'status' => 'open',
            'created_at' => now(),
        ];
    }
    /**
     *
     * @return void
     */
    public function test_product_can_be_created_with_interface()
    {
        $this->product->shouldReceive('create')->once()->andReturn(function($product) {
            $this->assertEquals($product->name, $this->data['name']);
            $this->assertDatabaseHas('product', ['name' => $this->data['name']]);
        });
        $this->product->create($this->data);
    }

     /**
     *
     * @return void
     */
    public function test_guest_user_cannot_create_product()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
        ])->postJson('api/v1/products', $this->data);

        $response->assertStatus(401);
    }

      /**
     *
     * @return void
     */
    public function test_auth_user_can_create_product()
    {
        $user = User::factory(1)->make();

        $this->actingAs($user->first());

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
        ])->postJson('api/v1/products', $this->data);

        $response->assertCreated();
        $response->assertJsonStructure([
            'status',
            'data'
        ]);
    }

    /**
     *
     * @return void
     */
    public function test_validation_errors_thrown()
    {
        $user = User::factory(1)->make();

        $this->actingAs($user->first());

        unset($this->data['name']);

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
        ])->postJson('api/v1/products', $this->data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);

    }

    protected function tearDown():void
    {
        parent::tearDown();
        Mockery::close();
    }
}
