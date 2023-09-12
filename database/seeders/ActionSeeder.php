<?php

namespace Database\Seeders;

use App\Domain\Models\Action;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        //1 *
        Action::create([
            'name' => 'إضافة طلب توظّيف',
            'description' => 'Add new job application',
            'severity' => 1,
        ]);

        //2 *
        Action::create([
            'name' => 'قبول طلب توظيف',
            'description' => 'Approve job application',
            'severity' => 1,
        ]);

        //3 *
        Action::create([
            'name' => 'رفض طلب توظيف',
            'description' => 'Reject job application',
            'severity' => 1,
        ]);

        //4 *
        Action::create([
            'name' => 'تعديل طلب توظيف',
            'description' => 'Edit job application',
            'severity' => 2,
        ]);

        //5 *
        Action::create([
            'name' => 'إضافة موَظف',
            'description' => 'Add new employee',
            'severity' => 1,
        ]);

        //6 *
        Action::create([
            'name' => 'تعديل سجل موظف',
            'description' => 'Edit employee record',
            'severity' => 2,
        ]);

        //7 *
        Action::create([
            'name' => 'حذف موظَف',
            'description' => 'Delete employee',
            'severity' => 3,
        ]);

        //8 *
        Action::create([
            'name' => 'تسجيل حضور موظف',
            'description' => 'Log employee attendance',
            'severity' => 2,
        ]);

        //9 *
        Action::create([
            'name' => 'تسجيل انصراف موظَف',
            'description' => 'Log employee leaving',
            'severity' => 2,
        ]);

        //10 *
        Action::create([
            'name' => 'إضافة طلب إجازة',
            'description' => 'Add vacation request',
            'severity' => 2,
        ]);

        //11 *
        Action::create([
            'name' => 'قبول طلب إجازة',
            'description' => 'Approve vacation request',
            'severity' => 1,
        ]);

        //12 *
        Action::create([
            'name' => 'رفض طلب إجازة',
            'description' => 'Reject vacation request',
            'severity' => 1,
        ]);

        //13 *
        Action::create([
            'name' => 'تعديل طلب إجازة',
            'description' => 'Edit vacation request',
            'severity' => 2,
        ]);

        //14 *
        Action::create([
            'name' => 'حذف طلب إجازة',
            'description' => 'Delete vacation request',
            'severity' => 2,
        ]);

        //15 *
        Action::create([
            'name' => 'إضافة طلب إزاحة دوام',
            'description' => 'Add shift change request',
            'severity' => 2,
        ]);

        //16 *
        Action::create([
            'name' => 'قبول طلب إزاحة دوام',
            'description' => 'Approve shift change request',
            'severity' => 1,
        ]);

        //17 *
        Action::create([
            'name' => 'رفض طلب إزاحة دوام',
            'description' => 'Reject shift change request',
            'severity' => 1,
        ]);

        //18 *
        Action::create([
            'name' => 'تعديل طلب إزاحة دوام',
            'description' => 'Edit shift change request',
            'severity' => 2,
        ]);

        //19 *
        Action::create([
            'name' => 'حذف طلب إزاحة دوام',
            'description' => 'Delete shift change request',
            'severity' => 2,
        ]);

        //20 *
        Action::create([
            'name' => 'إنشاء مسمى وظيفي',
            'description' => 'Create job title',
            'severity' => 1,
        ]);

        //21 *
        Action::create([
            'name' => 'تعديل صلاحيات مسمى وظيفي (اضافة)',
            'description' => 'Add permissions to job title',
            'severity' => 2,
        ]);

        //22 *
        Action::create([
            'name' => 'تعديل صلاحيات مسمى وظيفي (حذف)',
            'description' => 'Remove permissions from job title',
            'severity' => 2,
        ]);

        //23 *
        Action::create([
            'name' => 'تعديل صلاحيات مستخدم (اضافة)',
            'description' => 'Add permissions to user',
            'severity' => 2,
        ]);

        //24 *
        Action::create([
            'name' => 'تعديل صلاحيات مستخدم (حذف)',
            'description' => 'Remove permissions from user',
            'severity' => 2,
        ]);

        //25 *
        Action::create([
            'name' => 'إسناد مسمى وظيفي',
            'description' => 'Assign job title to user',
            'severity' => 1,
        ]);

        //26 *
        Action::create([
            'name' => 'تبرير غياب موظف',
            'description' => 'Justify employee absence',
            'severity' => 2,
        ]);

    }

}
