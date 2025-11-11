<?php

namespace App\Cabinet\Service;

use App\Cabinet\Dto\AdvertDto;
use App\Models\Adverts\Advert;
use App\Models\Users\User;
use Illuminate\Pagination\LengthAwarePaginator;

class FavoriteService
{
    const int PER_PAGE = 10;

    public function favoritesList(): LengthAwarePaginator
    {
        $query = Advert::favoriteByUser(auth()->user())
            ->with(['firstPhoto', 'statusRelation']);

        $adverts = $query->orderByDesc('id')->paginate(self::PER_PAGE);

        $adverts->setCollection(
            $adverts->getCollection()->map(fn (Advert $advert) => AdvertDto::fromModel($advert))
        );

        return $adverts;
    }

    public function add(int $userId, int $advertId): void
    {
        $user = $this->getUser($userId);
        $advert = $this->getAdvert($advertId);

        $user->addToFavorites($advert->id);
    }

    public function remove(int $userId, int $advertId): void
    {
        $user = $this->getUser($userId);
        $advert = $this->getAdvert($advertId);

        $user->removeFromFavorites($advert->id);
    }

    private function getUser(int $userId)
    {
        return User::findOrFail($userId);
    }

    private function getAdvert(int $advertId)
    {
        return Advert::findOrFail($advertId);
    }
}
