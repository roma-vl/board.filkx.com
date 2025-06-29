<?php

namespace App\Http\Services\Admin;

use App\Models\Adverts\Advert;
use App\Models\Adverts\AdvertOrder;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardStatsService
{
    public function getStats($today): array
    {
        return [
            'stats' => [
                'users' => $this->getUserStats($today),
                'orders' => $this->getOrderStats($today),
                'adverts' => $this->getAdvertStats($today),
            ],
            'todayData' => [
                'users' => $this->getDetailedTodayUsers($today),
                'orders' => $this->getDetailedTodayOrders($today),
                'adverts' => $this->getDetailedTodayAdverts($today),
                'moderationAdverts' => $this->getModerationAdverts(),
            ],
            'charts' => [
                'ordersByStatus' => $this->getOrdersByStatus($today),
                'advertsByStatus' => $this->getAdvertsByStatus($today),
                'hourlyStats' => $this->getHourlyStats($today),
            ],
            'today' => $today->format('Y-m-d'),
        ];
    }

    private function getUserStats(Carbon $today): array
    {
        $stats = User::selectRaw('COUNT(*) as total,
                SUM(CASE WHEN DATE(created_at) = ? THEN 1 ELSE 0 END) as today_count', [$today->toDateString()])
            ->first();

        $total = $stats->total;
        $todayCount = $stats->today_count;

        return [
            'today' => $todayCount,
            'total' => $total,
            'growth' => $total > 0 ? round(($todayCount / $total) * 100, 2) : 0,
        ];
    }

    private function getOrderStats(Carbon $today): array
    {
        $stats = AdvertOrder::whereDate('created_at', $today)
            ->selectRaw('count(*) as count, sum(case when status = "paid" then price else 0 end) as revenue')
            ->first();

        $count = $stats->count ?? 0;
        $revenue = $stats->revenue ?? 0;
        $total = AdvertOrder::count();

        return [
            'today' => $count,
            'total' => $total,
            'revenue_today' => $revenue,
            'growth' => $total > 0 ? round(($count / $total) * 100, 2) : 0,
        ];
    }

    private function getAdvertStats(Carbon $today): array
    {
        $stats = Advert::selectRaw('COUNT(*) as total,
                SUM(CASE WHEN DATE(created_at) = ? THEN 1 ELSE 0 END) as today_count', [$today->toDateString()])
            ->first();

        $total = $stats->total;
        $todayCount = $stats->today_count;

        return [
            'today' => $todayCount,
            'total' => $total,
            'growth' => $total > 0 ? round(($todayCount / $total) * 100, 2) : 0,
        ];
    }

    private function getDetailedTodayUsers(Carbon $today)
    {
        return User::whereDate('created_at', $today)
            ->select('id', 'name', 'email', 'avatar_url', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    private function getDetailedTodayOrders(Carbon $today)
    {
        return AdvertOrder::whereDate('created_at', $today)
            ->with(['advert:id,title,user_id', 'advert.user:id,name,email'])
            ->select('id', 'advert_id', 'service_type', 'price', 'status', 'payment_method', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    private function getDetailedTodayAdverts(Carbon $today)
    {
        return Advert::whereDate('created_at', $today)
            ->with(['user:id,name,email'])
            ->select('id', 'user_id', 'title', 'price', 'status', 'premium', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    private function getOrdersByStatus(Carbon $today): array
    {
        return AdvertOrder::whereDate('created_at', $today)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    private function getAdvertsByStatus(Carbon $today): array
    {
        return Advert::whereDate('created_at', $today)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    private function getModerationAdverts()
    {
        return Advert::where('status', 'moderation')
            ->with(['user:id,name,email'])
            ->select('id', 'user_id', 'title', 'price', 'status', 'premium', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    private function getHourlyStats(Carbon $today): array
    {
        $hourlyUsers = User::selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->whereDate('created_at', $today)
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        $hourlyOrders = AdvertOrder::selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->whereDate('created_at', $today)
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        $hourlyAdverts = Advert::selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->whereDate('created_at', $today)
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        $hourlyStats = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $hourlyStats[] = [
                'hour' => $hour,
                'users' => $hourlyUsers[$hour] ?? 0,
                'orders' => $hourlyOrders[$hour] ?? 0,
                'adverts' => $hourlyAdverts[$hour] ?? 0,
            ];
        }

        return $hourlyStats;
    }
}
