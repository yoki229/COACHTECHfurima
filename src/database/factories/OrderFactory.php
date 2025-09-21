<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\User;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        $user = User::inRandomOrder()->first();

        return [
            'user_id'        => $user->id,
            'payment_method' => $this->faker->randomElement(Order::PAYMENT_METHODS),
            'postal_code'    => $user->postal_code,
            'address'        => $user->address,
            'building'       => $user->building,
        ];
    }
}
