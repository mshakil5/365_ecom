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

        @if (empty($cartItems) || count($cartItems) === 0)
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
                            @foreach ($cartItems as $item)
                                <tr data-key="{{ $item['key'] }}" class="cart-item-row">
                                    <td>
                                        <a
                                            href="{{ $item['product'] ? route('product.show', $item['product']->slug) : '#' }}">
                                            <img src="{{ asset('' . $item['product_image']) }}"
                                                alt="{{ $item['product_name'] }}" class="product-thumb">
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ $item['product'] ? route('product.show', $item['product']->slug) : '#' }}"
                                            class="product-title">{{ $item['product_name'] }}</a>

                                        <div class="product-meta">
                                            @if ($item['ean'])
                                                <div>EAN: <strong>{{ $item['ean'] }}</strong></div>
                                            @endif
                                            @if (!empty($item['size_id']))
                                                @php $size = \App\Models\Size::find($item['size_id']); @endphp
                                                <div>Size: <strong>{{ $size ? $size->name : $item['size_id'] }}</strong>
                                                </div>
                                            @endif
                                            @if (!empty($item['color_id']))
                                                @php $color = \App\Models\Color::find($item['color_id']); @endphp
                                                <div>Color: <strong>{{ $color ? $color->name : $item['color_id'] }}</strong>
                                                </div>
                                            @endif

                                            {{-- Customization details --}}
                                            @if (!empty($item['customization']))
                                                <div style="margin-top:6px;">
                                                    <small><strong>Customization</strong></small>
                                                    <div class="customization-list">
                                                        @foreach ($item['customization'] as $c)
                                                            <div class="custom-preview">
                                                                @if (isset($c['type']) && $c['type'] === 'image' && isset($c['data']['src']))
                                                                    <img src="{{ $c['data']['src'] }}" alt="Custom Image">
                                                                @endif
                                                                <div>
                                                                    <div style="font-weight:600; font-size:14px;">
                                                                        {{ ucfirst($c['method'] ?? '') }} @if (isset($c['position']))
                                                                            - {{ $c['position'] }}
                                                                        @endif
                                                                    </div>
                                                                    @if (isset($c['type']) && $c['type'] === 'text' && isset($c['data']['text']))
                                                                        <div style="font-size:13px; color:#555;">Text:
                                                                            "{{ $c['data']['text'] }}"</div>
                                                                    @endif
                                                                    {{-- optionally show font/size/color for text customizations --}}
                                                                    @if (isset($c['type']) && $c['type'] === 'text' && isset($c['data']))
                                                                        <div style="font-size:12px; color:#888;">Font:
                                                                            {{ $c['data']['fontFamily'] ?? 'N/A' }}, Size:
                                                                            {{ $c['data']['fontSize'] ?? 'N/A' }}</div>
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
                                            <input type="text" class="qty-input" value="{{ $item['quantity'] }}"
                                                data-key="{{ $item['key'] }}">
                                            <button class="qty-btn btn-increase" aria-label="increase">+</button>
                                        </div>
                                    </td>

                                    <td class="item-subtotal">{{ $currency }}{{ number_format($item['subtotal'], 2) }}
                                    </td>

                                    <td>
                                        <button class="btn-remove btn-remove-item"
                                            data-key="{{ $item['key'] }}">Remove</button>
                                    </td>
                                </tr>

                                {{-- MOBILE stacked card (hidden on wide screens by CSS media queries) --}}
                                {{-- <tr class="cart-row-mobile" style="display:none;"></tr> --}}
                                <!-- Mobile stacked card -->
                                <div class="cart-row-mobile" data-key="{{ $item['key'] }}">
                                    <div class="cart-row">
                                        <img src="{{ asset('' . $item['product_image']) }}"
                                            alt="{{ $item['product_name'] }}" class="product-thumb">
                                        <div class="product-info">
                                            <a href="{{ $item['product'] ? route('product.show', $item['product']->slug) : '#' }}"
                                                class="product-title">{{ $item['product_name'] }}</a>
                                            <div class="product-meta">
                                                @if ($item['ean'])
                                                    <div>EAN: <strong>{{ $item['ean'] }}</strong></div>
                                                @endif
                                                @if (!empty($item['size_id']))
                                                    @php $size = \App\Models\Size::find($item['size_id']); @endphp
                                                    <div>Size:
                                                        <strong>{{ $size ? $size->name : $item['size_id'] }}</strong></div>
                                                @endif
                                                @if (!empty($item['color_id']))
                                                    @php $color = \App\Models\Color::find($item['color_id']); @endphp
                                                    <div>Color:
                                                        <strong>{{ $color ? $color->name : $item['color_id'] }}</strong>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="price-qty">
                                                <div>{{ $currency }}{{ number_format($item['price'], 2) }}</div>
                                                <div class="qty-control" data-key="{{ $item['key'] }}">
                                                    <button class="qty-btn btn-decrease">-</button>
                                                    <input type="text" class="qty-input" value="{{ $item['quantity'] }}"
                                                        data-key="{{ $item['key'] }}">
                                                    <button class="qty-btn btn-increase">+</button>
                                                </div>
                                            </div>
                                            <div class="item-subtotal">
                                                {{ $currency }}{{ number_format($item['subtotal'], 2) }}</div>
                                            <button class="btn-remove btn-remove-item"
                                                data-key="{{ $item['key'] }}">Remove</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>

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
                                <div id="summary-subtotal">{{ $currency }}{{ number_format($total, 2) }}</div>
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
                                <div id="summary-total">{{ $currency }}{{ number_format($total, 2) }}</div>
                            </div>

                            <a href="{{ route('checkout') ?? '#' }}" class="checkout-btn" id="checkout-btn">Proceed to
                                Checkout</a>

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

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            const csrfToken = "{{ csrf_token() }}";

            function updateCartQty(key, qty) {
                qty = Math.max(1, parseInt(qty) || 1)
                $.ajax({
                    url: "{{ route('cart.update') }}",
                    method: "POST",
                    data: {
                        _token: csrfToken,
                        key: key,
                        quantity: qty
                    },
                    success: function(json) {
                        applyCartUpdate(json);
                        updateCartCount();
                    },
                    error: function() {
                        alert('Could not update item. Try again.');
                    }
                });
            }

            function removeCartItem(key) {
                if (!confirm('Remove this item from cart?')) return;
                $.ajax({
                    url: "{{ route('cart.remove') }}",
                    method: "POST",
                    data: {
                        _token: csrfToken,
                        key: key
                    },
                    success: function(json) {
                        applyCartUpdate(json, key, true);
                        updateCartCount();
                    },
                    error: function() {
                        alert('Could not remove item. Try again.');
                    }
                });
            }

            function applyCartUpdate(json, removedKey = null, isRemove = false) {
                if (!json) return;

                if (json.items) {
                    $.each(json.items, function(k, item) {
                        // Update desktop
                        const row = $('tr[data-key="' + k + '"]');
                        row.find('.item-subtotal').text("{{ $currency }}" + item.subtotal.toFixed(2));
                        row.find('.qty-input').val(item.quantity);

                        // Update mobile
                        const mRow = $('.cart-row-mobile[data-key="' + k + '"]');
                        mRow.find('.item-subtotal').text("{{ $currency }}" + item.subtotal.toFixed(2));
                        mRow.find('.qty-input').val(item.quantity);
                    });
                }

                if (isRemove && removedKey) {
                    $('tr[data-key="' + removedKey + '"]').remove();
                    $('.cart-row-mobile[data-key="' + removedKey + '"]').remove();
                }

                if (json.total !== undefined) {
                    const total = "{{ $currency }}" + json.total.toFixed(2);
                    $('#summary-subtotal, #summary-total').text(total);
                }

                $('#items-count').text((json.items_count || 0) + ' items');

                if ((json.items_count || 0) === 0) {
                    $('.checkout-section, #checkout-btn').hide();
                    $('tbody.cart-table-body').html(
                        '<tr><td colspan="5" class="text-center py-4">Your cart is empty.</td></tr>');
                } else {
                    $('.checkout-section, #checkout-btn').show();
                }
            }


            function updateCartCount() {
                $.ajax({
                    url: "{{ route('cart.getCount') }}",
                    method: "GET",
                    success: function(res) {
                        $('.cartCount').text(res.count || 0);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            }

            $('.btn-increase, .btn-decrease').on('click', function() {
                const wrapper = $(this).closest('.qty-control');
                const input = wrapper.find('.qty-input');
                let qty = parseInt(input.val()) || 1;

                if ($(this).hasClass('btn-increase')) qty++;
                else qty = Math.max(1, qty - 1);

                input.val(qty);
                updateCartQty(input.data('key'), qty);
            });

            $('.qty-input').on('blur', function() {
                const key = $(this).data('key');
                let qty = Math.max(1, parseInt($(this).val()) || 1);
                $(this).val(qty);
                updateCartQty(key, qty);
            });

            $('.btn-remove-item').on('click', function() {
                removeCartItem($(this).data('key'));
            });

            updateCartCount();
        });
    </script>
@endsection