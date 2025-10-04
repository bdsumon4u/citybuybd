@extends('backend.layout.template')
@section('body-content')
    <div class="container-fluid">
        <div id="accordion2" class="accordion accordion-head-colored accordion-primary" role="tablist" aria-multiselectable="true">
            <div class="card">
                <div class="card-header" role="tab" id="headingOne">
                    <h6 class="mg-b-0">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo"
                           aria-expanded="true" aria-controls="collapseTwo" class="tx-purple transition">
                             Attribute Filter


                            <i class="fa-duotone fa-arrow-down-arrow-up"></i>
                        </a>
                    </h6>
                </div>
                <!-- card-header -->
                <div id="collapseTwo" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
                    <div class="card-block pd-5" style="background-color: #e9ecef;border: 1px solid lightgrey;">
                        <div class="row pb-3">
                            <div class="col-md-1 mr-5">
                                <a href="" data-toggle="modal" data-target="#add" class="btn btn-success">Add Attribute</a>
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
            <div class="br-pagebody" >
                        <div class="br-section-wrapper">


                            <!-- Modal for add -->
                                              <div class="modal fade" id="add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                  <div class="modal-content">
                                                    <div class="modal-header">
                                                      <h5 class="modal-title" id="exampleModalLabel">Add Attribute </h5>
                                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                      </button>
                                                    </div>
                                                    <div class="modal-body">
                                                      <form action="{{ route('attribute.store')}}" method="POST">
                                                    @csrf

                                                    <div class="form-row">
                                                        <div class="form-group col-lg-12">
                                                            <label class="font-weight-bold text-dark text-2">Title</label>
                                                            <input type="text"  name="name" class="form-control form-control-lg" required="required" >
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
                                                            <input type="submit" value="Add" class="btn btn-success " data-loading-text="Loading...">
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

                      <div class="row" >
                        <div class="col-lg-12" >

                                    <table class="table mg-b-0 table-bordered table-striped">
                                      <thead>
                                        <tr>
                                          <th scope="col">#Sl</th>
                                          <th scope="col">Title</th>
                                           <th scope="col">Attribute Item(s)</th>
                                          <th scope="col">Status</th>

                                          <th scope="col">Action</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        @php $i=1 @endphp
                                        @foreach( $attributes as $attribute )
                                        <tr>
                                          <th scope="row">{{ $i }}</th>

                                          <td>{{ $attribute ->name }}</td>

                                          <td class="action-button">


                                            @foreach(App\Models\Atr_item::where('atr_id',$attribute->id)->get() as $atr_item)
                                                  <div class="list-group mt-1">
                                                      <div class="list-group-item  d-xs-flex align-items-center justify-content-start">
                                                          <div class="mg-xs-l-15 mg-t-10 mg-xs-t-0 mg-r-auto">
                                                              <p class="mg-b-0 tx-inverse tx-medium">{{$atr_item->name}}</p>
                                                          </div>
                                                          <div class="d-flex align-items-center mg-t-10 mg-xs-t-0">
                                                              <a href="" data-toggle="modal" data-target="#atredit{{$atr_item->id}}" class="btn btn-outline-primary btn-icon">
                                                                  <div class="tx-10"><i class="fa-solid fa-pen-to-square tx-18"></i></div>
                                                              </a>
                                                              <a href="" data-toggle="modal" data-target="#atrdelete{{$atr_item->id}}" class="btn btn-outline-primary btn-icon mg-l-5">
                                                                  <div class="tx-10"><i class="text-danger fa-solid fa-delete-left tx-18"></i></div>
                                                              </a>
                                                          </div>
                                                      </div>
                                                  </div>




                                                <!-- Modal for edit -->
                                              <div class="modal fade" id="atredit{{$atr_item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                  <div class="modal-content">
                                                    <div class="modal-header">
                                                      <h5 class="modal-title" id="exampleModalLabel">Edit Attribute Item</h5>
                                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                      </button>
                                                    </div>
                                                    <div class="modal-body">
                                                      <form action="{{ route('attribute.item_update',$atr_item->id)}}" method="POST">
                                                    @csrf
                                                      <div class="form-row">
                                                        <div class="form-group col-lg-12">
                                                            <label class="font-weight-bold text-dark text-2">Attribute Title</label>
                                                           <select name="atr_id" class="form-control">

                                                               <option value="{{$attribute->id}}">{{$attribute->name}}</option>


                                                           </select>
                                                        </div>

                                                    </div>

                                                    <div class="form-row">
                                                        <div class="form-group col-lg-12">
                                                            <label class="font-weight-bold text-dark text-2">Title</label>
                                                            <input type="text" value="{{ $atr_item->name }}"  name="name" class="form-control form-control-lg" required="required" >
                                                        </div>
                                                    </div>



                                                    <div class="form-row">

                                                        <div class="form-group col-lg-3">
                                                            <input type="submit" value="Update" class="btn btn-success " data-loading-text="Loading...">
                                                        </div>
                                                    </div>
                                                </form>
                                                <!-- Customer signup form  End-->


                                                    </div>

                                                  </div>
                                                </div>
                                              </div>

                                              <!-- Modal edit End -->




                                           <!--item delete Modal start -->
                                            <div class="modal fade" id="atrdelete{{ $atr_item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                              <div class="modal-dialog">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Confirm Delete</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                    </button>
                                                  </div>
                                                  <div class="modal-body">
                                                    Are you sure to want to delete this attribute Item?
                                                  </div>
                                                  <div class="modal-footer">
                                                    <form action="{{ route('attribute.item_destroy', $atr_item->id)}}" method="POST">
                                                      @csrf
                                                      <input type="submit" value="Confirm" name="delete" class="btn btn-danger" >

                                                    </form>



                                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>

                                          @endforeach

<div class="mt-2">
    <a href="" data-toggle="modal" data-target="#add_item{{$attribute->id}}" class="btn btn-teal btn-block btn-sm mg-b-10">Add</a>

</div>

                            <!-- Modal for add_item -->
                                              <div class="modal fade" id="add_item{{$attribute->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                  <div class="modal-content">
                                                    <div class="modal-header">
                                                      <h5 class="modal-title" id="exampleModalLabel">Add Attribute Item </h5>
                                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                      </button>
                                                    </div>
                                                    <div class="modal-body">
                                                      <form action="{{ route('attribute.item_store')}}" method="POST">
                                                    @csrf

                                                     <div class="form-row">
                                                        <div class="form-group col-lg-12">
                                                            <label class="font-weight-bold text-dark text-2">Attribute Title</label>
                                                           <select name="atr_id" class="form-control">

                                                               <option value="{{$attribute->id}}">{{$attribute->name}}</option>


                                                           </select>
                                                        </div>

                                                    </div>

                                                    <div class="form-row">
                                                        <div class="form-group col-lg-12">
                                                            <label class="font-weight-bold text-dark text-2">Title</label>
                                                            <input type="text"  name="name" class="form-control form-control-lg" required="required" >
                                                        </div>


                                                    </div>




                                                    <div class="form-row">

                                                        <div class="form-group col-lg-3">
                                                            <input type="submit" value="Add" class="btn btn-success " data-loading-text="Loading...">
                                                        </div>
                                                    </div>
                                                </form>
                                                <!-- Customer signup form  End-->


                                                    </div>

                                                  </div>
                                                </div>
                                              </div>


                                          </td>

                                          <td class="text-center">
                                            @if($attribute->status == 0)
                                                  <span  class="btn  btn-sm btn-danger ">Inactive</span>

                                            @elseif($attribute-> status== 1)
                                                  <span  class="btn  btn-sm btn-success ">Active</span>
                                            @endif



                                           </td>
                                        <td class="action-button">

                                          <ul>
                                              <li><a href="" data-toggle="modal" data-target="#edit{{$attribute->id}}"><i class="fa-solid fa-pen-to-square tx-18"></i></a></li>

                                                <!-- Modal for edit -->
                                              <div class="modal fade" id="edit{{$attribute->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                  <div class="modal-content">
                                                    <div class="modal-header">
                                                      <h5 class="modal-title" id="exampleModalLabel">Edit Attribute </h5>
                                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                      </button>
                                                    </div>
                                                    <div class="modal-body">
                                                      <form action="{{ route('attribute.update',$attribute->id)}}" method="POST">
                                                    @csrf

                                                    <div class="form-row">
                                                        <div class="form-group col-lg-12">
                                                            <label class="font-weight-bold text-dark text-2">Title</label>
                                                            <input type="text" value="{{ $attribute->name }}"  name="name" class="form-control form-control-lg" required="required" >
                                                        </div>


                                                    </div>



                                                     <div class="form-row">
                                                        <div class="form-group col-lg-12">
                                                            <label class="font-weight-bold text-dark text-2">Status</label>
                                                           <select name="status" class="form-control">
                                                               <option value="1" @if($attribute->status==1)selected @endif>Active</option>
                                                               <option value="0" @if($attribute->status==0)selected @endif>Inactive</option>
                                                           </select>
                                                        </div>

                                                    </div>
                                                    <div class="form-row">

                                                        <div class="form-group col-lg-3">
                                                            <input type="submit" value="Add" class="btn btn-success " data-loading-text="Loading...">
                                                        </div>
                                                    </div>
                                                </form>
                                                <!-- Customer signup form  End-->


                                                    </div>

                                                  </div>
                                                </div>
                                              </div>

                                              <!-- Modal edit End -->

                                              <li><a href="" data-toggle="modal" data-target="#delete{{$attribute->id}}"><i class="text-danger fa-solid fa-delete-left tx-18"></i></a></li>
                                          </ul>

                                           <!-- Modal -->
                                            <div class="modal fade" id="delete{{ $attribute->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                              <div class="modal-dialog">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Confirm Delete</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                    </button>
                                                  </div>
                                                  <div class="modal-body">
                                                    Are you sure to want to delete this attribute?
                                                  </div>
                                                  <div class="modal-footer">
                                                    <form action="{{ route('attribute.destroy', $attribute->id)}}" method="POST">
                                                      @csrf
                                                      <input type="submit" value="Confirm" name="delete" class="btn btn-danger" >

                                                    </form>



                                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>

                                          <!-- Modal End -->
                                        </td>
                                        </tr>



                                        @php $i++ @endphp
                                        @endforeach
                                      </tbody>
                                      @if( $attributes->count()==0)
                                      <div class="alert alert-info">
                                        no attribute found Yet.

                                      </div>

                                      @endif
                                    </table>

                        </div>

                      </div>
            </div>
            </div>
          </div>

@endsection
