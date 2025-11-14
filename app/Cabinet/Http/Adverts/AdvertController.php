<?php

namespace App\Cabinet\Http\Adverts;

use App\Cabinet\Service\AdvertService;
use App\Enum\PermissionEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\Adverts\UpdateRequest;
use App\Http\Services\Adverts\CategoryService;
use App\Models\Adverts\Advert;
use DomainException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
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

        return Inertia::render('Cabinet/Advert/Index', [
            'adverts' => $advertsList,
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('check-permission', [[PermissionEnum::MANAGE_OWN_ADVERTS->value]]);

        $categories = $this->categoryService->getCategories();

        return Inertia::render('Cabinet/Advert/Create', [
            'categories' => $categories,
        ]);
    }

    public function edit(Advert $advert): Response
    {
        Gate::authorize('check-permission', [[PermissionEnum::MANAGE_OWN_ADVERTS->value]]);

        $categories = $this->categoryService->getCategories();
        $data = $this->advertService->getEditData($advert);

        return Inertia::render('Cabinet/Advert/Edit', [
            ...$data,
            'categories' => $categories,
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
        $this->advertService->publish($advert);

        return back()->with('success', __('adverts.advert_send_for_moderation'));
    }

    public function draft(Advert $advert): RedirectResponse
    {
        Gate::authorize('check-permission', [[PermissionEnum::MANAGE_OWN_ADVERTS->value]]);
        $this->advertService->toDraft($advert);

        return back()->with('success', __('adverts.advert_back_to_draft'));
    }

    public function destroy(Advert $advert): RedirectResponse
    {
        Gate::authorize('check-permission', [[PermissionEnum::MANAGE_OWN_ADVERTS->value]]);
        $this->advertService->remove($advert);

        return redirect()
            ->route('account.adverts.index')
            ->with('danger', __('adverts.advert_delete'));
    }

    public function close(Advert $advert): RedirectResponse
    {
        Gate::authorize('check-permission', [[PermissionEnum::MANAGE_OWN_ADVERTS->value]]);
        $this->advertService->close($advert);

        return redirect()
            ->route('account.adverts.index')
            ->with('danger', __('adverts.advert_delete'));
    }

    public function getAttributes(int $categoryId): JsonResponse
    {
        Gate::authorize('check-permission', [[PermissionEnum::MANAGE_OWN_ADVERTS->value]]);

        return response()->json(
            $this->categoryService->getAllAttributes($categoryId)
        );
    }
}
