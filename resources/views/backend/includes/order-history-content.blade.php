@php
    $role = Auth::user()?->role;
    $editRouteName = match ((int) $role) {
        1 => 'order.edit',
        2 => 'manager.order.edit',
        3 => 'employee.order.edit',
        default => 'order.edit',
    };

    $statusConfig = [
        1 => ['label' => 'Processing', 'class' => 'badge-primary'],
        2 => ['label' => 'Courier Entry', 'class' => 'badge-warning text-dark'],
        3 => ['label' => 'On Hold', 'class' => 'badge-warning text-dark'],
        4 => ['label' => 'Cancel', 'class' => 'badge-danger'],
        5 => ['label' => 'Delivery', 'class' => 'badge-success'],
        6 => ['label' => 'Pending Payment', 'class' => 'badge-warning text-dark'],
        7 => ['label' => 'On Delivery', 'class' => 'badge-info'],
        8 => ['label' => 'No Response 1', 'class' => 'badge-secondary'],
        9 => ['label' => 'No Response 2', 'class' => 'badge-secondary'],
        11 => ['label' => 'Courier Hold', 'class' => 'badge-warning text-dark'],
        12 => ['label' => 'Return', 'class' => 'badge-danger'],
        13 => ['label' => 'Partial Delivery', 'class' => 'badge-info'],
        14 => ['label' => 'Paid Return', 'class' => 'badge-danger'],
        15 => ['label' => 'Stock Out', 'class' => 'badge-danger'],
        16 => ['label' => 'Total Courier', 'class' => 'badge-info'],
        17 => ['label' => 'Printed Invoice', 'class' => 'badge-info'],
        18 => ['label' => 'Pending Return', 'class' => 'badge-warning text-dark'],
        19 => ['label' => 'Double', 'class' => 'badge-dark'],
    ];
@endphp

<!-- Customer Info & Stats Card -->
<div class="card mb-3 border-light bg-light">
    <div class="card-body p-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 10px;">
            <div>
                <h6 class="mb-1 text-dark font-weight-bold d-flex align-items-center flex-wrap" style="gap: 6px;">
                    <span>{{ $customer['name'] ?? 'Customer' }}</span>
                    <span class="badge badge-secondary px-2 py-1"><i class="fa-solid fa-phone mr-1"></i>{{ $customer['phone'] }}</span>
                </h6>
                @if(!empty($customer['address']))
                    <p class="mb-0 text-muted small"><i class="fa-solid fa-location-dot text-danger mr-1"></i>{{ $customer['address'] }}</p>
                @endif
            </div>
            <div class="d-flex align-items-center flex-wrap" style="gap: 6px;">
                <span class="badge badge-primary px-3 py-2 font-weight-bold" style="font-size: 12px;">
                    Total: {{ $stats['total'] }}
                </span>
                <span class="badge badge-success px-3 py-2 font-weight-bold" style="font-size: 12px;">
                    Delivered: {{ $stats['completed'] }}
                </span>
                <span class="badge badge-danger px-3 py-2 font-weight-bold" style="font-size: 12px;">
                    Returned: {{ $stats['returned'] }}
                </span>
                <span class="badge badge-dark px-3 py-2 font-weight-bold" style="font-size: 12px;">
                    Cancelled: {{ $stats['cancelled'] }}
                </span>
                <span class="badge badge-info px-3 py-2 font-weight-bold" style="font-size: 12px;">
                    Spent: ৳{{ number_format($stats['total_spent']) }}
                </span>
            </div>
        </div>
    </div>
</div>

@if ($orders->isEmpty())
    <div class="alert alert-warning text-center my-4 py-3">
        <i class="fa-solid fa-circle-info mr-1"></i> No previous orders found for this phone number.
    </div>
@else
    <div class="table-responsive" style="max-height: 60vh; overflow-y: auto;">
        <table class="table table-bordered table-hover table-striped mg-b-0" style="font-size: 13px;">
            <thead class="thead-colored thead-dark" style="position: sticky; top: 0; z-index: 5;">
                <tr>
                    <th scope="col" style="width: 85px;">#Invoice</th>
                    <th scope="col" style="width: 110px;">Date</th>
                    <th scope="col" style="width: 90px;">Type</th>
                    <th scope="col">Products & Quantity</th>
                    <th scope="col" style="width: 90px;">Total</th>
                    <th scope="col" style="width: 120px;">Status</th>
                    <th scope="col" style="width: 110px;">Courier</th>
                    <th scope="col" style="width: 110px;">Assigned</th>
                    <th scope="col">Note</th>
                    <th scope="col" style="width: 80px;" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    @php
                        $st = $statusConfig[$order->status] ?? ['label' => 'Unknown (' . $order->status . ')', 'class' => 'badge-secondary'];
                    @endphp
                    <tr>
                        <td class="font-weight-bold align-middle">
                            <a href="{{ route('order.print', $order->id) }}" target="_blank" class="text-primary font-weight-bold" title="Print Invoice">
                                #{{ $order->id }}
                            </a>
                        </td>
                        <td class="align-middle">
                            {{ $order->created_at?->format('d M, Y') }}<br>
                            <small class="text-muted">{{ $order->created_at?->format('h:i A') }}</small>
                        </td>
                        <td class="align-middle">
                            <span class="badge badge-light border text-dark text-capitalize">
                                {{ $order->order_type ?? 'online' }}
                            </span>
                        </td>
                        <td class="align-middle">
                            <div class="d-flex flex-column" style="gap: 4px;">
                                @foreach ($order->many_cart as $cart)
                                    @php
                                        $attrs = array_filter([$cart->color, $cart->size, $cart->model]);
                                    @endphp
                                    <div class="d-flex align-items-center flex-wrap" style="gap: 4px;">
                                        <span class="badge badge-dark">{{ $cart->quantity }}x</span>
                                        <span>{{ $cart->product?->name ?? 'Product' }}</span>
                                        @if (!empty($attrs))
                                            <small class="text-muted">({{ implode(', ', $attrs) }})</small>
                                        @endif
                                        <span class="text-muted small ml-auto">৳{{ number_format($cart->price * $cart->quantity) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                        <td class="align-middle font-weight-bold text-nowrap">
                            ৳{{ number_format($order->total) }}
                        </td>
                        <td class="align-middle">
                            <span class="badge {{ $st['class'] }} px-2 py-1 text-nowrap">
                                {{ $st['label'] }}
                            </span>
                        </td>
                        <td class="align-middle">
                            <div>{{ $order->couriers?->name ?? '-' }}</div>
                            @if (!empty($order->courier_status))
                                <small class="text-muted">{{ $order->courier_status }}</small>
                            @endif
                        </td>
                        <td class="align-middle">
                            <span class="text-nowrap">{{ $order->user?->name ?? 'Unassigned' }}</span>
                        </td>
                        <td class="align-middle" style="max-width: 180px;">
                            <small class="text-muted">{{ $order->order_note ?: '-' }}</small>
                        </td>
                        <td class="align-middle text-center text-nowrap">
                            <a href="{{ route('order.print', $order->id) }}" target="_blank" class="btn btn-xs btn-outline-info mr-1" title="Print Invoice">
                                <i class="fa-solid fa-print"></i>
                            </a>
                            <a href="{{ route($editRouteName, $order->id) }}" target="_blank" class="btn btn-xs btn-outline-secondary" title="Edit Order">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
