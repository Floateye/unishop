<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;

class CartController extends Controller
{
    public function sync(Request $request)
    { // our cart syncing mechanism...
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $cartData = $request->input('cart', []);

        $cart = Cart::firstOrCreate(
            ['user_id' => $user->id, 'is_open' => true]
        );

        $cart->items()->delete();

        foreach ($cartData as $item) {
            $cart->items()->create([
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
            ]);
        }

        return response()->json(['message' => 'Cart synced successfully.']);
    }
}
