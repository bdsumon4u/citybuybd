@extends('manager.layout.template')
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
                                <a class="btn btn-success" href="{{route('manager.product.create')}}">Add Product</a>
                            </div>
                            <div class="col-md-4">
                                <input id="myInputProduct" type="text" class="form-control" placeholder="Search Products">
                            </div>
                            <div class="col-md-2 col-12">
                                <form action="{{route('p_selected_status')}}" method="post" id="p_all_status_form">
                                    @csrf


                                    <input type="hidden" id="p_all_status" name="p_all_status">
                                    <select name="p_status" id="p_status" class="form-control">
                                        <option value="">Select Status</option>
                                        <option value="1">Published</option>
                                        <option value="0">Unpublished</option>
                                    </select>
                                </form>
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


                {{--
                                <button type="submit"  class="btn btn-danger btn-sm my-3">Delete Product</button>--}}

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
                                    <th scope="col">Image</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Category</th>
                                     <th scope="col">Serial</th>
                                    <th scope="col">Brand</th>
                                    <th scope="col">SKU</th>
                                    <th scope="col">stock</th>
                                    <th scope="col">Regular Price</th>
                                    <th scope="col">Offer Price</th>
{{--                                    <th scope="col">Attributes</th>--}}
                                    <th scope="col">Status</th>
                                    <th scope="col">Assign Employee</th>
                                    <th scope="col">Action</th>
                                </tr>
                                </thead>
                                <tbody id="myTableProduct">

                                @foreach( $products as $product )


                                    <tr>
                                        <th scope="row">
                                            <input type="checkbox" name="ids[{{$product->id}}]" class="checkBoxClass" data-id="{{$product->id}}" value="{{$product->id}}">
                                        </th>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <img src="{{ asset('backend/img/products/'.$product->image)  }}" width="30">
                                        </td>
                                        <td>
                                            <a href="{{route('details',$product->slug)}}" target="_blank"><p>  {{ $product->name }}</p></a>
                                            
                                           
                                        
                                        
                                        </td>
                                        <td>@if(!is_null($product->category))
                                                {{ $product->category->title }} @endif
                                        </td>
                                        <td>
                                            {{ $product->serial }}
                                            
                                          
                                        </td>
                                        <td>@if(!is_null($product->brand))
                                                {{ $product->brand->title }} @endif
                                        </td>
                                        <td>{{ $product->sku }}</td>
                                        <td>{{ $product->stock }}</td>
                                        <td>{{$settings->currency ?? "৳"}} {{ $product->regular_price }}</td>
                                        <td>
                                            @if( !empty( $product->offer_price ) )
                                                {{$settings->currency ?? "৳"}} {{ $product->offer_price }}
                                            @else
                                                -N\A-

                                            @endif

                                        </td>
{{--                                        <td>--}}

{{--                                            @if($product->atr_item !=NULL)--}}
{{--                                                @foreach(App\Models\ProductAttribute::whereIn('id',explode('"',$product->atr))->get() as $b)--}}
{{--                                                    <span ><strong class="text-danger">{{$b->name}}</strong>: @foreach(App\Models\Atr_item::whereIn('id',explode('"',$product->atr_item))->where('atr_id',$b->id)->get() as $c)--}}
{{--                                                            {{$c->name}},--}}

{{--                                                        @endforeach--}}

{{--                                                    </span><br/>--}}



{{--                                                @endforeach--}}
{{--                                            @endif--}}


{{--                                        </td>--}}
                                        <td> @if($product->status == 1)
                                                <a href="#" class="btn btn-sm btn-success btn-block">Published</a>
                                            @elseif($product->status ==0 )
                                                <a href="#" class="btn btn-sm btn-danger btn-block">Unpublished</a>
                                            @endif

                                        </td>
                                        <td>

                                            @if($product->assign == !NULL)


                                                <p>
                                                    <a href="#" class="btn btn-sm btn-info btn-block">{{$product->assign_emp->name}}</a>
{{--                                                    <a href="{{route('assign_dlt',$product->id)}}" onclick=" return confirm('do you confirm to delete')" >--}}
{{--                                                        <i class="fa-solid fa-delete-left" style--}}
{{--                                                        ="color: red;"></i>--}}
{{--                                                    </a>--}}
                                                </p>


                                            @endif
                                        </td>
                                        <td class="action-button">
                                            <ul>
                                                <li>
                                                    <a href="{{route('manager.product.edit', $product->id)}}">
                                                        <i class="fa-solid fa-pen-to-square tx-18"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="" data-toggle="modal" data-target="#delete{{$product->id}}">
                                                        <i class="text-danger fa-solid fa-delete-left tx-18"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                            <!-- Modal Start -->
                                            <div class="modal fade" id="delete{{ $product->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                                            <a href="{{route('manager.product.destroy',$product->id)}}" class="btn btn-danger">Delete</a>
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





