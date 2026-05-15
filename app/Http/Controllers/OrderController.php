<?php

namespace App\Http\Controllers;

use App\Enum\PermissionType;
use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    public function show(Order $order)
    {
        Gate::authorize(PermissionType::OrderView);

        $order->load('items.product.category', 'user');

        return view('orders.show', [
            'order' => $order
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items'              => ['required', 'array', 'min:1'],
            'items.*.id'         => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity'   => ['required', 'integer', 'min:1'],
            'total'              => ['required', 'numeric', 'min:0'],
            'shipping'           => ['required', 'array'],
            'discount_code'      => ['sometimes', 'nullable', 'string', 'max:8'],
        ]);

        // Validate and apply discount if provided
        $total = $validated['total'];
        if (!empty($validated['discount_code'])) {
            $discount = Discount::where('code', strtoupper($validated['discount_code']))
                ->where('is_active', true)
                ->where('starts_at', '<=', now())
                ->where('expires_at', '>=', now())
                ->first();
            if ($discount) {
                $total = round($total * (1 - $discount->rate), 2);
            }
        }

        DB::transaction(function () use ($validated, $total) {
            $order = Order::create([
                'user_id'           => auth()->id(),
                'shipping_snapshot' => $validated['shipping'],
                'total_amount'      => $total,
                'payment_status'    => 'unpaid',
            ]);

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['id']);


                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'quantity'   => $item['quantity'],
                    'unit_price' => $product->price,
                ]);

                // Decrement stock, floor at 0
                $product->decrement('quantity', min($item['quantity'], $product->quantity));
            }
        });

        return response()->json(['message' => 'Order placed successfully!'], 201);
    }
}
