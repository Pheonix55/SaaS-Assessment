<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlansTableSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            // Basic
            [
                'name' => 'Basic',
                'duration' => 'monthly',
                'price' => 10, 
                'price_id' => 'price_1Sf0oyCOsHux0vC6xK2HTz0Z', 
            ],
            [
                'name' => 'Basic',
                'duration' => 'yearly',
                'price' => 100,
                'price_id' => 'price_1Sf0pYCOsHux0vC6g8ibFup4',
            ],
            // Pro
            [
                'name' => 'Pro',
                'duration' => 'monthly',
                'price' => 15,
                'price_id' => 'price_1Sf0s8COsHux0vC6yqColSV2',
            ],
            [
                'name' => 'Pro',
                'duration' => 'yearly',
                'price' => 150,
                'price_id' => 'price_1Sf0taCOsHux0vC6WWZvQG1q',
            ],
            // Ultimate
            [
                'name' => 'Ultimate',
                'duration' => 'monthly',
                'price' => 20,
                'price_id' => 'price_1Sf0uOCOsHux0vC6BeQ9XTCg',
            ],
            [
                'name' => 'Ultimate',
                'duration' => 'yearly',
                'price' => 200,
                'price_id' => 'price_1Sf0urCOsHux0vC63G8I7iY2',
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(
                ['name' => $plan['name'], 'duration' => $plan['duration']],
                ['price' => $plan['price'], 'price_id' => $plan['price_id']]
            );
        }
    }
}
