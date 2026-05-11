@php
    $customer = $serviceNote->customer;
    $device = $serviceNote->device;
    $setting = static fn (string $key, string $fallback = '') => $settings[$key] ?? $fallback;
    $display = static fn ($value, string $fallback = '-') => filled($value) ? $value : $fallback;
    $signatureFile = static function (?string $path): ?string {
        if (! $path) {
            return null;
        }

        $filePath = \Illuminate\Support\Facades\Storage::disk('public')->path($path);

        return file_exists($filePath) ? $filePath : null;
    };
@endphp
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="utf-8">
    <title>{{ $serviceNote->service_no }}</title>
    <style>
        @page {
            margin: 18mm 14mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            color: #111827;
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10px;
            line-height: 1.35;
            margin: 0;
        }

        h1,
        h2,
        h3,
        p {
            margin: 0;
        }

        .header-table,
        .info-table,
        .section-table,
        .charge-table,
        .signature-table {
            border-collapse: collapse;
            width: 100%;
        }

        .header-table td {
            vertical-align: top;
        }

        .brand {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: .3px;
        }

        .report-title {
            font-size: 16px;
            font-weight: 700;
            text-align: right;
            text-transform: uppercase;
        }

        .muted {
            color: #4b5563;
        }

        .service-box {
            border: 1px solid #111827;
            padding: 7px 9px;
            text-align: right;
        }

        .service-no {
            font-size: 13px;
            font-weight: 700;
            margin-top: 3px;
        }

        .block {
            margin-top: 10px;
        }

        .section-title {
            background: #111827;
            color: #ffffff;
            font-size: 10px;
            font-weight: 700;
            padding: 5px 7px;
            text-transform: uppercase;
        }

        .info-table th,
        .info-table td,
        .section-table td,
        .charge-table th,
        .charge-table td,
        .signature-table td {
            border: 1px solid #111827;
            padding: 6px 7px;
            vertical-align: top;
        }

        .info-table th,
        .charge-table th {
            background: #f3f4f6;
            font-size: 9px;
            font-weight: 700;
            text-align: left;
            text-transform: uppercase;
            width: 24%;
        }

        .section-table td {
            height: 62px;
        }

        .label {
            color: #4b5563;
            display: block;
            font-size: 8px;
            font-weight: 700;
            margin-bottom: 3px;
            text-transform: uppercase;
        }

        .value {
            white-space: pre-line;
        }

        .charge-table td:last-child,
        .charge-table th:last-child {
            text-align: right;
        }

        .total-row td {
            font-size: 12px;
            font-weight: 700;
        }

        .warranty-note {
            border: 1px solid #111827;
            min-height: 42px;
            padding: 7px;
        }

        .signature-line {
            border-top: 1px solid #111827;
            margin-top: 38px;
            padding-top: 5px;
            text-align: center;
        }

        .signature-image {
            display: block;
            height: 42px;
            margin: 0 auto 4px;
            max-width: 220px;
            object-fit: contain;
        }

        .footer {
            border-top: 1px solid #9ca3af;
            color: #4b5563;
            font-size: 8px;
            margin-top: 11px;
            padding-top: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="width: 58%;">
                <div class="brand">{{ $setting('company_name', 'LaptopPro') }}</div>
                <p class="muted">{{ $display($setting('address', 'Address placeholder')) }}</p>
                <p class="muted">Mobile: {{ $display($setting('phone', 'Phone placeholder')) }}</p>
                <p class="muted">Office: {{ $display($setting('office_phone', 'Office phone placeholder')) }}</p>
                <p class="muted">Email: {{ $display($setting('support_email', 'support@example.com')) }}</p>
            </td>
            <td style="width: 42%;">
                <div class="report-title">Service Report</div>
                <div class="service-box block">
                    <span class="label">No. Service</span>
                    <div class="service-no">{{ $serviceNote->service_no }}</div>
                    <p class="muted">Tarikh Terima: {{ $serviceNote->received_date?->format('Y-m-d') ?? '-' }}</p>
                    <p class="muted">Status: {{ $serviceNote->status }}</p>
                </div>
            </td>
        </tr>
    </table>

    <table class="info-table block">
        <tr>
            <td class="section-title" colspan="2">Maklumat Pelanggan / Customer Information</td>
            <td class="section-title" colspan="2">Maklumat Peranti / Device Information</td>
        </tr>
        <tr>
            <th>Nama</th>
            <td>{{ $display($customer?->name) }}</td>
            <th>Jenis</th>
            <td>
                {{ $display($device?->device_type) }}
                @if ($device?->device_type_other)
                    ({{ $device->device_type_other }})
                @endif
            </td>
        </tr>
        <tr>
            <th>Telefon</th>
            <td>{{ $display($customer?->phone) }}</td>
            <th>Model</th>
            <td>{{ $display($device?->brand_model) }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $display($customer?->email) }}</td>
            <th>Serial No.</th>
            <td>{{ $display($device?->serial_number) }}</td>
        </tr>
        <tr>
            <th>Alamat</th>
            <td class="value">{{ $display($customer?->address) }}</td>
            <th>Spesifikasi</th>
            <td class="value">{{ $display($device?->specifications) }}</td>
        </tr>
    </table>

    <div class="block">
        <div class="section-title">Masalah Dilaporkan / Customer Reported Issue</div>
        <table class="section-table">
            <tr>
                <td class="value">{{ $display($serviceNote->reported_issue) }}</td>
            </tr>
        </table>
    </div>

    <div class="block">
        <div class="section-title">Pemeriksaan Awal / Initial Diagnosis</div>
        <table class="section-table">
            <tr>
                <td class="value">{{ $display($serviceNote->initial_diagnosis) }}</td>
            </tr>
        </table>
    </div>

    <div class="block">
        <div class="section-title">Kerja Baik Pulih / Repair Action Taken</div>
        <table class="section-table">
            <tr>
                <td class="value">{{ $display($serviceNote->repair_action) }}</td>
            </tr>
        </table>
    </div>

    <table class="charge-table block">
        <tr>
            <td class="section-title" colspan="2">Alat Ganti / Parts Replaced</td>
            <td class="section-title" colspan="2">Kos Baiki / Charges</td>
        </tr>
        <tr>
            <td class="value" colspan="2" rowspan="4">{{ $display($serviceNote->parts_replaced) }}</td>
            <th>Upah Servis</th>
            <td>RM {{ number_format((float) $serviceNote->service_charge, 2) }}</td>
        </tr>
        <tr>
            <th>Alat Ganti</th>
            <td>RM {{ number_format((float) $serviceNote->parts_charge, 2) }}</td>
        </tr>
        <tr class="total-row">
            <th>Jumlah</th>
            <td>RM {{ number_format((float) $serviceNote->total_charge, 2) }}</td>
        </tr>
        <tr>
            <th>Technician</th>
            <td>{{ $display($serviceNote->technician_name) }}</td>
        </tr>
    </table>

    <div class="block">
        <div class="section-title">Waranti Servis / Service Warranty</div>
        <div class="warranty-note">
            <p>
                Tempoh:
                @if ($serviceNote->warranty_duration)
                    {{ $serviceNote->warranty_duration }} {{ $serviceNote->warranty_unit ?: 'Hari' }}
                @else
                    -
                @endif
            </p>
            <p class="value">{{ $display($serviceNote->warranty_note ?: $setting('default_warranty_note')) }}</p>
        </div>
    </div>

    <table class="signature-table block">
        <tr>
            <td style="width: 50%;">
                <span class="label">Customer Signature</span>
                @if ($customerSignatureFile = $signatureFile($serviceNote->customer_signature_path))
                    <img src="{{ $customerSignatureFile }}" class="signature-image" alt="Customer Signature">
                @endif
                <div class="signature-line">Pelanggan</div>
            </td>
            <td style="width: 50%;">
                <span class="label">Technician Signature</span>
                @if ($technicianSignatureFile = $signatureFile($serviceNote->technician_signature_path))
                    <img src="{{ $technicianSignatureFile }}" class="signature-image" alt="Technician Signature">
                @endif
                <div class="signature-line">Disahkan Oleh</div>
            </td>
        </tr>
    </table>

    @if ($setting('footer_note') !== '')
        <div class="footer">{{ $setting('footer_note') }}</div>
    @endif
</body>
</html>
