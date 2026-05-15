<?php

namespace App\Http\Controllers;

use App\Enum\PermissionType;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DiscountController extends Controller
{
    public function create()
    {
        return redirect()->to(route('dashboard') . '#discounts');
    }

    public function store(Request $request)
    {
        Gate::authorize(PermissionType::DashboardView);

        $validated = $request->validateWithBag('createDiscount', [
            'code'       => ['required', 'string', 'max:8', 'unique:discounts,code'],
            'rate'       => ['required', 'numeric', 'min:1', 'max:100'],
            'starts_at'  => ['required', 'date'],
            'expires_at' => ['required', 'date', 'after:starts_at'],
        ]);

        Discount::create([
            'code'       => strtoupper($validated['code']),
            'type'       => 'percentage',
            'rate'       => $validated['rate'] / 100,
            'starts_at'  => $validated['starts_at'],
            'expires_at' => $validated['expires_at'],
            'is_active'  => $request->boolean('is_active', true),
        ]);

        return redirect()->to(route('dashboard') . '#discounts')
            ->with('status', 'Discount code "' . strtoupper($validated['code']) . '" created successfully.');
    }

    public function apply(Request $request)
    {
        $code = strtoupper(trim($request->input('code', '')));

        if (!$code) {
            return response()->json(['valid' => false, 'message' => 'Please enter a code.'], 422);
        }

        $discount = Discount::where('code', $code)
            ->where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('expires_at', '>=', now())
            ->first();

        if (!$discount) {
            return response()->json(['valid' => false, 'message' => 'Invalid or expired discount code.'], 422);
        }

        $percent = round($discount->rate * 100);

        return response()->json([
            'valid'       => true,
            'code'        => $discount->code,
            'rate'        => (float) $discount->rate,
            'description' => "{$percent}% off",
        ]);
    }
}
