<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::pluck('id', 'name');

        $products = [
            // Cloths
            ['name' => 'IAU Classic Hoodie',    'price' => 89.99,  'quantity' => 50,  'category' => 'Cloths',      'description' => 'A comfortable IAU-branded hoodie for everyday wear.'],
            ['name' => 'IAU Polo Shirt',         'price' => 45.00,  'quantity' => 100, 'category' => 'Cloths',      'description' => 'Elegant polo shirt with IAU embroidery on the chest.'],
            ['name' => 'IAU Varsity Jacket',     'price' => 149.99, 'quantity' => 30,  'category' => 'Cloths',      'description' => 'Premium varsity jacket with IAU branding on the back.'],
            ['name' => 'IAU Sports T-Shirt',     'price' => 35.00,  'quantity' => 150, 'category' => 'Cloths',      'description' => 'Breathable sports T-shirt for campus activities.'],

            // Accessories
            ['name' => 'IAU Backpack',           'price' => 120.00, 'quantity' => 40,  'category' => 'Accessories', 'description' => 'Durable backpack with IAU logo and multiple compartments.'],
            ['name' => 'IAU Keychain',           'price' => 15.00,  'quantity' => 200, 'category' => 'Accessories', 'description' => 'Solid metal keychain with the IAU crest.'],
            ['name' => 'IAU Cap',                'price' => 55.00,  'quantity' => 75,  'category' => 'Accessories', 'description' => 'Adjustable cap with embroidered IAU logo.'],
            ['name' => 'IAU Lanyard',            'price' => 20.00,  'quantity' => 300, 'category' => 'Accessories', 'description' => 'Official IAU lanyard for student ID cards.'],

            // Souvenirs
            ['name' => 'IAU Ceramic Mug',        'price' => 40.00,  'quantity' => 80,  'category' => 'Souvenirs',   'description' => 'High-quality ceramic mug with IAU campus artwork.'],
            ['name' => 'IAU Photo Frame',        'price' => 65.00,  'quantity' => 50,  'category' => 'Souvenirs',   'description' => 'Wooden photo frame with IAU laser engraving.'],
            ['name' => 'IAU Notebook',           'price' => 25.00,  'quantity' => 120, 'category' => 'Souvenirs',   'description' => 'Hardcover A5 notebook with IAU branding on the cover.'],
            ['name' => 'IAU Campus Map Poster',  'price' => 30.00,  'quantity' => 60,  'category' => 'Souvenirs',   'description' => 'Decorative poster featuring the IAU campus map illustration.'],
        ];

        foreach ($products as $data) {
            Product::create([
                'name' => $data['name'],
                'slug'  => Str::slug($data['name']),
                'price' => $data['price'],
                'quantity' => $data['quantity'],
                'category_id' => $categories[$data['category']],
                'description' => $data['description'],
            ]);
        }
    }
}
