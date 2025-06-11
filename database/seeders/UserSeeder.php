<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            "name" => "User 1",
            "email" => "user1@example.com",
            "password" => Hash::make("12345")
        ]);

        $user = User::create([
            "name" => "User 2",
            "email" => "user2@example.com",
            "password" => Hash::make("12345")
        ]);
    }
}
