<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $category = [
            'Cloths' =>'Apparel and wearable items including T-shirts, hoodies, jackets, and other IAU-branded clothing.',
            'Accessories' => 'Complementary add-ons such as bags, keychains, caps, lanyards, and other carry-along items.',
            'Souvenirs' => 'Collectible and giftable memorabilia including mugs, frames, stationery, and campus-themed keepsakes'
        ];
        foreach ($category as $name => $description)
        {
            Category::create([
                'name' => $name,
                'description' => $description,
            ]);
        }


    }
}
