@extends('backend.layout.template')
@section('body-content')

    <div class="container-fluid">
     <div id="accordion2" class="accordion accordion-head-colored accordion-primary" role="tablist" aria-multiselectable="true">
         <div class="card">
             <div class="card-header" role="tab" id="headingOne">
                 <h6 class="mg-b-0">
                     <a data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo"
                        aria-expanded="true" aria-controls="collapseTwo" class="tx-purple transition">
                         Customer Filter
                         <i class="fa-duotone fa-arrow-down-arrow-up"></i>
                     </a>

                 </h6>
             </div><!-- card-header -->

             <div id="collapseTwo" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
                 <div class="card-block pd-5" style="background-color: #e9ecef;border: 1px solid lightgrey;">
                     <div class="row pb-3">

                         <div class="col-md-6">
                             <input id="myInputCustomer" type="text" class="form-control" placeholder="Search Customer">
                         </div>

                         <div class="col-md-2 col-12">
                             <a href="{{ route('customer.export')}}" class="btn btn-info ">Export All Customer</a>
                         </div>
                     </div>


                 </div>
             </div>
         </div><!-- card -->
         <!-- ADD MORE CARD HERE -->
     </div><!-- accordion -->

 </div>
    <div class="br-pagebody" >
                <div class="br-section-wrapper">

                <div class="bd bd-gray-300 rounded ">
                    <div class="row justify-content-center">
                        <span class="tx-20 text-center mt-1" >All Customers</span>
                    </div>
                <div class="row" >
                    <div class="col-lg-12" >
                        <table class="table mg-b-0 table-bordered table-striped">
                          <thead>
                            <tr>
{{--                                <th scope="col"><input type="checkbox" class="chkCheckAll"></th>--}}
                              <th scope="col">#Sl</th>
                              <th scope="col">Name</th>
                              <th scope="col">Phone</th>
                              <th scope="col">Email</th>
                              <th scope="col">Address</th>
                            </tr>
                          </thead>
                          <tbody id="myTableCustomer">

                            @foreach($allCustomers as $customers)

                            <tr>
{{--                                <th scope="row">--}}
{{--                                    <input type="checkbox" class="sub_chk" data-id="{{$customers->id}}">--}}
{{--                                </th>--}}
                              <th scope="row">

                                {{$loop->iteration}}
                              </th>
                              <td>{{$customers->name}}</td>
                              <td>{{$customers->phone}}</td>
                              <td>{{$customers->email}}</td>
                              <td>{{$customers->address}}</td>


                            </tr>
                            @endforeach

                          </tbody>
                        </table>

                        <div class="ht-80 bd d-flex align-items-center justify-content-center">
                            <ul class="pagination pagination-basic pagination-danger mg-b-0">
                                <li>{{$allCustomers->withQueryString()->links()}}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endsection
