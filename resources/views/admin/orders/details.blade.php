@extends('admin.pages.master')
@section('title', 'Order Details')

@section('content')
    <div class="container-fluid">

        <div class="row">
            {{-- LEFT SIDE: Products & Order Status --}}
            <div class="col-xl-9">

                {{-- ORDER PRODUCTS --}}
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h5 class="card-title flex-grow-1 mb-0">Order #{{ $order->order_number }}</h5>

                        <div class="me-3">
                            <span class="badge bg-primary">{{ ucfirst($order->status) }}</span>
                        </div>

                        <div class="flex-shrink-0">
                            <a href="{{ route('order.invoice', $order->id) }}" target="_blank"
                                class="btn btn-success btn-sm">
                                <i class="ri-download-2-fill align-middle me-1"></i> Invoice
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive table-card">
                            <table class="table table-nowrap align-middle table-borderless mb-0">
                                <thead class="table-light text-muted">
                                    <tr>
                                        <th scope="col">Product Details</th>
                                        <th scope="col">Item Price</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col" class="text-end">Total Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->orderDetails as $detail)
                                        <tr>
                                            <td>
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 avatar-md bg-light rounded p-1">
                                                        <img src="{{ asset($detail->product->feature_image ?? 'assets/images/no-image.png') }}"
                                                            alt="{{ $detail->product->name ?? '' }}"
                                                            class="img-fluid d-block">
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h5 class="fs-15">
                                                            <a href="#"
                                                                class="link-primary">{{ $detail->product->name ?? '' }}</a>
                                                        </h5>
                                                        @if ($detail->color)
                                                            <p class="text-muted mb-0">Color: <span
                                                                    class="fw-medium">{{ $detail->color->name }}</span></p>
                                                        @endif
                                                        @if ($detail->size)
                                                            <p class="text-muted mb-0">Size: <span
                                                                    class="fw-medium">{{ $detail->size->name }}</span></p>
                                                        @endif
                                                        @if ($detail->ean)
                                                            <p class="text-muted mb-0">EAN: <span
                                                                    class="fw-medium">{{ $detail->ean }}</span></p>
                                                        @endif

                                                        {{-- Customizations Preview --}}
                                                        {{-- Customizations --}}
                                                        @if ($detail->orderCustomisations && $detail->orderCustomisations->count())
                                                            <div class="mt-2">
                                                                <small><strong>Customizations:</strong></small>
                                                                <div class="accordion"
                                                                    id="customizationAccordion{{ $detail->id }}">
                                                                    @foreach ($detail->orderCustomisations as $index => $c)
                                                                        @php
                                                                            $data = json_decode($c->data, true) ?? [];
                                                                        @endphp
                                                                        <div class="accordion-item mb-1">
                                                                            <h2 class="accordion-header"
                                                                                id="heading{{ $detail->id }}{{ $index }}">
                                                                                <button class="accordion-button collapsed"
                                                                                    type="button" data-bs-toggle="collapse"
                                                                                    data-bs-target="#collapse{{ $detail->id }}{{ $index }}"
                                                                                    aria-expanded="false"
                                                                                    aria-controls="collapse{{ $detail->id }}{{ $index }}">
                                                                                    {{ ucfirst($c->method ?? 'N/A') }}
                                                                                    @if ($c->position)
                                                                                        - {{ $c->position }}
                                                                                    @endif
                                                                                    ({{ ucfirst($c->customization_type) }})
                                                                                </button>
                                                                            </h2>
                                                                            <div id="collapse{{ $detail->id }}{{ $index }}"
                                                                                class="accordion-collapse collapse"
                                                                                aria-labelledby="heading{{ $detail->id }}{{ $index }}"
                                                                                data-bs-parent="#customizationAccordion{{ $detail->id }}">
                                                                                <div class="accordion-body">
                                                                                    {{-- Text Customization --}}
                                                                                    @if ($c->customization_type === 'text' && isset($data['text']))
                                                                                        <div class="mb-2">
                                                                                            <strong>Text:</strong>
                                                                                            {{ $data['text'] }}<br>
                                                                                            <strong>Font:</strong>
                                                                                            {{ $data['fontFamily'] ?? 'N/A' }}<br>
                                                                                            <strong>Size:</strong>
                                                                                            {{ $data['fontSize'] ?? 'N/A' }}<br>
                                                                                            <strong>Color:</strong>
                                                                                            {{ $data['color'] ?? 'N/A' }}
                                                                                        </div>
                                                                                    @endif

                                                                                    {{-- Image Customization --}}
                                                                                    @if ($c->customization_type === 'image' && isset($data['src']))
                                                                                        <div class="mb-2">
                                                                                            {!! '<img src="' . $data['src'] . '" alt="Custom Image" style="max-height:100px;">' !!}<br>
                                                                                            <strong>Method:</strong>
                                                                                            {{ $c->method ?? 'N/A' }}<br>
                                                                                            <strong>Position:</strong>
                                                                                            {{ $c->position ?? 'N/A' }}<br>
                                                                                            <strong>Z-Index:</strong>
                                                                                            {{ $c->z_index ?? 'N/A' }}<br>
                                                                                            <strong>Layer ID:</strong>
                                                                                            {{ $c->layer_id ?? 'N/A' }}
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif





                                                    </div>
                                                </div>
                                            </td>
                                            <td>${{ number_format($detail->price, 2) }}</td>
                                            <td>{{ $detail->quantity }}</td>
                                            <td class="fw-medium text-end">${{ number_format($detail->subtotal, 2) }}</td>
                                        </tr>
                                    @endforeach

                                    {{-- Totals --}}
                                    <tr class="border-top border-top-dashed">
                                        <td colspan="2"></td>
                                        <td colspan="2" class="fw-medium p-0">
                                            <table class="table table-borderless mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td>Sub Total :</td>
                                                        <td class="text-end">${{ number_format($order->subtotal, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Shipping Charge :</td>
                                                        <td class="text-end">
                                                            ${{ number_format($order->shipping_charge, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>VAT ({{ $order->vat_percent }}%) :</td>
                                                        <td class="text-end">${{ number_format($order->vat_amount, 2) }}
                                                        </td>
                                                    </tr>
                                                    <tr class="border-top border-top-dashed">
                                                        <th scope="row">Total :</th>
                                                        <th class="text-end">${{ number_format($order->total_amount, 2) }}
                                                        </th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            {{-- RIGHT SIDE: Customer & Shipping --}}
            <div class="col-xl-3">
                {{-- Customer Details --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Customer Details</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>{{ $order->full_name }}</strong></p>
                        <p>{{ $order->email }}</p>
                        <p>{{ $order->phone }}</p>
                    </div>
                </div>

                {{-- Billing Address --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Billing Address</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ $order->billing_full_name }}</p>
                        <p>{{ $order->billing_address_first_line }} {{ $order->billing_address_second_line }}
                            {{ $order->billing_address_third_line }}</p>
                        <p>{{ $order->billing_city }} - {{ $order->billing_postcode }}</p>
                    </div>
                </div>

                {{-- Shipping Address --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Shipping Address</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ $order->full_name }}</p>
                        <p>{{ $order->address_first_line }} {{ $order->address_second_line }}
                            {{ $order->address_third_line }}</p>
                        <p>{{ $order->city }} - {{ $order->postcode }}</p>
                    </div>
                </div>

                {{-- Payment Details --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Payment Details</h5>
                    </div>
                    <div class="card-body">
                        <p>Method: {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                        <p>Total Amount: ${{ number_format($order->total_amount, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection