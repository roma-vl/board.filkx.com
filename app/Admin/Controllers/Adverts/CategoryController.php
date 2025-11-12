<?php

namespace App\Admin\Controllers\Adverts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Http\Services\Adverts\CategoryService;
use App\Models\Adverts\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function __construct(private readonly CategoryService $categoryService) {}

    public function index(): Response
    {
        return Inertia::render('Admin/Advert/Category/Index', [
            'categories' => $this->categoryService->getCategories(),
        ]);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $this->categoryService->createCategory($request->validated());

        return redirect()->route('admin.adverts.category.index')->with('success', __('adverts.category_created'));
    }

    public function create(): JsonResponse
    {
        return response()->json([
            'categories' => $this->categoryService->getCategories(),
        ]);
    }

    public function edit(Category $category): JsonResponse
    {
        return response()->json([
            'category' => $category,
            'categories' => Category::defaultOrder()->withDepth()->get(),
        ]);
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $this->categoryService->updateCategory($category, $request->validated());

        return redirect()->route('admin.adverts.category.index')->with('success', __('adverts.category_updated'));
    }

    public function show(Category $category): Response
    {
        $parentAttributes = $category->getParentAttributes();
        $attributes = $category->attributes()->orderBy('sort')->get();

        return Inertia::render('Admin/Advert/Category/Show',
            compact('category', 'attributes', 'parentAttributes'));
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->categoryService->deleteCategory($category);

        return redirect()->route('admin.adverts.category.index')->with('success', __('adverts.category_deleted'));
    }

    public function moveUp(Category $category): RedirectResponse
    {
        $this->categoryService->moveUp($category);

        return redirect()->route('admin.adverts.category.index');
    }

    public function moveDown(Category $category): RedirectResponse
    {
        $this->categoryService->moveDown($category);

        return redirect()->route('admin.adverts.category.index');
    }

    public function moveToTop(Category $category): RedirectResponse
    {
        $this->categoryService->moveToTop($category);

        return redirect()->route('admin.adverts.category.index');
    }

    public function moveToBottom(Category $category): RedirectResponse
    {
        $this->categoryService->moveToBottom($category);

        return redirect()->route('admin.adverts.category.index');
    }
}
