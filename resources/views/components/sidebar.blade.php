<aside
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col bg-sidebar transition-transform duration-300 lg:translate-x-0"
>
    {{-- Logo --}}
    <div class="flex h-16 items-center gap-3 border-b border-slate-700/50 px-5">
        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary-600">
            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
        </div>
        <div>
            <h1 class="text-base font-bold text-white">FitPro Gym</h1>
            <p class="text-xs text-slate-400">Management System</p>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-scroll flex-1 overflow-y-auto px-3 py-4 space-y-1" x-data="{ openMenus: { members: {{ request()->routeIs('members.*') ? 'true' : 'false' }} } }">
        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'sidebar-link-active' : '' }}">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            Dashboard
        </a>

        {{-- Members --}}
        <div>
            <button @click="openMenus.members = !openMenus.members" class="sidebar-link w-full justify-between {{ request()->routeIs('members.*') ? 'sidebar-link-active' : '' }}">
                <span class="flex items-center gap-3">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Members
                </span>
                <svg :class="openMenus.members ? 'rotate-180' : ''" class="h-4 w-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="openMenus.members" x-transition class="ml-4 mt-1 space-y-0.5 border-l border-slate-700 pl-3">
                <a href="{{ route('members.index') }}" class="sidebar-link text-xs {{ request()->routeIs('members.index') && !request('filter') ? 'sidebar-link-active' : '' }}">All Members</a>
                <a href="{{ route('members.create') }}" class="sidebar-link text-xs {{ request()->routeIs('members.create') ? 'sidebar-link-active' : '' }}">Add Member</a>
                <a href="{{ route('members.index', ['filter' => 'active']) }}" class="sidebar-link text-xs {{ request('filter') === 'active' ? 'sidebar-link-active' : '' }}">Active Members</a>
                <a href="{{ route('members.index', ['filter' => 'expired']) }}" class="sidebar-link text-xs {{ request('filter') === 'expired' ? 'sidebar-link-active' : '' }}">Expired Members</a>
                <a href="{{ route('members.index', ['filter' => 'deleted']) }}" class="sidebar-link text-xs {{ request('filter') === 'deleted' ? 'sidebar-link-active' : '' }}">Deleted Members</a>
            </div>
        </div>

        {{-- Coming soon sections --}}
        @foreach([
            ['Membership', 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
            ['Invoices', 'M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z'],
            ['Attendance', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
            ['Diet Plans', 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
            ['Enquiries', 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
            ['Reports', 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
        ] as [$label, $icon])
            <span class="sidebar-link opacity-50 cursor-not-allowed">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/></svg>
                {{ $label }}
                <span class="ml-auto text-[10px] bg-slate-700 text-slate-400 px-1.5 py-0.5 rounded">Soon</span>
            </span>
        @endforeach
    </nav>

    {{-- Sidebar footer --}}
    <div class="border-t border-slate-700/50 p-4">
        <div class="flex items-center gap-3 rounded-lg bg-slate-800/50 p-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-primary-600 text-sm font-semibold text-white">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="truncate text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                <p class="truncate text-xs text-slate-400">Administrator</p>
            </div>
        </div>
    </div>
</aside>
