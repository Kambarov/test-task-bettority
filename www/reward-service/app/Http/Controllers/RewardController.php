<?php

namespace App\Http\Controllers;


use App\Http\Requests\Rewards\RewardActionRequest;
use App\Http\Resources\Rewards\RewardResource;
use App\Models\Reward;
use App\Services\RewardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RewardController extends Controller
{
    public function __construct(public readonly RewardService $rewardService)
    {
    }

    public function index(): AnonymousResourceCollection
    {
        $rewards = Cache::remember('rewards_list', config('cache.default_ttl'), function () {
            return $this->rewardService->all();
        });

        return RewardResource::collection($rewards);
    }

    public function attachToUser(Reward $reward, int $userId): JsonResponse
    {
        DB::beginTransaction();

        try {
            $this->rewardService->attachRewardToUser(
                reward: $reward,
                userId: $userId
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Attached'
            ]);

        } catch (\Exception $exception) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }

    public function create(RewardActionRequest $request): JsonResponse|RewardResource
    {
        DB::beginTransaction();

        try {
            $reward = $this->rewardService->create(attributes: $request->validated());

            DB::commit();

            return new RewardResource($reward);
        } catch (\Exception $exception) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }

    public function update(Reward $reward, RewardActionRequest $request): JsonResponse|RewardResource
    {
        DB::beginTransaction();

        try {
            $reward = $this->rewardService->update(
                reward: $reward,
                attributes: $request->validated()
            );

            DB::commit();

            return new RewardResource($reward);
        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }

    public function getByUserId(int $userId): AnonymousResourceCollection
    {
        $rewards = $this->rewardService->getByUserId($userId);
        return RewardResource::collection($rewards);
    }

    public function delete(Reward $reward)
    {
        DB::beginTransaction();

        try {
            $this->rewardService->delete($reward);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Reward deleted'
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }
}
