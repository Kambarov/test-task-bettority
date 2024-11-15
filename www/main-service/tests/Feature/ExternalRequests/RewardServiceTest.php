<?php

namespace Tests\Feature\ExternalRequests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RewardServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_rewards_list()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/api/me');

        $response
            ->assertStatus(200)
            ->assertExactJsonStructure([
                'user',
                'rewards'
            ]);
    }
}
