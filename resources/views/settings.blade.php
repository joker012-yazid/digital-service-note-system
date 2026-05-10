@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="mb-6">
        <p class="text-sm font-semibold text-amber-600">PDF company profile</p>
        <h2 class="mt-1 text-2xl font-semibold text-slate-950">Company Settings</h2>
    </div>

    @if (session('status'))
        <div class="mb-5 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-5 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <p class="font-semibold">Sila semak semula maklumat settings.</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('settings.update') }}" class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
        @csrf
        @method('PUT')

        <div class="grid gap-4 md:grid-cols-2">
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-slate-700" for="company_name">Company Name <span class="text-red-600">*</span></label>
                <input type="text" name="company_name" id="company_name" value="{{ old('company_name', $settings['company_name'] ?? '') }}" required class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
            </div>

            <div>
                <label class="text-sm font-medium text-slate-700" for="phone">Phone <span class="text-red-600">*</span></label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $settings['phone'] ?? '') }}" required class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
            </div>

            <div>
                <label class="text-sm font-medium text-slate-700" for="office_phone">Office Phone</label>
                <input type="text" name="office_phone" id="office_phone" value="{{ old('office_phone', $settings['office_phone'] ?? '') }}" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
            </div>

            <div class="md:col-span-2">
                <label class="text-sm font-medium text-slate-700" for="support_email">Support Email <span class="text-red-600">*</span></label>
                <input type="email" name="support_email" id="support_email" value="{{ old('support_email', $settings['support_email'] ?? '') }}" required class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
            </div>

            <div class="md:col-span-2">
                <label class="text-sm font-medium text-slate-700" for="address">Address <span class="text-red-600">*</span></label>
                <textarea name="address" id="address" rows="3" required class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">{{ old('address', $settings['address'] ?? '') }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label class="text-sm font-medium text-slate-700" for="default_warranty_note">Default Warranty Note</label>
                <textarea name="default_warranty_note" id="default_warranty_note" rows="3" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">{{ old('default_warranty_note', $settings['default_warranty_note'] ?? '') }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label class="text-sm font-medium text-slate-700" for="footer_note">Footer Note</label>
                <textarea name="footer_note" id="footer_note" rows="3" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">{{ old('footer_note', $settings['footer_note'] ?? '') }}</textarea>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="rounded-md bg-slate-950 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                Save Settings
            </button>
        </div>
    </form>
</div>
@endsection
