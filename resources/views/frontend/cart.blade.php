@extends('frontend.pages.master')

@section('content')

    <link rel="stylesheet" href="{{ asset('resources/frontend/css/customization.css') }}">

@php
    $currency = $currency ?? '£';
@endphp

<div class="container cart-page">
    <div class="cart-header">
        <h1>Shopping Cart</h1>
        <p class="text-muted">Review items, adjust quantities or customize before checkout.</p>
    </div>

    @if(empty($cartItems) || count($cartItems) === 0)
        <h3 class="text-center my-5">Your cart is empty</h3>
    @else
    <div class="cart-wrapper">
        <div class="cart-items">
            <!-- Desktop table -->
            <table class="cart-table" role="table" aria-label="Cart items">
                <thead>
                    <tr>
                        <th style="width:110px"></th>
                        <th>Product</th>
                        <th style="width:110px">Price</th>
                        <th style="width:180px">Quantity</th>
                        <th style="width:120px">Subtotal</th>
                        <th style="width:100px"></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($cartItems as $item)
                    <tr data-key="{{ $item['key'] }}" class="cart-item-row">
                        <td>
                            <a href="{{ $item['product'] ? route('product.show', $item['product']->slug) : '#' }}">
                                <img src="{{ $item['product_image'] }}" alt="{{ $item['product_name'] }}" class="product-thumb">
                            </a>
                        </td>
                        <td>
                            <a href="{{ $item['product'] ? route('product.show', $item['product']->slug) : '#' }}" class="product-title">{{ $item['product_name'] }}</a>

                            <div class="product-meta">
                                @if($item['ean'])
                                    <div>EAN: <strong>{{ $item['ean'] }}</strong></div>
                                @endif
                                @if(!empty($item['size_id']))
                                    @php $size = \App\Models\Size::find($item['size_id']); @endphp
                                    <div>Size: <strong>{{ $size ? $size->name : $item['size_id'] }}</strong></div>
                                @endif
                                @if(!empty($item['color_id']))
                                    @php $color = \App\Models\Color::find($item['color_id']); @endphp
                                    <div>Color: <strong>{{ $color ? $color->name : $item['color_id'] }}</strong></div>
                                @endif

                                {{-- Customization details --}}
                                @if(!empty($item['customization']))
                                    <div style="margin-top:6px;">
                                        <small><strong>Customization</strong></small>
                                        <div class="customization-list">
                                            @foreach($item['customization'] as $c)
                                                <div class="custom-preview">
                                                    @if(isset($c['type']) && $c['type'] === 'image' && isset($c['data']['src']))
                                                        <img src="{{ $c['data']['src'] }}" alt="Custom Image">
                                                    @endif
                                                    <div>
                                                        <div style="font-weight:600; font-size:14px;">{{ ucfirst($c['method'] ?? '') }} @if(isset($c['position'])) - {{ $c['position'] }} @endif</div>
                                                        @if(isset($c['type']) && $c['type'] === 'text' && isset($c['data']['text']))
                                                            <div style="font-size:13px; color:#555;">Text: "{{ $c['data']['text'] }}"</div>
                                                        @endif
                                                        {{-- optionally show font/size/color for text customizations --}}
                                                        @if(isset($c['type']) && $c['type'] === 'text' && isset($c['data']))
                                                            <div style="font-size:12px; color:#888;">Font: {{ $c['data']['fontFamily'] ?? 'N/A' }}, Size: {{ $c['data']['fontSize'] ?? 'N/A' }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </td>

                        <td>{{ $currency }}{{ number_format($item['price'], 2) }}</td>

                        <td>
                            <div class="qty-control" data-key="{{ $item['key'] }}">
                                <button class="qty-btn btn-decrease" aria-label="decrease">-</button>
                                <input type="number" min="1" class="qty-input" value="{{ $item['quantity'] }}" data-key="{{ $item['key'] }}">
                                <button class="qty-btn btn-increase" aria-label="increase">+</button>
                                <button class="btn-update" data-key="{{ $item['key'] }}">Update</button>
                            </div>
                        </td>

                        <td class="item-subtotal">{{ $currency }}{{ number_format($item['subtotal'], 2) }}</td>

                        <td>
                            <button class="btn-remove btn-remove-item" data-key="{{ $item['key'] }}">Remove</button>
                        </td>
                    </tr>

                    {{-- MOBILE stacked card (hidden on wide screens by CSS media queries) --}}
                    <tr class="cart-row-mobile" style="display:none;"></tr>

                @endforeach
                </tbody>
            </table>

            {{-- Mobile stacked layout: display as cards via JS/CSS or simply the above table hidden on mobile and show card representation using same data.
                 For brevity we rely on CSS to convert table to stacked cards (see CSS). --}}

        </div>

        <div class="cart-summary">
            <div class="summary-card" id="summary-card">
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <h4 style="margin:0">Cart Summary</h4>
                    <small id="items-count">{{ count($cartItems) }} items</small>
                </div>

                <div style="margin-top:12px;">
                    <div class="summary-row">
                        <div>Subtotal</div>
                        <div id="summary-subtotal">{{ $currency }}{{ number_format($total,2) }}</div>
                    </div>
                    <div class="summary-row">
                        <div>Estimated Shipping</div>
                        <div><em>Calculated at checkout</em></div>
                    </div>
                    <div class="summary-row">
                        <div>Estimated Tax</div>
                        <div><em>Dependent on address</em></div>
                    </div>
                    <div class="summary-row" style="font-weight:700; font-size:1.05rem;">
                        <div>Total</div>
                        <div id="summary-total">{{ $currency }}{{ number_format($total,2) }}</div>
                    </div>

                    <a href="{{ route('checkout') ?? '#' }}" class="checkout-btn" id="checkout-btn">Proceed to Checkout</a>

                    <div style="margin-top:12px; font-size:13px; color:#666;">
                        <div>• <strong>Free returns</strong> in 30 days</div>
                        <div>• Secure checkout • Multiple payment methods</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- CSRF token for JS requests --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
document.addEventListener('DOMContentLoaded', function () {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // helper to send POST JSON
    async function postJSON(url, data) {
        const resp = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });
        return resp.json();
    }

    // Update quantity handlers
    document.querySelectorAll('.btn-increase').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const wrapper = e.currentTarget.closest('.qty-control');
            const input = wrapper.querySelector('.qty-input');
            input.value = parseInt(input.value || 1) + 1;
        });
    });
    document.querySelectorAll('.btn-decrease').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const wrapper = e.currentTarget.closest('.qty-control');
            const input = wrapper.querySelector('.qty-input');
            const newVal = Math.max(1, (parseInt(input.value || 1) - 1));
            input.value = newVal;
        });
    });

    // Update action
    document.querySelectorAll('.btn-update').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            const key = e.currentTarget.getAttribute('data-key');
            const input = document.querySelector('.qty-input[data-key="'+key+'"]');
            const qty = parseInt(input.value || 1);
            e.currentTarget.disabled = true;
            e.currentTarget.textContent = 'Updating...';

            try {
                const json = await postJSON("{{ route('cart.update') }}", { key: key, quantity: qty });
                applyCartUpdate(json);
            } catch (err) {
                console.error(err);
                alert('Could not update item. Try again.');
            } finally {
                e.currentTarget.disabled = false;
                e.currentTarget.textContent = 'Update';
            }
        });
    });

    // Remove action
    document.querySelectorAll('.btn-remove-item').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            const key = e.currentTarget.getAttribute('data-key');
            if (!confirm('Remove this item from cart?')) return;
            e.currentTarget.disabled = true;
            e.currentTarget.textContent = 'Removing...';

            try {
                const json = await postJSON("{{ route('cart.remove') }}", { key: key });
                applyCartUpdate(json, key, true);
            } catch (err) {
                console.error(err);
                alert('Could not remove item. Try again.');
            } finally {
                // no-op
            }
        });
    });

    // apply returned totals; if an item is removed, remove its row
    function applyCartUpdate(json, removedKey = null, isRemove = false) {
        if (!json) return;

        // update per-item subtotals if present
        if (json.items) {
            Object.keys(json.items).forEach(k => {
                const row = document.querySelector('tr[data-key="'+k+'"]');
                if (row) {
                    const subtotalTd = row.querySelector('.item-subtotal');
                    if (subtotalTd) {
                        subtotalTd.textContent = "{{ $currency }}" + Number(json.items[k].subtotal).toFixed(2);
                    }
                    const qtyInput = row.querySelector('.qty-input[data-key="'+k+'"]');
                    if (qtyInput) {
                        qtyInput.value = json.items[k].quantity;
                    }
                }
            });
        }

        // if removal: delete row from DOM
        if (isRemove && removedKey) {
            const row = document.querySelector('tr[data-key="'+removedKey+'"]');
            if (row) row.remove();

            // update items count
            document.getElementById('items-count').textContent = (json.items_count || 0) + ' items';
        }

        // update summary
        if (typeof json.total !== 'undefined') {
            const formatted = "{{ $currency }}" + Number(json.total).toFixed(2);
            document.getElementById('summary-subtotal').textContent = formatted;
            document.getElementById('summary-total').textContent = formatted;
        }

        // if no items left, show empty message (reload or replace content)
        if ((json.items_count || 0) === 0) {
            // simple page refresh to show empty view (you can instead render a client-side template)
            window.location.reload();
        }
    }
});
</script>
@endsection
