<?php

namespace App\Admin\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\Billing\Coupon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CouponController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Coupons/Index', [
            'coupons' => Coupon::latest()->paginate(20),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Coupons/Create');
    }

    public function edit(Coupon $coupon): Response
    {
        return Inertia::render('Admin/Coupons/Edit', [
            'coupon' => $coupon,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'discount_type' => 'required|in:fixed,percent',
            'discount_amount' => 'required|numeric|min:0',
            'applicable_services' => 'nullable|array',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
        ]);

        Coupon::create($validated);

        return redirect()->route('admin.coupons.index')->with('success', 'Купон створено');
    }

    public function update(Coupon $coupon, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string',
            'discount_type' => 'required|in:fixed,percent',
            'discount_amount' => 'required|numeric|min:0',
            'applicable_services' => 'nullable|array',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
        ]);
        $coupon->update($validated);

        return redirect()->route('admin.coupons.index')->with('success', 'Купон оновлено');

    }

    public function destroy(Coupon $coupon): RedirectResponse
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')->with('success', 'Купон видалено');
    }
}
