<?php

namespace App\Http\Controllers;

use App\Enum\PermissionType;
use App\Enum\UserRole;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    public function __invoke()
    {

        Gate::authorize(PermissionType::DashboardView);

        $categories = Category::select('id', 'name')->get();

        $products = Product::with('category')->latest()->paginate(5);

        $discounts = Discount::with('products')->latest()->paginate(5);

        $orders = Order::with(['user:id,name','items'])->latest()->paginate(5);

        return view('dashboard',[
            'categories' => $categories,
            'products' => $products,
            'discounts' => $discounts,
            'orders' => $orders,
            'allUsers' => User::all()->count(),
            'totalProducts' => Product::all()->count(),
            'totalOrders' => Order::all()->count(),
        ]);
    }
}
