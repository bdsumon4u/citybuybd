@extends('backend.layout.template')
@section('body-content')
    <div class="br-pagetitle">
        <div>
            <h4>xSell Orders</h4>
            <p class="mg-b-0">
                Orders that qualified for the xSell bonus for {{ $payroll->month_name }} {{ $payroll->year }}
            </p>
        </div>
    </div>

    <div class="br-pagebody">
        <div class="br-section-wrapper pd-20">
            <div class="mb-3 d-flex flex-wrap align-items-center justify-content-between">
                <div>
                    <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mb-1">
                        {{ $payroll->user->name ?? 'N/A' }}
                    </h6>
                    <div class="text-muted">
                        xSell bonus: ৳{{ number_format($payroll->xsell_bonus_amount ?? 0, 2) }}
                    </div>
                </div>
                <div>
                    <a href="{{ route('admin.payroll.monthly', ['month' => $payroll->month, 'year' => $payroll->year]) }}"
                        class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to payroll
                    </a>
                </div>
            </div>

            @if ($xsellOrders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Phone</th>
                                <th>Delivered At</th>
                                <th>Ordered Qty</th>
                                <th>Delivered Qty</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($xsellOrders as $index => $xsellOrder)
                                @php $order = $xsellOrder['order']; @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->name ?? '-' }}</td>
                                    <td>{{ $order->phone ?? '-' }}</td>
                                    <td>{{ optional($order->delivered_at)->format('d M Y, h:i A') ?? '-' }}</td>
                                    <td>{{ $xsellOrder['ordered_quantity'] }}</td>
                                    <td>{{ $xsellOrder['delivered_quantity'] }}</td>
                                    <td>{{ $xsellOrder['bonus_reason'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info mb-0">
                    No xSell orders were found for this payroll month.
                </div>
            @endif
        </div>
    </div>
@endsection
