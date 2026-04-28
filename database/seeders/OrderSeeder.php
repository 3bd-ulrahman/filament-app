<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::query()->pluck('id');

        $products = Product::query()->pluck('id');

        Order::factory()->count(50)
            ->state(function (array $attributes) use ($users, $products) {
                return [
                    'user_id' => $users->random(),
                    'product_id' => $products->random()
                ];
            })
            ->create();
    }
}
