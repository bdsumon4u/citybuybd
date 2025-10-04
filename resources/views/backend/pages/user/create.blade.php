@extends('backend.layout.template')
@section('body-content')
 <div class="br-pagetitle">


      </div>
            <div class="br-pagebody">
            <div class="br-section-wrapper">




                                    <div class="col-md-12">
                                        <div class="featured-box featured-box-primary text-left mt-2">
                                            <div class="box-content">
                                                <h4 class="color-primary font-weight-semibold text-4 text-uppercase mb-3">Add User</h4>

                                                <!-- Customer signup form  start-->
                                                <form action="{{ route('user.store')}}" method="POST">
                                                    @csrf
                                                    <div class="form-row">
                                                        <div class="form-group col-lg-6">
                                                            <label class="font-weight-bold text-dark text-2">Full Name</label>
                                                            <input type="text" value="{{ old('name')}}" name="name" class="form-control form-control-lg" required="required" >
                                                        </div>
                                                        <div class="form-group col-lg-6">
                                                            <label class="font-weight-bold text-dark text-2">E-mail Address</label>
                                                            <input type="text" value="{{ old('email')}}" name="email" class="form-control form-control-lg" required="required" >
                                                        </div>

                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-lg-6">
                                                            <label class="font-weight-bold text-dark text-2">Start Time Name</label>
                                                            <input type="time" value="{{ old('start_time')}}" name="name" class="form-control form-control-lg" required="required" >
                                                        </div>
                                                        <div class="form-group col-lg-6">
                                                            <label class="font-weight-bold text-dark text-2">E-mail Address</label>
                                                            <input type="text" value="{{ old('email')}}" name="email" class="form-control form-control-lg" required="required" >
                                                        </div>

                                                    </div>
                                                     <div class="form-row">
                                                        <div class="form-group col-lg-6">
                                                            <label class="font-weight-bold text-dark text-2">Phone</label>
                                                            <input type="text" s name="phone" class="form-control form-control-lg" required="required" >
                                                        </div>
                                                        <div class="form-group col-lg-6">
                                                            <label class="font-weight-bold text-dark text-2">Role</label>
                                                            <select name="role" required="required" class="form-control">

                                                                <option value="1">Admin</option>
                                                                <option value="2">Mangager</option>
                                                                <option value="3">Employee</option>
                                                            </select>
                                                        </div>

                                                    </div>

                                                    <div class="form-row">
                                                        <div class="form-group col-lg-6">
                                                            <label class="font-weight-bold text-dark text-2">Password</label>
                                                            <input type="password" required name="password" class="form-control form-control-lg">
                                                        </div>
                                                        <div class="form-group col-lg-6">
                                                            <label class="font-weight-bold text-dark text-2">Re-enter Password</label>
                                                            <input type="password" required name="password_confirmation" class="form-control form-control-lg">
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
                                                            <input type="submit" value="Register" class="btn btn-primary float-right" data-loading-text="Loading...">
                                                        </div>
                                                    </div>
                                                </form>
                                                <!-- Customer signup form  End-->
                        </div>
            		</div>

            	</div>
            </div>
          </div>

@endsection
