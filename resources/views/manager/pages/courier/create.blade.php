

@extends('manager.layout.template')
@section('body-content')

    <div class="br-pagebody">
        <div class="br-section-wrapper">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card bd-0 pd-10 overflow-hidden">
                        <form action="{{ route('manager.courier.store')}}"   method="POST">
                            @csrf
                            <div class="row justify-content-center">
                                <div class="col-lg-6">

                                    <div class="form-group">
                                        <label >Courier Name *</label>
                                        <input type="text" name="name" class="form-control"  required="required" >
                                    </div>


                                    <div class="form-group">
                                        <label >Courier Charge *</label>
                                        <input type="number" name="charge" class="form-control"  required="required" >
                                    </div>




                                    <div class="form-group">
                                        <input type="checkbox"  name="city_av" value="On">

                                        <label >City Available</label>

                                    </div>


                                    <div class="form-group">
                                        <input type="checkbox"  name="zone_av" value="On">

                                        <label >Zone Available</label>

                                    </div>







                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" class="form-control">

                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" name="addcourier" value="Add courier Method" class="btn btn-teal btn-block mg-b-10">
                                    </div>

                                </div>

                            </div>







                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
