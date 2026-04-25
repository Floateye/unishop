<?php

namespace App\Http\Controllers;

use App\Enum\PermissionType;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\AdminProduct;
use App\Models\Product;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    //
    public function index()
    {

        return view('products.index');
    }
    public function store(ProductStoreRequest $request)
    {
        $request->validated();

        $image = $request->file('image')->store('img', 'public');


        $product = Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'price' => $request->price,
            'quantity' => $request->quantity,
            'description' => $request->description,
            'image' => $image,
        ]);

        AdminProduct::create([
            'product_id' => $product->id,
            'admin_id' => auth()->user()->admin->id,
            'action_type' => 'create',
        ]);

        return redirect()->route('dashboard')->with(['status' => 'Product added successfully!']);
    }
    public function edit(Product $product){

        Gate::authorize(PermissionType::ProductEdit);

        return view('products.edit', [
            'product' => $product
        ]);
    }
    public function update( ProductUpdateRequest $request, Product $product){


        $attributes = $request->validated();

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

        $product->delete();

        return redirect(route('dashboard') . '#manage')->with(['status' => 'Product deleted successfully!']);
    }
}
