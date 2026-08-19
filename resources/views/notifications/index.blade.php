@extends('layouts.admin')
@section('title', 'Notifications')
@section('page-title', 'Notifications')
@section('breadcrumb', 'System Notifications')

@section('content')
<div class="mb-6 flex justify-end">
    <form method="POST" action="{{ route('notifications.read-all') }}">@csrf @method('PATCH')<button class="btn btn-secondary btn-sm">Mark All as Read</button></form>
</div>
<div class="card divide-y divide-slate-100">
    @forelse($notifications as $n)
        <div class="flex items-start gap-4 p-4 {{ !$n->is_read ? 'bg-primary-50/50' : '' }}">
            <div class="mt-1 h-2 w-2 shrink-0 rounded-full {{ match($n->type){ 'danger'=>'bg-red-500','warning'=>'bg-amber-500','success'=>'bg-emerald-500',default=>'bg-blue-500' } }}"></div>
            <div class="flex-1">
                <p class="font-medium text-slate-900">{{ $n->title }}</p>
                <p class="text-sm text-slate-600 mt-0.5">{{ $n->message }}</p>
                <p class="text-xs text-slate-400 mt-1">{{ $n->created_at->diffForHumans() }}</p>
            </div>
            @if(!$n->is_read)
                <form method="POST" action="{{ route('notifications.read', $n) }}">@csrf @method('PATCH')<button class="btn btn-secondary btn-sm">Mark Read</button></form>
            @endif
        </div>
    @empty
        <div class="p-12 text-center text-slate-400">No notifications</div>
    @endforelse
</div>
@if($notifications->hasPages())<div class="mt-4">{{ $notifications->links() }}</div>@endif
@endsection
