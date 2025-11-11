<?php

namespace App\Cabinet\Dto;

use App\Models\Adverts\Advert;
use Carbon\Carbon;

final class AdvertListingDto
{
    public function __construct(
        public int $id,
        public string $title,
        public ?float $price,
        public ?string $city,
        public ?string $firstPhoto,
        public bool $isFavorited,
        public int $favoritesCount,
        public bool $isNew,
        public bool $isPromo,
        public bool $premium,
        public ?\DateTime $createdAt,
        public ?\DateTime $expiresAt,
        public ?\DateTime $deletedAt,
    ) {}

    public static function fromModel(Advert $advert): self
    {
        $now = Carbon::now();

        return new self(
            id: $advert->id,
            title: $advert->title,
            price: $advert->price,
            city: $advert->address,
            firstPhoto: $advert->firstPhoto?->file,
            isFavorited: $advert->is_favorited,
            favoritesCount: $advert->favorites->count(),
            isNew: $advert->created_at->greaterThan($now->subDays(7)),
            isPromo: $advert->expires_at?->isToday() ?? false,
            premium: $advert->premium,
            createdAt: $advert->created_at,
            expiresAt: $advert->expires_at,
            deletedAt: $advert->deleted_at,
        );
    }
}
