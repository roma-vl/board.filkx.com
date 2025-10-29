<?php

namespace App\Http\Dto\Cabinet\Adverts;

use App\Models\Adverts\Advert;

readonly class AdvertDto
{
    public function __construct(
        public int $id,
        public string $title,
        public ?string $content,
        public ?int $price,
        public ?string $address,
        public ?AdvertStatusDto $status,
        public ?string $firstPhoto,
        public string $createdAt,
    ) {}

    public static function fromModel(Advert $advert): self
    {
        $statusDto = $advert->statusRelation ? AdvertStatusDto::fromModel($advert->statusRelation) : null;

        return new self(
            id: $advert->id,
            title: $advert->title,
            content: $advert->content,
            price: $advert->price,
            address: $advert->address,
            status: $statusDto,
            firstPhoto: $advert->firstPhoto?->file,
            createdAt: $advert->created_at->toIso8601String(),
        );
    }
}
