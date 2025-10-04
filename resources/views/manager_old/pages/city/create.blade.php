

@extends('manager.layout.template')
@section('body-content')

    <div class="br-pagebody">
        <div class="br-section-wrapper">


            <div class="row">
                <div class="col-lg-12">
                    <div class="card bd-0 pd-10 overflow-hidden">
                        <form action="{{ route('manager.city.store')}}"   method="POST">
                            @csrf
                            <div class="row justify-content-center">
                                <div class="col-lg-6">


                                    <div class="form-group">
                                        <label >Courier Name *</label>
                                        <select name="courier_id" required class="form-control" >
                                            @foreach($couriers as $courier)
                                                <option value="{{$courier->id}}">{{$courier->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label >city Name *</label>
                                        <input type="text" name="city" class="form-control"  required="required" >
                                    </div>















                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" class="form-control">

                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" name="addcity" value="Add City" class="btn btn-teal btn-block mg-b-10">
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
