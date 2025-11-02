<?php

namespace App\Cabinet\Service;

use App\Http\Dto\Cabinet\Adverts\AdvertDto;
use App\Http\Requests\Cabinet\Adverts\AttributesRequest;
use App\Http\Requests\Cabinet\Adverts\CreateRequest;
use App\Http\Requests\Cabinet\Adverts\EditRequest;
use App\Http\Requests\Cabinet\Adverts\PhotosRequest;
use App\Http\Requests\Cabinet\Adverts\UpdateRequest;
use App\Models\Adverts\Advert;
use App\Models\Adverts\AdvertStatus;
use App\Models\Adverts\Category;
use App\Models\Adverts\Location;
use App\Models\Users\User;
use App\States\Adverts\Draft;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AdvertService
{
    const int PER_PAGE = 10;

    public function advertsList(): LengthAwarePaginator
    {
        $query = Advert::forUser(auth()->user())
            ->with(['firstPhoto', 'statusRelation']);

        $adverts = $query->orderByDesc('id')->paginate(self::PER_PAGE);

        $adverts->setCollection(
            $adverts->getCollection()->map(fn (Advert $advert) => AdvertDto::fromModel($advert))
        );

        return $adverts;
    }

    public function create($userId, CreateRequest $request): Advert
    {
        $categoryId = $request->input('category_id');
        $regionId = $request->input('region_id');

        $user = User::findOrFail($userId);
        $category = Category::findOrFail($categoryId);
        $region = $regionId ? Location::findOrFail($regionId) : null;

        return DB::transaction(function () use ($user, $category, $region, $request) {
            $draftStatus = AdvertStatus::where('state', Draft::$state)->firstOrFail();

            $advert = Advert::make([
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'price' => $request->input('price'),
                'address' => $request->input('address'),
                'status_id' => $draftStatus->id,
                'status' => Advert::STATUS_DRAFT,
            ]);

            $advert->user()->associate($user);
            $advert->category()->associate($category);
            $advert->region()->associate($region);
            $advert->saveOrFail();

            foreach ($category->allArrayAttributes() as $attribute) {
                $value = $request['attributes'][$attribute['id']] ?? null;
                if ($value !== null) {
                    $advert->value()->create([
                        'attribute_id' => $attribute['id'],
                        'value' => $value,
                    ]);
                }
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $advert->photo()->create([
                        'file' => $image->store('adverts/'.$advert->id, 'public'),
                    ]);
                }
            }

            return $advert;
        });
    }

    public function edit($id, EditRequest $request): void
    {
        $advert = $this->getAdvert($id);
        $oldPrice = $advert->price;
        $advert->update($request->only([
            'title',
            'content',
            'price',
            'address',
        ]));

        //        if ($oldPrice != $advert->price) {
        //            foreach ($advert->favorites()->create() as $user) {
        //                \Mail::to($user->email)->queue(new AdvertPriceChanged($user, $advert, $oldPrice));
        //            }
        //        }
    }

    public function update(UpdateRequest $request, Advert $advert): void
    {
        DB::transaction(function () use ($advert, $request) {
            $advert->update($request->only([
                'title',
                'content',
                'price',
                'address',
                'category_id',
                'region_id',
            ]));

            $advert->value()->delete();

            foreach ($advert->category->allArrayAttributes() as $attribute) {
                $value = $request['attributes'][$attribute['id']] ?? null;
                if ($value !== null) {
                    $advert->value()->create([
                        'attribute_id' => $attribute['id'],
                        'value' => $value,
                    ]);
                }
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $advert->photo()->create([
                        'file' => $image->store('adverts/'.$advert->id, 'public'),
                    ]);
                }
            }
        });
    }

    public function editAttributes($id, AttributesRequest $request): void
    {
        $advert = $this->getAdvert($id);

        DB::transaction(function () use ($request, $advert) {
            foreach ($advert->value as $value) {
                $value->delete();
            }

            foreach ($advert->category->allArrayAttributes() as $attribute) {
                $value = $request['attributes'][$attribute['id']] ?? null;
                if ($value !== null) {
                    $advert->value()->create([
                        'attribute_id' => $attribute['id'],
                        'value' => $value,
                    ]);
                }
            }
            $advert->update();
        });
    }

    public function addPhoto($id, PhotosRequest $photosRequest): void
    {
        $advert = $this->getAdvert($id);

        DB::transaction(function () use ($photosRequest, $advert) {
            foreach ($photosRequest->file('photos') as $photo) {
                $advert->photo()->create([
                    'file' => $photo->store('adverts/'.$advert->id, 'public'),
                ]);
            }
        });
    }

    public function close($id): void
    {
        $advert = $this->getAdvert($id);
        $advert->close();
    }

    public function remove($id): void
    {
        $advert = $this->getAdvert($id);
        $advert->delete();
    }

    public function getAdvert($id): Advert
    {
        return Advert::findOrFail($id);
    }

    public function getLatest(): Collection
    {
        return Advert::query()
            ->where('status', 'active')
            ->with(['firstPhoto', 'favorites'])
            ->latest()
            ->take(4)
            ->get()
            ->map(fn ($advert) => $this->toListingResource($advert));
    }

    public function getVip(): Collection
    {
        return Advert::query()
            ->where('premium', 1)
            ->with(['firstPhoto', 'favorites'])
            ->inRandomOrder()
            ->take(4)
            ->get()
            ->map(fn ($advert) => $this->toListingResource($advert));
    }

    public function toListingResource(Advert $advert): array
    {
        $now = Carbon::now();

        return [
            'id' => $advert->id,
            'title' => $advert->title,
            'price' => $advert->price,
            'city' => $advert->city,
            'created_at' => $advert->created_at,
            'expires_at' => $advert->expires_at,
            'deleted_at' => $advert->deleted_at,
            'is_favorited' => $advert->is_favorited,
            'first_photo' => $advert->firstPhoto?->file,
            'favorites_count' => $advert->favorites->count(),
            'is_new' => $advert->created_at->greaterThan($now->subDays(7)),
            'is_promo' => $advert->expires_at?->isToday() ?? false,
            'premium' => $advert->premium,
        ];
    }
}
