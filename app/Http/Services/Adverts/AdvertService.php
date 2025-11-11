<?php

namespace App\Http\Services\Adverts;

use App\Events\Advert\AdvertChanged;
use App\Events\Advert\ModerationPassed;
use App\Filters\AdvertFilter;
use App\Http\Requests\Admin\Adverts\RejectRequest;
use App\Models\Adverts\Advert;
use App\Notifications\Advert\ModerationRejectNotification;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class AdvertService
{
    public function moderate(Advert $advert): void
    {
        if (! $advert->isActive()) {
            $advert->moderate(Carbon::now());
        }

        event(new ModerationPassed($advert));
        event(new AdvertChanged($advert));
    }

    public function reject(Advert $advert, RejectRequest $request): void
    {
        $advert->reject($request['reject_reason']);

        $advert->user->notify(new ModerationRejectNotification($advert, $request['reject_reason']));
    }

    public function getFilteredPaginatedAdverts(array $params): LengthAwarePaginator
    {
        $filter = new AdvertFilter($params);

        return Advert::query()
            ->with('region', 'category', 'user')
            ->withTrashed()
            ->filter($filter)
            ->orderBy($params['sort_by'], $params['sort_order'])
            ->paginate($params['per_page']);
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
