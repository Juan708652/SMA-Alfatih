@props([
    'url' => url()->current(),
    'title' => '',
    'label' => 'Bagikan:',
])

@php
    $shareTitle = $title !== '' ? $title : ($settings['site_name'] ?? config('app.name'));
    $encodedUrl = rawurlencode($url);
    $encodedTitle = rawurlencode($shareTitle);
    $targets = [
        'whatsapp' => 'https://wa.me/?text='.rawurlencode($shareTitle.' '.$url),
        'facebook' => 'https://www.facebook.com/sharer/sharer.php?u='.$encodedUrl,
        'telegram' => 'https://t.me/share/url?url='.$encodedUrl.'&text='.$encodedTitle,
        'twitter' => 'https://twitter.com/intent/tweet?url='.$encodedUrl.'&text='.$encodedTitle,
    ];
@endphp

<div
    x-data="shareLink(@js((string) $url), @js($shareTitle))"
    {{ $attributes->merge(['class' => 'flex flex-wrap items-center gap-2']) }}
>
    @if ($label)
        <span class="mr-0.5 text-sm font-semibold text-slate-500">{{ $label }}</span>
    @endif

    {{-- Salin tautan --}}
    <button
        type="button"
        @click="copy()"
        :aria-label="copied ? 'Tautan tersalin' : 'Salin tautan'"
        class="inline-flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-3.5 py-2 text-sm font-bold text-emerald-800 transition hover:border-emerald-300 hover:bg-emerald-100"
        :class="copied ? 'ring-2 ring-emerald-500 ring-offset-1' : ''"
    >
        <svg x-show="!copied" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
        </svg>
        <svg x-show="copied" x-cloak class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
        <span x-text="copied ? 'Tersalin!' : 'Salin Link'">Salin Link</span>
    </button>

    {{-- Bagikan lewat aplikasi lain (HP) --}}
    <button
        type="button"
        x-show="canNative"
        x-cloak
        @click="nativeOrCopy()"
        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm font-bold text-slate-600 transition hover:border-emerald-300 hover:text-emerald-700"
        aria-label="Bagikan lewat aplikasi lain"
    >
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z" />
        </svg>
        Lainnya
    </button>

    {{-- Media sosial --}}
    <a href="{{ $targets['whatsapp'] }}" target="_blank" rel="noopener" aria-label="Bagikan ke WhatsApp" title="WhatsApp"
       class="flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 transition hover:border-transparent hover:bg-[#25D366] hover:text-white">
        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" /></svg>
    </a>
    <a href="{{ $targets['facebook'] }}" target="_blank" rel="noopener" aria-label="Bagikan ke Facebook" title="Facebook"
       class="flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 transition hover:border-transparent hover:bg-[#1877F2] hover:text-white">
        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" /></svg>
    </a>
    <a href="{{ $targets['telegram'] }}" target="_blank" rel="noopener" aria-label="Bagikan ke Telegram" title="Telegram"
       class="flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 transition hover:border-transparent hover:bg-[#229ED9] hover:text-white">
        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z" /></svg>
    </a>
    <a href="{{ $targets['twitter'] }}" target="_blank" rel="noopener" aria-label="Bagikan ke X (Twitter)" title="X (Twitter)"
       class="flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 transition hover:border-transparent hover:bg-black hover:text-white">
        <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z" /></svg>
    </a>
</div>
