@props([
    'url' => url()->current(),
    'title' => '',
])

@php
    $shareTitle = $title !== '' ? $title : ($settings['site_name'] ?? config('app.name'));
@endphp

<div
    x-data="shareLink(@js((string) $url), @js($shareTitle))"
    {{ $attributes->merge(['class' => 'relative']) }}
>
    <button
        type="button"
        @click.prevent="nativeOrCopy()"
        :aria-label="copied ? 'Tautan tersalin' : 'Bagikan tautan'"
        title="Bagikan tautan"
        class="flex h-9 w-9 items-center justify-center rounded-full bg-white/90 text-emerald-800 shadow-md ring-1 ring-black/5 backdrop-blur transition hover:bg-emerald-600 hover:text-white"
        :class="copied ? 'bg-emerald-600 text-white' : ''"
    >
        <svg x-show="!copied" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z" />
        </svg>
        <svg x-show="copied" x-cloak class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
    </button>

    <span
        x-show="copied"
        x-cloak
        x-transition
        class="pointer-events-none absolute right-0 top-full z-20 mt-2 whitespace-nowrap rounded-lg bg-emerald-900 px-2.5 py-1 text-xs font-semibold text-white shadow-lg"
    >
        Link disalin
    </span>
</div>
