@extends('backend.layout.template')
@section('body-content')

<div class="br-pagebody">
    <div class="row">
        <div class="col-lg-12">
            <div class="overflow-hidden card bd-0 pd-15">
                <h4 class="mb-4 text-center">Order Notes Management</h4>

                <div class="mb-4">
                    <h5>Add New Order Note</h5>
                    <form action="{{ route('settings.orderNoteStore') }}" method="POST" class="mb-4">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <textarea name="note" class="form-control" rows="3" placeholder="Enter order note (e.g., Phone Off, Call not received, etc.)" required></textarea>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary btn-block">Add Note</button>
                            </div>
                        </div>
                    </form>
                </div>

                <hr>

                <div class="mt-4">
                    <h5>Existing Order Notes</h5>
                    @if(session('message'))
                        <div class="alert alert-{{ session('alert-type', 'info') }}">
                            {{ session('message') }}
                        </div>
                    @endif

                    @if($notes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Note</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($notes as $note)
                                    <tr>
                                        <td>{{ $note->note }}</td>
                                        <td>
                                            <span class="badge badge-{{ $note->status ? 'success' : 'danger' }}">
                                                {{ $note->status ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <form action="{{ route('settings.orderNoteUpdate', $note->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <div class="input-group">
                                                    <textarea name="note" class="form-control form-control-sm" rows="2" required>{{ $note->note }}</textarea>
                                                    <div class="input-group-append">
                                                        <div class="py-1 input-group-text">
                                                            <input type="checkbox" name="status" value="1" {{ $note->status ? 'checked' : '' }}>
                                                        </div>
                                                    </div>
                                                    <div class="input-group-append">
                                                        <button type="submit" class="btn btn-sm btn-info">Update</button>
                                                    </div>
                                                </div>
                                            </form>
                                            <form action="{{ route('settings.orderNoteDestroy', $note->id) }}" method="POST" class="mt-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this order note?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No order notes found. Add one above.</p>
                    @endif
                </div>

                <div class="mt-4 alert alert-info">
                    <strong>Note:</strong> When creating or editing an order, you can select one of these pre-saved notes or type a custom note.
                </div>
            </div>
        </div>
    </div>
</div>

@endsection











