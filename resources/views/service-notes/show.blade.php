@extends('layouts.app')

@php
    $customer = $serviceNote->customer;
    $device = $serviceNote->device;

    $displayValue = static fn ($value, $fallback = '-') => filled($value) ? $value : $fallback;
    $errors = $errors ?? new \Illuminate\Support\ViewErrorBag();
@endphp

@section('content')
    @if (session('success'))
        <div class="mb-5 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-5 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <p class="font-semibold">Sila semak semula tindakan anda.</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <a href="{{ route('home') }}#search-records" class="text-sm font-semibold text-amber-700 hover:text-amber-800">Back to search</a>
            <p class="mt-3 text-sm font-semibold text-amber-600">Service note detail</p>
            <h2 class="mt-1 text-2xl font-semibold text-slate-950">{{ $serviceNote->service_no }}</h2>
            <p class="mt-2 text-sm text-slate-600">
                Received {{ $serviceNote->received_date?->format('Y-m-d') ?? '-' }}.
                Last updated {{ $serviceNote->updated_at?->format('Y-m-d H:i') ?? '-' }}.
            </p>
        </div>

        <div class="flex flex-col gap-2 sm:flex-row sm:flex-wrap lg:justify-end">
            <a href="{{ route('service-notes.edit', $serviceNote) }}" class="rounded-md bg-slate-950 px-4 py-2 text-center text-sm font-semibold text-white hover:bg-slate-800">Edit</a>
            <a href="{{ url('/service-notes/'.$serviceNote->id.'/pdf?print=1') }}" class="rounded-md border border-slate-300 bg-white px-4 py-2 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50">Print PDF</a>
            <a href="{{ url('/service-notes/'.$serviceNote->id.'/pdf') }}" class="rounded-md border border-slate-300 bg-white px-4 py-2 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50">Download PDF</a>
            <a href="{{ route('home') }}#service-note-form" class="rounded-md border border-slate-300 bg-white px-4 py-2 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50">New Form</a>
        </div>
    </div>

    <div class="grid gap-5 lg:grid-cols-[minmax(0,2fr)_minmax(300px,1fr)]">
        <div class="space-y-5">
            <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex flex-col gap-3 border-b border-slate-200 pb-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-950">Maklumat Service</h3>
                        <p class="mt-1 text-sm text-slate-600">Status semasa dan maklumat penerimaan.</p>
                    </div>
                    <span class="w-fit rounded-md bg-slate-100 px-3 py-1.5 text-sm font-semibold text-slate-800">{{ $serviceNote->status }}</span>
                </div>

                <dl class="mt-4 grid gap-4 md:grid-cols-3">
                    <div>
                        <dt class="text-xs font-semibold uppercase text-slate-500">No. Service</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-950">{{ $serviceNote->service_no }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase text-slate-500">Tarikh Terima</dt>
                        <dd class="mt-1 text-sm text-slate-800">{{ $serviceNote->received_date?->format('Y-m-d') ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase text-slate-500">Technician</dt>
                        <dd class="mt-1 text-sm text-slate-800">{{ $displayValue($serviceNote->technician_name) }}</dd>
                    </div>
                </dl>
            </section>

            <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-950">Maklumat Pelanggan</h3>
                <dl class="mt-4 grid gap-4 md:grid-cols-2">
                    <div>
                        <dt class="text-xs font-semibold uppercase text-slate-500">Nama</dt>
                        <dd class="mt-1 text-sm text-slate-800">{{ $displayValue($customer?->name) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase text-slate-500">No. Telefon</dt>
                        <dd class="mt-1 text-sm text-slate-800">{{ $displayValue($customer?->phone) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase text-slate-500">Email</dt>
                        <dd class="mt-1 text-sm text-slate-800">{{ $displayValue($customer?->email) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase text-slate-500">Alamat</dt>
                        <dd class="mt-1 whitespace-pre-line text-sm text-slate-800">{{ $displayValue($customer?->address) }}</dd>
                    </div>
                </dl>
            </section>

            <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-950">Maklumat Peranti</h3>
                <dl class="mt-4 grid gap-4 md:grid-cols-2">
                    <div>
                        <dt class="text-xs font-semibold uppercase text-slate-500">Jenis Peranti</dt>
                        <dd class="mt-1 text-sm text-slate-800">
                            {{ $displayValue($device?->device_type) }}
                            @if ($device?->device_type_other)
                                <span class="text-slate-500">({{ $device->device_type_other }})</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase text-slate-500">Jenama / Model</dt>
                        <dd class="mt-1 text-sm text-slate-800">{{ $displayValue($device?->brand_model) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase text-slate-500">Serial Number</dt>
                        <dd class="mt-1 text-sm text-slate-800">{{ $displayValue($device?->serial_number) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase text-slate-500">Password Peranti</dt>
                        <dd class="mt-1 text-sm font-semibold text-red-700">Disembunyikan secara default</dd>
                    </div>
                    <div class="md:col-span-2">
                        <dt class="text-xs font-semibold uppercase text-slate-500">Spesifikasi</dt>
                        <dd class="mt-1 whitespace-pre-line text-sm text-slate-800">{{ $displayValue($device?->specifications) }}</dd>
                    </div>
                </dl>
            </section>

            <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-950">Butiran Kerja</h3>
                <div class="mt-4 space-y-5">
                    <div>
                        <h4 class="text-sm font-semibold text-slate-900">Masalah Dilaporkan</h4>
                        <p class="mt-2 whitespace-pre-line rounded-md border border-slate-200 bg-slate-50 p-3 text-sm text-slate-800">{{ $displayValue($serviceNote->reported_issue) }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-slate-900">Pemeriksaan Awal</h4>
                        <p class="mt-2 whitespace-pre-line rounded-md border border-slate-200 bg-slate-50 p-3 text-sm text-slate-800">{{ $displayValue($serviceNote->initial_diagnosis) }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-slate-900">Kerja Baik Pulih</h4>
                        <p class="mt-2 whitespace-pre-line rounded-md border border-slate-200 bg-slate-50 p-3 text-sm text-slate-800">{{ $displayValue($serviceNote->repair_action) }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-slate-900">Alat Ganti</h4>
                        <p class="mt-2 whitespace-pre-line rounded-md border border-slate-200 bg-slate-50 p-3 text-sm text-slate-800">{{ $displayValue($serviceNote->parts_replaced) }}</p>
                    </div>
                </div>
            </section>
        </div>

        <aside class="space-y-5">
            <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-950">Kos Baiki</h3>
                <dl class="mt-4 space-y-3">
                    <div class="flex items-center justify-between gap-4">
                        <dt class="text-sm text-slate-600">Upah Servis</dt>
                        <dd class="text-sm font-semibold text-slate-900">RM {{ number_format((float) $serviceNote->service_charge, 2) }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <dt class="text-sm text-slate-600">Alat Ganti</dt>
                        <dd class="text-sm font-semibold text-slate-900">RM {{ number_format((float) $serviceNote->parts_charge, 2) }}</dd>
                    </div>
                    <div class="border-t border-slate-200 pt-3">
                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-sm font-semibold text-slate-950">Jumlah</dt>
                            <dd class="text-lg font-semibold text-slate-950">RM {{ number_format((float) $serviceNote->total_charge, 2) }}</dd>
                        </div>
                    </div>
                </dl>
            </section>

            <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-950">Waranti Servis</h3>
                <dl class="mt-4 space-y-4">
                    <div>
                        <dt class="text-xs font-semibold uppercase text-slate-500">Tempoh</dt>
                        <dd class="mt-1 text-sm text-slate-800">
                            @if ($serviceNote->warranty_duration)
                                {{ $serviceNote->warranty_duration }} {{ $serviceNote->warranty_unit ?: 'Hari' }}
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase text-slate-500">Nota</dt>
                        <dd class="mt-1 whitespace-pre-line text-sm text-slate-800">{{ $displayValue($serviceNote->warranty_note) }}</dd>
                    </div>
                </dl>
            </section>

            <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-950">Signature</h3>
                <div class="mt-4 space-y-4">
                    <div class="rounded-md border border-dashed border-slate-300 p-4">
                        <p class="text-sm font-semibold text-slate-900">Customer Signature</p>
                        @if ($serviceNote->customer_signature_path)
                            <div class="mt-3 rounded-md border border-slate-200 bg-slate-50 p-2">
                                <img src="{{ route('storage.public', ['path' => $serviceNote->customer_signature_path]) }}" alt="Customer signature" class="mx-auto h-20 max-w-full object-contain">
                            </div>
                            <p class="mt-2 text-sm font-medium text-emerald-700">Uploaded</p>
                        @else
                            <p class="mt-2 text-sm text-slate-600">Not uploaded</p>
                        @endif
                    </div>
                    <div class="rounded-md border border-dashed border-slate-300 p-4">
                        <p class="text-sm font-semibold text-slate-900">Technician Signature</p>
                        @if ($serviceNote->technician_signature_path)
                            <div class="mt-3 rounded-md border border-slate-200 bg-slate-50 p-2">
                                <img src="{{ route('storage.public', ['path' => $serviceNote->technician_signature_path]) }}" alt="Technician signature" class="mx-auto h-20 max-w-full object-contain">
                            </div>
                            <p class="mt-2 text-sm font-medium text-emerald-700">Uploaded</p>
                        @else
                            <p class="mt-2 text-sm text-slate-600">Not uploaded</p>
                        @endif
                    </div>
                </div>
            </section>

            <section class="rounded-lg border border-red-200 bg-red-50 p-5 shadow-sm">
                <h3 class="text-lg font-semibold text-red-950">Delete Service Note</h3>
                <p class="mt-2 text-sm text-red-800">
                    Tindakan ini akan memadam rekod dari senarai aktif menggunakan soft delete. Anggap tindakan ini irreversible sehingga fungsi restore dibina.
                </p>
                <form action="{{ route('service-notes.destroy', $serviceNote) }}" method="POST" class="mt-4 space-y-3" onsubmit="return confirm('Confirm delete service note {{ $serviceNote->service_no }}?')">
                    @csrf
                    @method('DELETE')

                    <div>
                        <label for="delete_confirmation" class="text-sm font-medium text-red-950">Taip No. Service untuk sahkan</label>
                        <input id="delete_confirmation" name="delete_confirmation" type="text" placeholder="{{ $serviceNote->service_no }}" autocomplete="off" class="mt-2 w-full rounded-md border border-red-300 bg-white px-3 py-2 text-sm text-red-950">
                    </div>

                    <button type="submit" class="w-full rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white hover:bg-red-800">Delete Service Note</button>
                </form>
            </section>
        </aside>
    </div>
@endsection
