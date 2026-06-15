<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@partiva.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('P@rtiva#2026!Adm1n$X9'),
            ]
        );
    }
}
