<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $title }} - {{ $date }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 10mm;
        }

        h2 {
            text-align: center;
            margin-bottom: 16px;
        }

        .summary {
            margin-bottom: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        @page {
            size: A4;
            margin: 10mm;
        }

        table {
            page-break-inside: auto;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
    </style>
</head>

<body>
    <h2>{{ $title }} - {{ $date }}</h2>
    <div class="summary">
        <strong>Total Orders:</strong> {{ count($orders) }}
        &nbsp; | &nbsp;
        <strong>Total COD:</strong> {{ number_format($totalCod, 0) }}
    </div>

    <table>
        <thead>
            <tr>
                <th>SL.</th>
                <th>Invoice ID</th>
                <th>Customer Info</th>
                <th>COD</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $index => $order)
                <tr>
                    <td>{{ count($orders) - $index }}</td>
                    <td>INV{{ $order['id'] }}</td>
                    <td>
                        <strong>Name</strong> {{ $order['customer_name'] ?? 'N/A' }}<br>
                        <strong>Phone</strong> {{ $order['customer_phone'] ?? 'N/A' }}<br>
                        <strong>Address</strong> {{ $order['customer_address'] ?? 'N/A' }}
                    </td>
                    <td>{{ isset($order['cod']) ? number_format((float) $order['cod'], 0) : '0' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center;">No orders scanned</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>

</html>
