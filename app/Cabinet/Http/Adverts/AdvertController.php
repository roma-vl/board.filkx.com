<?php

namespace App\Cabinet\Http\Adverts;

use App\Cabinet\Service\AdvertService;
use App\Enum\PermissionEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\Adverts\CreateRequest;
use App\Http\Requests\Cabinet\Adverts\UpdateRequest;
use App\Http\Services\Adverts\CategoryService;
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
        private readonly CategoryService $categoryService,
        private readonly AdvertService $advertService
    ) {}

    public function index(): Response
    {
        Gate::authorize('check-permission', [[PermissionEnum::VIEW_OWN_ADVERTS->value]]);
        $advertsList = $this->advertService->advertsList();

        return Inertia::render('Account/Advert/Index', [
            'adverts' => $advertsList,
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('check-permission', [[PermissionEnum::MANAGE_OWN_ADVERTS->value]]);

        $categories = $this->categoryService->getCategories();

        return Inertia::render('Account/Advert/Create', [
            'categories' => $categories,
        ]);
    }

    public function edit(Advert $advert): Response
    {
        Gate::authorize('check-permission', [[PermissionEnum::MANAGE_OWN_ADVERTS->value]]);

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
        Gate::authorize('check-permission', [[PermissionEnum::MANAGE_OWN_ADVERTS->value]]);

        try {
            $this->advertService->update($request, $advert);
        } catch (DomainException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return redirect()->route('account.adverts.index')->with('success', __('adverts.advert_update'));
    }

    public function publish(Advert $advert): RedirectResponse
    {
        Gate::authorize('check-permission', [[PermissionEnum::MANAGE_OWN_ADVERTS->value]]);

        $advert->sendToModeration();

        return back()->with('success', __('adverts.advert_send_for_moderation'));
    }

    public function draft(Advert $advert): RedirectResponse
    {
        Gate::authorize('check-permission', [[PermissionEnum::MANAGE_OWN_ADVERTS->value]]);

        $advert->backToDraft();

        return back()->with('success', __('adverts.advert_back_to_draft'));
    }

    public function store(CreateRequest $request): RedirectResponse|JsonResponse
    {
        Gate::authorize('check-permission', [[PermissionEnum::MANAGE_OWN_ADVERTS->value]]);
        try {
            $this->advertService->create(Auth::id(), $request);
        } catch (DomainException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return redirect()->route('account.adverts.index')->with('success', __('adverts.advert_create'));
    }

    public function destroy(Advert $advert): RedirectResponse
    {
        Gate::authorize('check-permission', [[PermissionEnum::MANAGE_OWN_ADVERTS->value]]);

        $advert->delete();

        return redirect()->route('account.adverts.index')->with('danger', __('adverts.advert_delete'));
    }

    public function close(Advert $advert): RedirectResponse
    {
        Gate::authorize('check-permission', [[PermissionEnum::MANAGE_OWN_ADVERTS->value]]);

        $advert->close();

        return redirect()->route('account.adverts.index')->with('danger', __('adverts.advert_delete'));
    }

    public function getAttributes(int $categoryId): JsonResponse
    {
        Gate::authorize('check-permission', [[PermissionEnum::MANAGE_OWN_ADVERTS->value]]);

        $category = Category::findOrFail($categoryId);
        $parentAttributes = $category->getParentAttributes()->toArray();
        $attributes = $category->attributes()->orderBy('sort')->get()->toArray();

        return response()->json(array_merge($parentAttributes, $attributes));
    }
}
