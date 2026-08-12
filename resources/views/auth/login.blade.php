@extends('layouts.auth')

@section('content')
<div x-data="{ loading: false }" class="card p-8 shadow-2xl">
    {{-- Logo --}}
    <div class="mb-8 text-center">
        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-primary-600 shadow-lg shadow-primary-600/30">
            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-slate-900">FitPro Gym</h1>
        <p class="mt-1 text-sm text-slate-500">Sign in to your admin dashboard</p>
    </div>

    <form method="POST" action="{{ route('login') }}" @submit="loading = true">
        @csrf

        {{-- Email --}}
        <div class="mb-4">
            <label for="email" class="form-label">Email Address</label>
            <div class="relative">
                <svg class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-input pl-10 @error('email') border-red-500 @enderror" placeholder="admin@gym.com" required autofocus>
            </div>
            @error('email')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <div class="relative">
                <svg class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <input type="password" id="password" name="password" class="form-input pl-10 @error('password') border-red-500 @enderror" placeholder="Enter your password" required>
            </div>
            @error('password')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember & Forgot --}}
        <div class="mb-6 flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                <span class="text-sm text-slate-600">Remember me</span>
            </label>
            <a href="#" class="text-sm font-medium text-primary-600 hover:text-primary-700">Forgot password?</a>
        </div>

        {{-- Submit --}}
        <button type="submit" :disabled="loading" class="btn btn-primary w-full py-3">
            <template x-if="loading">
                <svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
            </template>
            <span x-text="loading ? 'Signing in...' : 'Sign In'">Sign In</span>
        </button>
    </form>

    <p class="mt-6 text-center text-xs text-slate-400">
        Demo: admin@gym.com / password
    </p>
</div>
@endsection
