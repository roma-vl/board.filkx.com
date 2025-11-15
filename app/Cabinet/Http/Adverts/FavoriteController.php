<?php

namespace App\Cabinet\Http\Adverts;

use App\Cabinet\Service\FavoriteService;
use App\Enum\PermissionEnum;
use App\Models\Adverts\Advert;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class FavoriteController
{
    private FavoriteService $favoriteService;

    public function __construct(FavoriteService $favoriteService)
    {
        $this->favoriteService = $favoriteService;
    }

    public function index(): Response
    {
        Gate::authorize('check-permission', [[PermissionEnum::VIEW_OWN_FAVORITE->value]]);

        $favoriteAdverts = $this->favoriteService->favoritesList();

        return Inertia::render('Cabinet/Favorites/Index', [
            'favoriteAdverts' => $favoriteAdverts,
        ]);
    }

    public function add(Advert $advert)
    {
        Gate::authorize('check-permission', [[PermissionEnum::MANAGE_OWN_FAVORITE->value]]);

        try {
            $this->favoriteService->add(auth()->id(), $advert->id);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', __('adverts.advert_add_to_favorite', ['advert' => $advert->title]));
    }

    public function remove(Advert $advert)
    {
        Gate::authorize('check-permission', [[PermissionEnum::MANAGE_OWN_FAVORITE->value]]);

        try {
            $this->favoriteService->remove(auth()->id(), $advert->id);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', __('adverts.advert_remove_from_favorite', ['advert' => $advert->title]));
    }
}
