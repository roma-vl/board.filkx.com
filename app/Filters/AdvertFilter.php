<?php

namespace App\Filters;

class AdvertFilter extends QueryFilter
{
    public function name(string $value): void
    {
        $this->builder->where('title', 'like', "%{$value}%");
    }

    public function search(string $value): void
    {
        $this->builder->where('title', 'like', "%{$value}%")
            ->orWhere('content', 'like', "%{$value}%");
    }

    public function email(string $value): void
    {
        $this->builder->where('email', 'like', "%{$value}%");
    }

    public function status(string $value): void
    {
        $this->builder->where('status', 'like', "%{$value}%");
    }

    public function price(string $value): void
    {
        $this->builder->where('price', 'like', "%{$value}%");
    }
}
