<?php

namespace Tests\Feature;

use App\Repository\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private $base_path = '/api/v1/';
    private $user_repo;
    private $sample_data = [
        'name'     => 'TestName',
        'email'    => 'test@test.com',
        'password' => '123456789'
    ];
    public function setUp(): void
    {
        parent::setUp();

        $this->user_repo = app(UserRepository::class);
    }

    public function test_valid_login()
    {
        $this->user_repo->create($this->sample_data);
        $response = $this->post($this->base_path.'login', ['email' => 'test@test.com', 'password' => '123456789']);

        $response->assertStatus(200)->assertJson(fn (AssertableJson $json) => 
            $json->has('data.token')
        );
    }

    public function test_invalid_login()
    {
        $this->user_repo->create($this->sample_data);
        $response = $this->post($this->base_path.'login', ['email' => 'tes1t@test.com', 'password' => '123456789']);

        $response->assertStatus(401);
    }
}
