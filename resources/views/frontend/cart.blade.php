@extends('frontend.pages.master')

@section('content')
<style>
    .cart-delete-btn {
        border: none;
        background-color: #ea1c26;
        color: #fff;
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        padding: 5px 20px;
        border-radius: 5px;
        transition: all .3s ease;
    }
    .cart-delete-btn:hover { background: #333; }
</style>

@php $currency = \App\Models\CompanyDetails::value('currency'); @endphp

@if(empty($cart))
<h1 class="text-center my-5">Cart is empty</h1>
@else
<div class="page-content">
    <div class="cart">
        <div class="container">
            <h1 class="text-center mb-5 mt-4">Shopping Cart</h1>
            <div class="row">
                <div class="col-lg-8">
                    <table class="table table-cart table-mobile">
                        <tbody>
                            @foreach ($cart as $item)
                                @php 
                                    $product = \App\Models\Product::find($item['productId']);
                                    $price = $product->price ?? 0;
                                    $quantity = $item['quantity'] ?? 1;
                                @endphp
                                <tr>
                                    <td>
                                        <a href="{{ route('product.show', $product->slug) }}">
                                            <img src="{{ $product->feature_image }}" 
                                                 alt="{{ $product->name }}" class="img-fluid" 
                                                 style="height:100px;width:100px;object-fit:cover;">
                                        </a>
                                    </td>
                                    <td>
                                        <h3><a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a></h3>
                                        <small>Quantity: {{ $quantity }}</small>
                                    </td>
                                    <td>{{ $currency }}{{ number_format($price,2) }}</td>
                                    <td>{{ $currency }}{{ number_format($price * $quantity,2) }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('cart.remove') }}">
                                            @csrf
                                            <input type="hidden" name="productId" value="{{ $product->id }}">
                                            <button class="cart-delete-btn"><i class="fa fa-trash-o"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <a href="{{ route('frontend.shop') }}" class="contact-submit-btn d-inline-block">CONTINUE SHOPPING</a>
                </div>

                <aside class="col-lg-4">
                    <h3>Cart Totals</h3>
                    <table class="table table-summary">
                        <tbody>
                            @php
                                $total = 0;
                                foreach($cart as $item) {
                                    $p = \App\Models\Product::find($item['productId']);
                                    $total += ($p->price ?? 0) * ($item['quantity'] ?? 1);
                                }
                            @endphp
                            <tr class="summary-total text-center">
                                <td>Total:</td>
                                <td>{{ $currency }}{{ number_format($total,2) }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <form action="#" method="POST">
                        @csrf
                        <button type="submit" class="contact-submit-btn btn-order btn-block mb-3">Proceed To Checkout</button>
                    </form>
                </aside>
            </div>
        </div>
    </div>
</div>
@endif
@endsection