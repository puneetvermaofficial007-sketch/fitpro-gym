<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Supplier;
use App\Services\StockService;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $stockService = app(StockService::class);

        $categoryNames = ['Supplements', 'Accessories', 'Clothing', 'Equipment', 'Drinks', 'Food', 'Other'];
        $categories = collect($categoryNames)->map(fn ($name) => ProductCategory::firstOrCreate(
            ['name' => $name],
            ['status' => 'active']
        ));

        $supplier = Supplier::firstOrCreate(
            ['email' => 'orders@abcnutrition.com'],
            [
                'name' => 'ABC Nutrition',
                'company_name' => 'ABC Nutrition Pvt Ltd',
                'phone' => '9876501234',
                'address' => 'Industrial Area, Mumbai',
                'status' => 'active',
            ]
        );

        $products = [
            ['Whey Protein', 'Supplements', 1500, 1800, 25, 10],
            ['Creatine', 'Supplements', 600, 900, 15, 5],
            ['Pre-Workout', 'Supplements', 800, 1200, 8, 5],
            ['Protein Bar', 'Food', 80, 120, 50, 20],
            ['Gym Gloves', 'Accessories', 200, 350, 12, 5],
            ['Shaker Bottle', 'Accessories', 150, 350, 30, 10],
            ['T-Shirt', 'Clothing', 300, 599, 20, 8],
            ['Gym Bag', 'Accessories', 500, 899, 6, 3],
            ['Water Bottle', 'Drinks', 100, 199, 40, 15],
            ['Resistance Band', 'Equipment', 250, 450, 18, 8],
        ];

        foreach ($products as [$name, $catName, $purchase, $selling, $stock, $min]) {
            $cat = $categories->firstWhere('name', $catName);
            if (! $cat) {
                continue;
            }

            $product = Product::firstOrCreate(
                ['name' => $name],
                [
                    'sku' => Product::generateSku(),
                    'category_id' => $cat->id,
                    'purchase_price' => $purchase,
                    'selling_price' => $selling,
                    'current_stock' => 0,
                    'minimum_stock_level' => $min,
                    'unit' => 'pcs',
                    'supplier_id' => $supplier->id,
                    'status' => 'active',
                ]
            );

            if ($product->wasRecentlyCreated && $stock > 0) {
                $product->update(['current_stock' => $stock]);
                \App\Models\StockTransaction::create([
                    'product_id' => $product->id,
                    'type' => 'purchase',
                    'quantity' => $stock,
                    'previous_stock' => 0,
                    'new_stock' => $stock,
                    'notes' => 'Initial stock',
                    'user_id' => 1,
                ]);
            }
        }

        Product::where('name', 'Creatine')->update(['current_stock' => 0]);
    }
}
