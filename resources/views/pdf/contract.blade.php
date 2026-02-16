<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Estimate #{{ $estimate->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; line-height: 1.5; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>
    <h1>Estimate #{{ $estimate->id }}</h1>

    <p><strong>Client:</strong> {{ $estimate->client_name }}</p>
    <p><strong>Email:</strong> {{ $estimate->client_email }}</p>
    <p><strong>Date:</strong> {{ $estimate->created_at->format('F j, Y') }}</p>

    <h3>Items</h3>
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Taxes</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($estimate->items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 2) }}</td>
                    <td>
                        @foreach($item->itemTaxes as $tax)
                            {{ $tax->name }} ({{ $tax->rate }}%)<br>
                        @endforeach
                    </td>
                    <td>{{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($estimate->discounts->count())
        <h3>Discounts</h3>
        <ul>
            @foreach($estimate->discounts as $discount)
                <li>{{ $discount->name }}: {{ number_format($discount->amount, 2) }}</li>
            @endforeach
        </ul>
    @endif

    @if($estimate->taxes->count())
        <h3>Taxes</h3>
        <ul>
            @foreach($estimate->taxes as $tax)
                <li>{{ $tax->name }}: {{ number_format($tax->amount, 2) }}</li>
            @endforeach
        </ul>
    @endif

    @if($estimate->installments->count())
        <h3>Installments</h3>
        <ul>
            @foreach($estimate->installments as $inst)
                <li>{{ $inst->due_date->format('F j, Y') }}: {{ number_format($inst->amount, 2) }}</li>
            @endforeach
        </ul>
    @endif

    <h3>Total: {{ number_format($estimate->total, 2) }}</h3>
</body>
</html>
