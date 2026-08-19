@props(['title', 'action' => null, 'actionLabel' => null, 'actionRoute' => null])

<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        @if(isset($subtitle))
            <p class="text-sm text-slate-500">{{ $subtitle }}</p>
        @endif
    </div>
    @if($actionRoute)
        <a href="{{ $actionRoute }}" class="btn btn-primary">
            {{ $actionLabel ?? 'Add New' }}
        </a>
    @endif
    {{ $action ?? '' }}
</div>
