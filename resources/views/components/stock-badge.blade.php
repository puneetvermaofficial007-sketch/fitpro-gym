@props(['product'])

@php
    $status = $product->stockStatus();
    $class = match($status) {
        'out_of_stock' => 'badge-danger',
        'low_stock' => 'badge-warning',
        default => 'badge-success',
    };
@endphp
<span class="badge {{ $class }}">{{ $product->stockStatusLabel() }}</span>
