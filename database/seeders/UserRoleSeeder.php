<?php

namespace Database\Seeders;

use App\Models\UserRole;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (UserRole::count() > 0) return;
    
        $data = [
            [
                'name' => "Admin",
                'description' => "Demographics: Varied age groups, typically adults, with diverse backgrounds and experiences in the real estate industry. Roles: Real estate agents, brokers, and professionals involved in buying, selling, and managing properties.",
            ],
            [
                'name' => "Realtor",
                'description' => "Demographics: Typically professionals with experience in real estate management or administration. Roles: Managers, supervisors, or executives responsible for overseeing real estate operations, including lead management, analytics, and user activities.",
            ],
            [
                'name' => "Home Owner",
                'description' => "Demographics: Varied age groups, typically adults who own or are interested in owning properties. Roles: Individuals seeking real estate services, such as buying or selling homes, and engaging with real estate professionals for assistance.",
            ],
            [
                'name' => "Company",
                'description' => "Demographics: Varied sizes and structures, including small boutique firms to large corporate agencies. Roles: Real estate companies utilizing the platform for managing their operations, monitoring user activities, and accessing analytics to optimize performance.",
            ],
        ];
    
        $roles = array_map(fn($role) => [
            'name'          => $role['name'],
            'description'   => $role['description'],
            'updated_at'    => now(),
            'created_at'    => now(),
        ], $data);
    
        UserRole::insert($roles);
    }
}
