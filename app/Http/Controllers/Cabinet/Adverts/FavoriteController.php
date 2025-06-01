<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Http\Services\Adverts\FavoriteService;
use App\Models\Adverts\Advert;
use Illuminate\Support\Facades\Auth;
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
        $favoriteAdverts = Advert::favoriteByUser(Auth::user())
            ->with(['firstPhoto'])
            ->orderByDesc('id')->paginate(10);

        return Inertia::render('Account/Favorites/Index', [
            'favoriteAdverts' => $favoriteAdverts,
        ]);
    }

    public function add(Advert $advert)
    {
        try {
            $this->favoriteService->add(auth()->id(), $advert->id);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', __('adverts.advert_add_to_favorite', ['advert' => $advert->title]));
    }

    public function remove(Advert $advert)
    {
        try {
            $this->favoriteService->remove(auth()->id(), $advert->id);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', __('adverts.advert_remove_from_favorite', ['advert' => $advert->title]));
    }
}
