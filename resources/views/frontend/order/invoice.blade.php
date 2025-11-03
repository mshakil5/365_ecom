<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @php
        $company = \App\Models\CompanyDetails::select(
            'company_name',
            'company_logo',
            'address1',
            'email1',
            'phone1',
            'website',
            'company_reg_number',
            'vat_number',
            'business_name',
        )->first();
    @endphp
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html">
    <title>{{ $company->company_name }} - Invoice</title>
    <style>
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

    @media print {
        body {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            font-size: 12px;
        }
    }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            padding: 5px;
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #dee2e6;
        }

        .bg-light {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body onload="window.print(); setTimeout(() => window.close(), 100);">

    <section class="invoice">
        <div style="max-width:1170px; margin:20px auto;">

            <table>
                <tbody>
                    <tr>
                        <td style="width:50%; text-align:left;">
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/company/' . $company->company_logo))) }}"
                                width="120px" />
                        </td>
                        <td style="width:50%; text-align:right;">
                            <h1 style="color:blue;">INVOICE</h1>
                        </td>
                    </tr>
                </tbody>
            </table>

            <br>

            <table>
                <tbody>
                    <tr>
                        <td style="width:50%; vertical-align:top;">
                            <h5>Delivery To</h5>
                            <p>{{ $order->full_name }}</p>
                            <p>{{ $order->email }}</p>
                            <p>{{ $order->phone }}</p>
                            <p>
                                {{ implode(
                                    ', ',
                                    array_filter([
                                        $order->address_first_line,
                                        $order->address_second_line,
                                        $order->address_third_line,
                                        $order->city,
                                        $order->postcode,
                                    ]),
                                ) }}
                            </p>
                        </td>
                        <td style="width:50%; vertical-align:top; text-align:right;">
                            <p>Invoice No: {{ $order->order_number }}</p>
                            <p>Date: {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}</p>
                            <p>Payment Method: {{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</p>
                            <p>Order Type: Website</p>
                            <p>Collection Type:
                                {{ $order->shipping_method == 0 ? 'Ship to Address' : 'Pick Up In Store' }}</p>
                        </td>
                    </tr>
                </tbody>
            </table>

            <br>

            <table class="table-bordered">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price/unit</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderDetails as $detail)
                        @php
                            $productName = $detail->product_id
                                ? $detail->product->name ?? 'Unknown'
                                : 'Unknown Product';
                            $customizations = $detail->orderCustomisations ?? [];
                        @endphp
                        <tr>
                            <td>
                                {{ $productName }}
                                @if ($detail->size_id)
                                    <br><small style="font-size:10px;">Size:
                                        {{ $detail->size?->name ?? 'N/A' }}</small>
                                @endif
                                @if ($detail->color_id)
                                    <br><small style="font-size:10px;">Color:
                                        {{ $detail->color?->name ?? 'N/A' }}</small>
                                @endif
                                @if ($detail->ean)
                                    <br><small style="font-size:10px;">EAN: {{ $detail->ean }}</small>
                                @endif

                                {{-- Show customizations --}}
                                @if (count($customizations) > 0)
                                    <br>
                                    <small style="font-size:10px; padding-left:5px;">
                                        @foreach ($customizations as $c)
                                            - Type: {{ $c->customization_type }}, Method: {{ $c->method }},
                                            Position: {{ $c->position }}<br>
                                        @endforeach
                                    </small>
                                @endif
                            </td>
                            <td class="text-center">{{ $detail->quantity }}</td>
                            <td class="text-center">£{{ number_format($detail->price, 2) }}</td>
                            <td class="text-right">£{{ number_format($detail->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>


            <table style="margin-top:10px;">
                <tbody>
                    <tr>
                        <td style="width:70%;"></td>
                        <td>Subtotal</td>
                        <td class="text-right">£{{ number_format($order->subtotal, 2) }}</td>
                    </tr>
                    @if ($order->shipping_charge > 0)
                        <tr>
                            <td></td>
                            <td>Shipping</td>
                            <td class="text-right">£{{ number_format($order->shipping_charge, 2) }}</td>
                        </tr>
                    @endif
                    @if ($order->vat_amount > 0)
                        <tr>
                            <td></td>
                            <td>VAT ({{ $order->vat_percent }}%)</td>
                            <td class="text-right">£{{ number_format($order->vat_amount, 2) }}</td>
                        </tr>
                    @endif
                    <tr class="">
                        <td></td>
                        <td>Total</td>
                        <td class="text-right">£{{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            <br><br>

            <div style="font-size:12px; position: fixed; bottom: 0; width: 100%;">
                <table>
                    <tr>
                        <td style="width:50%;"><b>{{ $company->business_name }}</b><br>
                            Registration Number: {{ $company->company_reg_number }}<br>
                            VAT Number: {{ $company->vat_number }}<br>
                            {{ $company->address1 }}
                        </td>
                        <td style="width:50%; text-align:right;"><b>Contact Information</b><br>
                            {{ $company->phone1 }}<br>
                            {{ $company->email1 }}<br>
                            {{ $company->website }}
                        </td>
                    </tr>
                </table>
            </div>

        </div>
    </section>

</body>

</html>