<?php

namespace App\Api\Auth;

use App\Api\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Services\Users\UserService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class RegisteredUserController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {}

    #[OA\Post(
        path: '/register',
        summary: 'Реєстрація нового користувача',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/RegisterRequest')
        ),
        tags: ['Auth'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Користувача створено. Перевірте емейл',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'string', example: 'перевірте емейл і підтвердіть пошту'),
                    ]
                )
            ),
        ]
    )]
    public function register(RegisterRequest $request): JsonResponse
    {
        $this->userService->createUser($request);

        return response()->json([
            'success' => 'перевірте емейл і підтвердіть пошту',
        ], ResponseAlias::HTTP_CREATED);
    }
}
