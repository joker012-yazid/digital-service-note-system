<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Customer;
use App\Models\Device;
use App\Models\ServiceNote;
use App\Services\ServiceNumberGenerator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ServiceNoteController extends Controller
{
    public function index(Request $request): View
    {
        $statuses = ServiceNote::STATUSES;
        $deviceTypes = $this->deviceTypes();

        $searchFilters = [
            'keyword' => trim((string) $request->query('keyword', '')),
            'status' => (string) $request->query('status', ''),
            'device_type' => (string) $request->query('device_type', ''),
            'received_from' => (string) $request->query('received_from', ''),
            'received_to' => (string) $request->query('received_to', ''),
        ];

        foreach (['received_from', 'received_to'] as $dateFilter) {
            if ($searchFilters[$dateFilter] !== '' && ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $searchFilters[$dateFilter])) {
                $searchFilters[$dateFilter] = '';
            }
        }

        $serviceNotesQuery = ServiceNote::query()
            ->with(['customer', 'device'])
            ->latest('received_date')
            ->latest('id');

        if ($searchFilters['keyword'] !== '') {
            $keyword = $searchFilters['keyword'];
            $likeKeyword = "%{$keyword}%";

            $serviceNotesQuery->where(function ($query) use ($likeKeyword) {
                $query
                    ->where('service_no', 'like', $likeKeyword)
                    ->orWhere('reported_issue', 'like', $likeKeyword)
                    ->orWhere('received_date', 'like', $likeKeyword)
                    ->orWhere('status', 'like', $likeKeyword)
                    ->orWhere('technician_name', 'like', $likeKeyword)
                    ->orWhereHas('customer', function ($customerQuery) use ($likeKeyword) {
                        $customerQuery
                            ->where('name', 'like', $likeKeyword)
                            ->orWhere('phone', 'like', $likeKeyword)
                            ->orWhere('email', 'like', $likeKeyword);
                    })
                    ->orWhereHas('device', function ($deviceQuery) use ($likeKeyword) {
                        $deviceQuery
                            ->where('brand_model', 'like', $likeKeyword)
                            ->orWhere('serial_number', 'like', $likeKeyword);
                    });
            });
        }

        if (in_array($searchFilters['status'], $statuses, true)) {
            $serviceNotesQuery->where('status', $searchFilters['status']);
        }

        if (in_array($searchFilters['device_type'], $deviceTypes, true)) {
            $serviceNotesQuery->whereHas('device', function ($query) use ($searchFilters) {
                $query->where('device_type', $searchFilters['device_type']);
            });
        }

        if ($searchFilters['received_from'] !== '') {
            $serviceNotesQuery->whereDate('received_date', '>=', $searchFilters['received_from']);
        }

        if ($searchFilters['received_to'] !== '') {
            $serviceNotesQuery->whereDate('received_date', '<=', $searchFilters['received_to']);
        }

        $serviceNotes = $serviceNotesQuery
            ->paginate(10)
            ->withQueryString();

        return view('home', compact('statuses', 'deviceTypes', 'searchFilters', 'serviceNotes'));
    }

    public function show(ServiceNote $serviceNote): View
    {
        $serviceNote = $this->resolveRouteServiceNote($serviceNote);
        $serviceNote->load(['customer', 'device']);

        return view('service-notes.show', compact('serviceNote'));
    }

    public function edit(ServiceNote $serviceNote): View
    {
        $serviceNote = $this->resolveRouteServiceNote($serviceNote);
        $serviceNote->load(['customer', 'device']);

        $statuses = ServiceNote::STATUSES;
        $deviceTypes = $this->deviceTypes();

        return view('service-notes.edit', compact('serviceNote', 'statuses', 'deviceTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->serviceNoteRules());

        $serviceCharge = round((float) ($validated['service_charge'] ?? 0), 2);
        $partsCharge = round((float) ($validated['parts_charge'] ?? 0), 2);

        $serviceNote = DB::transaction(function () use ($validated, $serviceCharge, $partsCharge): ServiceNote {
            $customer = Customer::firstOrNew([
                'phone' => $validated['customer_phone'],
            ]);

            $customer->fill([
                'name' => $validated['customer_name'],
                'email' => $validated['customer_email'] ?? $customer->email,
                'address' => $validated['customer_address'] ?? $customer->address,
            ]);
            $customer->save();

            $deviceQuery = Device::query()->where('customer_id', $customer->id);

            if (! empty($validated['serial_number'])) {
                $deviceQuery->where('serial_number', $validated['serial_number']);
                $device = $deviceQuery->first();
            } else {
                $device = null;
            }

            $device ??= new Device(['customer_id' => $customer->id]);
            $device->fill([
                'device_type' => $validated['device_type'],
                'device_type_other' => $validated['device_type_other'] ?? null,
                'brand_model' => $validated['brand_model'],
                'serial_number' => $validated['serial_number'] ?? null,
                'specifications' => $validated['specifications'] ?? null,
                'device_password' => $validated['device_password'] ?? null,
            ]);
            $device->customer()->associate($customer);
            $device->save();

            $serviceNote = ServiceNote::create([
                'service_no' => app(ServiceNumberGenerator::class)->generate(),
                'customer_id' => $customer->id,
                'device_id' => $device->id,
                'received_date' => $validated['received_date'],
                'reported_issue' => $validated['reported_issue'],
                'initial_diagnosis' => $validated['initial_diagnosis'] ?? null,
                'repair_action' => $validated['repair_action'] ?? null,
                'parts_replaced' => $validated['parts_replaced'] ?? null,
                'service_charge' => $serviceCharge,
                'parts_charge' => $partsCharge,
                'total_charge' => $serviceCharge + $partsCharge,
                'warranty_duration' => $validated['warranty_duration'] ?? null,
                'warranty_unit' => $validated['warranty_unit'] ?? null,
                'warranty_note' => $validated['warranty_note'] ?? null,
                'status' => $validated['status'],
                'technician_name' => $validated['technician_name'] ?? null,
            ]);

            $serviceNote->logs()->create([
                'action' => 'created',
                'description' => "Service note {$serviceNote->service_no} created"
                    . (! empty($serviceNote->technician_name) ? " by {$serviceNote->technician_name}" : '.'),
            ]);

            return $serviceNote;
        });

        if ($request->input('action') === 'save_pdf') {
            return redirect()->route('service-notes.pdf', $serviceNote);
        }

        return redirect()
            ->route('home')
            ->with('success', "Service note {$serviceNote->service_no} berjaya disimpan.")
            ->with('service_note_id', $serviceNote->id);
    }

    public function update(Request $request, ServiceNote $serviceNote): RedirectResponse
    {
        $serviceNote = $this->resolveRouteServiceNote($serviceNote);
        $validated = $request->validate($this->serviceNoteRules());

        $serviceCharge = round((float) ($validated['service_charge'] ?? 0), 2);
        $partsCharge = round((float) ($validated['parts_charge'] ?? 0), 2);

        $serviceNote = DB::transaction(function () use ($serviceNote, $validated, $serviceCharge, $partsCharge): ServiceNote {
            $serviceNote->load(['customer', 'device']);

            $customer = Customer::firstOrNew([
                'phone' => $validated['customer_phone'],
            ]);

            $customer->fill([
                'name' => $validated['customer_name'],
                'email' => $validated['customer_email'] ?? $customer->email,
                'address' => $validated['customer_address'] ?? $customer->address,
            ]);
            $customer->save();

            $device = null;

            if (! empty($validated['serial_number'])) {
                $device = Device::query()
                    ->where('customer_id', $customer->id)
                    ->where('serial_number', $validated['serial_number'])
                    ->first();
            }

            $device ??= $serviceNote->device ?? new Device;
            $devicePassword = $validated['device_password'] ?? null;

            if (($devicePassword === null || $devicePassword === '') && $device->exists) {
                $devicePassword = $device->device_password;
            }

            $device->fill([
                'device_type' => $validated['device_type'],
                'device_type_other' => $validated['device_type_other'] ?? null,
                'brand_model' => $validated['brand_model'],
                'serial_number' => $validated['serial_number'] ?? null,
                'specifications' => $validated['specifications'] ?? null,
                'device_password' => $devicePassword,
            ]);
            $device->customer()->associate($customer);
            $device->save();

            $serviceNote->fill([
                'customer_id' => $customer->id,
                'device_id' => $device->id,
                'received_date' => $validated['received_date'],
                'reported_issue' => $validated['reported_issue'],
                'initial_diagnosis' => $validated['initial_diagnosis'] ?? null,
                'repair_action' => $validated['repair_action'] ?? null,
                'parts_replaced' => $validated['parts_replaced'] ?? null,
                'service_charge' => $serviceCharge,
                'parts_charge' => $partsCharge,
                'total_charge' => $serviceCharge + $partsCharge,
                'warranty_duration' => $validated['warranty_duration'] ?? null,
                'warranty_unit' => $validated['warranty_unit'] ?? null,
                'warranty_note' => $validated['warranty_note'] ?? null,
                'status' => $validated['status'],
                'technician_name' => $validated['technician_name'] ?? null,
            ]);
            $serviceNote->customer()->associate($customer);
            $serviceNote->device()->associate($device);
            $serviceNote->save();

            $serviceNote->logs()->create([
                'action' => 'updated',
                'description' => "Service note {$serviceNote->service_no} updated"
                    . (! empty($serviceNote->technician_name) ? " by {$serviceNote->technician_name}" : '.'),
            ]);

            return $serviceNote;
        });

        return redirect()
            ->route('service-notes.show', $serviceNote)
            ->with('success', "Service note {$serviceNote->service_no} berjaya dikemaskini.");
    }

    public function destroy(Request $request, ServiceNote $serviceNote): RedirectResponse
    {
        $serviceNote = $this->resolveRouteServiceNote($serviceNote);
        $request->validate([
            'delete_confirmation' => ['required', 'string', Rule::in([$serviceNote->service_no])],
        ], [
            'delete_confirmation.in' => 'Sila taip nombor service yang tepat untuk sahkan delete.',
        ]);

        DB::transaction(function () use ($serviceNote): void {
            $serviceNote->logs()->create([
                'action' => 'deleted',
                'description' => "Service note {$serviceNote->service_no} soft deleted.",
            ]);

            $serviceNote->delete();
        });

        return redirect()
            ->route('home')
            ->with('success', "Service note {$serviceNote->service_no} telah dipadam dari rekod aktif.");
    }

    public function pdf(ServiceNote $serviceNote)
    {
        $serviceNote = $this->resolveRouteServiceNote($serviceNote);
        $serviceNote->load(['customer', 'device']);

        $settings = Setting::query()
            ->pluck('value', 'key')
            ->all();

        $fileName = 'service-note-'
            . Str::slug($serviceNote->service_no)
            . '-'
            . Str::slug($serviceNote->customer?->name ?? 'customer')
            . '.pdf';

        $pdf = Pdf::loadView('service-notes.pdf', [
            'serviceNote' => $serviceNote,
            'settings' => $settings,
        ])->setPaper('a4', 'portrait');

        // Log the action before returning response.
        if (request()->has('print')) {
            $serviceNote->logs()->create([
                'action' => 'printed',
                'description' => "Service note {$serviceNote->service_no} PDF printed",
            ]);
            return $pdf->stream($fileName);
        }

        $serviceNote->logs()->create([
            'action' => 'downloaded',
            'description' => "Service note {$serviceNote->service_no} PDF downloaded",
        ]);

        return $pdf->download($fileName);
    }

    private function deviceTypes(): array
    {
        return ['Laptop', 'Desktop', 'Printer', 'Monitor', 'Phone', 'Tablet', 'Others'];
    }

    private function serviceNoteRules(): array
    {
        return [
            'received_date' => ['required', 'date'],
            'status' => ['required', Rule::in(ServiceNote::STATUSES)],
            'technician_name' => ['nullable', 'string', 'max:255'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_address' => ['nullable', 'string'],
            'device_type' => ['required', Rule::in($this->deviceTypes())],
            'device_type_other' => ['required_if:device_type,Others', 'nullable', 'string', 'max:255'],
            'brand_model' => ['required', 'string', 'max:255'],
            'serial_number' => ['nullable', 'string', 'max:255'],
            'specifications' => ['nullable', 'string'],
            'device_password' => ['nullable', 'string', 'max:255'],
            'reported_issue' => ['required', 'string'],
            'initial_diagnosis' => ['nullable', 'string'],
            'repair_action' => ['nullable', 'string'],
            'parts_replaced' => ['nullable', 'string'],
            'service_charge' => ['nullable', 'numeric', 'min:0'],
            'parts_charge' => ['nullable', 'numeric', 'min:0'],
            'warranty_duration' => ['nullable', 'integer', 'min:0'],
            'warranty_unit' => ['nullable', Rule::in(['Hari', 'Bulan'])],
            'warranty_note' => ['nullable', 'string'],
        ];
    }

    private function resolveRouteServiceNote(ServiceNote $serviceNote): ServiceNote
    {
        if ($serviceNote->exists) {
            return $serviceNote;
        }

        return ServiceNote::query()->findOrFail(request()->route('serviceNote'));
    }

}
