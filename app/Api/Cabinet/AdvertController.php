<?php

namespace App\Api\Cabinet;

use App\Http\Requests\Cabinet\Adverts\AttributesRequest;
use App\Http\Requests\Cabinet\Adverts\CreateRequest;
use App\Http\Requests\Cabinet\Adverts\EditRequest;
use App\Http\Requests\Cabinet\Adverts\PhotosRequest;
use App\Http\Resources\Api\Advert\AdvertDetailResource;
use App\Http\Resources\Api\Advert\AdvertListResource;
use App\Http\Services\Adverts\AdvertService;
use App\Models\Adverts\Advert;
use Auth;
use Gate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[OA\Tag(
    name: 'Cabinet Adverts',
    description: 'Управління оголошеннями в кабінеті користувача'
)]
readonly class AdvertController
{
    public function __construct(private AdvertService $advertService) {}

    #[OA\Get(
        path: '/cabinet/adverts',
        description: 'Повертає пагінований список оголошень, створених поточним користувачем.',
        summary: 'Отримати список оголошень користувача',
        tags: ['Cabinet Adverts'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Список оголошень',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/AdvertListResource')
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
        $adverts = Advert::forUser(Auth::user())->orderByDesc('id')->paginate(20);

        return AdvertListResource::collection($adverts);
    }

    #[OA\Get(
        path: '/cabinet/adverts/{advert}',
        description: 'Повертає детальну інформацію про конкретне оголошення користувача.',
        summary: 'Отримати деталі оголошення',
        tags: ['Cabinet Adverts'],
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
                response: 200,
                description: 'Деталі оголошення',
                content: new OA\JsonContent(ref: '#/components/schemas/AdvertDetailResource')
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
                response: 403,
                description: 'Доступ заборонено',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'This action is unauthorized.'),
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
        ]
    )]
    public function show(Advert $advert): AdvertDetailResource
    {
        $this->checkAccess($advert);

        return new AdvertDetailResource($advert);
    }

    #[OA\Post(
        path: '/cabinet/adverts',
        description: 'Створює нове оголошення для поточного користувача.',
        summary: 'Створити нове оголошення',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/CreateRequest')
        ),
        tags: ['Cabinet Adverts'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Оголошення створено',
                content: new OA\JsonContent(ref: '#/components/schemas/AdvertDetailResource')
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
                response: 422,
                description: 'Помилка валідації',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
                        new OA\Property(property: 'errors', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function store(CreateRequest $request): JsonResponse
    {
        $advert = $this->advertService->create(
            Auth::id(),
            $request
        );

        return (new AdvertDetailResource($advert))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    #[OA\Put(
        path: '/cabinet/adverts/{advert}',
        description: 'Оновлює існуюче оголошення користувача.',
        summary: 'Оновити оголошення',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/EditRequest')
        ),
        tags: ['Cabinet Adverts'],
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
                response: 200,
                description: 'Оголошення оновлено',
                content: new OA\JsonContent(ref: '#/components/schemas/AdvertDetailResource')
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
                response: 403,
                description: 'Доступ заборонено',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'This action is unauthorized.'),
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
                description: 'Помилка валідації',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
                        new OA\Property(property: 'errors', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function update(EditRequest $request, Advert $advert): AdvertDetailResource
    {
        $this->checkAccess($advert);
        $this->advertService->edit($advert->id, $request);

        return new AdvertDetailResource(Advert::findOrFail($advert->id));
    }

    #[OA\Put(
        path: '/cabinet/adverts/{advert}/attributes',
        description: 'Оновлює атрибути існуючого оголошення користувача.',
        summary: 'Оновити атрибути оголошення',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/AttributesRequest')
        ),
        tags: ['Cabinet Adverts'],
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
                response: 200,
                description: 'Атрибути оголошення оновлено',
                content: new OA\JsonContent(ref: '#/components/schemas/AdvertDetailResource')
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
                response: 403,
                description: 'Доступ заборонено',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'This action is unauthorized.'),
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
                description: 'Помилка валідації',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
                        new OA\Property(property: 'errors', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function attributes(AttributesRequest $request, Advert $advert): AdvertDetailResource
    {
        $this->checkAccess($advert);
        $this->advertService->editAttributes($advert->id, $request);

        return new AdvertDetailResource(Advert::findOrFail($advert->id));
    }

    #[OA\Put(
        path: '/cabinet/adverts/{advert}/photos',
        description: 'Додає фото до існуючого оголошення користувача.',
        summary: 'Додати фото до оголошення',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/PhotosRequest')
        ),
        tags: ['Cabinet Adverts'],
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
                response: 200,
                description: 'Фото додано до оголошення',
                content: new OA\JsonContent(ref: '#/components/schemas/AdvertDetailResource')
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
                response: 403,
                description: 'Доступ заборонено',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'This action is unauthorized.'),
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
                description: 'Помилка валідації',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
                        new OA\Property(property: 'errors', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function photos(PhotosRequest $request, Advert $advert): AdvertDetailResource
    {
        $this->checkAccess($advert);
        $this->advertService->addPhoto($advert->id, $request);

        return new AdvertDetailResource(Advert::findOrFail($advert->id));
    }

    #[OA\Post(
        path: '/cabinet/adverts/{advert}/send',
        description: 'Надсилає оголошення на перевірку модератору.',
        summary: 'Надіслати оголошення на модерацію',
        tags: ['Cabinet Adverts'],
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
                response: 200,
                description: 'Оголошення надіслано на модерацію',
                content: new OA\JsonContent(ref: '#/components/schemas/AdvertDetailResource')
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
                response: 403,
                description: 'Доступ заборонено',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'This action is unauthorized.'),
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
                description: 'Неможливо надіслати на модерацію',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Cannot send to moderation.'),
                    ]
                )
            ),
        ]
    )]
    public function send(Advert $advert): AdvertDetailResource
    {
        $this->checkAccess($advert);
        $this->advertService->sendToModeration($advert->id);

        return new AdvertDetailResource(Advert::findOrFail($advert->id));
    }

    #[OA\Post(
        path: '/cabinet/adverts/{advert}/close',
        description: 'Закриває оголошення, воно більше не буде активним.',
        summary: 'Закрити оголошення',
        tags: ['Cabinet Adverts'],
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
                response: 200,
                description: 'Оголошення закрито',
                content: new OA\JsonContent(ref: '#/components/schemas/AdvertDetailResource')
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
                response: 403,
                description: 'Доступ заборонено',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'This action is unauthorized.'),
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
                description: 'Неможливо закрити оголошення',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Cannot close advert.'),
                    ]
                )
            ),
        ]
    )]
    public function close(Advert $advert): AdvertDetailResource
    {
        $this->checkAccess($advert);
        $this->advertService->close($advert->id);

        return new AdvertDetailResource(Advert::findOrFail($advert->id));
    }

    #[OA\Delete(
        path: '/cabinet/adverts/{advert}',
        description: 'Видаляє оголошення користувача.',
        summary: 'Видалити оголошення',
        tags: ['Cabinet Adverts'],
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
                description: 'Оголошення видалено'
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
                response: 403,
                description: 'Доступ заборонено',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'This action is unauthorized.'),
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
        ]
    )]
    public function destroy(Advert $advert): JsonResponse
    {
        $this->checkAccess($advert);
        $this->advertService->remove($advert->id);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    private function checkAccess(Advert $advert): void
    {
        if (! Gate::allows('manage-own-advert', $advert)) {
            abort(403);
        }
    }
}
