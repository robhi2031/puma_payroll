<?php

namespace Database\Seeders;

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
            'name' => 'E-PAYROLL PT. Puma Jaya Utama',
            'short_name' => 'E-PAYROLL',
            'description' => '-',
            'keyword' => 'sample, test, keyword aplikasi',
            'copyright' => '2023&copy;PT. Puma Jaya Utama - All rights reserved',
            'thumb' => 'thumb-123456.png',
            'login_bg' => 'login-bg-123456.jpg',
            'login_logo' => 'login-logo-123456.png',
            'backend_logo' => 'backend-logo-123456.png',
            'backend_logo_icon' => 'backend-logo-icon-123456.png',
            'user_updated' => NULL,
        ]);
    }
}