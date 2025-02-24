<?php

namespace Database\Seeders;

use App\Models\Appointment;
use Illuminate\Database\Seeder;

class AppointmentsDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB Table - Appointments
        $data = [
            [
                'user_id' => 1,
                'title' => "Design Review",
                'date_start' => "2025-01-05T07:39:04.315Z",
                'date_end' => "2025-01-06T07:39:04.315Z",
                'timezone' => 'Asia/Manila',
                'allDay' => false,
                'comments' => 'Lorem Ipsum gkawl gakefw kfkghhhsdqw qwowri qweri sa kdsgsmdf sdfkeie',
            ],
            [
                'user_id' => 1,
                'title' => "Meeting With Client",
                'date_start' => "2025-01-19T16:00:00.000Z",
                'date_end' => "2025-01-20T16:00:00.000Z",
                'timezone' => 'Asia/Manila',
                'allDay' => false,
                'comments' => 'Lorem Ipsum gkawl gakefw kfkghhhsdqw qwowri qweri sa kdsgsmdf sdfkeie',
            ],
            [
                'user_id' => 1,
                'title' => "Family Trip",
                'date_start' => "2025-01-21T16:00:00.000Z",
                'date_end' => "2025-01-23T16:00:00.000Z",
                'timezone' => 'Asia/Manila',
                'allDay' => false,
                'comments' => 'Lorem Ipsum gkawl gakefw kfkghhhsdqw qwowri qweri sa kdsgsmdf sdfkeie',
            ],
            [
                'user_id' => 1,
                'title' => "Doctor's Appointment",
                'date_start' => "2025-01-19T16:00:00.000Z",
                'date_end' => "2025-01-20T16:00:00.000Z",
                'timezone' => 'Asia/Manila',
                'allDay' => false,
                'comments' => 'Lorem Ipsum gkawl gakefw kfkghhhsdqw qwowri qweri sa kdsgsmdf sdfkeie',
            ],
            [
                'user_id' => 1,
                'title' => "Dart Game?",
                'date_start' => "2025-01-17T16:00:00.000Z",
                'date_end' => "2025-01-18T16:00:00.000Z",
                'timezone' => 'Asia/Manila',
                'allDay' => false,
                'comments' => 'Lorem Ipsum gkawl gakefw kfkghhhsdqw qwowri qweri sa kdsgsmdf sdfkeie',
            ],
            [
                'user_id' => 1,
                'title' => "Meditation",
                'date_start' => "2025-01-17T16:00:00.000Z",
                'date_end' => "2025-01-18T16:00:00.000Z",
                'timezone' => 'Asia/Manila',
                'allDay' => false,
                'comments' => 'Lorem Ipsum gkawl gakefw kfkghhhsdqw qwowri qweri sa kdsgsmdf sdfkeie',
            ],
            [
                'user_id' => 1,
                'title' => "Dinner",
                'date_start' => "2025-01-17T16:00:00.000Z",
                'date_end' => "2025-01-18T16:00:00.000Z",
                'timezone' => 'Asia/Manila',
                'allDay' => false,
                'comments' => 'Lorem Ipsum gkawl gakefw kfkghhhsdqw qwowri qweri sa kdsgsmdf sdfkeie',
            ],
            [
                'user_id' => 1,
                'title' => "Product Review",
                'date_start' => "2025-01-17T16:00:00.000Z",
                'date_end' => "2025-01-18T16:00:00.000Z",
                'timezone' => 'Asia/Manila',
                'allDay' => false,
                'comments' => 'Lorem Ipsum gkawl gakefw kfkghhhsdqw qwowri qweri sa kdsgsmdf sdfkeie',
            ],
            [
                'user_id' => 1,
                'title' => "Monthly Meeting",
                'date_start' => "2025-01-31T16:00:00.000Z",
                'date_end' => "2025-01-31T16:00:00.000Z",
                'timezone' => 'Asia/Manila',
                'allDay' => false,
                'comments' => 'Lorem Ipsum gkawl gakefw kfkghhhsdqw qwowri qweri sa kdsgsmdf sdfkeie',
            ],
            [
                'user_id' => 1,
                'title' => "Monthly Checkup",
                'date_start' => "2024-11-30T16:00:00.000Z",
                'date_end' => "2024-11-30T16:00:00.000Z",
                'timezone' => 'Asia/Manila',
                'allDay' => false,
                'comments' => 'Lorem Ipsum gkawl gakefw kfkghhhsdqw qwowri qweri sa kdsgsmdf sdfkeie',
            ],
        ];

        if(Appointment::count() > 0) return;
        
        Appointment::insert($data);
    }
}
