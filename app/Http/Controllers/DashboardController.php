<?php

namespace App\Http\Controllers;

use App\Enum\PermissionType;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    public function __invoke()
    {
        Gate::authorize(PermissionType::DashboardView);

        $categories = Category::select('id', 'name')->get();
        $products   = Product::with('category')->latest()->paginate(5);

        // ── Product search (inventory) ──────────────────────────────────
        $searchQuery   = request('q', '');
        $searchResults = $searchQuery
            ? Product::with('category')
                ->where('name', 'like', "%{$searchQuery}%")
                ->orWhere('description', 'like', "%{$searchQuery}%")
                ->get()
            : collect();

        // ── Orders search ───────────────────────────────────────────────
        $orderQ  = request('order_q', '');
        $orders  = Order::with(['user:id,first_name,last_name', 'items.product'])
            ->when($orderQ !== '', function ($q) use ($orderQ) {
                $q->where(function ($sub) use ($orderQ) {
                    if (is_numeric($orderQ)) {
                        $sub->orWhere('id', (int) $orderQ);
                    }
                    $sub->orWhereHas('user', fn ($u) =>
                        $u->where('first_name', 'like', "%{$orderQ}%")
                          ->orWhere('last_name', 'like', "%{$orderQ}%")
                          ->orWhereRaw("CONCAT(first_name,' ',last_name) LIKE ?", ["%{$orderQ}%"])
                    )->orWhereHas('items.product', fn ($p) =>
                        $p->where('name', 'like', "%{$orderQ}%")
                    );
                });
            })
            ->latest()->paginate(5, ['*'], 'orders_page')->withQueryString();

        // ── Discounts search ────────────────────────────────────────────
        $discountQ = request('discount_q', '');
        $discounts = Discount::when($discountQ !== '', fn ($q) =>
                $q->where('code', 'like', "%{$discountQ}%")
            )->latest()->paginate(5, ['*'], 'discounts_page')->withQueryString();

        // ── Reviews search + star filter ────────────────────────────────
        $reviewQ    = request('review_q', '');
        $reviewStars = request('review_stars', '');
        $reviews    = Review::with(['user', 'product'])
            ->when($reviewQ !== '', function ($q) use ($reviewQ) {
                $q->where(function ($sub) use ($reviewQ) {
                    $sub->where('body', 'like', "%{$reviewQ}%")
                        ->orWhereHas('product', fn ($p) =>
                            $p->where('name', 'like', "%{$reviewQ}%")
                        );
                });
            })
            ->when($reviewStars !== '', fn ($q) => $q->where('rating', (int) $reviewStars))
            ->latest()->paginate(10, ['*'], 'reviews_page')->withQueryString();

        // ── Users search + role/date filter ─────────────────────────────
        $userQ    = request('user_q', '');
        $userRole = request('user_role', '');
        $userDate = request('user_date', '');
        $users    = User::with('roles')
            ->when($userQ !== '', function ($q) use ($userQ) {
                $q->where(function ($sub) use ($userQ) {
                    $sub->where('email', 'like', "%{$userQ}%")
                        ->orWhere('first_name', 'like', "%{$userQ}%")
                        ->orWhere('last_name', 'like', "%{$userQ}%")
                        ->orWhereRaw("CONCAT(first_name,' ',last_name) LIKE ?", ["%{$userQ}%"]);
                });
            })
            ->when($userRole !== '', fn ($q) => $q->role($userRole))
            ->when($userDate !== '', fn ($q) => $q->whereDate('created_at', $userDate))
            ->latest()->paginate(15, ['*'], 'users_page')->withQueryString();

        return view('dashboard', [
            'categories'    => $categories,
            'products'      => $products,
            'discounts'     => $discounts,
            'orders'        => $orders,
            'reviews'       => $reviews,
            'users'         => $users,
            'searchQuery'   => $searchQuery,
            'searchResults' => $searchResults,
            'orderQ'        => $orderQ,
            'discountQ'     => $discountQ,
            'reviewQ'       => $reviewQ,
            'reviewStars'   => $reviewStars,
            'userQ'         => $userQ,
            'userRole'      => $userRole,
            'userDate'      => $userDate,
            'allUsers'      => User::count(),
            'totalProducts' => Product::count(),
            'totalOrders'   => Order::count(),
            'isSupervisor'  => auth()->user()->admin?->is_supervisor ?? false,
        ]);
    }
}
