<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt($this->faker->password(8, 16)),
            'profile_image' => 'user_sample.png',
            'postal_code' => $this->faker->regexify('[0-9]{3}-[0-9]{4}'),
            'address' => $this->faker->city() . $this->faker->streetAddress(),
            'building' => $this->faker->secondaryAddress,
            'remember_token' => Str::random(10),
            'email_verified_at' => now(),
        ];
    }

    // テスト用。「未認証状態」を再現
    public function unverified()
    {
        return $this->state(fn () => [
            'email_verified_at' => null,
        ]);
    }

    // 「認証状態」を再現
    public function verified()
    {
        return $this->state(fn () => [
            'email_verified_at' => now(),
        ]);
    }
}
