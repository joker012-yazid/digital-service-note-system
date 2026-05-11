@extends('layouts.app')

@php
    $customer = $serviceNote->customer;
    $device = $serviceNote->device;
    $errors = $errors ?? new \Illuminate\Support\ViewErrorBag();
@endphp

@section('content')
    @if ($errors->any())
        <div class="mb-5 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <p class="font-semibold">Sila semak semula maklumat borang.</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <a href="{{ route('service-notes.show', $serviceNote) }}" class="text-sm font-semibold text-amber-700 hover:text-amber-800">Back to detail</a>
            <p class="mt-3 text-sm font-semibold text-amber-600">Edit service note</p>
            <h2 class="mt-1 text-2xl font-semibold text-slate-950">{{ $serviceNote->service_no }}</h2>
        </div>

        <div class="flex flex-col gap-2 sm:flex-row sm:flex-wrap lg:justify-end">
            <a href="{{ route('service-notes.show', $serviceNote) }}" class="rounded-md border border-slate-300 bg-white px-4 py-2 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</a>
            <a href="{{ route('home') }}#search-records" class="rounded-md border border-slate-300 bg-white px-4 py-2 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50">Search Records</a>
        </div>
    </div>

    <section class="rounded-lg border border-slate-200 bg-white shadow-sm">
        <form action="{{ route('service-notes.update', $serviceNote) }}" method="POST" class="service-note-form" data-service-note-form>
            @csrf
            @method('PUT')

            <div class="space-y-6 p-5">
                <fieldset>
                    <legend class="text-base font-semibold text-slate-950">1. Maklumat Service</legend>
                    <div class="mt-3 grid gap-4 md:grid-cols-4">
                        <div>
                            <label for="service_no" class="text-sm font-medium text-slate-700">No. Service</label>
                            <input id="service_no" name="service_no" type="text" value="{{ $serviceNote->service_no }}" readonly class="mt-2 w-full rounded-md border border-slate-300 bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-800">
                        </div>
                        <div>
                            <label for="received_date" class="text-sm font-medium text-slate-700">Tarikh Terima <span class="text-red-600">*</span></label>
                            <input id="received_date" name="received_date" type="date" value="{{ old('received_date', $serviceNote->received_date?->format('Y-m-d')) }}" required class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label for="status" class="text-sm font-medium text-slate-700">Status Service <span class="text-red-600">*</span></label>
                            <select id="status" name="status" required class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}" @selected(old('status', $serviceNote->status) === $status)>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="technician_name" class="text-sm font-medium text-slate-700">Nama Technician</label>
                            <input id="technician_name" name="technician_name" type="text" value="{{ old('technician_name', $serviceNote->technician_name) }}" autocomplete="off" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                        </div>
                    </div>
                </fieldset>

                <fieldset class="border-t border-slate-200 pt-6">
                    <legend class="text-base font-semibold text-slate-950">2. Maklumat Pelanggan</legend>
                    <div class="mt-3 grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="customer_name" class="text-sm font-medium text-slate-700">Nama Pelanggan <span class="text-red-600">*</span></label>
                            <input id="customer_name" name="customer_name" type="text" value="{{ old('customer_name', $customer?->name) }}" required autocomplete="name" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label for="customer_phone" class="text-sm font-medium text-slate-700">No. Telefon <span class="text-red-600">*</span></label>
                            <input id="customer_phone" name="customer_phone" type="tel" value="{{ old('customer_phone', $customer?->phone) }}" required autocomplete="tel" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label for="customer_email" class="text-sm font-medium text-slate-700">Email</label>
                            <input id="customer_email" name="customer_email" type="email" value="{{ old('customer_email', $customer?->email) }}" autocomplete="email" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label for="customer_address" class="text-sm font-medium text-slate-700">Alamat</label>
                            <textarea id="customer_address" name="customer_address" rows="3" autocomplete="street-address" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">{{ old('customer_address', $customer?->address) }}</textarea>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="border-t border-slate-200 pt-6">
                    <legend class="text-base font-semibold text-slate-950">3. Maklumat Peranti</legend>
                    <div class="mt-3 grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="device_type" class="text-sm font-medium text-slate-700">Jenis Peranti <span class="text-red-600">*</span></label>
                            <select id="device_type" name="device_type" required data-device-type class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                                @foreach ($deviceTypes as $deviceType)
                                    <option value="{{ $deviceType }}" @selected(old('device_type', $device?->device_type) === $deviceType)>{{ $deviceType }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div data-device-type-other-wrap class="hidden">
                            <label for="device_type_other" class="text-sm font-medium text-slate-700">Jenis Peranti Lain <span class="text-red-600">*</span></label>
                            <input id="device_type_other" name="device_type_other" type="text" value="{{ old('device_type_other', $device?->device_type_other) }}" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label for="brand_model" class="text-sm font-medium text-slate-700">Jenama / Model <span class="text-red-600">*</span></label>
                            <input id="brand_model" name="brand_model" type="text" value="{{ old('brand_model', $device?->brand_model) }}" required class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label for="serial_number" class="text-sm font-medium text-slate-700">Serial Number</label>
                            <input id="serial_number" name="serial_number" type="text" value="{{ old('serial_number', $device?->serial_number) }}" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label for="specifications" class="text-sm font-medium text-slate-700">Spesifikasi</label>
                            <textarea id="specifications" name="specifications" rows="3" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">{{ old('specifications', $device?->specifications) }}</textarea>
                        </div>
                        <div>
                            <label for="device_password" class="text-sm font-medium text-slate-700">Password Peranti</label>
                            <input id="device_password" name="device_password" type="text" value="{{ old('device_password') }}" autocomplete="off" placeholder="Kosongkan jika tidak mahu tukar" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                            <p class="mt-2 text-xs font-medium text-red-700">Data ini sensitif. Nilai asal tidak dipaparkan.</p>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="border-t border-slate-200 pt-6">
                    <legend class="text-base font-semibold text-slate-950">4. Masalah Dilaporkan</legend>
                    <div class="mt-3">
                        <label for="reported_issue" class="text-sm font-medium text-slate-700">Masalah Dilaporkan <span class="text-red-600">*</span></label>
                        <textarea id="reported_issue" name="reported_issue" rows="4" required class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">{{ old('reported_issue', $serviceNote->reported_issue) }}</textarea>
                    </div>
                </fieldset>

                <fieldset class="border-t border-slate-200 pt-6">
                    <legend class="text-base font-semibold text-slate-950">5. Pemeriksaan Awal</legend>
                    <div class="mt-3">
                        <label for="initial_diagnosis" class="text-sm font-medium text-slate-700">Pemeriksaan Awal</label>
                        <textarea id="initial_diagnosis" name="initial_diagnosis" rows="4" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">{{ old('initial_diagnosis', $serviceNote->initial_diagnosis) }}</textarea>
                    </div>
                </fieldset>

                <fieldset class="border-t border-slate-200 pt-6">
                    <legend class="text-base font-semibold text-slate-950">6. Kerja Baik Pulih</legend>
                    <div class="mt-3">
                        <label for="repair_action" class="text-sm font-medium text-slate-700">Kerja Baik Pulih</label>
                        <textarea id="repair_action" name="repair_action" rows="4" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">{{ old('repair_action', $serviceNote->repair_action) }}</textarea>
                    </div>
                </fieldset>

                <fieldset class="border-t border-slate-200 pt-6">
                    <legend class="text-base font-semibold text-slate-950">7. Alat Ganti & Kos</legend>
                    <div class="mt-3">
                        <label for="parts_replaced" class="text-sm font-medium text-slate-700">Alat Ganti</label>
                        <textarea id="parts_replaced" name="parts_replaced" rows="3" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">{{ old('parts_replaced', $serviceNote->parts_replaced) }}</textarea>
                    </div>
                    <div class="mt-3 grid gap-4 md:grid-cols-3">
                        <div>
                            <label for="service_charge" class="text-sm font-medium text-slate-700">Upah Servis (RM)</label>
                            <input id="service_charge" name="service_charge" type="number" min="0" step="0.01" value="{{ old('service_charge', $serviceNote->service_charge) }}" data-service-charge class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label for="parts_charge" class="text-sm font-medium text-slate-700">Kos Alat Ganti (RM)</label>
                            <input id="parts_charge" name="parts_charge" type="number" min="0" step="0.01" value="{{ old('parts_charge', $serviceNote->parts_charge) }}" data-parts-charge class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label for="total_charge" class="text-sm font-medium text-slate-700">Jumlah (RM)</label>
                            <input id="total_charge" name="total_charge" type="text" value="{{ number_format((float) $serviceNote->total_charge, 2, '.', '') }}" readonly data-total-charge class="mt-2 w-full rounded-md border border-slate-300 bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-950">
                        </div>
                    </div>
                </fieldset>

                <fieldset class="border-t border-slate-200 pt-6">
                    <legend class="text-base font-semibold text-slate-950">8. Warranty & Signature</legend>
                    <div class="mt-3 grid gap-4 md:grid-cols-3">
                        <div>
                            <label for="warranty_duration" class="text-sm font-medium text-slate-700">Warranty Service</label>
                            <input id="warranty_duration" name="warranty_duration" type="number" min="0" value="{{ old('warranty_duration', $serviceNote->warranty_duration) }}" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label for="warranty_unit" class="text-sm font-medium text-slate-700">Unit Warranty</label>
                            <select id="warranty_unit" name="warranty_unit" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                                <option value="Hari" @selected(old('warranty_unit', $serviceNote->warranty_unit ?: 'Hari') === 'Hari')>Hari</option>
                                <option value="Bulan" @selected(old('warranty_unit', $serviceNote->warranty_unit) === 'Bulan')>Bulan</option>
                            </select>
                        </div>
                        <div>
                            <label for="warranty_note" class="text-sm font-medium text-slate-700">Nota Warranty</label>
                            <textarea id="warranty_note" name="warranty_note" rows="3" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">{{ old('warranty_note', $serviceNote->warranty_note) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-5 grid gap-4 md:grid-cols-2">
                        @include('partials.signature-pad', [
                            'title' => 'Customer Signature',
                            'caption' => 'Pelanggan',
                            'name' => 'customer_signature_data',
                            'signaturePath' => $serviceNote->customer_signature_path,
                        ])
                        @include('partials.signature-pad', [
                            'title' => 'Technician Signature',
                            'caption' => 'Disahkan Oleh',
                            'name' => 'technician_signature_data',
                            'signaturePath' => $serviceNote->technician_signature_path,
                        ])
                    </div>
                </fieldset>
            </div>

            <div class="sticky bottom-0 border-t border-slate-200 bg-white p-4">
                <div class="flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:justify-end">
                    <button type="submit" class="rounded-md bg-slate-950 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Update Service Note</button>
                    <a href="{{ route('service-notes.show', $serviceNote) }}" class="rounded-md border border-slate-300 px-4 py-2 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</a>
                </div>
            </div>
        </form>
    </section>
@endsection
