@extends('frontend.pages.master')

@section('content')

<style>
    .success-container {
        background: white;
        border-radius: 10px;
        padding: 40px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        max-width: 600px;
        margin: 0 auto;
    }
    .invoice-btn {
        background: linear-gradient(45deg, #1E2D7D, #2c3e50);
        border: none;
        padding: 12px 30px;
        font-size: 16px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    .invoice-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
</style>

<div class="container">
    <div class="success-container text-center mt-5">
      <div class="mb-4">
          <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
      </div>
        
        <h1 class="text-success mb-3">Order Placed Successfully!</h1>
        <p class="lead mb-3">Thank you for shopping with us. Your order has been placed successfully!</p>
        
        <div class="order-info bg-light p-3 rounded mb-4">
            <h5 class="mb-2">Order Details</h5>
            <p class="mb-1"><strong>Order Number:</strong> {{$order->order_number}}</p>
            <p class="mb-1"><strong>Total Amount:</strong> £{{ number_format($order->total_amount, 2) }}</p>
            <p class="mb-0"><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
        </div>
        
        <div class="mt-4">
            <a href="{{ route('order.invoice', $order) }}" 
               target="_blank" 
               class="btn invoice-btn text-white">
                <i class="fas fa-file-invoice me-2"></i> Download Invoice
            </a>
        </div>
        
        <div class="mt-4">
            <small class="text-muted">
                You will receive an order confirmation email shortly.
            </small>
        </div>
    </div>
</div>

@endsection