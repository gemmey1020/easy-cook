<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Subscription Invoice</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <style>
        @page {
            margin: 100px 25px;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        header {
            position: fixed;
            top: -80px;
            left: 0;
            right: 0;
            height: 60px;
        }
        footer {
            position: fixed;
            bottom: -50px;
            left: 0;
            right: 0;
            height: 40px;
            font-size: 0.8rem;
            color: #6c757d;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }
        .header-logo img {
            max-width: 120px;
        }
        .invoice-header {
            text-align: right;
        }
        .invoice-header h1 {
            font-size: 1.75rem;
            color: #343a40;
            margin: 0;
        }
        .invoice-details p {
            font-size: 0.9rem;
            color: #6c757d;
            margin: 0;
        }
        .info-card {
            margin-top: 20px;
        }
        .section-title {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table th,
        .table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            vertical-align: middle;
        }
        .table th {
            background: #f1f1f1;
            text-align: left;
        }
        .meal-img {
            max-width: 50px;
            max-height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }
        .invoice-note {
            font-size: 0.9rem;
            margin-top: 20px;
            color: #6c757d;
        }
        .content-body {
            margin-top: 80px;
            margin-bottom: 60px;
        }
        .meal-details-table th,.meal-details-table td {
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <div class="invoice-container header-content">
            <div class="header-logo">
                <img src="{{ public_path('assets/img/logo.png') }}" alt="Logo">
            </div>
            <div class="invoice-header text-right">
                <h1>Invoice</h1>
                <div class="invoice-details">
                    @php
                        $invoiceDate = $subscription->created_at
                            ->timezone('Africa/Cairo')
                            ->format('d/m/Y');
                        $invoiceNo = 'INV-' . $subscription->created_at->format('Ymd') . '-' . $subscription->id;
                    @endphp
                    <p><strong>No:</strong> {{ $invoiceNo }}</p>
                    <p><strong>Date:</strong> {{ $invoiceDate }}</p>
                </div>
            </div>
        </div>
    </header>

    <footer>
        <div class="invoice-container">
            <span>Thank you for your business!</span> |
            <span>Easy Cook <a href="{{ route('user-dashboard.subscriptions') }}">Your Dashboard</a> </span>
        </div>
    </footer>

    <div class="content-body">
        <div class="invoice-container">
            {{-- Invoiced To --}}
            <div class="info-card">
                <div class="section-title">Invoiced To</div>
                <table class="table">
                    <tr>
                        <td>Name:</td>
                        <td>
                            @if($address && $address->user)
                                {{ $address->user->first_name . ' ' . $address->user->last_name }}
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td>
                            {{ $address->user->email ?? 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <td>Phone:</td>
                        <td>{{ optional(optional($address)->user)->phone ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Company:</td>
                        <td>{{ optional(optional($address)->user)->company_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Total Price:</td>
                        <td>{{ number_format($subscription->price, 2) }} LE</td>
                    </tr>
                    <tr>
                        <td>City:</td>
                        <td>
                            @if($address)
                                {{ $address->town_city }}, {{ $address->postcode }}
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Street:</td>
                        <td>{{ optional($address)->street_address ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>

            <div class="info-card">
                <div class="section-title">Meals Details</div>
                <table class="table meal-details-table">
                    <thead>
                    <tr>
                        <th class="text-center">Plan Type</th>
                        <th class="text-center">No. of days</th>
                        <th class="text-center">
                            Price
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">{{ str_replace('_', ' ', $subscription->subscription_type) }}</td>
                            <td class="text-center">{{ $subscription->days_per_week }}</td>
                            <td class="text-center">
                                <div style="font-weight: normal; font-size: 0.9em; margin-top: .25em; line-height:1.4;">
                                    {{-- Subtotal (before VAT) --}}
                                    <div>Subtotal: {{ number_format($sub_total ?? 0, 2) }} LE</div>

                                    {{-- Discount --}}
                                    <div>
                                        Discount{{ !empty($discount_label) ? ' (' . $discount_label . ')' : '' }}:
                                        -{{ number_format($discount_amount ?? 0, 2) }} LE
                                    </div>

                                    {{-- After Discount --}}
                                    <div>Total After Discount: {{ number_format($after_discount ?? 0, 2) }} LE</div>

                                    {{-- VAT --}}
                                    <div>VAT ({{ (int) (($vat_rate ?? 0) * 100) }}%): {{ number_format($vat_amount ?? 0, 2) }} LE</div>
                                </div>
                                <hr />
                                <strong>
                                    Net Total: {{ number_format($grand_total ?? 0, 2) }} LE
                                </strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            // ضع الإحداثيات (x, y) بالنسبة للنقطة في الصفحة. تأكد من القياسات بعد معاينة PDF.
            $font = $fontMetrics->getFont("Arial", "normal");
            $size = 8;
            $pageText = "Page {PAGE_NUM} of {PAGE_COUNT}";
            // إحداثيات تقريبية: من اليسار 500، من الأعلى 820. عدّل حسب حجم الصفحة.
            $x = 520;
            $y = 820;
            $pdf->page_text($x, $y, $pageText, $font, $size, array(0,0,0));
        }
    </script>
</body>
</html>
