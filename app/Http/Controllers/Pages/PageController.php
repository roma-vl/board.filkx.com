<?php

namespace App\Http\Controllers\Pages;

use App\Http\Services\Pages\PageService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PageController
{
    public function __construct(private readonly PageService $pageService) {}

    public function show(Request $request, ?string $urlPath = ''): Response
    {
        $page = $this->pageService->parsePageFromUrl($urlPath);

        return Inertia::render('Page/Show', [
            'page' => $page->first(),
        ]);
    }
}
