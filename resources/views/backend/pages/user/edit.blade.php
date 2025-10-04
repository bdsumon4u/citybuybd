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
                                                <form action="{{ route('user.update', $user->id)}}" method="POST">
                                                    @csrf
                                                    <div class="form-row">
                                                        <div class="form-group col-lg-6">
                                                            <label class="font-weight-bold text-dark text-2">Full Name</label>
                                                            <input type="text" value="{{ $user->name}}" name="name" class="form-control form-control-lg" required="required" >
                                                        </div>
                                                        <div class="form-group col-lg-6">
                                                            <label class="font-weight-bold text-dark text-2">E-mail Address</label>
                                                            <input type="text" value="{{ $user->email}}" name="email" class="form-control form-control-lg" required="required" >
                                                        </div>
                                                        
                                                    </div>
                                                     <div class="form-row">
                                                        <div class="form-group col-lg-6">
                                                            <label class="font-weight-bold text-dark text-2">Phone</label>
                                                            <input type="text" value="{{ $user->phone}}" name="phone" class="form-control form-control-lg" required="required" >
                                                        </div>
                                                        <div class="form-group col-lg-6">
                                                            <label class="font-weight-bold text-dark text-2">Role</label>
                                                            <select name="role" required="required" class="form-control">
                                                                
                                                                <option value="1" @if($user->role==1)selected @endif>Admin</option>
                                                                <option value="2" @if($user->role==2)selected @endif>Mangager</option>
                                                                <option value="3" @if($user->role==3)selected @endif>Employee</option>
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
                                                        <div class="form-group col-lg-6">
                                                            <label class="font-weight-bold text-dark text-2">Start Time[optional]</label>
                                                            <input type="text" value="00:00:00" name="start_time" class="form-control form-control-lg" required="required" >
                                                        </div>
                                                        <div class="form-group col-lg-6">
                                                            <label class="font-weight-bold text-dark text-2">End Time[optional]</label>
                                                            <input type="text" value="23:59:59" name="end_time" class="form-control form-control-lg" required="required" >
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-lg-12">
                                                            <label class="font-weight-bold text-dark text-2">Status</label>
                                                           <select name="status" class="form-control">
                                                               <option value="1"@if($user->status==1)selected @endif>Active</option>
                                                               <option value="0"@if($user->status==0)selected @endif>Inactive</option>
                                                           </select>
                                                        </div>
                                                       
                                                    </div>
                                                    <div class="form-row">
                                                        
                                                        <div class="form-group col-lg-3">
                                                            <input type="submit" value="update" class="btn btn-primary float-right" data-loading-text="Loading...">
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