<?php

namespace Tests\Feature;

use App\Models\PhotographerRequest;
use App\Models\PhotoShoot;
use App\Models\Product;
use App\Models\User;
use App\Repositories\Contracts\PhotoShootContract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Mockery;
use Tests\TestCase;

class PhotoShootTest extends TestCase
{
    use WithFaker;
    protected $product;
    protected function Setup():void
    {
        parent::setUp();
        $this->photoshoot = Mockery::mock(PhotoShootContract::class);
        $this->data = [
            'images' => [
                UploadedFile::fake()->image('test_image.jpg'),
                UploadedFile::fake()->image('test_image.jpg'),
            ],
            'status' => rand(0, 1),
            'photographer_request_id' => PhotographerRequest::factory(1)->create()->first()->id,
            'created_at' => now(),
        ];
    }
    /**
     *
     * @return void
     */
    public function test_photoshoot_can_be_created_with_interface()
    {
        $this->photoshoot->shouldReceive('create')->once()->andReturn(function ($photoshoot) {
            $this->assertNotEmpty($photoshoot->name);
            $this->assertDatabaseHas('photo_shoots', ['photographer_request_id' => $this->data['photographer_request_id']]);
        });
        $this->photoshoot->create($this->data);
    }

    /**
    *
    * @return void
    */
    public function test_guest_user_cannot_view_photoshoot()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
        ])->postJson('api/v1/photoshoots', $this->data);

        $response->assertStatus(401);
    }

    /**
     *
     * @return void
     */
    public function test_auth_user_can_create_photoshoot()
    {
        $user = User::factory(1)->make();

        $this->actingAs($user->first());

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
        ])->postJson('api/v1/photoshoots', $this->data);

        $response->assertOk();
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

        $this->data['images'] = "";

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
        ])->postJson('api/v1/photoshoots', $this->data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['images']);
    }

    /**
     *
     * @return void
     */
    public function test_viewing_photoshoot_i_dont_own_and_i_am_not_the_photographer_should_throw_error()
    {
        $user = User::factory(1)->create();

        $this->actingAs($user->first());

        $photoshoot = PhotoShoot::factory(1)->create();

        $user = User::factory(1)->create();

        $this->actingAs($user->first());

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
        ])->getJson('api/v1/photoshoots/'.$photoshoot->first()->id);

        $response->assertStatus(403);
        $response->assertJsonStructure([
            'status',
            'message'
        ]);
    }

    /**
     *
     * @return void
     */
    public function test_product_owner_and_photoshoot_owner_can_view_photoshoot()
    {
        $roleId = buyerId();
        for ($i=0; $i < 2;$i++) {
            $users[] = User::factory()->make(['role_id' =>  $roleId]);
            $roleId = photographerId();
        }

        $owner = $users[0];

        $photographer = $users[1];


        $this->actingAs($owner->first());

        $product = Product::factory(1)->create(['user_id' => $owner->first()->id]);

        $photoGrapherRequest = PhotographerRequest::factory(1)->create(['product_id' => $product->first()->id, 'photographer_id' =>  $photographer->first()->id]);

        $photoshoot = PhotoShoot::factory(1)->create(['photographer_request_id' =>  $photoGrapherRequest->first()->id]);

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
        ])->getJson('api/v1/photoshoots/'.$photoshoot->first()->id);

        $response->assertOk();
        $response->assertJsonStructure([
            'status',
            'data'
        ]);

        $this->actingAs($photographer->first());

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
        ])->getJson('api/v1/photoshoots/'.$photoshoot->first()->id);

        $response->assertOk();
        $response->assertJsonStructure([
            'status',
            'data'
        ]);
    }


    /**
     *
     * @return void
     */
    public function test_product_owner_and_photoshoot_owner_can_download_photoshoot()
    {
        $roleId = buyerId();
        for ($i=0; $i < 2;$i++) {
            $users[] = User::factory()->make(['role_id' =>  $roleId]);
            $roleId = photographerId();
        }

        $owner = $users[0];

        $photographer = $users[1];

        $this->actingAs($owner);

        $product = Product::factory(1)->create(['user_id' => $owner['id']]);

        $photoGrapherRequest = PhotographerRequest::factory(1)->create(['product_id' => $product->first()->id, 'photographer_id' =>  $photographer['id']]);

        $photoshoot = PhotoShoot::factory(1)->create(['photographer_request_id' =>  $photoGrapherRequest->first()->id,'status' => 1]);

        $expectedName = $photoshoot->first()->name;

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            ])->getJson('api/v1/photoshoots/'.$photoshoot->first()->id.'/download');

        $this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename=' . $expectedName . '');

        $this->actingAs($photographer);

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
        ])->getJson('api/v1/photoshoots/'.$photoshoot->first()->id.'/download');

        $this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename=' . $expectedName . '');
    }
    protected function tearDown():void
    {
        parent::tearDown();
        Mockery::close();
    }
}
