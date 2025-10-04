@extends('backend.layout.template')
@section('body-content')
    <div class="container-fluid">
        <div id="accordion2" class="accordion accordion-head-colored accordion-primary" role="tablist" aria-multiselectable="true">
            <div class="card">
                <div class="card-header" role="tab" id="headingOne">
                    <h6 class="mg-b-0">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo"
                           aria-expanded="true" aria-controls="collapseTwo" class="tx-purple transition">
                            User Product Filter
                            <i class="fa-duotone fa-arrow-down-arrow-up"></i>
                        </a>

                    </h6>
                </div><!-- card-header -->

                <div id="collapseTwo" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
                    <div class="card-block pd-5" style="background-color: #e9ecef;border: 1px solid lightgrey;">
                        <div class="row pb-3">

                            <div class="col-md-6">
                                <input id="myInputUserProduct" type="text" class="form-control" placeholder="Search User/Product Name">
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

                      <div class="row" >
                        <div class="col-lg-12" style="overflow-x: auto;">

                                    <table class="table mg-b-0 table-bordered table-striped" >
                                      <thead class="">
                                        <tr>
                                          <th scope="col">#Sl</th>
                                          <th scope="col">Name</th>
                                          <th scope="col">Email</th>
                                          <th scope="col">Products</th>

                                        </tr>
                                      </thead>
                                      <tbody id="myInputUserProduct">


                                        @foreach( App\Models\User::where('role',3)->get() as $user )
                                        <tr>
                                          <th scope="row">{{ $loop->iteration }}</th>

                                          <td>{{ $user ->name }}</td>
                                          <td>{{ $user ->email }}</td>
                                          <td>
                                              <ul class="list-group">
                                                  @foreach( App\Models\Product::where('assign',$user->id)->get() as $product)
                                                  <li class="list-group-item rounded-top-0">
                                                      <p class="mg-b-0">
                                                          <i class="fa fa-cube tx-info mg-r-8"></i>
                                                          <strong class="tx-inverse tx-medium">{{$product->name}}</strong>
                                                      </p>
                                                  </li>
                                              @endforeach
                                              </ul>

                                          </td>




                                        </tr>




                                        @endforeach
                                      </tbody>

                                    </table>

                        </div>

                      </div>
            </div>
            </div>
          </div>

@endsection
