@extends('layouts.app')

@php
    $statuses = $statuses ?? [
        'Received',
        'Checking',
        'Waiting Customer Approval',
        'In Progress',
        'Waiting Parts',
        'Completed',
        'Collected',
        'Cancelled',
    ];

    $deviceTypes = $deviceTypes ?? ['Laptop', 'Desktop', 'Printer', 'Monitor', 'Phone', 'Tablet', 'Others'];
    $searchFilters = $searchFilters ?? [
        'keyword' => '',
        'status' => '',
        'device_type' => '',
        'received_from' => '',
        'received_to' => '',
    ];
@endphp

@section('content')
    @if (session('success'))
        <div class="mb-5 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

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

    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-semibold text-amber-600">Service counter</p>
            <h2 class="mt-1 text-2xl font-semibold text-slate-950">Service Note Form</h2>
        </div>
        <div class="rounded-md border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-700">
            No. Service: <span class="text-slate-950">Auto</span>
        </div>
    </div>

    <div class="fallback-grid grid gap-5 lg:grid-cols-[minmax(0,2fr)_minmax(320px,1fr)]">
        <section id="service-note-form" class="fallback-panel rounded-lg border border-slate-200 bg-white shadow-sm">
            <form action="{{ route('service-notes.store') }}" method="POST" class="service-note-form" data-service-note-form>
                @csrf

                <div class="space-y-6 p-5">
                    <fieldset>
                        <legend class="text-base font-semibold text-slate-950">1. Maklumat Service</legend>
                        <div class="mt-3 grid gap-4 md:grid-cols-4">
                            <div>
                                <label for="service_no" class="text-sm font-medium text-slate-700">No. Service</label>
                                <input id="service_no" name="service_no" type="text" value="Auto-generated" readonly class="mt-2 w-full rounded-md border border-slate-300 bg-slate-100 px-3 py-2 text-sm text-slate-700">
                            </div>
                            <div>
                                <label for="received_date" class="text-sm font-medium text-slate-700">Tarikh Terima <span class="text-red-600">*</span></label>
                                <input id="received_date" name="received_date" type="date" value="{{ old('received_date', now()->toDateString()) }}" required class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label for="status" class="text-sm font-medium text-slate-700">Status Service <span class="text-red-600">*</span></label>
                                <select id="status" name="status" required class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}" @selected(old('status', 'Received') === $status)>{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="technician_name" class="text-sm font-medium text-slate-700">Nama Technician</label>
                                <input id="technician_name" name="technician_name" type="text" value="{{ old('technician_name') }}" autocomplete="off" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border-t border-slate-200 pt-6">
                        <legend class="text-base font-semibold text-slate-950">2. Maklumat Pelanggan</legend>
                        <div class="mt-3 grid gap-4 md:grid-cols-2">
                            <div>
                                <label for="customer_name" class="text-sm font-medium text-slate-700">Nama Pelanggan <span class="text-red-600">*</span></label>
                                <input id="customer_name" name="customer_name" type="text" value="{{ old('customer_name') }}" required autocomplete="name" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label for="customer_phone" class="text-sm font-medium text-slate-700">No. Telefon <span class="text-red-600">*</span></label>
                                <input id="customer_phone" name="customer_phone" type="tel" value="{{ old('customer_phone') }}" required autocomplete="tel" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label for="customer_email" class="text-sm font-medium text-slate-700">Email</label>
                                <input id="customer_email" name="customer_email" type="email" value="{{ old('customer_email') }}" autocomplete="email" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label for="customer_address" class="text-sm font-medium text-slate-700">Alamat</label>
                                <textarea id="customer_address" name="customer_address" rows="3" autocomplete="street-address" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">{{ old('customer_address') }}</textarea>
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
                                        <option value="{{ $deviceType }}" @selected(old('device_type', 'Laptop') === $deviceType)>{{ $deviceType }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div data-device-type-other-wrap class="hidden">
                                <label for="device_type_other" class="text-sm font-medium text-slate-700">Jenis Peranti Lain <span class="text-red-600">*</span></label>
                                <input id="device_type_other" name="device_type_other" type="text" value="{{ old('device_type_other') }}" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label for="brand_model" class="text-sm font-medium text-slate-700">Jenama / Model <span class="text-red-600">*</span></label>
                                <input id="brand_model" name="brand_model" type="text" value="{{ old('brand_model') }}" required class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label for="serial_number" class="text-sm font-medium text-slate-700">Serial Number</label>
                                <input id="serial_number" name="serial_number" type="text" value="{{ old('serial_number') }}" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label for="specifications" class="text-sm font-medium text-slate-700">Spesifikasi</label>
                                <textarea id="specifications" name="specifications" rows="3" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">{{ old('specifications') }}</textarea>
                            </div>
                            <div>
                                <label for="device_password" class="text-sm font-medium text-slate-700">Password Peranti</label>
                                <input id="device_password" name="device_password" type="text" value="{{ old('device_password') }}" autocomplete="off" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                                <p class="mt-2 text-xs font-medium text-red-700">Data ini sensitif. Jangan isi jika tidak perlu.</p>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border-t border-slate-200 pt-6">
                        <legend class="text-base font-semibold text-slate-950">4. Masalah Dilaporkan</legend>
                        <div class="mt-3">
                            <label for="reported_issue" class="text-sm font-medium text-slate-700">Masalah Dilaporkan <span class="text-red-600">*</span></label>
                            <textarea id="reported_issue" name="reported_issue" rows="4" required placeholder="Contoh: Laptop tidak boleh ON, Windows corrupt, keyboard rosak..." class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">{{ old('reported_issue') }}</textarea>
                        </div>
                    </fieldset>

                    <fieldset class="border-t border-slate-200 pt-6">
                        <legend class="text-base font-semibold text-slate-950">5. Pemeriksaan Awal</legend>
                        <div class="mt-3">
                            <label for="initial_diagnosis" class="text-sm font-medium text-slate-700">Pemeriksaan Awal</label>
                            <textarea id="initial_diagnosis" name="initial_diagnosis" rows="4" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">{{ old('initial_diagnosis') }}</textarea>
                        </div>
                    </fieldset>

                    <fieldset class="border-t border-slate-200 pt-6">
                        <legend class="text-base font-semibold text-slate-950">6. Kerja Baik Pulih</legend>
                        <div class="mt-3">
                            <label for="repair_action" class="text-sm font-medium text-slate-700">Kerja Baik Pulih</label>
                            <textarea id="repair_action" name="repair_action" rows="4" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">{{ old('repair_action') }}</textarea>
                        </div>
                    </fieldset>

                    <fieldset class="border-t border-slate-200 pt-6">
                        <legend class="text-base font-semibold text-slate-950">7. Alat Ganti & Kos</legend>
                        <div class="mt-3">
                            <label for="parts_replaced" class="text-sm font-medium text-slate-700">Alat Ganti</label>
                            <textarea id="parts_replaced" name="parts_replaced" rows="3" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">{{ old('parts_replaced') }}</textarea>
                        </div>
                        <div class="mt-3 grid gap-4 md:grid-cols-3">
                            <div>
                                <label for="service_charge" class="text-sm font-medium text-slate-700">Upah Servis (RM)</label>
                                <input id="service_charge" name="service_charge" type="number" min="0" step="0.01" value="{{ old('service_charge', '0.00') }}" data-service-charge class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label for="parts_charge" class="text-sm font-medium text-slate-700">Kos Alat Ganti (RM)</label>
                                <input id="parts_charge" name="parts_charge" type="number" min="0" step="0.01" value="{{ old('parts_charge', '0.00') }}" data-parts-charge class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label for="total_charge" class="text-sm font-medium text-slate-700">Jumlah (RM)</label>
                                <input id="total_charge" name="total_charge" type="text" value="0.00" readonly data-total-charge class="mt-2 w-full rounded-md border border-slate-300 bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-950">
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border-t border-slate-200 pt-6">
                        <legend class="text-base font-semibold text-slate-950">8. Warranty & Signature</legend>
                        <div class="mt-3 grid gap-4 md:grid-cols-3">
                            <div>
                                <label for="warranty_duration" class="text-sm font-medium text-slate-700">Warranty Service</label>
                                <input id="warranty_duration" name="warranty_duration" type="number" min="0" value="{{ old('warranty_duration') }}" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label for="warranty_unit" class="text-sm font-medium text-slate-700">Unit Warranty</label>
                                <select id="warranty_unit" name="warranty_unit" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                                    <option value="Hari" @selected(old('warranty_unit', 'Hari') === 'Hari')>Hari</option>
                                    <option value="Bulan" @selected(old('warranty_unit') === 'Bulan')>Bulan</option>
                                </select>
                            </div>
                            <div>
                                <label for="warranty_note" class="text-sm font-medium text-slate-700">Nota Warranty</label>
                                <textarea id="warranty_note" name="warranty_note" rows="3" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">{{ old('warranty_note', 'Tidak termasuk kerosakan fizikal / liquid damage.') }}</textarea>
                            </div>
                        </div>

                        <div class="mt-5 grid gap-4 md:grid-cols-2">
                            <div class="rounded-md border border-dashed border-slate-300 p-4">
                                <p class="text-sm font-semibold text-slate-900">Customer Signature</p>
                                <div class="mt-8 border-t border-slate-300 pt-2 text-center text-sm text-slate-600">Pelanggan</div>
                            </div>
                            <div class="rounded-md border border-dashed border-slate-300 p-4">
                                <p class="text-sm font-semibold text-slate-900">Technician Signature</p>
                                <div class="mt-8 border-t border-slate-300 pt-2 text-center text-sm text-slate-600">Disahkan Oleh</div>
                            </div>
                        </div>
                    </fieldset>
                </div>

                <div class="sticky bottom-0 border-t border-slate-200 bg-white p-4">
                    <div class="flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:justify-end">
                        <button type="submit" name="action" value="save" class="rounded-md bg-slate-950 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Save Service Note</button>
                        <button type="submit" name="action" value="save_pdf" class="rounded-md bg-amber-500 px-4 py-2 text-sm font-semibold text-slate-950 hover:bg-amber-400">Save & Download PDF</button>
                        <button type="reset" class="rounded-md border border-red-200 px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-50">Clear Form / New Form</button>
                    </div>
                </div>
            </form>
        </section>

        <aside id="search-records" class="fallback-panel h-fit rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-950">Search Old Records</h3>
            <p class="mt-1 text-sm text-slate-600">Cari rekod lama pada halaman yang sama.</p>

            <form action="{{ route('home') }}" method="GET" class="mt-5 space-y-4">
                <div>
                    <label for="keyword" class="text-sm font-medium text-slate-700">Keyword</label>
                    <input id="keyword" name="keyword" type="search" value="{{ $searchFilters['keyword'] }}" placeholder="No. Service, nama, telefon, email, model..." class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                </div>
                <div>
                    <label for="search_status" class="text-sm font-medium text-slate-700">Status</label>
                    <select id="search_status" name="status" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                        <option value="">Semua Status</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" @selected($searchFilters['status'] === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="search_device_type" class="text-sm font-medium text-slate-700">Jenis Peranti</label>
                    <select id="search_device_type" name="device_type" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                        <option value="">Semua Peranti</option>
                        @foreach ($deviceTypes as $deviceType)
                            <option value="{{ $deviceType }}" @selected($searchFilters['device_type'] === $deviceType)>{{ $deviceType }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="received_from" class="text-sm font-medium text-slate-700">Tarikh Dari</label>
                        <input id="received_from" name="received_from" type="date" value="{{ $searchFilters['received_from'] }}" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label for="received_to" class="text-sm font-medium text-slate-700">Tarikh Hingga</label>
                        <input id="received_to" name="received_to" type="date" value="{{ $searchFilters['received_to'] }}" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                    </div>
                </div>
                <div class="flex flex-col gap-2 sm:flex-row">
                    <button type="submit" class="rounded-md bg-slate-950 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Search</button>
                    <a href="{{ route('home') }}#search-records" class="rounded-md border border-slate-300 px-4 py-2 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50">Reset</a>
                </div>
            </form>
        </aside>
    </div>

    <section class="mt-6 rounded-lg border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-2 border-b border-slate-200 p-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-slate-950">Search Results</h3>
                <p class="mt-1 text-sm text-slate-600">Paparan 10 rekod setiap halaman, termasuk rekod terkini jika tiada filter digunakan.</p>
            </div>
            @isset($serviceNotes)
                <div class="text-sm font-semibold text-slate-700">
                    {{ $serviceNotes->total() }} rekod
                </div>
            @endisset
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase text-slate-500">
                    <tr>
                        <th scope="col" class="px-4 py-3">No. Service</th>
                        <th scope="col" class="px-4 py-3">Received Date</th>
                        <th scope="col" class="px-4 py-3">Customer Name</th>
                        <th scope="col" class="px-4 py-3">Phone</th>
                        <th scope="col" class="px-4 py-3">Device Type</th>
                        <th scope="col" class="px-4 py-3">Brand / Model</th>
                        <th scope="col" class="px-4 py-3">Status</th>
                        <th scope="col" class="px-4 py-3">Technician</th>
                        <th scope="col" class="px-4 py-3 text-right">Total Charge</th>
                        <th scope="col" class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($serviceNotes ?? [] as $serviceNote)
                        <tr class="align-top">
                            <td class="whitespace-nowrap px-4 py-3 font-semibold text-slate-950">{{ $serviceNote->service_no }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-slate-700">{{ $serviceNote->received_date?->format('Y-m-d') }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $serviceNote->customer?->name ?? '-' }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-slate-700">{{ $serviceNote->customer?->phone ?? '-' }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-slate-700">
                                {{ $serviceNote->device?->device_type ?? '-' }}
                                @if ($serviceNote->device?->device_type_other)
                                    <span class="text-slate-500">({{ $serviceNote->device->device_type_other }})</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-slate-700">{{ $serviceNote->device?->brand_model ?? '-' }}</td>
                            <td class="whitespace-nowrap px-4 py-3">
                                <span class="rounded-md bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-700">{{ $serviceNote->status }}</span>
                            </td>
                            <td class="px-4 py-3 text-slate-700">{{ $serviceNote->technician_name ?: '-' }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-right font-semibold text-slate-900">RM {{ number_format((float) $serviceNote->total_charge, 2) }}</td>
                            <td class="px-4 py-3">
                                <div class="flex min-w-56 flex-wrap gap-2">
                                    <a href="{{ route('service-notes.show', $serviceNote) }}" class="rounded-md border border-slate-300 px-2.5 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">View</a>
                                    <a href="{{ route('service-notes.edit', $serviceNote) }}" class="rounded-md border border-slate-300 px-2.5 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">Edit</a>
                                    <a href="{{ url('/service-notes/'.$serviceNote->id.'/pdf?print=1') }}" class="rounded-md border border-slate-300 px-2.5 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">Print PDF</a>
                                    <a href="{{ url('/service-notes/'.$serviceNote->id.'/pdf') }}" class="rounded-md border border-slate-300 px-2.5 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">Download PDF</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-4 py-8 text-center text-sm text-slate-500">Tiada rekod dijumpai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @isset($serviceNotes)
            @if ($serviceNotes->hasPages())
                <div class="border-t border-slate-200 p-4">
                    {{ $serviceNotes->links() }}
                </div>
            @endif
        @endisset
    </section>
@endsection
