<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $settings = Setting::query()
            ->pluck('value', 'key')
            ->all();

        return view('settings', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'phone' => ['required', 'string', 'max:50'],
            'office_phone' => ['nullable', 'string', 'max:50'],
            'support_email' => ['required', 'email', 'max:255'],
            'footer_note' => ['nullable', 'string'],
            'default_warranty_note' => ['nullable', 'string'],
        ]);

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()
            ->route('settings')
            ->with('status', 'Settings updated successfully.');
    }
}
