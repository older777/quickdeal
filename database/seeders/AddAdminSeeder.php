<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AddAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate([
            'name' => 'manager',
        ], [
            'name' => 'manager',
            'email' => 'test@test',
            'password' => Hash::make('123'),
            'email_verified_at' => Carbon::now(),
        ]);
        User::firstOrCreate([
            'name' => 'moderator',
        ], [
            'name' => 'moderator',
            'email' => 'moder@moder',
            'password' => Hash::make('123'),
            'email_verified_at' => Carbon::now(),
        ]);
        User::factory(4)->create();
    }
}
