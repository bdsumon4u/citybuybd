@extends('backend.layout.template')
@section('body-content')

 <div class="container-fluid">
        <div id="accordion2" class="accordion accordion-head-colored accordion-primary" role="tablist" aria-multiselectable="true">
            <div class="card">
                <div class="card-header" role="tab" id="headingOne">
                    <h6 class="mg-b-0">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo"
                           aria-expanded="true" aria-controls="collapseTwo" class="tx-purple transition">
                            Product Filter


                            <i class="fa-duotone fa-arrow-down-arrow-up"></i>
                        </a>
                    </h6>
                </div>
                <!-- card-header -->
                <div id="collapseTwo" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
                    <div class="card-block pd-5" style="background-color: #e9ecef;border: 1px solid lightgrey;">
                        <div class="row pb-3">
                            <div class="col-md-1 mr-5">
                                <a class="btn btn-success" href="{{route('landing.create')}}">Add Landing Product</a>
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
            <form action="{{ route('deleteSelected')}}" method="POST">
                @csrf


                <div class="bd bd-gray-300 rounded ">
                    <div class="row">
                        <div class="col-lg-12" style="overflow-x: auto;">
                            <table class="table mg-b-0 table-bordered table-striped">
                                <thead class="">
                                <tr>
                                    <th scope="col">
                                        <input type="checkbox" class="chkCheckAll">
                                    </th>
                                    <th scope="col">#Sl</th>
                                    <th scope="col">Product Name</th>
              
                                    <th scope="col">Heading</th>
                             
                                    <th scope="col">Action</th>
                                </tr>
                                </thead>
                                <tbody >

                                @foreach( $landings as $landing )


                                    <tr>
                                        <th scope="row">
                                            <input type="checkbox" name="ids[{{$landing->id}}]" class="checkBoxClass" data-id="{{$landing->id}}" value="{{$landing->id}}">
                                        </th>
                                        
                                        <td>{{ $loop->iteration }}</td>
                                
                                        <td>{{ $landing->product->name }}</td>
                                        
                                       <td>{{ $landing->heading }}</td>
                  
                               
                                        <td class="action-button">
                                            <ul>
                                                <li>
                                                    <a href="{{route('front.landing', [$landing->id])}}" target="_blank">
                                                        <i class="fa-solid fa-eye  tx-18"></i>
                                                    </a>
                                                </li>
                                                
                                                <li>
                                                    <a href="{{route('landing.edit', $landing->id)}}">
                                                        <i class="fa-solid fa-pen-to-square tx-18"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="" data-toggle="modal" data-target="#delete{{$landing->id}}">
                                                        <i class="text-danger fa-solid fa-delete-left tx-18"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                            <!-- Modal Start -->
                                            <div class="modal fade" id="delete{{ $landing->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Confirm Delete</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Are you sure to want to delete this product?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <a href="{{route('landing.destroy',$landing->id)}}" class="btn btn-danger">Delete</a>
                                                            <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Modal End -->
                                        </td>
                                    </tr>

                                @endforeach


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>


@endsection





