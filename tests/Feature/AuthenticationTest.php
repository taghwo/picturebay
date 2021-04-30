<?php

namespace Tests\Feature;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use WithFaker;
    protected $data;
    protected function setUp():void
    {
        parent::setUp();
        $this->data = [
            "full_name" => $this->faker->name,
            "email" => $this->faker->safeEmail(),
            "password" => "password",
            "password_confirmation" => "password",
            "role_id" => rand(1, 2)
        ];
    }

    /**
     * An invalid role id should raise validation error
     *
     * @return void
     */
    public function test_registration_should_return_validation_error_for_invalid_role_id()
    {
        $this->data['role_id'] = rand(3, 100);

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
        ])->postJson('api/v1/auth/register', $this->data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['role_id']);
    }

    /**
     * An invalid role id should raise validation error
     *
     * @return void
     */
    public function test_registration_should_return_validation_error_if_email_is_taken()
    {
        $email = $this->faker->safeEmail();

        \App\Models\User::factory(1)->create(['email' => $email ]);

        $this->data['email'] = $email;

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
        ])->postJson('api/v1/auth/register', $this->data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    /**
    * An invalid role id should raise validation error
    *
    * @return void
    */
    public function test_registration_will_go_through_with_correct_data()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
        ])->postJson('api/v1/auth/register', $this->data);

        $response->assertCreated();
        $response->assertJsonStructure([
            'status',
            'data' => [
                'full_name',
                'token'
            ]
        ]);
    }

    /**
    * An invalid role id should raise validation error
    *
    * @return void
    */
    public function test_logged_in_user_can_logout()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
        ])->postJson('api/v1/auth/register', $this->data);


        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
        ])->postJson('api/v1/auth/login', ['email' => $this->data['email'], 'password' => $this->data['password']]);

        $response->assertOk();

        $token = $response->getData()->data->token;

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('api/v1/auth/logout');

        $response->assertOk();
        $response->assertExactJson([
            'status' => "success",
            'message' => "successfully logged out",
        ]);
    }


    /**
    * An invalid role id should raise validation error
    *
    * @return void
    */
    public function test_user_can_login()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
        ])->postJson('api/v1/auth/register', $this->data);


        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
        ])->postJson('api/v1/auth/login', ['email' => $this->data['email'], 'password' => $this->data['password']]);

        $response->assertOk();
        $response->assertJsonStructure([
            'status',
            'data' => [
                'full_name',
                'token'
            ]
        ]);
    }
}
