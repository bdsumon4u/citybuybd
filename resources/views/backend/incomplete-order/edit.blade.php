@extends('backend.layout.template')
@section('body-content')

<div class="br-pagebody">
    <div class="br-section-wrapper p-4">
        <h4>Edit Incomplete Order — {{ $order->token }}</h4>

        @if ($errors->any())
            <div class="alert alert-danger"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <form action="{{ route('order.incomplete.update', $order->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $order->name) }}">
                </div>

                <div class="form-group col-md-4">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $order->phone) }}">
                </div>

                <div class="form-group col-md-4">
                    <label>Assign to user</label>
                    <select name="user_id" class="form-control">
                        <option value="">-- none --</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ $order->user_id == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label>Address</label>
                    <textarea name="address" class="form-control" rows="3">{{ old('address', $order->address) }}</textarea>
                </div>

                <div class="form-group col-md-2">
                    <label>Shipping label</label>
                    <input type="text" name="shipping_method_label" class="form-control" value="{{ old('shipping_method_label', $order->shipping_method_label) }}">
                </div>

                <div class="form-group col-md-2">
                    <label>Shipping amount</label>
                    <input type="text" name="shipping_amount" class="form-control" value="{{ old('shipping_amount', $order->shipping_amount) }}">
                </div>

                <div class="form-group col-md-2">
                    <label>Sub total</label>
                    <input type="text" name="sub_total" class="form-control" value="{{ old('sub_total', $order->sub_total) }}">
                </div>

                <div class="form-group col-md-2">
                    <label>Total</label>
                    <input type="text" name="total" class="form-control" value="{{ old('total', $order->total) }}">
                </div>

                <div class="form-group col-md-2">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="0" {{ $order->status == 0 ? 'selected' : '' }}>Incomplete</option>
                        <option value="1" {{ $order->status == 1 ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save changes</button>
            <a href="{{ route('order.incomplete') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>

@endsection
