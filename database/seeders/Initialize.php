<?php

namespace Database\Seeders;
use App\Models\Roles;
use App\Models\User;
use App\Models\Appointments;
use Illuminate\Support\Facades\Hash;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Initialize extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB Table - Roles
        $data = [
            [
                'name' => "Admin",
                'description' => "Demographics: Varied age groups, typically adults, with diverse backgrounds and experiences in the real estate industry. Roles: Real estate agents, brokers, and professionals involved in buying, selling, and managing properties.",
            ],
            [
                'name' => "RealTor",
                'description' => "Demographics: Typically professionals with experience in real estate management or administration. Roles: Managers, supervisors, or executives responsible for overseeing real estate operations, including lead management, analytics, and user activities. ",
            ],
            [
                'name' => "HomeOwners",
                'description' => "Demographics: Varied age groups, typically adults who own or are interested in owning properties. Roles: Individuals seeking real estate services, such as buying or selling homes, and engaging with real estate professionals for assistance.",
            ],
            [
                'name' => "Company",
                'description' => "Demographics: Varied sizes and structures, including small boutique firms to large corporate agencies. Roles: Real estate companies utilizing the platform for managing their operations, monitoring user activities, and accessing analytics to optimize performance.",
            ],
        ];
        foreach ($data as $item) {
            Roles::create($item);
        }
        // End DB Table - Roles

        // DB Table - Users for Administrator role
        $data = [
            [
                'firstname'     => "Admin1",
                'lastname'      => "Reald2d",
                'email'         => "adminreald2d1@gmail.com",
                'company'       => "no company",
                'password'      => Hash::make("wief20318!gjiew**jefisw!1"),
                'role_id'       => 1
            ],
            [
                'firstname'     => "Admin2",
                'lastname'      => "Reald2d",
                'email'         => "adminreald2d2@gmail.com",
                'company'       => "no company",
                'password'      => Hash::make("wief20318!gjiew**jefisw!2"),
                'role_id'       => 1
            ]
        ];
        foreach ($data as $item) {
            User::create($item);
        }
        // End DB Table - Users for Administrator role

        // DB Table - Appointments
        $data = [
            [
                'user_id' => 2,
                'name' => "Design Review",
                'dateStart' => "2025-01-05T07:39:04.315Z",
                'dateEnd' => "2025-01-06T07:39:04.315Z",
                'allDay' => false,
                'comments' => 'Lorem Ipsum gkawl gakefw kfkghhhsdqw qwowri qweri sa kdsgsmdf sdfkeie',
            ],
            [
                'user_id' => 2,
                'name' => "Meeting With Client",
                'dateStart' => "2025-01-19T16:00:00.000Z",
                'dateEnd' => "2025-01-20T16:00:00.000Z",
                'allDay' => false,
                'comments' => 'Lorem Ipsum gkawl gakefw kfkghhhsdqw qwowri qweri sa kdsgsmdf sdfkeie',
            ],
            [
                'user_id' => 2,
                'name' => "Family Trip",
                'dateStart' => "2025-01-21T16:00:00.000Z",
                'dateEnd' => "2025-01-23T16:00:00.000Z",
                'allDay' => false,
                'comments' => 'Lorem Ipsum gkawl gakefw kfkghhhsdqw qwowri qweri sa kdsgsmdf sdfkeie',
            ],
            [
                'user_id' => 2,
                'name' => "Doctor's Appointment",
                'dateStart' => "2025-01-19T16:00:00.000Z",
                'dateEnd' => "2025-01-20T16:00:00.000Z",
                'allDay' => false,
                'comments' => 'Lorem Ipsum gkawl gakefw kfkghhhsdqw qwowri qweri sa kdsgsmdf sdfkeie',
            ],
            [
                'user_id' => 2,
                'name' => "Dart Game?",
                'dateStart' => "2025-01-17T16:00:00.000Z",
                'dateEnd' => "2025-01-18T16:00:00.000Z",
                'allDay' => false,
                'comments' => 'Lorem Ipsum gkawl gakefw kfkghhhsdqw qwowri qweri sa kdsgsmdf sdfkeie',
            ],
            [
                'user_id' => 2,
                'name' => "Meditation",
                'dateStart' => "2025-01-17T16:00:00.000Z",
                'dateEnd' => "2025-01-18T16:00:00.000Z",
                'allDay' => false,
                'comments' => 'Lorem Ipsum gkawl gakefw kfkghhhsdqw qwowri qweri sa kdsgsmdf sdfkeie',
            ],
            [
                'user_id' => 2,
                'name' => "Dinner",
                'dateStart' => "2025-01-17T16:00:00.000Z",
                'dateEnd' => "2025-01-18T16:00:00.000Z",
                'allDay' => false,
                'comments' => 'Lorem Ipsum gkawl gakefw kfkghhhsdqw qwowri qweri sa kdsgsmdf sdfkeie',
            ],
            [
                'user_id' => 2,
                'name' => "Product Review",
                'dateStart' => "2025-01-17T16:00:00.000Z",
                'dateEnd' => "2025-01-18T16:00:00.000Z",
                'allDay' => false,
                'comments' => 'Lorem Ipsum gkawl gakefw kfkghhhsdqw qwowri qweri sa kdsgsmdf sdfkeie',
            ],
            [
                'user_id' => 2,
                'name' => "Monthly Meeting",
                'dateStart' => "2025-01-31T16:00:00.000Z",
                'dateEnd' => "2025-01-31T16:00:00.000Z",
                'allDay' => false,
                'comments' => 'Lorem Ipsum gkawl gakefw kfkghhhsdqw qwowri qweri sa kdsgsmdf sdfkeie',
            ],
            [
                'user_id' => 2,
                'name' => "Monthly Checkup",
                'dateStart' => "2024-11-30T16:00:00.000Z",
                'dateEnd' => "2024-11-30T16:00:00.000Z",
                'allDay' => false,
                'comments' => 'Lorem Ipsum gkawl gakefw kfkghhhsdqw qwowri qweri sa kdsgsmdf sdfkeie',
            ],
        ];
        foreach ($data as $item) {
            Appointments::create($item);
        }
    }
}
