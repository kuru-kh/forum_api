<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
    public function test_register_api()
    {
        $data = [
            'name' => 'TestName',
            'email' => 'test@test.com',
            'password' => '123456789'
        ];
        $response = $this->post('/api/v1/register', $data);
        $response->assertStatus(201);
    }
}
