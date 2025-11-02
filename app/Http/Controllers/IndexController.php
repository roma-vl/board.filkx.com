<?php

namespace App\Http\Controllers;

use App\Cabinet\Service\AdvertService;
use App\Http\Requests\Adverts\SearchRequest;
use App\Http\Services\Adverts\CategoryService;
use App\Http\Services\Adverts\LocationService;
use App\Http\Services\Adverts\SearchService;
use App\Http\Services\Locale\LocaleService;
use App\Models\Adverts\Advert;
use App\Models\Adverts\Category;
use App\Models\Adverts\Location;
use App\Models\Users\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class IndexController extends Controller
{
    public function __construct(
        private readonly AdvertService $advertService,
        private readonly CategoryService $categoryService,
        private readonly SearchService $searchService,
        private readonly LocaleService $localeService,
        private readonly LocationService $locationService,
    ) {}

    public function changeLocale(string $locale): RedirectResponse
    {
        $this->localeService->changeLocale($locale);

        return redirect()->back();
    }

    public function index(): Response
    {
        return Inertia::render('Index/Index', [
            'categories' => $this->categoryService->getFirstLevelCategoriesWithChildren(),
            'news' => $this->advertService->getLatest(),
            'vip' => $this->advertService->getVip(),
        ]);
    }

    public function regions(): JsonResponse
    {
        $country = $this->locationService->getCountries()->first();
        $regions = $this->locationService->getRegions($country);

        return response()->json([
            'regions' => $regions,
        ]);
    }

    public function cities(Location $region): JsonResponse
    {
        $cities = $this->locationService->getCities($region);

        return response()->json([
            'cities' => $cities,
        ]);
    }

    public function search($region): JsonResponse
    {
        $regions = $this->locationService->search($region);

        return response()->json([
            'regions' => $regions,
        ]);

    }

    public function searchAdvert(SearchRequest $request, ?string $urlPath = ''): Response
    {
        $data = $this->categoryService->parseCategoryAndLocationFromUrl($urlPath);
        $pagination = $this->preparePaginationData($request);

        $results = $this->searchService->search(
            $data['categories']->last(),
            $data['locations']->last(),
            $request,
            $urlPath,
            $pagination['page'],
            $pagination['perPage']
        );

        $locations = $this->filterAvailableLocations($data['locations'], $results->regionsCounts);
        $categories = $this->filterAvailableCategories($data['categories'], $results->categoriesCounts);
        $childCategories = $this->filterAvailableCategories($data['childCategories'], $results->categoriesCounts)->values();

        $attributes = count($data['categories']) ? $data['categories']->last()->allArrayAttributes() : [];
        $categoryTree = $this->categoryService->getFirstLevelCategoriesWithChildren();
        $categoryFilters = $this->buildCategoryFilters($categoryTree);

        return Inertia::render('Search/List', [
            'adverts' => [
                'data' => $results->adverts->items(),
                'total' => $results->adverts->total(),
                'page' => $results->adverts->currentPage(),
                'per_page' => $results->adverts->perPage(),
                'last_page' => $results->adverts->lastPage(),
                'links' => $results->adverts->linkCollection(),
            ],
            'locations' => $locations,
            'categories' => $categories,
            'childCategories' => $childCategories,
            'attributes' => $attributes,
            'regionsCounts' => $results->regionsCounts,
            'activeRegion' => $data['locations']->last() ?: '',
            'activeCategory' => $data['categories']->last() ?: '',
            'categoriesCounts' => $results->categoriesCounts,
            'categoryFilters' => $categoryFilters,
            'query' => $request->query(),
        ]);
    }

    public function searchAdvertByUser(Request $request, User $user): Response
    {
        $pagination = $this->preparePaginationData2($request);

        $result = $this->searchService->searchByUser($user, $request, $pagination['page'], $pagination['perPage']);

        $categoryModels = Category::whereIn('id', array_keys($result->categoriesCounts))->get();

        return Inertia::render('Search/User', [
            'adverts' => [
                'data' => $result->adverts->items(),
                'total' => $result->adverts->total(),
                'page' => $result->adverts->currentPage(),
                'per_page' => $result->adverts->perPage(),
                'last_page' => $result->adverts->lastPage(),
                'links' => $result->adverts->linkCollection(),
            ],
            'user' => $user,
            'query' => $request->query(),
            'categoriesCounts' => $categoryModels->map(fn ($cat) => [
                'id' => $cat->id,
                'parent_id' => $cat->parent_id,
                'name' => $cat->name,
                'count' => $result->categoriesCounts[$cat->id] ?? 0,
            ])->values(),
        ]);
    }

    private function formatCategoryWithAttributes(Category $category): array
    {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'attributes' => $category->allArrayAttributes(), // або інший метод
            'children' => $category->children->map(fn ($child) => $this->formatCategoryWithAttributes($child))->values(),
        ];
    }

    public function show(Advert $advert)
    {
        $advert->load(['category.ancestors', 'region.ancestors', 'value.attribute',
            'photo', 'user', 'favorites']);
        $values = $advert->value->map(function ($value) {
            return [
                'attribute' => $value->attribute->name ?? null,
                'value' => $value->value,
            ];
        });

        $categories = $advert->category->ancestors
            ->filter(fn ($category) => $category->parent_id !== null)
            ->values()
            ->push($advert->category);

        $locations = $advert->region->ancestors
            ->filter(fn ($region) => true)
            ->values()
            ->push($advert->region);

        $isFavorited = $advert->favorites->contains(Auth::id());

        return Inertia::render('Advert/Show', [
            'advert' => $advert,
            'category' => $advert->category,
            'categories' => $categories->values(),
            'locations' => $locations->values(),
            'values' => $values,
            'user' => $advert->values,
            'region' => $advert->region,
            'photos' => $advert->photo,
            'isFavorited' => $isFavorited,
            'can' => $this->getPermissions(Auth::user(), $advert),
        ]);
    }

    public function getPermissions(?User $user, Advert $advert): array
    {
        return collect([
            'manage.own.advert' => Gate::forUser($user)->allows('manage.own.advert', $advert),
        ])
            ->filter()
            ->keys()
            ->toArray();
    }

    public function phone(Advert $advert): string
    {
        return $advert->user->phone;
    }

    private function preparePaginationData2(Request $request): array
    {
        $page = max((int) $request->input('page', 1), 1);
        $perPage = max((int) $request->input('per_page', 5), 1);

        return compact('page', 'perPage');
    }

    private function preparePaginationData(SearchRequest $request): array
    {
        $page = max((int) $request->input('page', 1), 1);
        $perPage = max((int) $request->input('per_page', 5), 1);

        return compact('page', 'perPage');
    }

    private function filterAvailableLocations($locations, $counts)
    {
        return $locations->filter(fn (Location $loc) => isset($counts[$loc->id]) && $counts[$loc->id] > 0);
    }

    private function filterAvailableCategories($categories, $counts)
    {
        return $categories->filter(fn (Category $cat) => isset($counts[$cat->id]) && $counts[$cat->id] > 0);
    }

    private function buildCategoryFilters($categoryTree)
    {
        return $categoryTree->map(fn ($cat) => $this->formatCategoryWithAttributes($cat));
    }
}
