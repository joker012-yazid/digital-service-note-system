@php
    $signaturePath = $signaturePath ?? null;
    $signatureUrl = $signaturePath ? route('storage.public', ['path' => $signaturePath]) : null;
@endphp

<div class="signature-pad rounded-md border border-dashed border-slate-300 bg-white/80 p-4" data-signature-pad>
    <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <p class="text-sm font-semibold text-slate-900">{{ $title }}</p>
            @if ($signatureUrl)
                <p class="mt-1 text-xs font-medium text-emerald-700">Signature sedia ada akan dikekalkan jika tiada signature baru dilukis.</p>
            @endif
        </div>
        <button type="button" class="w-fit rounded-md border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50" data-signature-clear>
            Clear
        </button>
    </div>

    @if ($signatureUrl)
        <div class="mt-3 rounded-md border border-slate-200 bg-slate-50 p-2">
            <img src="{{ $signatureUrl }}" alt="{{ $title }} preview" class="mx-auto h-20 max-w-full object-contain">
        </div>
    @endif

    <div class="mt-3 rounded-md border border-slate-200 bg-white p-2">
        <canvas class="signature-pad__canvas h-32 w-full rounded bg-white" data-signature-canvas aria-label="{{ $title }}"></canvas>
    </div>

    <input type="hidden" name="{{ $name }}" data-signature-input>

    <div class="mt-3 border-t border-slate-300 pt-2 text-center text-sm text-slate-600">{{ $caption }}</div>
    <p class="mt-2 text-xs font-medium text-slate-500" data-signature-status>Belum ada signature baru.</p>
</div>
