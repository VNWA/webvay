<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Platform Admin',
                'password' => Hash::make('admin@123'),
                'role' => UserRole::Admin,
                'email_verified_at' => now(),
            ],
        );

        $this->call([
            SitePageSeeder::class,
            BlogPostSeeder::class,
        ]);
    }
}
