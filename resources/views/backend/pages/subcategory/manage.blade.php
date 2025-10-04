@extends('backend.layout.template')
@section('body-content')

    <div class="container-fluid">
        <div id="accordion2" class="accordion accordion-head-colored accordion-primary" role="tablist" aria-multiselectable="true">
            <div class="card">
                <div class="card-header" role="tab" id="headingOne">
                    <h6 class="mg-b-0">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo"
                           aria-expanded="true" aria-controls="collapseTwo" class="tx-purple transition">
                            subcategory Filter


                            <i class="fa-duotone fa-arrow-down-arrow-up"></i>
                        </a>
                    </h6>
                </div>
                <!-- card-header -->
                <div id="collapseTwo" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
                    <div class="card-block pd-5" style="background-color: #e9ecef;border: 1px solid lightgrey;">
                        <div class="row pb-3">
                            <div class="col-md-1 mr-5">
                                <a href="{{ route('subcategory.create')}}" class="btn btn-success ">Add subcategory</a>
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

            <div class="bd bd-gray-300 rounded ">
                <div class="row" >
                    <div class="col-lg-12" >
                        <table class="table mg-b-0 table-bordered table-striped">
                            <thead>
                            <tr>
                                <th scope="col">#Sl</th>
                                <th scope="col">subcategory Title</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach( $categories as $subcategory )

                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td> <a class="nav-link" target="_blank" href="{{route('subcategory', $subcategory->id)}}">{{$subcategory->title}}</a> </td>
                                    <td class="text-center">
                                        @if($subcategory->status == 0)
                                            <span  class="btn  btn-sm btn-danger ">Inactive</span>


                                        @elseif($subcategory-> status== 1)
                                            <span  class="btn  btn-sm btn-success ">Active</span>

                                        @endif




                                    </td>
                                    <td class="action-button">
                                        <ul>
                                            <li>
                                                <a href="{{route('subcategory.edit', $subcategory->id)}}">
                                                    <i class="fa-solid fa-pen-to-square tx-18"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="" data-toggle="modal" data-target="#delete{{$subcategory->id}}">
                                                    <i class="text-danger fa-solid fa-delete-left tx-18"></i>
                                                </a>
                                            </li>
                                        </ul>
                                        <!-- Modal -->
                                        <div class="modal fade" id="delete{{ $subcategory->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Confirm Delete</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure to want to delete this subcategory?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form action="{{ route('subcategory.destroy', $subcategory->id)}}" method="POST">
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



                            @endforeach

                            </tbody>
                            @if( $categories->count()==0)

                                <div class="alert alert-info">
                                    no subcategory found Yet.

                                </div>

                            @endif

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
