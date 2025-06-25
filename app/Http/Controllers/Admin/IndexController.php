<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Adverts\Advert;
use App\Models\Adverts\AdvertOrder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class IndexController extends Controller
{
    public function index(): Response
    {
        $today = Carbon::today();

        // Статистика користувачів за сьогодні
        $todayUsers = User::whereDate('created_at', $today)->count();
        $totalUsers = User::count();

        // Статистика замовлень за сьогодні
        $todayOrders = AdvertOrder::whereDate('created_at', $today)->count();
        $todayOrdersRevenue = AdvertOrder::whereDate('created_at', $today)
            ->where('status', 'paid')
            ->sum('price');
        $totalOrders = AdvertOrder::count();

        // Статистика оголошень за сьогодні
        $todayAdverts = Advert::whereDate('created_at', $today)->count();
        $totalAdverts = Advert::count();

        // Детальні дані користувачів за сьогодні
        $todayUsersData = User::whereDate('created_at', $today)
            ->select('id', 'name', 'email', 'avatar_url', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        // Детальні дані замовлень за сьогодні
        $todayOrdersData = AdvertOrder::whereDate('created_at', $today)
            ->with(['advert:id,title,user_id', 'advert.user:id,name,email'])
            ->select('id', 'advert_id', 'service_type', 'price', 'status', 'payment_method', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        // Детальні дані оголошень за сьогодні
        $todayAdvertsData = Advert::whereDate('created_at', $today)
            ->with(['user:id,name,email'])
            ->select('id', 'user_id', 'title', 'price', 'status', 'premium', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        // Статистика по статусах
        $ordersByStatus = AdvertOrder::whereDate('created_at', $today)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $advertsByStatus = Advert::whereDate('created_at', $today)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $moderationAdverts = Advert::where('status', 'moderation')
            ->with(['user:id,name,email'])
            ->select('id', 'user_id', 'title', 'price', 'status', 'premium', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Статистика по годинах (для графіків)
        $hourlyStats = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $startHour = $today->copy()->setHour($hour);
            $endHour = $startHour->copy()->addHour();

            $hourlyStats[] = [
                'hour' => $hour,
                'users' => User::whereBetween('created_at', [$startHour, $endHour])->count(),
                'orders' => AdvertOrder::whereBetween('created_at', [$startHour, $endHour])->count(),
                'adverts' => Advert::whereBetween('created_at', [$startHour, $endHour])->count(),
            ];
        }

        return Inertia::render('Admin/Dashboard/Index', [
            'stats' => [
                'users' => [
                    'today' => $todayUsers,
                    'total' => $totalUsers,
                    'growth' => $totalUsers > 0 ? round(($todayUsers / $totalUsers) * 100, 2) : 0,
                ],
                'orders' => [
                    'today' => $todayOrders,
                    'total' => $totalOrders,
                    'revenue_today' => $todayOrdersRevenue,
                    'growth' => $totalOrders > 0 ? round(($todayOrders / $totalOrders) * 100, 2) : 0,
                ],
                'adverts' => [
                    'today' => $todayAdverts,
                    'total' => $totalAdverts,
                    'growth' => $totalAdverts > 0 ? round(($todayAdverts / $totalAdverts) * 100, 2) : 0,
                ],
            ],
            'todayData' => [
                'users' => $todayUsersData,
                'orders' => $todayOrdersData,
                'adverts' => $todayAdvertsData,
                'moderationAdverts' => $moderationAdverts,
            ],
            'charts' => [
                'ordersByStatus' => $ordersByStatus,
                'advertsByStatus' => $advertsByStatus,
                'hourlyStats' => $hourlyStats,
            ],
            'today' => $today->format('Y-m-d'),
        ]);
    }
}
