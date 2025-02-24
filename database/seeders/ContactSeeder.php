<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact;
use App\Models\User;
use Faker\Factory as Faker;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Ensure users exist before assigning contacts
        $users = User::pluck('id')->toArray();

        for ($i = 0; $i < 100; $i++) {
            Contact::create([
                'user_id' => $users ? $faker->randomElement($users) : null,
                'name' => $faker->name,
                'address' => $faker->address,
                'phone' => $faker->phoneNumber,
                'email' => $faker->email,
                'years_in_home' => $faker->numberBetween(1, 30),
                'possible_equity' => $faker->randomFloat(2, 5000, 500000),
                'rough_credit_score' => $faker->randomFloat(2, 300, 850),
                'zillow_estimate' => $faker->randomFloat(2, 100000, 1000000),
            ]);
        }
    }
}
