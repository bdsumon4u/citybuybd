@extends('backend.layout.template')
@section('body-content')

            <div class="br-pagebody" >
                        <div class="br-section-wrapper">

                        <div class="bd bd-gray-300 rounded ">

                    	<div class="row" >
                    		<div class="col-lg-12" >

                                    <table class="table mg-b-0 table-bordered table-striped">
                                      <thead>
                                        <tr>
                                          <th scope="col">#Sl</th>
                                          <th scope="col">File</th>
                                          <th scope="col">File Name</th>
                                          <th scope="col">File Type</th>
                                          <th scope="col">Action</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        @php $i=1 @endphp
                                        @foreach( App\Models\Product::whereNotNull('image')->get() as $image )
                                        <tr>
                                          <th scope="row">{{ $i }}</th>

                                          <td><img src="{{ asset('backend/img/products/'.$image->image)  }}" width="100"></td>

                                          <td> {{$image->image}}</td>
                                          <td>Product Image</td>
                        <td class="action-button">

                          <ul>
                              <li><a href="" data-toggle="modal" data-target="#edit{{$image->id}}"><i class="fa-solid fa-pen-to-square tx-18"></i></a></li>

                              <li><a  data-toggle="modal" data-target="#delete{{$image->id}}"><i class="text-danger fa-solid fa-delete-left tx-18"></i></a></li>
                          </ul>

                           <!-- Modal -->
<div class="modal fade" id="delete{{ $image->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Confirm Delete</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure to want to delete this image?
      </div>
      <div class="modal-footer">
        <a href="{{route('p_i_d',$image->id)}}" class="btn btn-danger">Confirm</a>



        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>


                        <!-- Modal -->
<div class="modal fade" id="edit{{ $image->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Image</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>
      <div class="modal-body">
        <form action="{{route('p_i_e',$image->id)}}" method="POST" enctype="multipart/form-data">
          @csrf
          <img src="{{ asset('backend/img/products/'.$image->image)  }}" width="100" style="margin-bottom: 10px;">

          <input type="file" class="form-control-file" name="image">
          <input type="submit" class="btn btn-success my-2" value="Update">
        </form>
      </div>

    </div>
  </div>
</div>



                          <!-- Modal End -->
                        </td>
                                        </tr>



                                        @php $i++ @endphp
                                        @endforeach
                                        @foreach( App\Models\Product::whereNotNull('gallery_images')->get() as $image )
                                        <tr>
                                          <th scope="row">{{ $i }}</th>

                                          <td><img src="{{ asset('backend/img/products/'.$image->gallery_images)  }}" width="100"></td>

                                          <td> {{$image->image}}</td>
                                          <td>Product Gallery</td>
                        <td class="action-button">

                          <ul>
                              <li><a href="" data-toggle="modal" data-target="#edit_g{{$image->id}}"><i class="fa-solid fa-pen-to-square tx-18"></i></a></li>

                              <li><a  data-toggle="modal" data-target="#delete_g{{$image->id}}"><i class="text-danger fa-solid fa-delete-left tx-18"></i></a></li>
                          </ul>

                           <!-- Modal -->
<div class="modal fade" id="delete_g{{ $image->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Confirm Delete</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure to want to delete this image?
      </div>
      <div class="modal-footer">
        <a href="{{route('p_g_d',$image->id)}}" class="btn btn-danger">Confirm</a>



        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>


                        <!-- Modal -->
<div class="modal fade" id="edit_g{{ $image->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Image</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>
      <div class="modal-body">
        <form action="{{route('p_g_e',$image->id)}}" method="POST" enctype="multipart/form-data">
          @csrf
          <img src="{{ asset('backend/img/products/'.$image->gallery_images)  }}" width="100">

          <input type="file" class="form-control-file" name="image">
          <input type="submit" class="btn btn-success my-2" value="Update">
        </form>
      </div>

    </div>
  </div>
</div>



                          <!-- Modal End -->
                        </td>
                                        </tr>



                                        @php $i++ @endphp
                                        @endforeach
                                        @foreach( App\Models\Slider::whereNotNull('name')->get() as $image )
                                        <tr>
                                          <th scope="row">{{ $i }}</th>

                                          <td><img src="{{ asset('backend/img/sliders/'.$image->name)  }}" width="100"></td>

                                          <td> {{$image->name}}</td>
                                          <td>Slider Image</td>
                        <td class="action-button">

                          <ul>
                              <li><a href="" data-toggle="modal" data-target="#edit_s{{$image->id}}"><i class="fa-solid fa-pen-to-square tx-18"></i></a></li>

                              <li><a  data-toggle="modal" data-target="#delete_s{{$image->id}}"><i class="text-danger fa-solid fa-delete-left tx-18"></i></a></li>
                          </ul>

                           <!-- Modal -->
<div class="modal fade" id="delete_s{{ $image->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Confirm Delete</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure to want to delete this image?
      </div>
      <div class="modal-footer">
        <a href="{{route('p_s_d',$image->id)}}" class="btn btn-danger">Confirm</a>



        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>


                        <!-- Modal -->
<div class="modal fade" id="edit_s{{ $image->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Image</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>
      <div class="modal-body">
        <form action="{{route('p_s_e',$image->id)}}" method="POST" enctype="multipart/form-data">
          @csrf
          <img src="{{ asset('backend/img/sliders/'.$image->name)  }}" width="100">
          <input type="file" class="form-control-file" name="image">

          <input type="submit" class="btn btn-success my-2" value="Update">
        </form>
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

                                    </table>

                    		</div>

                    	</div>
            </div>
            </div>
          </div>

@endsection
