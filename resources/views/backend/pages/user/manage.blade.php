@extends('backend.layout.template')
@section('body-content')
    <div class="container-fluid">
        <div id="accordion2" class="accordion accordion-head-colored accordion-primary" role="tablist"
            aria-multiselectable="true">
            <div class="card">
                <div class="card-header" role="tab" id="headingOne">
                    <h6 class="mg-b-0">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo" aria-expanded="true"
                            aria-controls="collapseTwo" class="tx-purple transition">
                            User Filter


                            <i class="fa-duotone fa-arrow-down-arrow-up"></i>
                        </a>
                    </h6>
                </div>
                <!-- card-header -->
                <div id="collapseTwo" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
                    <div class="card-block pd-5" style="background-color: #e9ecef;border: 1px solid lightgrey;">
                        <div class="row pb-3">
                            <div class="col-md-1 mr-5">
                                <a href="" data-toggle="modal" data-target="#add" class="btn btn-success">Add
                                    user</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- card -->
            <!-- ADD MORE CARD HERE -->
        </div>
        <!-- accordion -->
    </div>
    <div class="br-pagebody">
        <div class="br-section-wrapper">

            <!-- Modal for add -->
            <div class="modal fade" id="add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Product</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('user.store') }}" method="POST">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-6">
                                        <label class="font-weight-bold text-dark text-2">Full Name</label>
                                        <input type="text" value="{{ old('name') }}" name="name"
                                            class="form-control form-control-lg" required="required">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label class="font-weight-bold text-dark text-2">E-mail Address</label>
                                        <input type="text" value="{{ old('email') }}" name="email"
                                            class="form-control form-control-lg" required="required">
                                    </div>

                                </div>
                                <div class="form-row">
                                    <div class="form-group col-lg-6">
                                        <label class="font-weight-bold text-dark text-2">Phone</label>
                                        <input type="text" s name="phone" class="form-control form-control-lg"
                                            required="required">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label class="font-weight-bold text-dark text-2">Role</label>
                                        <select name="role" required="required" class="form-control role">

                                            <option value="1">Admin</option>
                                            <option value="2">Mangager</option>
                                            <option value="3">Employee</option>
                                        </select>
                                    </div>

                                </div>

                                <div class="form-row">
                                    <div class="form-group col-lg-6">
                                        <label class="font-weight-bold text-dark text-2">Password</label>
                                        <input type="password" required name="password"
                                            class="form-control form-control-lg">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label class="font-weight-bold text-dark text-2">Re-enter Password</label>
                                        <input type="password" required name="password_confirmation"
                                            class="form-control form-control-lg">
                                    </div>
                                </div>
                                <div class="form-row emp_time">
                                    <div class="form-group col-lg-6">
                                        <label class="font-weight-bold text-dark text-2">Start Time[optional]</label>
                                        <input type="text" value="00:00:00" name="start_time"
                                            class="form-control form-control-lg" required="required">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label class="font-weight-bold text-dark text-2">End Time[optional]</label>
                                        <input type="text" value="23:59:59" name="end_time"
                                            class="form-control form-control-lg" required="required">
                                    </div>

                                </div>
                                <div class="form-row emp_time">
                                    <div class="form-group col-lg-6">
                                        <label class="font-weight-bold text-dark text-2">Daily Salary (৳)</label>
                                        <input type="number" step="0.01" value="0" name="daily_salary"
                                            class="form-control form-control-lg">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label class="font-weight-bold text-dark text-2">Off Days</label>
                                        <input type="text" value="" name="off_days"
                                            class="form-control form-control-lg" placeholder="e.g. Friday,Saturday">
                                        <small class="text-muted">Comma separated: Friday,Saturday</small>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-lg-12">
                                        <label class="font-weight-bold text-dark text-2">Status</label>
                                        <select name="status" class="form-control">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="form-row">

                                    <div class="form-group col-lg-3">
                                        <input type="submit" value="Register" class="btn btn-primary float-right"
                                            data-loading-text="Loading...">
                                    </div>
                                </div>
                            </form>
                            <!-- Customer signup form  End-->


                        </div>

                    </div>
                </div>
            </div>

            <!-- Modal add End -->


            <div class="bd bd-gray-300 rounded ">

                <div class="row">
                    <div class="col-lg-12">

                        <table class="table mg-b-0 table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#Sl</th>
                                    <th scope="col">Name vvv</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Schedule</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i=1 @endphp
                                @foreach ($users as $user)
                                    <tr>
                                        <th scope="row">{{ $i }}</th>

                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if ($user->role == 1)
                                                <span class="btn  btn-sm btn-indigo  ">Admin</span>
                                            @elseif($user->role == 2)
                                                <span class="btn  btn-sm btn-primary  ">Manager</span>
                                            @elseif($user->role == 3)
                                                <span class="btn  btn-sm btn-dark ">Employee</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($user->role == 3 || $user->role == 2)
                                                <p class="mb-0"><b>Start:</b>{{ $user->start_time }}</p>
                                                <p class="mb-0"><b>End:</b>{{ $user->end_time }}</p>
                                                <p class="mb-0"><b>Salary:</b> ৳{{ $user->daily_salary ?? 0 }}/day</p>
                                                <p class="mb-0"><b>Off:</b> {{ $user->off_days ?? 'N/A' }}</p>
                                            @endif


                                        </td>

                                        <td>
                                            @if ($user->status == 0)
                                                <span class="btn  btn-sm btn-danger ">Inactive</span>
                                            @elseif($user->status == 1)
                                                <span class="btn  btn-sm btn-success ">Active</span>
                                            @endif



                                        </td>
                                        <td class="action-button">

                                            <ul>
                                                <li><a href="" data-toggle="modal"
                                                        data-target="#edit{{ $user->id }}"><i
                                                            class="fa-solid fa-pen-to-square tx-17"></i></a></li>
                                                @if (Auth::user()->id == $user->id)
                                                @else
                                                    <li><a href="" data-toggle="modal"
                                                            data-target="#delete{{ $user->id }}"><i
                                                                class="text-danger fa-solid fa-delete-left tx-17"></i></a>
                                                    </li>
                                                @endif


                                            </ul>

                                            <!-- Modal for delete -->
                                            <div class="modal fade" id="delete{{ $user->id }}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Confirm Delete
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Are you sure to want to delete this user?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <form action="{{ route('user.destroy', $user->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <input type="submit" value="Confirm" name="delete"
                                                                    class="btn btn-danger">

                                                            </form>



                                                            <button type="button" class="btn btn-primary"
                                                                data-dismiss="modal">Cancel</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal delete End -->
                                            <!-- Modal for edit start -->
                                            <div class="modal fade" id="edit{{ $user->id }}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <!-- form start -->
                                                            <form action="{{ route('user.update', $user->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <div class="form-row">
                                                                    <div class="form-group col-lg-6">
                                                                        <label
                                                                            class="font-weight-bold text-dark text-2">Full
                                                                            Name fffff</label>
                                                                        <input type="text" value="{{ $user->name }}"
                                                                            name="name"
                                                                            class="form-control form-control-lg"
                                                                            required="required">
                                                                    </div>
                                                                    <div class="form-group col-lg-6">
                                                                        <label
                                                                            class="font-weight-bold text-dark text-2">E-mail
                                                                            Address</label>
                                                                        <input type="text" value="{{ $user->email }}"
                                                                            name="email"
                                                                            class="form-control form-control-lg"
                                                                            required="required">
                                                                    </div>

                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="form-group col-lg-6">
                                                                        <label
                                                                            class="font-weight-bold text-dark text-2">Phone</label>
                                                                        <input type="text" value="{{ $user->phone }}"
                                                                            name="phone"
                                                                            class="form-control form-control-lg"
                                                                            required="required">
                                                                    </div>
                                                                    <div class="form-group col-lg-6">
                                                                        <label
                                                                            class="font-weight-bold text-dark text-2">Role</label>
                                                                        <select name="role" required="required"
                                                                            class="form-control role_two">

                                                                            <option value="1"
                                                                                @if ($user->role == 1) selected @endif>
                                                                                Admin</option>
                                                                            <option value="2"
                                                                                @if ($user->role == 2) selected @endif>
                                                                                Mangager</option>
                                                                            <option value="3"
                                                                                @if ($user->role == 3) selected @endif>
                                                                                Employee</option>
                                                                        </select>
                                                                    </div>

                                                                </div>

                                                                <div class="form-row">
                                                                    <div class="form-group col-lg-6">
                                                                        <label
                                                                            class="font-weight-bold text-dark text-2">Password</label>
                                                                        <input type="password" name="password"
                                                                            class="form-control form-control-lg">
                                                                    </div>
                                                                    <div class="form-group col-lg-6">
                                                                        <label
                                                                            class="font-weight-bold text-dark text-2">Re-enter
                                                                            Password</label>
                                                                        <input type="password"
                                                                            name="password_confirmation"
                                                                            class="form-control form-control-lg">
                                                                    </div>
                                                                </div>
                                                                <div class="form-row ">
                                                                    <div class="form-group col-lg-6">
                                                                        <label
                                                                            class="font-weight-bold text-dark text-2">Start
                                                                            Time[optional]</label>
                                                                        <input type="text"
                                                                            value="{{ $user->start_time }}"
                                                                            name="start_time"
                                                                            class="form-control form-control-lg"
                                                                            required="required">
                                                                    </div>
                                                                    <div class="form-group col-lg-6">
                                                                        <label
                                                                            class="font-weight-bold text-dark text-2">End
                                                                            Time[optional]</label>
                                                                        <input type="text"
                                                                            value="{{ $user->end_time }}" name="end_time"
                                                                            class="form-control form-control-lg"
                                                                            required="required">
                                                                    </div>

                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="form-group col-lg-6">
                                                                        <label
                                                                            class="font-weight-bold text-dark text-2">Daily
                                                                            Salary (৳)</label>
                                                                        <input type="number" step="0.01"
                                                                            value="{{ $user->daily_salary ?? 0 }}"
                                                                            name="daily_salary"
                                                                            class="form-control form-control-lg">
                                                                    </div>
                                                                    <div class="form-group col-lg-6">
                                                                        <label
                                                                            class="font-weight-bold text-dark text-2">Off
                                                                            Days</label>
                                                                        <input type="text"
                                                                            value="{{ $user->off_days }}" name="off_days"
                                                                            class="form-control form-control-lg"
                                                                            placeholder="e.g. Friday,Saturday">
                                                                        <small class="text-muted">Comma separated:
                                                                            Friday,Saturday</small>
                                                                    </div>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="form-group col-lg-12">
                                                                        <label
                                                                            class="font-weight-bold text-dark text-2">Status</label>
                                                                        <select name="status" class="form-control">
                                                                            <option
                                                                                value="1"@if ($user->status == 1) selected @endif>
                                                                                Active</option>
                                                                            <option
                                                                                value="0"@if ($user->status == 0) selected @endif>
                                                                                Inactive</option>
                                                                        </select>
                                                                    </div>

                                                                </div>
                                                                <div class="form-row">

                                                                    <div class="form-group col-lg-3">
                                                                        <input type="submit" value="update"
                                                                            class="btn btn-primary float-right"
                                                                            data-loading-text="Loading...">
                                                                    </div>
                                                                </div>
                                                            </form>
                                                            <!-- form end -->

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <!-- modal for edit end -->

                                        </td>
                                    </tr>



                                    @php $i++ @endphp
                                @endforeach
                            </tbody>
                            @if ($users->count() == 0)
                                <div class="alert alert-info">
                                    no user found Yet.

                                </div>
                            @endif
                        </table>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
