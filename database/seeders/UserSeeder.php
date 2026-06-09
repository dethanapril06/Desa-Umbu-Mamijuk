<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('users')->updateOrInsert(
            ['email' => 'admin@umbumamijuk.desa.id'],
            [
                'name' => 'Administrator XXXXX',
                'email_verified_at' => $now,
                'password' => Hash::make('password'),
                'role' => 'admin',
                'remember_token' => null,
                'deleted_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }
}