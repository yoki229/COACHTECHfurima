<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
<<<<<<< HEAD
use App\Models\User;

class UserFactory extends Factory
{
    protected $model = User::class;

=======

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
>>>>>>> 72ed649cee5500fa55949e29f80ebd02f1ca65c4
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
<<<<<<< HEAD
            'password' => bcrypt($this->faker->password(8, 16)),
            'profile_image' => $this->faker->boolean(50) ? 'storage/test_image/user_sample.png' : null,
            'postal_code' => $this->faker->regexify('[0-9]{3}-[0-9]{4}'),
            'address' => $this->faker->city() . $this->faker->streetAddress(),
            'building' => $this->faker->secondaryAddress,
            'remember_token' => Str::random(10),
        ];
    }
=======
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
>>>>>>> 72ed649cee5500fa55949e29f80ebd02f1ca65c4
}
