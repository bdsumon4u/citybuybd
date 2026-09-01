@extends('backend.layout.template')
@section('body-content')
    <div class="br-pagetitle">
        <div>
            <h4>Manual / Add Orders</h4>
            <p class="mg-b-0">
                Orders created manually by staff that were delivered in {{ $payroll->month_name }} {{ $payroll->year }}
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
                        Manual Order bonus: ৳{{ number_format($payroll->manual_order_bonus_amount ?? 0, 2) }}
                        ({{ $manualOrders->count() }} delivered orders)
                    </div>
                </div>
                <div>
                    <a href="{{ route('admin.payroll.monthly', ['month' => $payroll->month, 'year' => $payroll->year]) }}"
                        class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to payroll
                    </a>
                </div>
            </div>

            @if ($manualOrders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Phone</th>
                                <th>Order Type</th>
                                <th>Delivered At</th>
                                <th>Delivered Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($manualOrders as $index => $order)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <a href="{{ route('order.edit', $order->id) }}" target="_blank" class="font-weight-bold text-primary">
                                            #{{ $order->id }}
                                        </a>
                                    </td>
                                    <td>{{ $order->name ?? '-' }}</td>
                                    <td>{{ $order->phone ?? '-' }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $order->order_type ?? 'manual' }}</span>
                                    </td>
                                    <td>{{ optional($order->delivered_at)->format('d M Y, h:i A') ?? '-' }}</td>
                                    <td>{{ $order->delivered_quantity ?: ($order->ordered_quantity ?: 1) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info mb-0">
                    No delivered manual orders were found for this payroll month.
                </div>
            @endif
        </div>
    </div>
@endsection
