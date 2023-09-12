<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
         * 1- pending
         * 2- accepted
         * 3- rejected
         * 4- archived
         */
        DB::table('application_statuses')
            ->insert([
                ['name' => 'بانتظار المعالجة', 'description' => 'الطلب قيد المعالجة'],
                ['name' => 'مقبول', 'description' => 'تم قبول الطلب'],
                ['name' => 'مرفوض', 'description' => 'تم رفض الطلب'],
                ['name' => 'مؤرشف', 'description' => 'تم أرشفة الطلب'],
                ['name' => 'مستخدم', 'description' => 'تم استخدام إنشاء حساب للموظف صاحب ملف التوظيف'],
            ]);
    }
}
