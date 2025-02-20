<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as FakerFactory;
use Spatie\Permission\Models\Role;

// Import Spatie Role Model

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        $faker = FakerFactory::create('ar_SA');

        return [
            'full_name' => $faker->name,
            'national_id' => $faker->unique()->numerify('############'),
            'phone' => $faker->unique()->phoneNumber,
            'email' => $faker->unique()->safeEmail,
            'image' => $faker->imageUrl(200, 200, 'people'),
            'fcm_token' => $faker->uuid,
            'jwt_token' => $faker->uuid,
            'password' => Hash::make('password'),
            'otp' => null,
            'otp_expire' => null,
            'status' => $faker->randomElement([0, 1]),
            'delete_reason' => $faker->optional()->sentence,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            // Fetch roles only from the 'web' guard
            $role = Role::where('guard_name', 'user')->inRandomOrder()->first();
            if ($role) {
                $user->assignRole($role->name);
            }
        });
    }
}
