@extends('backend.layout.template')
@section('body-content')

<div class="br-pagebody">
    <div class="row">
        <div class="col-lg-12">
            <div class="overflow-hidden card bd-0 pd-15">
                <h4 class="mb-4 text-center">Manual Order Types Management</h4>

                <div class="mb-4">
                    <h5>Add New Manual Order Type</h5>
                    <form action="{{ route('settings.manualOrderTypeStore') }}" method="POST" class="mb-4">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <input type="text" name="name" class="form-control" placeholder="Enter order type name (e.g., Phone, WhatsApp, Messenger)" required>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary btn-block">Add Type</button>
                            </div>
                        </div>
                    </form>
                </div>

                <hr>

                <div class="mt-4">
                    <h5>Existing Manual Order Types</h5>
                    @if(session('message'))
                        <div class="alert alert-{{ session('alert-type', 'info') }}">
                            {{ session('message') }}
                        </div>
                    @endif

                    @if($types->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($types as $type)
                                    <tr>
                                        <td>{{ $type->name }}</td>
                                        <td>
                                            <span class="badge badge-{{ $type->status ? 'success' : 'danger' }}">
                                                {{ $type->status ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <form action="{{ route('settings.manualOrderTypeUpdate', $type->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <div class="input-group">
                                                    <input type="text" name="name" value="{{ $type->name }}" class="form-control form-control-sm" required>
                                                    <div class="input-group-append">
                                                        <div class="py-1 input-group-text">
                                                            <input type="checkbox" name="status" value="1" {{ $type->status ? 'checked' : '' }}>
                                                        </div>
                                                    </div>
                                                    <div class="input-group-append">
                                                        <button type="submit" class="btn btn-sm btn-info">Update</button>
                                                    </div>
                                                </div>
                                            </form>
                                            <form action="{{ route('settings.manualOrderTypeDestroy', $type->id) }}" method="POST" class="mt-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this order type?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No manual order types found. Add one above.</p>
                    @endif
                </div>

                <div class="mt-4 alert alert-info">
                    <strong>Note:</strong> When creating an order manually, you can select one of these types. If no type is selected, the order will be saved as "Manual".
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

