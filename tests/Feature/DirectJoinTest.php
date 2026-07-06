<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\AccessCode;
use App\Models\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DirectJoinTest extends TestCase
{
    use RefreshDatabase;

    public function test_direct_join_invalid_code_redirects_home_with_error(): void
    {
        $response = $this->get('/code=INVALID');

        $response->assertRedirect(route('home'));
        $response->assertSessionHasErrors('access_code');
    }

    public function test_direct_join_valid_resource_code_redirects_to_resource(): void
    {
        $code = AccessCode::create([
            'code' => 'RESC12',
            'type' => 'resource',
            'resource_url' => 'https://example.com/some-resource',
            'expires_at' => null,
            'rules' => [],
        ]);

        $response = $this->get('/code=RESC12');

        $response->assertRedirect('https://example.com/some-resource');
    }

    public function test_direct_join_valid_test_code_redirects_to_test_join(): void
    {
        $test = Test::create([
            'name' => 'Demo Test',
            'description' => 'Demo Test Description',
            'duration_minutes' => 45,
            'question_ids' => [1, 2, 3],
            'is_active' => true,
        ]);

        $code = AccessCode::create([
            'code' => 'TEST12',
            'type' => 'testing',
            'test_id' => $test->id,
            'expires_at' => null,
            'rules' => [],
        ]);

        $response = $this->get('/code=TEST12');

        $response->assertRedirect(route('test.join', ['code' => 'TEST12']));
    }

    public function test_homepage_with_code_query_parameter_handles_direct_join(): void
    {
        $test = Test::create([
            'name' => 'Demo Test',
            'description' => 'Demo Test Description',
            'duration_minutes' => 45,
            'question_ids' => [1, 2, 3],
            'is_active' => true,
        ]);

        $code = AccessCode::create([
            'code' => 'TEST12',
            'type' => 'testing',
            'test_id' => $test->id,
            'expires_at' => null,
            'rules' => [],
        ]);

        $response = $this->get('/?code=TEST12');

        $response->assertRedirect(route('test.join', ['code' => 'TEST12']));
    }
}
