<?php

namespace App\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserFilterRequest;
use App\Http\Resources\Api\User\UserResource;
use App\Http\Services\Users\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
    ) {}

    public function index(UserFilterRequest $request): JsonResponse
    {
        $validated = $request->validatedWithDefaults();
        $users = $this->userService->getFilteredPaginatedUsers($validated);

        return response()->json(UserResource::collection($users));
    }
}
