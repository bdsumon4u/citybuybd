@extends('backend.layout.template')
@section('body-content')

    <div class="container-fluid mt-4">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-end">
                    <div class="col-lg-8 mb-3 mb-lg-0">
                        <h4 class="mb-1">On Courier Too Long</h4>
                        <p class="text-muted mb-0">
                            Orders currently in courier-progress states that have been there for at least
                            {{ $days }} days.
                        </p>
                    </div>
                    <div class="col-lg-4">
                        <form method="GET" action="{{ route('order.onCourierTooLong') }}"
                            class="form-inline justify-content-lg-end">
                            <div class="input-group w-100">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Days</span>
                                </div>
                                <input type="number" min="1" name="days" value="{{ $days }}"
                                    class="form-control" placeholder="Days">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="br-pagebody mt-3">
            <div class="br-section-wrapper">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <thead>
                            <tr>
                                <th>#Sl</th>
                                <th>Invoice</th>
                                <th>Customer</th>
                                <th>Products</th>
                                <th>Courier</th>
                                <th>Courier Status</th>
                                <th>Courier Since</th>
                                <th>Days</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Assigned</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                @php
                                    $courierSince = \Illuminate\Support\Carbon::parse(
                                        $order->courier_since ?? $order->created_at,
                                    );
                                    $statusLabels = [
                                        \App\Models\Order::STATUS_TOTAL_DELIVERY => 'Total Courier',
                                        \App\Models\Order::STATUS_ON_DELIVERY => 'On Delivery',
                                        \App\Models\Order::STATUS_NO_RESPONSE1 => 'No Response 1',
                                        \App\Models\Order::STATUS_NO_RESPONSE2 => 'No Response 2',
                                        \App\Models\Order::STATUS_COURIER_HOLD => 'Courier Hold',
                                        \App\Models\Order::STATUS_PENDING_RETURN => 'Pending Return',
                                    ];
                                    $statusLabel = $statusLabels[$order->status] ?? 'Courier';
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>INV-{{ $order->id }}</td>
                                    <td>
                                        <div class="font-weight-bold">{{ $order->name ?? 'N/A' }}</div>
                                        <div><a href="tel:{{ $order->phone ?? 'N/A' }}">{{ $order->phone ?? 'N/A' }}</a>
                                        </div>
                                        <div class="text-muted">{{ $order->address ?? 'N/A' }}</div>
                                    </td>
                                    <td>
                                        @foreach ($order->many_cart as $cart)
                                            <div>
                                                {{ $cart->quantity }} x {{ $cart->product->name ?? 'N/A' }}
                                            </div>
                                        @endforeach
                                    </td>
                                    <td>{!! $order->my_courier !!}</td>
                                    <td>{{ $order->courier_status ?? 'N/A' }}</td>
                                    <td>{{ $courierSince->format('d M, Y h:i A') }}</td>
                                    <td>{{ $courierSince->diffInDays(now()) }}</td>
                                    <td>৳ {{ $order->total }}</td>
                                    <td>{{ $statusLabel }}</td>
                                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('order.edit', $order->id) }}"
                                            class="btn btn-sm btn-outline-primary">Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center text-danger">No overdue courier orders found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $orders->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection
