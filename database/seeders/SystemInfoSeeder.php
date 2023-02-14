<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SystemInfo;

class SystemInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SystemInfo::create([
            'name' => 'APP NAME',
            'short_name' => 'APP SHORT NAME',
            'description' => '-',
            'keyword' => 'sample, test, keyword aplikasi',
            'copyright' => '2023©Sample - All rights reserved',
            'thumb' => '',
            'login_bg' => '',
            'login_logo' => '',
            'backend_logo' => '',
            'user_updated' => NULL,
        ]);
    }
}