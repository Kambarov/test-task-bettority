<?php

namespace Tests\Feature;

use App\Models\Reward;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CrudRewardTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_rewards_list()
    {
        Reward::factory()->createMany(10);

        $response = $this->get('/api/rewards');

        $response
            ->assertStatus(200)
            ->assertExactJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'amount'
                    ]
                ]
            ]);
    }

    public function test_get_rewards_list_by_user_id()
    {
        $reward = Reward::factory()->create();

        $this->post(sprintf('/api/rewards/%s/1/attach', $reward->id));

        $response = $this->get('/api/rewards/user/1');

        $response
            ->assertStatus(200)
            ->assertExactJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'amount'
                    ]
                ]
            ]);
    }

    public function test_create_reward()
    {
        /** @var Reward $reward */
        $reward = Reward::factory()->make();

        $this->postJson('/api/rewards', [
            'title' => $reward->title,
            'description' => $reward->description,
            'amount' => $reward->amount
        ]);

        $this->assertDatabaseHas('rewards', [
            'title' => $reward->title,
            'description' => $reward->description,
            'amount' => $reward->amount
        ]);
    }

    public function test_update_reward()
    {
        /** @var Reward $mockReward */
        $mockReward = Reward::factory()->make();

        /** @var Reward $reward */
        $reward = Reward::factory()->create();

        $this->putJson('/api/rewards/' . $reward->id . '/update', [
            'title' => $mockReward->title,
            'description' => $mockReward->description,
            'amount' => $mockReward->amount
        ])->assertStatus(200);

        $this->assertDatabaseHas('rewards', [
            'title' => $mockReward->title,
            'description' => $mockReward->description,
            'amount' => $mockReward->amount
        ]);
    }

    public function test_attach_reward_to_user()
    {
        /** @var Reward $reward */
        $reward = Reward::factory()->create();

        $response = $this->post(sprintf('api/rewards/%s/%s/attach', $reward->id, 2));

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'success' => true
            ]);
    }

    public function test_delete_reward()
    {
        /** @var Reward $reward */
        $reward = Reward::factory()->create();

        $this->deleteJson('/api/rewards/' . $reward->id . '/delete');

        $this->assertDatabaseHas('rewards', $reward->toArray());
    }
}
