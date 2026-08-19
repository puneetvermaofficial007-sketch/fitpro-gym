<aside
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col bg-sidebar transition-transform duration-300 lg:translate-x-0"
>
    <div class="flex h-16 items-center gap-3 border-b border-slate-700/50 px-5">
        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary-600">
            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
        <div>
            <h1 class="text-base font-bold text-white">FitPro Gym</h1>
            <p class="text-xs text-slate-400">Management System</p>
        </div>
    </div>

    @php
        $open = fn($routes) => request()->routeIs($routes) ? 'true' : 'false';
    @endphp

    <nav class="sidebar-scroll flex-1 overflow-y-auto px-3 py-4 space-y-1" x-data="{
        openMenus: {
            members: {{ $open('members.*') }},
            memberships: {{ $open('memberships.*') }},
            invoices: {{ $open('invoices.*') }},
            attendance: {{ $open('attendance.*') }},
            dietPlans: {{ $open('diet-plans.*') }},
            enquiries: {{ $open('enquiries.*') }},
            inventory: {{ $open('inventory.*') }},
            sales: {{ $open('sales.*') }},
            reports: {{ $open('reports.*') }},
        }
    }">

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

        {{-- Membership --}}
        <div>
            <button @click="openMenus.memberships = !openMenus.memberships" class="sidebar-link w-full justify-between {{ request()->routeIs('memberships.*') ? 'sidebar-link-active' : '' }}">
                <span class="flex items-center gap-3">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    Membership
                </span>
                <svg :class="openMenus.memberships ? 'rotate-180' : ''" class="h-4 w-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="openMenus.memberships" x-transition class="ml-4 mt-1 space-y-0.5 border-l border-slate-700 pl-3">
                <a href="{{ route('memberships.plans.index') }}" class="sidebar-link text-xs {{ request()->routeIs('memberships.plans.*') ? 'sidebar-link-active' : '' }}">Membership Plans</a>
                <a href="{{ route('memberships.plans.create') }}" class="sidebar-link text-xs {{ request()->routeIs('memberships.plans.create') ? 'sidebar-link-active' : '' }}">Add Plan</a>
                <a href="{{ route('memberships.active') }}" class="sidebar-link text-xs {{ request()->routeIs('memberships.active') ? 'sidebar-link-active' : '' }}">Active Memberships</a>
                <a href="{{ route('memberships.expired') }}" class="sidebar-link text-xs {{ request()->routeIs('memberships.expired') ? 'sidebar-link-active' : '' }}">Expired Memberships</a>
                <a href="{{ route('memberships.expiring') }}" class="sidebar-link text-xs {{ request()->routeIs('memberships.expiring') ? 'sidebar-link-active' : '' }}">Expiring Soon</a>
                <a href="{{ route('memberships.renewals') }}" class="sidebar-link text-xs {{ request()->routeIs('memberships.renewals') ? 'sidebar-link-active' : '' }}">Renewals</a>
            </div>
        </div>

        {{-- Invoices --}}
        <div>
            <button @click="openMenus.invoices = !openMenus.invoices" class="sidebar-link w-full justify-between {{ request()->routeIs('invoices.*') ? 'sidebar-link-active' : '' }}">
                <span class="flex items-center gap-3">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                    Invoices
                </span>
                <svg :class="openMenus.invoices ? 'rotate-180' : ''" class="h-4 w-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="openMenus.invoices" x-transition class="ml-4 mt-1 space-y-0.5 border-l border-slate-700 pl-3">
                <a href="{{ route('invoices.index') }}" class="sidebar-link text-xs {{ request()->routeIs('invoices.index') && !request('filter') ? 'sidebar-link-active' : '' }}">All Invoices</a>
                <a href="{{ route('invoices.create') }}" class="sidebar-link text-xs {{ request()->routeIs('invoices.create') ? 'sidebar-link-active' : '' }}">Create Invoice</a>
                <a href="{{ route('invoices.index', ['filter' => 'paid']) }}" class="sidebar-link text-xs {{ request('filter') === 'paid' ? 'sidebar-link-active' : '' }}">Paid Invoices</a>
                <a href="{{ route('invoices.index', ['filter' => 'pending']) }}" class="sidebar-link text-xs {{ request('filter') === 'pending' ? 'sidebar-link-active' : '' }}">Pending Payments</a>
                <a href="{{ route('invoices.index', ['filter' => 'overdue']) }}" class="sidebar-link text-xs {{ request('filter') === 'overdue' ? 'sidebar-link-active' : '' }}">Overdue Payments</a>
            </div>
        </div>

        {{-- Attendance --}}
        <div>
            <button @click="openMenus.attendance = !openMenus.attendance" class="sidebar-link w-full justify-between {{ request()->routeIs('attendance.*') ? 'sidebar-link-active' : '' }}">
                <span class="flex items-center gap-3">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    Attendance
                </span>
                <svg :class="openMenus.attendance ? 'rotate-180' : ''" class="h-4 w-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="openMenus.attendance" x-transition class="ml-4 mt-1 space-y-0.5 border-l border-slate-700 pl-3">
                <a href="{{ route('attendance.index', ['filter' => 'today']) }}" class="sidebar-link text-xs {{ request()->routeIs('attendance.index') && request('filter', 'today') === 'today' ? 'sidebar-link-active' : '' }}">Today's Attendance</a>
                <a href="{{ route('attendance.mark') }}" class="sidebar-link text-xs {{ request()->routeIs('attendance.mark') ? 'sidebar-link-active' : '' }}">Mark Attendance</a>
                <a href="{{ route('attendance.index', ['filter' => 'history']) }}" class="sidebar-link text-xs {{ request('filter') === 'history' ? 'sidebar-link-active' : '' }}">Attendance History</a>
                <a href="{{ route('attendance.index', ['filter' => 'monthly']) }}" class="sidebar-link text-xs {{ request('filter') === 'monthly' ? 'sidebar-link-active' : '' }}">Monthly Report</a>
            </div>
        </div>

        {{-- Diet Plans --}}
        <div>
            <button @click="openMenus.dietPlans = !openMenus.dietPlans" class="sidebar-link w-full justify-between {{ request()->routeIs('diet-plans.*') ? 'sidebar-link-active' : '' }}">
                <span class="flex items-center gap-3">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Diet Plans
                </span>
                <svg :class="openMenus.dietPlans ? 'rotate-180' : ''" class="h-4 w-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="openMenus.dietPlans" x-transition class="ml-4 mt-1 space-y-0.5 border-l border-slate-700 pl-3">
                <a href="{{ route('diet-plans.index') }}" class="sidebar-link text-xs {{ request()->routeIs('diet-plans.index') ? 'sidebar-link-active' : '' }}">All Diet Plans</a>
                <a href="{{ route('diet-plans.create') }}" class="sidebar-link text-xs {{ request()->routeIs('diet-plans.create') ? 'sidebar-link-active' : '' }}">Create Diet Plan</a>
                <a href="{{ route('diet-plans.assignments') }}" class="sidebar-link text-xs {{ request()->routeIs('diet-plans.assignments') ? 'sidebar-link-active' : '' }}">Assigned Plans</a>
                <a href="{{ route('diet-plans.assign') }}" class="sidebar-link text-xs {{ request()->routeIs('diet-plans.assign') ? 'sidebar-link-active' : '' }}">Assign to Member</a>
            </div>
        </div>

        {{-- Inventory --}}
        <div>
            <button @click="openMenus.inventory = !openMenus.inventory" class="sidebar-link w-full justify-between {{ request()->routeIs('inventory.*') ? 'sidebar-link-active' : '' }}">
                <span class="flex items-center gap-3">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Inventory
                </span>
                <svg :class="openMenus.inventory ? 'rotate-180' : ''" class="h-4 w-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="openMenus.inventory" x-transition class="ml-4 mt-1 space-y-0.5 border-l border-slate-700 pl-3">
                <a href="{{ route('inventory.products.index') }}" class="sidebar-link text-xs {{ request()->routeIs('inventory.products.*') ? 'sidebar-link-active' : '' }}">Products</a>
                <a href="{{ route('inventory.categories.index') }}" class="sidebar-link text-xs {{ request()->routeIs('inventory.categories.*') ? 'sidebar-link-active' : '' }}">Categories</a>
                <a href="{{ route('inventory.stock.index') }}" class="sidebar-link text-xs {{ request()->routeIs('inventory.stock.index') ? 'sidebar-link-active' : '' }}">Stock Management</a>
                <a href="{{ route('inventory.adjustments.index') }}" class="sidebar-link text-xs {{ request()->routeIs('inventory.adjustments.*') ? 'sidebar-link-active' : '' }}">Stock Adjustments</a>
                <a href="{{ route('inventory.suppliers.index') }}" class="sidebar-link text-xs {{ request()->routeIs('inventory.suppliers.*') ? 'sidebar-link-active' : '' }}">Suppliers</a>
                <a href="{{ route('inventory.purchases.create') }}" class="sidebar-link text-xs {{ request()->routeIs('inventory.purchases.*') ? 'sidebar-link-active' : '' }}">Purchase Stock</a>
                <a href="{{ route('inventory.stock.low') }}" class="sidebar-link text-xs {{ request()->routeIs('inventory.stock.low') ? 'sidebar-link-active' : '' }}">Low Stock</a>
                <a href="{{ route('inventory.stock.history') }}" class="sidebar-link text-xs {{ request()->routeIs('inventory.stock.history') ? 'sidebar-link-active' : '' }}">Stock History</a>
            </div>
        </div>

        {{-- Sales / POS --}}
        <div>
            <button @click="openMenus.sales = !openMenus.sales" class="sidebar-link w-full justify-between {{ request()->routeIs('sales.*') ? 'sidebar-link-active' : '' }}">
                <span class="flex items-center gap-3">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Sales / POS
                </span>
                <svg :class="openMenus.sales ? 'rotate-180' : ''" class="h-4 w-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="openMenus.sales" x-transition class="ml-4 mt-1 space-y-0.5 border-l border-slate-700 pl-3">
                <a href="{{ route('sales.pos') }}" class="sidebar-link text-xs {{ request()->routeIs('sales.pos') ? 'sidebar-link-active' : '' }}">New Sale</a>
                <a href="{{ route('sales.index') }}" class="sidebar-link text-xs {{ request()->routeIs('sales.index') ? 'sidebar-link-active' : '' }}">Sales History</a>
                <a href="{{ route('sales.today') }}" class="sidebar-link text-xs {{ request()->routeIs('sales.today') ? 'sidebar-link-active' : '' }}">Today's Sales</a>
                <a href="{{ route('sales.pending') }}" class="sidebar-link text-xs {{ request()->routeIs('sales.pending') ? 'sidebar-link-active' : '' }}">Pending Payments</a>
                <a href="{{ route('sales.returns') }}" class="sidebar-link text-xs {{ request()->routeIs('sales.returns') ? 'sidebar-link-active' : '' }}">Returns</a>
            </div>
        </div>

        {{-- Enquiries --}}
        <div>
            <button @click="openMenus.enquiries = !openMenus.enquiries" class="sidebar-link w-full justify-between {{ request()->routeIs('enquiries.*') ? 'sidebar-link-active' : '' }}">
                <span class="flex items-center gap-3">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    Enquiries
                </span>
                <svg :class="openMenus.enquiries ? 'rotate-180' : ''" class="h-4 w-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="openMenus.enquiries" x-transition class="ml-4 mt-1 space-y-0.5 border-l border-slate-700 pl-3">
                <a href="{{ route('enquiries.index') }}" class="sidebar-link text-xs {{ request()->routeIs('enquiries.index') && !request('filter') ? 'sidebar-link-active' : '' }}">All Enquiries</a>
                <a href="{{ route('enquiries.create') }}" class="sidebar-link text-xs {{ request()->routeIs('enquiries.create') ? 'sidebar-link-active' : '' }}">New Enquiry</a>
                <a href="{{ route('enquiries.index', ['filter' => 'follow-ups']) }}" class="sidebar-link text-xs {{ request('filter') === 'follow-ups' ? 'sidebar-link-active' : '' }}">Follow-ups</a>
                <a href="{{ route('enquiries.index', ['filter' => 'converted']) }}" class="sidebar-link text-xs {{ request('filter') === 'converted' ? 'sidebar-link-active' : '' }}">Converted</a>
                <a href="{{ route('enquiries.index', ['filter' => 'lost']) }}" class="sidebar-link text-xs {{ request('filter') === 'lost' ? 'sidebar-link-active' : '' }}">Lost Enquiries</a>
            </div>
        </div>

        {{-- Reports --}}
        <div>
            <button @click="openMenus.reports = !openMenus.reports" class="sidebar-link w-full justify-between {{ request()->routeIs('reports.*') ? 'sidebar-link-active' : '' }}">
                <span class="flex items-center gap-3">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Reports
                </span>
                <svg :class="openMenus.reports ? 'rotate-180' : ''" class="h-4 w-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="openMenus.reports" x-transition class="ml-4 mt-1 space-y-0.5 border-l border-slate-700 pl-3">
                <a href="{{ route('reports.index') }}" class="sidebar-link text-xs {{ request()->routeIs('reports.index') ? 'sidebar-link-active' : '' }}">All Reports</a>
                <a href="{{ route('reports.show', 'members') }}" class="sidebar-link text-xs">Member Report</a>
                <a href="{{ route('reports.show', 'revenue') }}" class="sidebar-link text-xs">Revenue Report</a>
                <a href="{{ route('reports.show', 'attendance') }}" class="sidebar-link text-xs">Attendance Report</a>
                <a href="{{ route('reports.show', 'pending-payments') }}" class="sidebar-link text-xs">Pending Payments</a>
                <a href="{{ route('reports.show', 'inventory-stock') }}" class="sidebar-link text-xs">Inventory Stock</a>
                <a href="{{ route('reports.show', 'product-sales') }}" class="sidebar-link text-xs">Product Sales</a>
                <a href="{{ route('reports.show', 'product-profit') }}" class="sidebar-link text-xs">Product Profit</a>
            </div>
        </div>

        <a href="{{ route('notifications.index') }}" class="sidebar-link {{ request()->routeIs('notifications.*') ? 'sidebar-link-active' : '' }}">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            Notifications
        </a>
    </nav>

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
