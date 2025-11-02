<?php

namespace App\Cabinet\Dto;

readonly class AdvertStatusDto
{
    public function __construct(
        public string $name,
        public string $color,
        public int $state
    ) {}

    public static function fromModel($status): self
    {
        $name = is_array($status->name) ? ($status->name[app()->getLocale()] ?? 'unknown') : 'unknown';

        return new self(
            name: $name,
            color: $status->color ?? '#ccc',
            state: $status->state,
        );
    }
}
