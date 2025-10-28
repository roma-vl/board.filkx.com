<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Http\Controllers\Controller;
use App\Http\Dto\Cabinet\Adverts\AdvertDto;
use App\Http\Requests\Cabinet\Adverts\CreateRequest;
use App\Http\Requests\Cabinet\Adverts\UpdateRequest;
use App\Http\Services\Adverts\AdvertService;
use App\Http\Services\Adverts\CategoryService;
use App\Http\Services\Adverts\LocationService;
use App\Models\Adverts\Advert;
use App\Models\Adverts\Category;
use DomainException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class AdvertController extends Controller
{
    public function __construct(
        private readonly LocationService $locationService,
        private readonly CategoryService $categoryService,
        private readonly AdvertService $advertService
    ) {}

    public function index(): Response
    {
        $query = Advert::forUser(auth()->user())
            ->with(['firstPhoto', 'statusRelation']);

        $adverts = $query->orderByDesc('id')->paginate(10);

        $adverts->setCollection(
            $adverts->getCollection()->map(fn(Advert $advert) => AdvertDto::fromModel($advert))
        );

        return Inertia::render('Account/Advert/Index', [
            'adverts' => $adverts,
        ]);
    }

    public function create(): Response
    {
        $categories = $this->categoryService->getCategories();

        return Inertia::render('Account/Advert/Create', [
            'categories' => $categories,
        ]);
    }

    public function edit(Advert $advert): Response
    {
        if (! Gate::any(['manage.own.advert', 'admin'], $advert)) {
            abort(403);
        }

        $activeAttributes = [];
        $categories = $this->categoryService->getCategories();
        $active = $advert->values()->get();
        foreach ($active as $activeAttribute) {
            $activeAttributes[$activeAttribute->attribute_id] = $activeAttribute->value;
        }
        $advert->region = $advert->region()->get();
        $advert->images = $advert->photo()->get();
        $category = Category::findOrFail($advert->category_id);
        $attributes = array_merge($category->getParentAttributes()->toArray(),
            $category->attributes()->orderBy('sort')->get()->toArray());

        return Inertia::render('Account/Advert/Edit', [
            'advert' => $advert,
            'categories' => $categories,
            'attributes' => $attributes,
            'activeAttributes' => $activeAttributes,
        ]);

    }

    public function update(UpdateRequest $request, Advert $advert): RedirectResponse|JsonResponse
    {
        if (! Gate::any(['manage.own.advert', 'admin'], $advert)) {
            abort(403);
        }
        try {
            $this->advertService->update($request, $advert);
        } catch (DomainException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return redirect()->route('account.adverts.index')->with('success', __('adverts.advert_update'));
    }

    public function publish(Advert $advert): RedirectResponse
    {
        if (! Gate::any(['manage.own.advert', 'admin'], $advert)) {
            abort(403);
        }

        $advert->sendToModeration();

        return back()->with('success', __('adverts.advert_send_for_moderation'));
    }

    public function draft(Advert $advert): RedirectResponse
    {
        if (! Gate::any(['manage.own.advert', 'admin'], $advert)) {
            abort(403);
        }

        $advert->backToDraft();

        return back()->with('success', __('adverts.advert_back_to_draft'));
    }

    public function store(CreateRequest $request): RedirectResponse|JsonResponse
    {
        try {
            $this->advertService->create(Auth::id(), $request);
        } catch (DomainException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return redirect()->route('account.adverts.index')->with('success', __('adverts.advert_create'));
    }

    public function photos(Advert $advert): Response
    {
        return Inertia::render('Account/Advert/Photos', [
            'advert' => $advert,
        ]);
    }

    public function destroy(Advert $advert): RedirectResponse
    {
        if (! Gate::any(['manage.own.advert', 'admin'], $advert)) {
            abort(403);
        }

        $advert->delete();

        return redirect()->route('account.adverts.index')->with('danger', __('adverts.advert_delete'));
    }

    public function getAreas(int $regionId): JsonResponse
    {
        return response()->json($this->locationService->getAreas($regionId));
    }

    public function getVillages(int $areaId): JsonResponse
    {
        return response()->json($this->locationService->getVillages($areaId));
    }

    public function getAttributes(int $categoryId): JsonResponse
    {
        $category = Category::findOrFail($categoryId);
        $parentAttributes = $category->getParentAttributes()->toArray();
        $attributes = $category->attributes()->orderBy('sort')->get()->toArray();

        return response()->json(array_merge($parentAttributes, $attributes));
    }
}
