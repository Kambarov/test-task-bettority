<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Services\RewardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function __construct(public readonly RewardService $rewardService) {}

    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->validated())) {
            /** @var User $user */
            $user = Auth::user();
            $user->tokens()->delete();

            $token = $user->createToken('Personal Access Token')->plainTextToken;

            return response()->json([
                'success' => true,
                'token' => $token,
                'user' => $user,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Email or Password mismatch'
        ], 401);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    public function getMe(Request $request): JsonResponse
    {
        try {
            $rewards = $this->rewardService
                ->getUserRewards(userId: $request->user()->id);

            return response()->json([
                [
                    'user' => $request->user(),
                    'rewards' => $rewards
                ]
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }

}
