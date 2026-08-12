@props(['label', 'value', 'icon', 'trend' => null, 'trendUp' => true, 'color' => 'primary'])

@php
$colorClasses = match($color) {
    'emerald' => 'bg-emerald-50 text-emerald-600',
    'amber' => 'bg-amber-50 text-amber-600',
    'red' => 'bg-red-50 text-red-600',
    'blue' => 'bg-blue-50 text-blue-600',
    'purple' => 'bg-purple-50 text-purple-600',
    default => 'bg-primary-50 text-primary-600',
};
@endphp

<div class="stat-card">
    <div class="flex items-start justify-between">
        <div class="flex h-11 w-11 items-center justify-center rounded-xl {{ $colorClasses }}">
            {!! $icon !!}
        </div>
        @if($trend)
            <span class="flex items-center gap-1 text-xs font-medium {{ $trendUp ? 'text-emerald-600' : 'text-red-600' }}">
                @if($trendUp)
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                @else
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                @endif
                {{ $trend }}
            </span>
        @endif
    </div>
    <div>
        <p class="text-2xl font-bold text-slate-900">{{ $value }}</p>
        <p class="text-sm text-slate-500">{{ $label }}</p>
    </div>
</div>
