<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Device;
use App\Models\ServiceNote;
use App\Models\ServiceNoteLog;
use Illuminate\Database\Seeder;

class SampleServiceNoteSeeder extends Seeder
{
    public function run(): void
    {
        $customer = Customer::updateOrCreate(
            ['phone' => '0123456789'],
            [
                'name' => 'Ahmad Test',
                'email' => 'ahmad@example.com',
                'address' => null,
            ],
        );

        $device = Device::updateOrCreate(
            [
                'customer_id' => $customer->id,
                'serial_number' => 'TEST12345',
            ],
            [
                'device_type' => 'Laptop',
                'device_type_other' => null,
                'brand_model' => 'Lenovo ThinkPad Test',
                'specifications' => null,
                'device_password' => null,
            ],
        );

        $serviceNo = 'SN-'.now()->format('Y').'-0001';

        $serviceNote = ServiceNote::updateOrCreate(
            ['service_no' => $serviceNo],
            [
                'customer_id' => $customer->id,
                'device_id' => $device->id,
                'received_date' => now()->toDateString(),
                'reported_issue' => 'Laptop tidak boleh ON',
                'initial_diagnosis' => 'Perlu pemeriksaan lanjut',
                'repair_action' => null,
                'parts_replaced' => null,
                'service_charge' => 0,
                'parts_charge' => 0,
                'total_charge' => 0,
                'warranty_duration' => null,
                'warranty_unit' => 'Hari',
                'warranty_note' => 'Tidak termasuk kerosakan fizikal / liquid damage.',
                'status' => 'Received',
                'technician_name' => 'Technician Test',
                'customer_signature_path' => null,
                'technician_signature_path' => null,
                'pdf_original_path' => null,
            ],
        );

        ServiceNoteLog::firstOrCreate(
            [
                'service_note_id' => $serviceNote->id,
                'action' => 'created',
            ],
            [
                'description' => "Sample service note {$serviceNo} created by seeder.",
            ],
        );
    }
}
