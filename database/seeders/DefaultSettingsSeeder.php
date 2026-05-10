<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class DefaultSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'company_name' => 'LaptopPro',
            'address' => 'Address placeholder',
            'phone' => 'Phone placeholder',
            'office_phone' => 'Office phone placeholder',
            'support_email' => 'support@example.com',
            'footer_note' => '',
            'default_warranty_note' => 'Tidak termasuk kerosakan fizikal / liquid damage.',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value],
            );
        }
    }
}
