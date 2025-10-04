@extends('backend.layout.template')
@section('body-content')


   

    <div class="container-fluid">
        <div id="accordion2" class="accordion accordion-head-colored accordion-primary" role="tablist" aria-multiselectable="true">
            <div class="card">
                <div class="card-header" role="tab" id="headingOne">
                    <h6 class="mg-b-0">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo"
                           aria-expanded="true" aria-controls="collapseTwo" class="tx-purple transition">
                            Orders [Product] Filter
                            <i class="fa-duotone fa-arrow-down-arrow-up"></i>
                        </a>
                    </h6>
                </div><!-- card-header -->

                <div id="collapseTwo" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
                    <div class="card-block pd-5" style="background-color: #e9ecef;border: 1px solid lightgrey;">
                        <div class="row pb-3">
                            <div class="col-md-5 col-12">
                                
                                 <form action="{{route('product_orders_search')}}" method="GET">
                                                 
                                    <div class="form-row">
                                        <div class="form-group col-lg-6">
                                             <select name="product" class="form-control select2">
                                                <option value="">Select product</option>
                                                @foreach(App\Models\Product::all() as $product)
                                                    <option value="{{$product->id}}">{{$product->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        

                                    </div>
                                     <div class="form-row">
                                        <div class="form-group col-lg-6">
                                            <select name="searchDays" required="required" class="form-control searchDays">
                                                <option value="00">Select Time</option>
                                                <option value="0">Today</option>
                                                <option value="1">Yesterday</option>
                                                <option value="7">Last 7 Days</option>
                                                <option value="15">Last 15 Days</option>
                                                <option value="30">Last 30 Days</option>
                                                <option value="100">Total</option>
                                                <option value="200">Custom</option>
                                            </select>
                                        </div>
                                        

                                    </div>

                                    <div class="form-row onDate"  >
                                        <div class="d-flex">
                                            <div>
                                                <input type="date" name="fromDate" class="form-control">
                                            </div>
                                            <div>
                                                <input type="date" name="toDate" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                      
                                     
                                    <div class="form-row">

                                        <div class="mt-2">
                                            <input type="submit" value="search" class="btn btn-primary float-right" data-loading-text="Loading...">
                                        </div>
                                    </div>
                                </form>
                            </div>

                        


                        </div>


                    </div>
                </div>
            </div><!-- card -->
            <!-- ADD MORE CARD HERE -->
        </div><!-- accordion -->

    </div>





@endsection

