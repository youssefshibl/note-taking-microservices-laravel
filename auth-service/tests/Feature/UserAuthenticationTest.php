<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserAuthenticationTest extends TestCase
{
    use  RefreshDatabase, WithFaker;


    private $apiUrl = '/api';
    private $user;
    /**
     * A basic feature test example.
     */

    public function setUp(): void
    {
        parent::setUp();
        // Initialize the user data only once
        if (is_null($this->user)) {
            $this->user = [
                'name' => $this->faker->name,
                'email' => $this->faker->email,
                'password' => 'Password@123'
            ];
        }

        $this->withHeaders([
            'Accept' => 'application/json',
        ]);
    }

    public function test_register_user(): void
    {
        $endpoint = $this->apiUrl . '/register';
        // Register the user
        $response = $this->post($endpoint, $this->user);
        print_r($response->getContent());


        // Check the response
        $response->assertStatus(200);
        // Check the response structure
        $response->assertJsonStructure([
            'token', 'token_type'
        ]);
    }

    // test email is exists
    public function test_register_user_email_exists(): void
    {
        $endpoint = $this->apiUrl . '/register';

        // Register the user for the first time
        $this->post($endpoint, $this->user);

        // Try to register the same user again
        $response = $this->post($endpoint, $this->user);

        // Check the response
        $response->assertStatus(422);
        // Check the response structure
        $response->assertSeeText('Email is already taken');
    }


    public function login_user(): void
    {

        $endpoint = $this->apiUrl . '/login';
        // Login the user
        $response = $this->post($endpoint, [
            'email' => $this->user['email'],
            'password' => $this->user['password']
        ]);

        // Check the response
        $response->assertStatus(200);
        // Check the response structure
        $response->assertJsonStructure([
            'token', 'token_type'
        ]);

        // set token in header
        $token = $response->json()['token'];
        $token_type = $response->json()['token_type'];
        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => $token_type . ' ' . $token
        ]);
    }


    public function test_login_user(): void
    {

        // register the user
        $this->test_register_user();


        $this->login_user();
    }

    


    public function test_check_jwt_token(): void
    {
        // register the user
        $this->test_register_user();

        // login the user
        $this->login_user();

        $endpoint = $this->apiUrl . '/check';
        // Check the token
        $response = $this->get($endpoint);

        // Check the response
        $response->assertStatus(200);
        // Check the response structure
        $response->assertJsonStructure([
            'user'
        ]);

    }

    public function test_delete_user(): void
    {
        // register the user
        $this->test_register_user();

        // login the user
        $this->login_user();

        $endpoint = $this->apiUrl . '/delete';
        // Check the token
        $response = $this->delete($endpoint);

        // Check the response
        $response->assertStatus(200);
        // Check the response structure
        $response->assertSeeText('User deleted');
    }

}
