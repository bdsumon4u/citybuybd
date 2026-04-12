@extends('backend.layout.template')
@section('body-content')

    <div class="br-pagebody">
        <div class="p-4 br-section-wrapper">
            <div class="mb-3 d-flex justify-content-between">
                <h4>Incomplete Order — {{ $order->token }}</h4>
                <div>
                    <a href="{{ route('order.incomplete.edit', $order->id) }}" class="btn btn-warning">Edit</a>
                    <a href="{{ route('order.incomplete') }}" class="btn btn-secondary">Back to list</a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <p><strong>Name:</strong> {{ $order->name }}</p>
                    <p><strong>Phone:</strong> {{ $order->phone }}</p>
                    <p><strong>Address:</strong> {{ $order->address }}</p>
                    <p><strong>Shipping:</strong> {{ $order->shipping_method_label }} ({{ $order->shipping_amount ?? 0 }})
                    </p>
                    <p><strong>Source:</strong> {{ $order->slave_domain ?? '-' }}</p>
                    <p><strong>Master ID:</strong> {{ $order->master_id ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Sub Total:</strong> {{ $order->sub_total ?? '-' }}</p>
                    <p><strong>Total:</strong> {{ $order->total ?? '-' }}</p>
                    <p><strong>Last Activity:</strong>
                        {{ $order->last_activity_at ? $order->last_activity_at->format('Y-m-d H:i') : '-' }}</p>
                    <p><strong>Forwarding:</strong> {{ $order->forwarding_status ?? 'pending' }}</p>
                    <p><strong>Status:</strong>
                        @if ($order->isCancelled())
                            <span class="badge badge-danger">Cancelled</span>
                        @else
                            <span class="badge badge-warning">Incomplete</span>
                        @endif
                    </p>
                    @if ($order->isCancelled() && $order->cancellation_reason)
                        <p><strong>Cancellation Reason:</strong> <span
                                class="text-danger">{{ $order->cancellation_reason }}</span></p>
                    @endif
                </div>
            </div>

            <hr>

            <!-- <h5>Cart Snapshot</h5> -->
            @if ($order->cart_snapshot && is_array($order->cart_snapshot) && count($order->cart_snapshot))
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->cart_snapshot as $item)
                                <tr>
                                    <td>{{ $item['name'] ?? ($item['title'] ?? 'Unnamed product') }}</td>
                                    <td>{{ $item['quantity'] ?? ($item['qty'] ?? 1) }}</td>
                                    <td>{{ isset($item['price']) ? number_format($item['price'], 2) : (isset($item['unit_price']) ? number_format($item['unit_price'], 2) : '-') }}
                                    </td>
                                    <td>
                                        @php
                                            $qty = $item['quantity'] ?? ($item['qty'] ?? 1);
                                            $price = $item['price'] ?? ($item['unit_price'] ?? 0);
                                        @endphp
                                        {{ number_format($qty * $price, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <!-- <p>No cart snapshot available.</p> -->
            @endif

        </div>
    </div>

@endsection
