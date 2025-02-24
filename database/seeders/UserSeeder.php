<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserRole; // Import UserRole model
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(User::count() > 0) return;

        if (App::environment(['local', 'staging'])) {
            // Seed user roles
            UserRole::firstOrCreate(['id' => 1, 'name' => 'Administrator']);
            UserRole::firstOrCreate(['id' => 2, 'name' => 'User']);

            // DB Table - Users for Administrator role
            $data = [
                [
                    'firstname'     => "Admin1",
                    'lastname'      => "Reald2d",
                    'email'         => "adminreald2d1@gmail.com",
                    'company'       => "no company",
                    'password'      => Hash::make("wief20318!gjiew**jefisw!1"),
                    'user_role_id'  => 1
                ],
                [
                    'firstname'     => "Admin2",
                    'lastname'      => "Reald2d",
                    'email'         => "adminreald2d2@gmail.com",
                    'company'       => "no company",
                    'password'      => Hash::make("wief20318!gjiew**jefisw!2"),
                    'user_role_id'  => 1
                ],
                [
                    'firstname'     => "TestUser",
                    'lastname'      => "Reald2d",
                    'email'         => "Test@gmail.com",
                    'company'       => "no company",
                    'password'      => Hash::make("Test123!"),
                    'user_role_id'  => 2
                ]
            ];
            foreach ($data as $item) {
                User::factory()->create($item);
            }
        }
    }
}
