@extends('layouts.admin')
@section('title', 'New Sale')
@section('page-title', 'New Sale')
@section('breadcrumb', 'Sales / POS')

@section('content')
<div x-data="posSystem(@js($products), @js($members))" class="grid grid-cols-1 gap-6 xl:grid-cols-3">
    <div class="xl:col-span-2 space-y-4">
        <div class="card p-4">
            <input type="text" x-model="search" placeholder="Search product or scan barcode..." class="form-input text-lg" autofocus>
        </div>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4 max-h-[60vh] overflow-y-auto">
            <template x-for="product in filteredProducts" :key="product.id">
                <button type="button" @click="addToCart(product)" class="card p-4 text-left hover:shadow-md hover:border-primary-300 transition-all" :class="product.current_stock <= 0 ? 'opacity-50 cursor-not-allowed' : ''" :disabled="product.current_stock <= 0">
                    <p class="font-semibold text-sm" x-text="product.name"></p>
                    <p class="text-primary-600 font-bold mt-1">₹<span x-text="Number(product.selling_price).toLocaleString()"></span></p>
                    <p class="text-xs text-slate-500 mt-1">Stock: <span x-text="product.current_stock"></span></p>
                </button>
            </template>
        </div>
    </div>

    <div class="card p-5 xl:sticky xl:top-20 h-fit">
        <h3 class="font-bold text-lg mb-4">Cart</h3>
        <template x-if="cart.length === 0"><p class="text-slate-400 text-sm py-8 text-center">No items in cart</p></template>
        <div class="space-y-2 max-h-48 overflow-y-auto mb-4">
            <template x-for="(item, index) in cart" :key="item.id">
                <div class="flex items-center justify-between gap-2 border-b pb-2">
                    <div class="flex-1 min-w-0"><p class="text-sm font-medium truncate" x-text="item.name"></p><p class="text-xs text-slate-500">₹<span x-text="item.price"></span> × <span x-text="item.qty"></span></p></div>
                    <div class="flex items-center gap-1">
                        <button type="button" @click="updateQty(index, -1)" class="h-7 w-7 rounded bg-slate-100 text-sm">-</button>
                        <span class="w-6 text-center text-sm" x-text="item.qty"></span>
                        <button type="button" @click="updateQty(index, 1)" class="h-7 w-7 rounded bg-slate-100 text-sm">+</button>
                        <button type="button" @click="cart.splice(index,1)" class="text-red-500 ml-1 text-xs">✕</button>
                    </div>
                </div>
            </template>
        </div>

        <form method="POST" action="{{ route('sales.store') }}" @submit="prepareSubmit">
            @csrf
            <div id="hidden-items"></div>

            <div class="space-y-3 border-t pt-4">
                <div><label class="form-label">Customer Type</label>
                    <select name="customer_type" x-model="customerType" class="form-input">
                        <option value="walk_in">Walk-in Customer</option>
                        <option value="member">Gym Member</option>
                    </select>
                </div>
                <div x-show="customerType === 'member'"><label class="form-label">Member</label>
                    <select name="member_id" class="form-input"><option value="">Select member</option>@foreach($members as $m)<option value="{{ $m->id }}">{{ $m->full_name }} ({{ $m->member_code }})</option>@endforeach</select>
                </div>
                <div x-show="customerType === 'walk_in'" class="grid grid-cols-2 gap-2">
                    <div><label class="form-label">Name</label><input type="text" name="customer_name" class="form-input"></div>
                    <div><label class="form-label">Phone</label><input type="text" name="customer_phone" class="form-input"></div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div><label class="form-label">Discount (₹)</label><input type="number" name="discount" x-model.number="discount" min="0" step="0.01" class="form-input"></div>
                    <div><label class="form-label">Tax (₹)</label><input type="number" name="tax" x-model.number="tax" min="0" step="0.01" class="form-input"></div>
                </div>
                <div><label class="form-label">Payment Method</label>
                    <select name="payment_method" class="form-input"><option value="cash">Cash</option><option value="card">Card</option><option value="upi">UPI</option><option value="other">Other</option></select>
                </div>
                <div><label class="form-label">Payment Status</label>
                    <select name="payment_status" class="form-input"><option value="paid">Paid</option><option value="pending">Pending</option><option value="partial">Partial</option></select>
                </div>
                <div class="rounded-lg bg-slate-50 p-3 space-y-1 text-sm">
                    <div class="flex justify-between"><span>Subtotal</span><span>₹<span x-text="subtotal.toLocaleString()"></span></span></div>
                    <div class="flex justify-between text-red-600"><span>Discount</span><span>-₹<span x-text="discount.toLocaleString()"></span></span></div>
                    <div class="flex justify-between font-bold text-lg border-t pt-2"><span>Total</span><span>₹<span x-text="total.toLocaleString()"></span></span></div>
                </div>
                <button type="submit" :disabled="cart.length === 0" class="btn btn-primary w-full py-3" :class="cart.length === 0 ? 'opacity-50' : ''">Complete Sale</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function posSystem(products, members) {
    return {
        search: '',
        cart: [],
        customerType: 'walk_in',
        discount: 0,
        tax: 0,
        products: products,
        get filteredProducts() {
            const q = this.search.toLowerCase();
            if (!q) return this.products.filter(p => p.status === 'active');
            return this.products.filter(p => p.status === 'active' && (
                p.name.toLowerCase().includes(q) || (p.sku && p.sku.toLowerCase().includes(q)) || (p.barcode && p.barcode.includes(q))
            ));
        },
        get subtotal() { return this.cart.reduce((s, i) => s + i.price * i.qty, 0); },
        get total() { return Math.max(0, this.subtotal - this.discount + this.tax); },
        addToCart(product) {
            if (product.current_stock <= 0) return;
            const existing = this.cart.find(i => i.id === product.id);
            if (existing) {
                if (existing.qty >= product.current_stock) { alert(`Insufficient stock. Available: ${product.current_stock}`); return; }
                existing.qty++;
            } else {
                this.cart.push({ id: product.id, name: product.name, price: parseFloat(product.selling_price), qty: 1, maxStock: product.current_stock });
            }
        },
        updateQty(index, delta) {
            const item = this.cart[index];
            const newQty = item.qty + delta;
            if (newQty <= 0) { this.cart.splice(index, 1); return; }
            if (newQty > item.maxStock) { alert(`Insufficient stock. Available: ${item.maxStock}`); return; }
            item.qty = newQty;
        },
        prepareSubmit(e) {
            if (this.cart.length === 0) { e.preventDefault(); return; }
            const container = document.getElementById('hidden-items');
            container.innerHTML = '';
            this.cart.forEach((item, i) => {
                container.innerHTML += `<input type="hidden" name="items[${i}][product_id]" value="${item.id}"><input type="hidden" name="items[${i}][quantity]" value="${item.qty}">`;
            });
        }
    }
}
</script>
@endpush
