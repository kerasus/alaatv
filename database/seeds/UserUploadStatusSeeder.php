<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserUploadStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('useruploadstatuses')
          ->delete();
        $data = [
            [
                'id'          => '1',
                'name'        => 'pending',
                'displayName' => 'در انتظار',
                'description' => 'فایل دانش آموز هنوز مشاهده نشده است',
            ],
            [
                'id'          => '2',
                'displayName' => 'در حال بررسی',
                'name'        => 'processing',
                'description' => 'در حال بررسی فایل دانش آموز',
            ],
            [
                'id'          => '3',
                'name'        => 'done',
                'displayName' => 'پاسخ داده شده',
                'description' => 'به فایل دانش آموز پاسخ داده شده است',
            ],
        ];

        DB::table('useruploadstatuses')
          ->insert($data); // Query Builder
    }
}
