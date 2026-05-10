<?php

namespace Tests\Feature;

use App\Models\ServiceNote;
use App\Models\Setting;
use Database\Seeders\DefaultSettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceNoteMvpTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware();
        $this->seed(DefaultSettingsSeeder::class);
    }

    public function test_homepage_has_form_and_search_without_login_or_dashboard(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Service Note Form')
            ->assertSee('Search Old Records')
            ->assertDontSee('Dashboard');

        $this->get('/login')->assertNotFound();
        $this->get('/dashboard')->assertNotFound();
    }

    public function test_service_note_can_be_created_and_searched(): void
    {
        $year = now()->format('Y');

        $this->post('/service-notes', $this->validPayload())
            ->assertRedirect(route('home'));

        $this->assertDatabaseHas('customers', [
            'name' => 'MVP Customer',
            'phone' => '0111111111',
            'email' => 'mvp@example.com',
        ]);

        $this->assertDatabaseHas('devices', [
            'device_type' => 'Laptop',
            'brand_model' => 'Dell Latitude 5420',
            'serial_number' => 'MVP-SERIAL-001',
            'device_password' => 'secret-password',
        ]);

        $this->assertDatabaseHas('service_notes', [
            'service_no' => "SN-{$year}-0001",
            'reported_issue' => 'Cannot boot into Windows',
            'status' => 'Received',
            'total_charge' => 150,
        ]);

        $this->get('/?keyword=MVP-SERIAL-001')
            ->assertOk()
            ->assertSee("SN-{$year}-0001")
            ->assertSee('MVP Customer')
            ->assertSee('Dell Latitude 5420');
    }

    public function test_save_and_download_pdf_redirects_to_pdf_generation(): void
    {
        $response = $this->post('/service-notes', array_merge($this->validPayload(), [
            'action' => 'save_pdf',
        ]));

        $response->assertRedirect(route('service-notes.pdf', ServiceNote::query()->firstOrFail()));
    }

    public function test_detail_edit_pdf_and_settings_paths_work(): void
    {
        $serviceNote = $this->createServiceNote();

        $this->get(route('service-notes.show', $serviceNote))
            ->assertOk()
            ->assertSee($serviceNote->service_no)
            ->assertSee('Password Peranti')
            ->assertDontSee('secret-password');

        $this->put(route('service-notes.update', $serviceNote), array_merge($this->validPayload(), [
            'customer_name' => 'Updated Customer',
            'reported_issue' => 'Updated issue',
            'status' => 'Checking',
            'device_password' => '',
        ]))->assertRedirect(route('service-notes.show', $serviceNote));

        $serviceNote->refresh();
        $this->assertSame('Updated issue', $serviceNote->reported_issue);
        $this->assertSame('Checking', $serviceNote->status);
        $this->assertSame('secret-password', $serviceNote->device->device_password);

        $this->put(route('settings.update'), [
            'company_name' => 'MVP Repair Sdn Bhd',
            'address' => '1 Jalan Test, Kuala Lumpur',
            'phone' => '012-222 2222',
            'office_phone' => '03-3333 3333',
            'support_email' => 'support@mvp.test',
            'footer_note' => 'Thank you for choosing MVP Repair.',
            'default_warranty_note' => 'Warranty excludes liquid damage.',
        ])->assertRedirect(route('settings'));

        $settings = Setting::query()->pluck('value', 'key')->all();
        $html = view('service-notes.pdf', [
            'serviceNote' => $serviceNote->load(['customer', 'device']),
            'settings' => $settings,
        ])->render();

        $this->assertStringContainsString('MVP Repair Sdn Bhd', $html);
        $this->assertStringContainsString('support@mvp.test', $html);
        $this->assertStringNotContainsString('secret-password', $html);

        $this->get(route('service-notes.pdf', $serviceNote))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');

        $this->get(route('service-notes.pdf', [$serviceNote, 'print' => 1]))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    private function createServiceNote(): ServiceNote
    {
        $this->post('/service-notes', $this->validPayload())->assertRedirect(route('home'));

        return ServiceNote::query()->with(['customer', 'device'])->firstOrFail();
    }

    private function validPayload(): array
    {
        return [
            'received_date' => now()->toDateString(),
            'status' => 'Received',
            'technician_name' => 'Technician MVP',
            'customer_name' => 'MVP Customer',
            'customer_phone' => '0111111111',
            'customer_email' => 'mvp@example.com',
            'customer_address' => 'Customer address',
            'device_type' => 'Laptop',
            'brand_model' => 'Dell Latitude 5420',
            'serial_number' => 'MVP-SERIAL-001',
            'specifications' => 'Intel i5, 16GB RAM',
            'device_password' => 'secret-password',
            'reported_issue' => 'Cannot boot into Windows',
            'initial_diagnosis' => 'SSD health check required',
            'repair_action' => 'Reinstalled OS',
            'parts_replaced' => 'SSD',
            'service_charge' => '100.00',
            'parts_charge' => '50.00',
            'warranty_duration' => '30',
            'warranty_unit' => 'Hari',
            'warranty_note' => 'Warranty excludes physical damage.',
        ];
    }
}
