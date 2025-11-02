@extends('frontend.pages.master')

@section('content')

<div class="d-flex flex-column justify-content-center align-items-center text-center py-5">
    <h1>Order Placed Successfully!</h1>
    <p>Thank you for shopping with us. Your order has been placed successfully...!</p>
    <p>Inv: {{$order->order_number}}</p>
</div>

@endsection