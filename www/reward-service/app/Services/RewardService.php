<?php

namespace App\Services;

use App\Models\Reward;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RewardService
{
    public function all(): Collection
    {
        return Reward::query()
            ->latest()
            ->get();
    }

    public function getByUserId(int $userId): Collection
    {
        return Reward::query()
            ->leftJoin('reward_user', 'rewards.id', '=', 'reward_user.reward_id')
            ->where('reward_user.user_id', $userId)
            ->get();

    }

    public function create(array $attributes): Reward
    {
        $reward = Reward::query()
            ->create($attributes);

        Cache::clear();

        return $reward;

    }

    public function update(Reward $reward, array $attributes): Reward
    {
        $reward->update($attributes);

        Cache::clear();

        return $reward;
    }

    public function delete(Reward $reward): void
    {
        $reward->delete();
        Cache::clear();
    }

    public function attachRewardToUser(Reward $reward, int $userId): void
    {
        DB::table('reward_user')
            ->insert([
                'user_id' => $userId,
                'reward_id' => $reward->id
            ]);
    }
}
