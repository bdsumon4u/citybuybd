@extends('backend.layout.template')
@section('body-content')

@include('backend.includes.statistics')

<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<div class="br-pagebody">
    <div class="br-section-wrapper">
        <div class="p-3 mb-2 row justify-content-between">
            <div class="col-auto">
                <h4 class="tx-20">Incomplete Orders</h4>
                <button id="deleteAllSelected" class="mb-2 btn btn-danger">Delete Selected</button>
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
                        <th><input type="checkbox" class="chkCheckAll"></th>
                        <th>#</th>
                        <!-- <th>Token</th> -->
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Product</th>
                        <th>Last Activity</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>User</th>
                        <th style="width:160px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($incompletes as $in)
                        <tr>
                            <td><input type="checkbox" class="sub_chk" data-id="{{ $in->id }}"></td>
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
                            <td>{{ $in->last_activity_at ? $in->last_activity_at->format('Y-m-d H:i') : '-' }}</td>
                            <td>{{ isset($in->total) ? number_format($in->total, 2) : '-' }}</td>
                            <td>
                                @if($in->isCompleted())
                                    <span class="badge badge-success">Completed</span>
                                @else
                                    <span class="badge badge-warning">Incomplete</span>
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
                                <form action="{{ route('order.incomplete.destroy', $in->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>

                                <!-- Convert button -->
                                <form action="{{ route('order.incomplete.convert', $in->id) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    <button class="btn btn-sm btn-success" onclick="return confirm('Convert this incomplete order to completed order?');">Convert</button>
                                </form>
                            </td>

                        </tr>
                    @empty
                        <tr><td colspan="10" class="text-center">No incomplete orders found.</td></tr>
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
</script>

@endsection
