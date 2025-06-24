<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CouponController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Coupons/Index', [
            'coupons' => Coupon::latest()->paginate(20),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Coupons/Create');
    }

    public function store(Request $request)
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

    public function update(Coupon $coupon, Request $request)
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

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')->with('success', 'Купон видалено');
    }
}
