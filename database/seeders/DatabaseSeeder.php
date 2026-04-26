<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Users ──────────────────────────────────────────────
        $admin = User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@feed.pro',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);

        $staff = User::create([
            'name'     => 'Staff Member',
            'email'    => 'staff@feed.pro',
            'password' => Hash::make('staff123'),
            'role'     => 'staff',
        ]);

        // ── Products ───────────────────────────────────────────
        $products = [
            ['name' => 'Premium Chicken Feed',  'type' => 'Poultry',   'price' => 45.99, 'stock_quantity' => 120],
            ['name' => 'Cattle Grain Mix',       'type' => 'Livestock', 'price' => 89.50, 'stock_quantity' => 3],
            ['name' => 'Layer Pellets',          'type' => 'Poultry',   'price' => 38.00, 'stock_quantity' => 0],
            ['name' => 'Goat Mineral Lick',      'type' => 'Goat',      'price' => 22.75, 'stock_quantity' => 45],
            ['name' => 'Pig Grower Ration',      'type' => 'Pig',       'price' => 62.00, 'stock_quantity' => 2],
            ['name' => 'Horse Oat Mix',          'type' => 'Horse',     'price' => 110.00, 'stock_quantity' => 18],
            ['name' => "Duck & Geese Pellets",   'type' => 'Poultry',   'price' => 41.00, 'stock_quantity' => 55],
            ['name' => 'Sheep Finisher',         'type' => 'Livestock', 'price' => 75.50, 'stock_quantity' => 30],
        ];

        foreach ($products as $p) {
            Product::create($p);
        }

        // ── Sample Orders ──────────────────────────────────────
        $orderData = [
            [
                'customer_name' => 'Green Acres Farm',
                'customer_phone' => '+48 123 456 789',
                'customer_address' => 'ul. Polna 12, Warsaw',
                'items' => [[1, 10]],
            ],
            [
                'customer_name' => 'Blue Ridge Ranch',
                'customer_phone' => '+48 987 654 321',
                'customer_address' => 'ul. Leśna 5, Kraków',
                'items' => [[4, 8], [7, 3]],
            ],
            [
                'customer_name' => 'Sunny Hill Farm',
                'customer_phone' => '+48 555 222 111',
                'customer_address' => 'ul. Słoneczna 3, Gdańsk',
                'items' => [[6, 5], [4, 3]],
            ],
            [
                'customer_name' => 'River Bend Livestock',
                'customer_phone' => '+48 444 111 222',
                'customer_address' => 'ul. Wodna 8, Wrocław',
                'items' => [[2, 5]],
            ],
        ];

        foreach ($orderData as $od) {
            $total = 0;
            $order = Order::create([
                'customer_name'    => $od['customer_name'],
                'customer_phone'   => $od['customer_phone'],
                'customer_address' => $od['customer_address'],
                'total_price'      => 0,
                'user_id'          => $admin->id,
            ]);

            foreach ($od['items'] as [$productId, $qty]) {
                $product = Product::find($productId);
                if (!$product) continue;
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $productId,
                    'quantity'   => $qty,
                    'price'      => $product->price,
                ]);
                $total += $product->price * $qty;
            }

            $order->update(['total_price' => $total]);
        }
    }
}
