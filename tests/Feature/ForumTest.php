<?php

namespace Tests\Feature;

use App\Models\Forum;
use App\Models\User;
use App\Repository\ForumRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ForumTest extends TestCase
{
    use RefreshDatabase;

    private $base_path = '/api/v1/';
    private $user;
    private $admin_user;
    private $sample_data = [
        'title'     => 'This is a forum title',
        'user_id' => 2,
    ];
    public function setUp(): void
    {
        parent::setUp();

        $this->forum_repo = app(ForumRepository::class);
        $this->user = new User([
            'id' => 1,
            'name' => 'TestName',
            'email' => 'test@test.com',
            'password' => '123456789',
            'is_admin' => 0
        ]);
        $this->admin_user = new User([
            'id' => 2,
            'name' => 'TestName',
            'email' => 'admin@test.com',
            'password' => '123456789',
            'is_admin' => 1
        ]);
        // $this->artisan('db:seed');

        
    }

    public function test_forum_create_as_admin()
    {
        $response = $this->be($this->admin_user)->post($this->base_path . 'forums', array_merge($this->sample_data, ['is_approved' => 1]))->assertStatus(201)
        ->assertJson(fn (AssertableJson $json) =>
            $json->where('data.is_approved', 1)
        );
    }

    public function test_forum_create_as_user()
    {
        $response = $this->be($this->user)->post($this->base_path . 'forums', $this->sample_data)->assertStatus(201)
        ->assertJson(fn (AssertableJson $json) =>
            $json->where('data.is_approved', 0)
        );
    }

    public function test_forum_approved_list()
    {
        Forum::factory()->count(3)->create();
        Forum::factory()->count(3)->approved()->create();
        $response = $this->be($this->user)->get($this->base_path . 'forums')->assertStatus(200)->assertJson(fn (AssertableJson $json) =>
            $json->has('data', 3)
        );
    }

    public function test_forum_unapproved_list()
    {
        Forum::factory()->count(3)->create();
        Forum::factory()->count(3)->approved()->create();
        $response = $this->be($this->admin_user)->get($this->base_path . 'forums/pending')->assertStatus(200)->assertJson(fn (AssertableJson $json) =>
            $json->has('data', 3)
        );
    }

    public function test_forum_unapproved_list_access_limit_to_admin()
    {
        $response = $this->be($this->user)->get($this->base_path . 'forums/pending')->assertStatus(422);
    }

    public function test_approval_api_with_admin()
    {       
        Forum::create(array_merge($this->sample_data, ['id' => 1]));
        $response = $this->be($this->admin_user)->put($this->base_path . 'forums/1', ['is_approved' => 1])->assertStatus(200)->assertJson(['data' => ['is_approved' => 1]]);
    }
    public function test_approval_api_access_with_user()
    {
        Forum::factory(['id' =>1])->make();
        User::factory(['id' =>2])->make();
        $response = $this->be($this->user)->put($this->base_path . 'forums/1', ['is_approved' => 1])->assertStatus(422);
    }
}
