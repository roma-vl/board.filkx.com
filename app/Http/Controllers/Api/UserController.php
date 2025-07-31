<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserFilterRequest;
use App\Http\Resources\Api\User\UserResource;
use App\Http\Services\Users\UserService;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
    ) {}

    public function index(UserFilterRequest $request)
    {
        $validated = $request->validatedWithDefaults();
        $users = $this->userService->getFilteredPaginatedUsers($validated);

        return response()->json(UserResource::collection($users));
    }
}
