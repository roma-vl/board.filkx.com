<?php

namespace App\Api\Cabinet;

use App\Cabinet\Service\FavoriteService;
use App\Http\Resources\Api\Advert\AdvertDetailResource;
use App\Models\Adverts\Advert;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Cabinet Favorites',
    description: 'Управління обраними оголошеннями в кабінеті користувача'
)]
readonly class FavoriteController
{
    public function __construct(private FavoriteService $service) {}

    #[OA\Get(
        path: '/cabinet/favorites',
        description: 'Повертає пагінований список оголошень, доданих до обраних поточним користувачем.',
        summary: 'Отримати список обраних оголошень',
        tags: ['Cabinet Favorites'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Список обраних оголошень',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/AdvertDetailResource')
                        ),
                        new OA\Property(property: 'links', ref: '#/components/schemas/PaginationLinks'),
                        new OA\Property(property: 'meta', ref: '#/components/schemas/PaginationMeta'),
                    ]
                )
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
        ]
    )]
    public function index(): AnonymousResourceCollection
    {
        $adverts = Advert::favoredByUser(Auth::user())->orderByDesc('id')->paginate(20);

        return AdvertDetailResource::collection($adverts);
    }

    #[OA\Delete(
        path: '/cabinet/favorites/{advert}',
        description: 'Видаляє вказане оголошення зі списку обраних для поточного користувача та перенаправляє назад.',
        summary: 'Видалити оголошення з обраних',
        tags: ['Cabinet Favorites'],
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
                response: 302,
                description: 'Перенаправлення на список обраних'
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
            new OA\Response(
                response: 422,
                description: 'Неможливо видалити з обраних',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Cannot remove from favorites.'),
                    ]
                )
            ),
        ]
    )]
    public function remove(Advert $advert): RedirectResponse
    {
        try {
            $this->service->remove(Auth::id(), $advert->id);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.favorites.index');
    }
}
