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
                                    <input type="text" name="name" class="form-control"
                                        placeholder="Enter order type name (e.g., Phone, WhatsApp, Messenger)" required>
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
                        @if (session('message'))
                            <div class="alert alert-{{ session('alert-type', 'info') }}">
                                {{ session('message') }}
                            </div>
                        @endif

                        @if ($types->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Status</th>
                                            <th>Logo URL</th>
                                            <th style="width: 120px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($types as $type)
                                            <tr>
                                                <td>{{ $type->name }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $type->status ? 'success' : 'danger' }}">
                                                        {{ $type->status ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($type->logo_url)
                                                        <small><img src="{{ $type->logo_url }}" alt="Logo"
                                                                style="max-height: 25px;"></small>
                                                    @else
                                                        <small class="text-muted">—</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-info" title="Edit"
                                                        data-toggle="modal" data-target="#editModal{{ $type->id }}"
                                                        style="width: 32px;">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <form action="{{ route('settings.manualOrderTypeDestroy', $type->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete"
                                                            onclick="return confirm('Are you sure?')" style="width: 32px;">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>

                                            <!-- Edit Modal -->
                                            <div class="modal fade" id="editModal{{ $type->id }}" tabindex="-1"
                                                role="dialog">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit {{ $type->name }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form
                                                            action="{{ route('settings.manualOrderTypeUpdate', $type->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label>Type Name</label>
                                                                    <input type="text" name="name"
                                                                        class="form-control" value="{{ $type->name }}"
                                                                        required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Logo URL</label>
                                                                    <input type="url" name="logo_url"
                                                                        class="form-control"
                                                                        value="{{ $type->logo_url ?? '' }}"
                                                                        placeholder="https://example.com/logo.png">
                                                                    @if ($type->logo_url)
                                                                        <small class="text-muted d-block mt-2">Current: <img
                                                                                src="{{ $type->logo_url }}" alt="Logo"
                                                                                style="max-height: 40px; margin-top: 5px;"></small>
                                                                    @endif
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>
                                                                        <input type="checkbox" name="status" value="1"
                                                                            {{ $type->status ? 'checked' : '' }}>
                                                                        Active
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Cancel</button>
                                                                <button type="submit"
                                                                    class="btn btn-primary">Update</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">No manual order types found. Add one above.</p>
                        @endif
                    </div>

                    <div class="mt-4 alert alert-info">
                        <strong>Note:</strong> When creating an order manually, you can select one of these types. If no
                        type is selected, the order will be saved as "Manual".
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
