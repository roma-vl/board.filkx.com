<?php

namespace App\Http\Resources\Admin\Advert;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdvertResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => $this->user,
            'category' => $this->category,
            'region' => $this->region,
            'title' => $this->title,
            'slug' => $this->slug,
            'price' => $this->price,
            'address' => $this->address,
            'content' => $this->content,
            'status' => $this->status,
            'reject_reason' => $this->reject_reason,
            'premium' => $this->premium,
            'published_at' => $this->published_at ? $this->published_at->diffForHumans() : null,
            'expires_at' => $this->expires_at ? $this->expires_at->diffForHumans() : null,
            'created_at' => $this->created_at ? $this->created_at->diffForHumans() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->diffForHumans() : null,
            'deleted_at' => $this->deleted_at ? $this->deleted_at->diffForHumans() : null,
        ];
    }
}
