@extends('backend.layout.template')
@section('body-content')

    <div class="mt-4 container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-end">
                    <div class="mb-3 col-lg-4 mb-lg-0">
                        <h4 class="mb-1">Duplicate Phone Orders</h4>
                        <p class="mb-0 text-muted">
                            Orders from customers with multiple orders (duplicate phone numbers).
                        </p>
                    </div>
                    <div class="mb-3 col-lg-3 mb-lg-0">
                        <form method="GET" action="{{ route('order.orderDouble') }}">
                            <div class="input-group w-100">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Page Length</span>
                                </div>
                                <select name="per_page" class="form-control" onchange="this.form.submit()">
                                    @foreach ([25, 50, 100, 200, 500, 1000, 1500, 2000] as $length)
                                        <option value="{{ $length }}"
                                            {{ (int) $perPage === $length ? 'selected' : '' }}>
                                            {{ $length }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="mb-3 col-lg-3 mb-lg-0">
                        &nbsp;
                    </div>
                    <div class="col-lg-2">
                        <form action="{{ route('selected_status') }}" method="post" id="all_status_form">
                            @csrf
                            <input type="hidden" id="all_status" name="all_status">
                            <input type="hidden" class="bulk_over_cod_input" name="status_over_cod">
                            <select name="status" id="status" class="form-control">
                                <option value="">Bulk Status</option>
                                <option value="1">Processing</option>
                                <option value="2">Courier Entry</option>
                                <option value="17">Printed Invoice</option>
                                <option value="16">Total Courier</option>
                                <option value="7">On Delivery</option>
                                <option value="8">No Response 1</option>
                                <option value="9">No Response 2</option>
                                <option value="11">Courier Hold</option>
                                <option value="18">Pending Return</option>
                                <option value="12">Return</option>
                                <option value="13">Partial Delivery</option>
                                <option value="14">Paid Return</option>
                                <option value="5">Delivery</option>
                                <option value="4">Cancel</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3 br-pagebody">
            <div class="br-section-wrapper">
                <div class="table-responsive">
                    <table class="table mb-0 table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" class="chkCheckAll">
                                </th>
                                <th>#Sl</th>
                                <th>Invoice</th>
                                <th>Customer</th>
                                <th>Phone</th>
                                <th>Products</th>
                                <th>Courier</th>
                                <th>Courier Status</th>
                                <th>Created At</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Assigned</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                @php
                                    $statusLabels = [
                                        \App\Models\Order::STATUS_PROCESSING => 'Processing',
                                        \App\Models\Order::STATUS_PENDING_DELIVERY => 'Courier Entry',
                                        \App\Models\Order::STATUS_ON_HOLD => 'On Hold',
                                        \App\Models\Order::STATUS_CANCEL => 'Canceled',
                                        \App\Models\Order::STATUS_COMPLETED => 'Delivery',
                                        \App\Models\Order::STATUS_PENDING_PAYMENT => 'Pending Payment',
                                        \App\Models\Order::STATUS_ON_DELIVERY => 'On Delivery',
                                        \App\Models\Order::STATUS_NO_RESPONSE1 => 'No Response 1',
                                        \App\Models\Order::STATUS_NO_RESPONSE2 => 'No Response 2',
                                        \App\Models\Order::STATUS_COURIER_HOLD => 'Courier Hold',
                                        \App\Models\Order::STATUS_ORDER_RETURN => 'Return',
                                        \App\Models\Order::STATUS_PARTIAL_DELIVERY => 'Partial Delivery',
                                        \App\Models\Order::STATUS_PAID_RETURN => 'Paid Return',
                                        \App\Models\Order::STATUS_STOCK_OUT => 'Stock Out',
                                        \App\Models\Order::STATUS_TOTAL_DELIVERY => 'Total Courier',
                                        \App\Models\Order::STATUS_PRINTED_INVOICE => 'Printed Invoice',
                                        \App\Models\Order::STATUS_PENDING_RETURN => 'Pending Return',
                                    ];
                                    $statusLabel = $statusLabels[$order->status] ?? 'N/A';
                                @endphp
                                <tr>
                                    <td>
                                        <input type="checkbox" class="sub_chk" data-id="{{ $order->id }}">
                                    </td>
                                    <td>{{ ($orders->firstItem() ?? 1) + $loop->index }}</td>
                                    <td>INV-{{ $order->id }}</td>
                                    <td>
                                        <div class="font-weight-bold">{{ $order->name ?? 'N/A' }}</div>
                                        <div class="text-muted">{{ $order->address ?? 'N/A' }}</div>
                                    </td>
                                    <td><a href="tel:{{ $order->phone ?? 'N/A' }}">{{ $order->phone ?? 'N/A' }}</a></td>
                                    <td>
                                        @foreach ($order->many_cart as $cart)
                                            <div>
                                                {{ $cart->quantity }} x {{ $cart->product->name ?? 'N/A' }}
                                            </div>
                                        @endforeach
                                    </td>
                                    <td>{!! $order->my_courier !!}</td>
                                    <td>{{ $order->courier_status ?? 'N/A' }}</td>
                                    <td>{{ $order->created_at->format('d M, Y h:i A') }}</td>
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
                                    <td colspan="13" class="text-center text-danger">No duplicate phone orders found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 d-flex justify-content-center">
                    {{ $orders->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection
