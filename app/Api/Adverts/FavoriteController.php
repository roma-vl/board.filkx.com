<?php

namespace App\Api\Adverts;

use App\Cabinet\Service\FavoriteService;
use App\Models\Adverts\Advert;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[OA\Tag(name: 'Favorites', description: 'Управління обраними оголошеннями')]
readonly class FavoriteController
{
    public function __construct(
        private FavoriteService $favoriteService
    ) {}

    #[OA\Post(
        path: '/favorites/{advert}',
        description: 'Додає вказане оголошення до списку обраних для поточного користувача.',
        summary: 'Додати оголошення до обраних',
        tags: ['Favorites'],
        parameters: [
            new OA\Parameter(
                name: 'advert',
                description: 'ID оголошення',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 123)
            ),
        ],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Оголошення додано до обраних',
                content: new OA\JsonContent
            ),
            new OA\Response(
                response: 401,
                description: 'Неавторизований доступ',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Оголошення не знайдено',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Advert not found.'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Неможливо додати до обраних',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Cannot add to favorites.'),
                    ]
                )
            ),
        ]
    )]
    public function add(Advert $advert): JsonResponse
    {
        $this->favoriteService->add(Auth::id(), $advert->id);

        return response()->json([], Response::HTTP_CREATED);
    }

    #[OA\Delete(
        path: '/favorites/{advert}',
        description: 'Видаляє вказане оголошення зі списку обраних для поточного користувача.',
        summary: 'Видалити оголошення з обраних',
        tags: ['Favorites'],
        parameters: [
            new OA\Parameter(
                name: 'advert',
                description: 'ID оголошення',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 123)
            ),
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Оголошення видалено з обраних'
            ),
            new OA\Response(
                response: 401,
                description: 'Неавторизований доступ',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Оголошення не знайдено або не було в обраних',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Advert not found or not in favorites.'),
                    ]
                )
            ),
        ]
    )]
    public function remove(Advert $advert): JsonResponse
    {
        $this->favoriteService->remove(Auth::id(), $advert->id);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
