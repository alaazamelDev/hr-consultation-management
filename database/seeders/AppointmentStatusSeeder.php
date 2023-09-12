<?php

namespace Database\Seeders;

use App\Domain\Models\CD\AppointmentStatus;
use Illuminate\Database\Seeder;

class AppointmentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $status = [
            'ملغى (مراجع)',
            'ملغى (موظف)',
            'ملغى (مستشار)',
            'مكتمل',
            'محجوز',
            'متاح',
            'فائت (مراجع)',
            'فائت (مستشار)',
            'هاتف',
            'مغلق',
            'غير مسجل',
        ];

        foreach ($status as $item) {
            AppointmentStatus::create([
                'name' => $item,
            ]);
        }
    }
}
