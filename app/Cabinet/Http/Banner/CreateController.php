<?php

namespace App\Cabinet\Http\Banner;

use App\Http\Requests\Cabinet\Banners\CreateRequest;
use App\Http\Services\Adverts\CategoryService;
use App\Http\Services\Banners\BannerService;
use App\Models\Adverts\Category;
use App\Models\Adverts\Location;
use App\Models\Banners\Banner;
use Inertia\Inertia;

class CreateController
{
    public function __construct(
        private readonly BannerService $bannerService,
        private readonly CategoryService $categoryService
    ) {}

    public function category()
    {
        $formats = Banner::formatsList();
        $categories = $this->categoryService->getCategories();

        return Inertia::render('Cabinet/Banner/Create', [
            'categories' => $categories,
            'formats' => $formats,
        ]);
    }

    public function region(Category $category, Location $region)
    {
        $regions = Location::where('parent_id', $region ? $region->id : null)->orderBy('name')->get();

        return view('cabinet.banners.create.region', compact('category', 'region', 'regions'));
    }

    public function banner(Category $category, Location $region)
    {
        $formats = Banner::formatsList();

        return view('cabinet.banners.create.banner', compact('category', 'region', 'formats'));
    }

    public function store(CreateRequest $request, Category $category, Location $region)
    {
        try {
            $banner = $this->bannerService->create(\Auth::user(), $request);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('Cabinet/Banner/View', $banner)->with('success', __('banner.banner_create'));
    }
}
