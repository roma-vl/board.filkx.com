<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\Admin\DashboardStatsService;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class IndexController extends Controller
{
    public function __construct(private readonly DashboardStatsService $dashboardStatsService) {}

    public function index(): Response
    {
        $today = Carbon::today();
        $data = $this->dashboardStatsService->getStats($today);

        return Inertia::render('Admin/Dashboard/Index', $data);
    }
}
