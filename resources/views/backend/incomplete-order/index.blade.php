@extends('backend.layout.template')
@section('body-content')

<!-- Custom Incomplete Order Statistics -->
<div class="px-1 container-fluid mb-3">
    <div class="row">
        <div class="col-md-3">
            <a href="{{ route('order.incomplete') }}" style="text-decoration: none;">
                <div class="card shadow-base bd-0 rounded-right" style="cursor: pointer; {{ !$statusFilter ? 'border: 3px solid #007bff;' : '' }}">
                    <div class="overflow-hidden rounded shadow row no-gutters">
                        <div class="col-md-3 d-flex align-items-center justify-content-center" style="background: #6f42c1">
                            <i class="text-white fas fa-cart-arrow-down fa-2x"></i>
                        </div>
                        <div class="col-md-9 d-flex align-items-center" style="background: #6f42c1">
                            <div class="py-0 text-center text-white py-md-3 w-100">
                                <h6 class="mb-1 text-uppercase">Total Orders</h6>
                                <h4 class="mb-0 fw-bold">{{ $totalIncomplete }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('order.incomplete', ['status' => 'cancelled']) }}" style="text-decoration: none;">
                <div class="card shadow-base bd-0 rounded-right" style="cursor: pointer; {{ $statusFilter === 'cancelled' ? 'border: 3px solid #007bff;' : '' }}">
                    <div class="overflow-hidden rounded shadow row no-gutters">
                        <div class="col-md-3 d-flex align-items-center justify-content-center" style="background: #dc3545">
                            <i class="text-white fas fa-times-circle fa-2x"></i>
                        </div>
                        <div class="col-md-9 d-flex align-items-center" style="background: #dc3545">
                            <div class="py-0 text-center text-white py-md-3 w-100">
                                <h6 class="mb-1 text-uppercase">Cancelled</h6>
                                <h4 class="mb-0 fw-bold">{{ $totalCancelled }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

@include('backend.includes.statistics')

<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<div class="br-pagebody">
    <div class="br-section-wrapper">
        <div class="p-3 mb-2 row justify-content-between">
            <div class="col-auto">
                <h4 class="tx-20">Incomplete Orders</h4>
                @if(auth()->user()->role == 1)
                    <button id="deleteAllSelected" class="mb-2 btn btn-danger">Delete Selected</button>
                @endif
            </div>

            <div class="col-auto">
                <form method="get" action="{{ route('order.incomplete') }}" class="form-inline">
                    <input type="text" name="search" id="search_input" class="form-control" placeholder="Search token / name / phone" value="{{ request('search') }}">
                    <button type="submit" class="ml-2 btn btn-primary">Search</button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="mt-3 table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        @if(auth()->user()->role == 1)
                            <th><input type="checkbox" class="chkCheckAll"></th>
                        @endif
                        <th>#</th>
                        <!-- <th>Token</th> -->
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Product</th>
                        <th>Last Activity</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Cancellation Reason</th>
                        <th>User</th>
                        <th style="width:160px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($incompletes as $in)
                        <tr>
                            @if(auth()->user()->role == 1)
                                <td><input type="checkbox" class="sub_chk" data-id="{{ $in->id }}"></td>
                            @endif
                            <td>{{ $loop->iteration + ($incompletes->currentPage()-1) * $incompletes->perPage() }}</td>
                            <!-- <td>{{ $in->token }}</td> -->
                            <td>{{ $in->name }}</td>
                            <td>{{ $in->phone }}</td>
                            <td>
                                @if($in->product)
                                    <a href="{{ route('details', $in->product->slug) }}" target="_blank">{{ $in->product->slug }}</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $in->last_activity_at ? $in->last_activity_at->format('Y-m-d H:i:s') : '-' }}</td>
                            <td>{{ isset($in->total) ? number_format($in->total, 2) : '-' }}</td>
                            <td>
                                @if($in->isCancelled())
                                    <span class="badge badge-danger">Cancelled</span>
                                @else
                                    <span class="badge badge-warning">Incomplete</span>
                                @endif
                            </td>
                            <td>
                                @if($in->isCancelled() && $in->cancellation_reason)
                                    <small class="text-muted">{{ Str::limit($in->cancellation_reason, 50) }}</small>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $in->user->name ?? '-' }}</td>
                            <!-- <td>
                                <a href="{{ route('order.incomplete.show', $in->id) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('order.incomplete.edit', $in->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('order.incomplete.destroy', $in->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td> -->
                            <td>
                                <a href="{{ route('order.incomplete.show', $in->id) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('order.incomplete.edit', $in->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                
                                @if(auth()->user()->role == 1)
                                    {{-- Only admins can delete --}}
                                    <form action="{{ route('order.incomplete.destroy', $in->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Are you sure you want to delete this incomplete order?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                @else
                                    {{-- Employees and Managers can only cancel if not already cancelled --}}
                                    @if(!$in->isCancelled())
                                        <button class="btn btn-sm btn-secondary" onclick="showCancelModal({{ $in->id }})">Cancel</button>
                                    @endif
                                @endif

                                <!-- Convert button -->
                                @if(!$in->isCancelled())
                                    <form action="{{ route('order.incomplete.convert', $in->id) }}" method="POST" style="display:inline-block">
                                        @csrf
                                        <button class="btn btn-sm btn-success" onclick="return confirm('Convert this incomplete order to completed order?');">Convert</button>
                                    </form>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr><td colspan="{{ auth()->user()->role == 1 ? '11' : '10' }}" class="text-center">No incomplete orders found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-2">{{ $incompletes->links() }}</div>
    </div>
</div>

<script>
$(document).ready(function () {
    console.log("Bulk delete script loaded"); // Debug

    // Select/Deselect all
    $('.chkCheckAll').on('click', function() {
        console.log("Select all clicked");
        $('.sub_chk').prop('checked', $(this).prop('checked'));
    });

    // Bulk delete
    $('#deleteAllSelected').on('click', function() {
        console.log("Delete Selected clicked");

        var allIds = [];
        $('.sub_chk:checked').each(function() {
            allIds.push($(this).data('id'));
        });
        console.log("Selected IDs:", allIds);

        if(allIds.length <= 0){
            alert("Please select at least one row.");
            return;
        }

        if(confirm("Are you sure you want to delete selected orders?")) {
            $.ajax({
                url: "{{ route('order.incomplete.bulk-delete') }}",
                type: 'POST',
                data: {
                    ids: allIds,
                    _token: "{{ csrf_token() }}",
                    _method: 'DELETE'
                },
                success: function(response){
                    console.log("AJAX success:", response);
                    alert(response.success);
                    location.reload();
                },
                error: function(xhr){
                    console.error("AJAX error:", xhr.responseText);
                    alert('Something went wrong!');
                }
            });
        }
    });
});

// Cancel modal function
function showCancelModal(orderId) {
    let reason = prompt("Please enter cancellation reason:", "");
    if (reason !== null) {
        let form = $('<form>')
            .attr('action', "{{ url('') }}/incomplete/" + orderId + "/cancel")
            .attr('method', 'POST')
            .html('<input type="hidden" name="_token" value="{{ csrf_token() }}">' +
                  '<input type="hidden" name="cancellation_reason" value="' + reason.replace(/"/g, '&quot;') + '">');
        $(form).appendTo('body').submit();
    }
}
</script>

@endsection
