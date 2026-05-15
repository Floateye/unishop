<?php

namespace App\Http\Controllers;

use App\Enum\PermissionType;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\AdminProduct;
use App\Models\Product;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use App\Models\Review;

class ProductController extends Controller
{
    //
    public function index()
    {
        $products = Product::with('category')->get();
        $dbCart = [];
        if (auth()->check()) {
            $cart = \App\Models\Cart::where('user_id', auth()->id())->where('is_open', true)->first();
            if ($cart) {
                foreach ($cart->items as $item) {
                    $p = clone $item->product;
                    $p->quantity = $item->quantity;
                    $dbCart[] = [
                        'id' => $p->id,
                        'title' => $p->name,
                        'category' => $p->category ? strtolower($p->category->name) : 'other',
                        'price' => (float) $p->price,
                        'image' => $p->image ? asset('storage/' . $p->image) : '/assets/img/tshirt.webp',
                        'description' => $p->description,
                        'size' => $p->size ?? [],
                        'quantity' => $item->quantity
                    ];
                }
            }
        }
        return view('products.index', compact('products', 'dbCart'));
    }
    public function store(ProductStoreRequest $request)
    {
        $request->validated();

        $image = $request->file('image')->store('img', 'public');


        $sizes = $request->input('size', []);

        $product = Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'price' => $request->price,
            'quantity' => $request->quantity,
            'description' => $request->description,
            'size' => $sizes,
            'image' => $image,
        ]);

        AdminProduct::create([
            'product_id' => $product->id,
            'admin_id' => auth()->user()->admin->id,
            'action_type' => 'create',
        ]);

        return redirect()->route('dashboard')->with(['status' => 'Product added successfully!']);
    }
    public function show(Product $product)
    {
        Gate::authorize(PermissionType::DashboardView);

        $reviews = $product->reviews()->with('user')->latest()->get();

        $sentimentCounts = [
            'positive' => $reviews->where('sentiment', 'positive')->count(),
            'negative' => $reviews->where('sentiment', 'negative')->count(),
            'mixed'    => $reviews->where('sentiment', 'mixed')->count(),
            'neutral'  => $reviews->where('sentiment', 'neutral')->count(),
            'unknown'  => $reviews->whereNull('sentiment')->count(),
        ];

        return view('products.show', compact('product', 'reviews', 'sentimentCounts'));
    }

    public function edit(Product $product){

        Gate::authorize(PermissionType::ProductEdit);

        $categories = \App\Models\Category::all();

        return view('products.edit', [
            'product' => $product,
            'categories' => $categories
        ]);
    }
    public function update( ProductUpdateRequest $request, Product $product){


        $attributes = $request->validated();

        if ($request->hasFile('image')) {
            $attributes['image'] = $request->file('image')->store('img', 'public');
        }

        if ($request->has('size')) {
            $attributes['size'] = $request->input('size', []);
        } else {
        //we explicitly set it to empty array if 'size' is not present in the request.
            $attributes['size'] = [];
        }

        if ($request->has('name')) {
            $attributes['slug'] = Str::slug($request->name);
        }

        $product->update($attributes);

        AdminProduct::create([
            'product_id' => $product->id,
            'admin_id' => auth()->user()->admin->id,
            'action_type' => 'update',
        ]);

        return redirect()->route('dashboard')->with(['status' => 'Product updated successfully!']);
    }
    public function destroy(Product $product){

        Gate::authorize(PermissionType::ProductDelete);

        if ($product->orderItems()->exists()) {
            return redirect(route('dashboard') . '#manage')->with(['error' => 'Cannot delete this product — it is referenced in existing orders.']);
        }

        $product->adminProducts()->delete();
        $product->reviews()->delete();
        $product->delete();

        return redirect(route('dashboard') . '#manage')->with(['status' => 'Product deleted successfully!']);
    }
}
