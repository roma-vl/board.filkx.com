<?php

namespace App\Enum;

enum AdvertServiceType: string
{
    case HIGHLIGHT = 'highlight';
    case URGENT = 'urgent';
    case PIN = 'pin';
    case PREMIUM = 'premium';
    case TURBO7 = 'turbo7';
    case TURBO30 = 'turbo30';
    case MAXIMAL = 'maximal';

    public function durationDays(): int
    {
        return match ($this) {
            self::HIGHLIGHT, self::URGENT, self::PIN, self::PREMIUM, self::TURBO7 => 7,
            self::TURBO30, self::MAXIMAL => 30,
        };
    }

    public static function fromString(string $value): self
    {
        return self::tryFrom($value) ?? throw new \InvalidArgumentException("Invalid service type: $value");
    }
}
