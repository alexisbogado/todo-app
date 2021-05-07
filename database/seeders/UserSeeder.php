<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_count = User::count();

        User::when(($user_count < 1), function ($query) {
            $query->create([
                'name' => 'product.manager',
                'email' => 'product@manager.com',
                'password' => Hash::make('admin'),
            ]);
        });
    }
}
