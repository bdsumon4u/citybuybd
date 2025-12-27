@extends('backend.layout.template')
@section('body-content')
    <div class="container-fluid">
        <div id="accordion2" class="accordion accordion-head-colored accordion-primary" role="tablist" aria-multiselectable="true">
            <div class="card">
                <div class="card-header" role="tab" id="headingOne">
                    <h6 class="mg-b-0">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo"
                           aria-expanded="true" aria-controls="collapseTwo" class="tx-purple transition">
                            Ordered Product Filter
                            <i class="fa-duotone fa-arrow-down-arrow-up"></i>
                        </a>

                    </h6>
                </div><!-- card-header -->

                <div id="collapseTwo" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
                    <div class="card-block pd-5" style="background-color: #e9ecef;border: 1px solid lightgrey;">
                        <div class="row pb-3">

                            <div class="col-md-12">
                                <input id="myInputOrderedProduct" type="text" class="form-control" placeholder="Search Product">
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
                <div class="row justify-content-center">
                    <span class="tx-12 text-center mt-1" >All Ordered Product Status</span>
                </div>


            <div class="row" >
                <div class="col-lg-12" >
                    <div class="pd-5" style="overflow-x: auto;" >
                        <table class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th scope="col">#Sl</th>
                          <th scope="col">Product Name</th>
                          <th scope="col" class="text-center">Total Orders</th>
                          <th scope="col" class="text-center"> Processing</th>
                          <th scope="col" class="text-center"> Courier Entry</th>
                          <th scope="col" class="text-center"> On Delivery</th>
                          <th scope="col" class="text-center"> Pending Payment</th>
                          <th scope="col" class="text-center"> Hold</th>
                          <th scope="col" class="text-center"> Courier Hold</th>
                          
                          <th scope="col" class="text-center"> No Response 1</th>
                          <th scope="col" class="text-center"> Printed Invoice</th>
                          
                           <th scope="col" class="text-center"> Canceled</th>
                           
                          <th scope="col" class="text-center"> Return </th>
                          
                          <th scope="col" class="text-center"> Completed</th>
                         
                        </tr>
                      </thead>
                      <tbody id="myTableOrderedProduct">

                        @foreach($topsales as $product)

                        <tr>
                          <th scope="row">

                            {{$loop->iteration}}

                          </th>
                          <td style="width: 30%">{{$product->name}}</td>
                          <td class="text-center">
                            @php
                                
                                echo '<span class="tx-16 font-weight-bold ">'.$product->total .'</span>';
                            @endphp
                          </td>
                          <td class="text-center">
   
                            @php
                            $arr_sum = 0;
                            foreach($all_carts->where('product_id',$product->id) as $single_cart){
                                if($single_cart?->order?->status==1){
                                    $arr_sum +=1;
                                  }else{
                                    $arr_sum +=0;
    
                                  }
                            }
                            echo '<span class="tx-16 font-weight-bold text-primary">'.$arr_sum .'</span>';

                            @endphp
                            


                          </td>
                          <td class="text-center">
                             @php
                            $arr_sum = 0;
                            foreach($all_carts->where('product_id',$product->id) as $single_cart){
                                if($single_cart?->order?->status==2){
                                    $arr_sum +=1;
                                  }else{
                                    $arr_sum +=0;
    
                                  }
                            }
                            echo '<span class="tx-16 font-weight-bold text-warning">'.$arr_sum .'</span>';

                            @endphp



                          </td>
                          <td class="text-center">
                             @php
                            $arr_sum = 0;
                            foreach($all_carts->where('product_id',$product->id) as $single_cart){
                                if($single_cart?->order?->status==7){
                                    $arr_sum +=1;
                                  }else{
                                    $arr_sum +=0;
    
                                  }
                            }
                            echo '<span class="tx-16 font-weight-bold text-danger">'.$arr_sum .'</span>';

                            @endphp



                          </td>

                          <td class="text-center"> 
                         @php
                            $arr_sum = 0;
                            foreach($all_carts->where('product_id',$product->id) as $single_cart){
                                if($single_cart?->order?->status==6){
                                    $arr_sum +=1;
                                  }else{
                                    $arr_sum +=0;
    
                                  }
                            }
                            echo '<span class="tx-16 font-weight-bold text-warning">'.$arr_sum .'</span>';

                            @endphp
                            
                            
                            </td>
                          <td class="text-center"> 
                          
                         @php
                            $arr_sum = 0;
                            foreach($all_carts->where('product_id',$product->id) as $single_cart){
                                if($single_cart?->order?->status==3){
                                    $arr_sum +=1;
                                  }else{
                                    $arr_sum +=0;
    
                                  }
                            }
                            echo '<span class="tx-16 font-weight-bold text-primary">'.$arr_sum .'</span>';

                            @endphp
                            
                            </td>
                          <td class="text-center"> 
                          
                         @php
                            $arr_sum = 0;
                            foreach($all_carts->where('product_id',$product->id) as $single_cart){
                                if($single_cart?->order?->status==11){
                                    $arr_sum +=1;
                                  }else{
                                    $arr_sum +=0;
    
                                  }
                            }
                            echo '<span class="tx-16 font-weight-bold text-primary">'.$arr_sum .'</span>';

                            @endphp
                            
                            </td>
                            <td class="text-center"> 
                          
                         @php
                            $arr_sum = 0;
                            foreach($all_carts->where('product_id',$product->id) as $single_cart){
                                if($single_cart?->order?->status==8){
                                    $arr_sum +=1;
                                  }else{
                                    $arr_sum +=0;
    
                                  }
                            }
                            echo '<span class="tx-16 font-weight-bold text-primary">'.$arr_sum .'</span>';

                            @endphp
                            
                            </td>
                            <td class="text-center"> 
                          
                         @php
                            $arr_sum = 0;
                            foreach($all_carts->where('product_id',$product->id) as $single_cart){
                                if($single_cart?->order?->status==9){
                                    $arr_sum +=1;
                                  }else{
                                    $arr_sum +=0;
    
                                  }
                            }
                            echo '<span class="tx-16 font-weight-bold text-primary">'.$arr_sum .'</span>';

                            @endphp
                            
                            </td>
                            <td class="text-center"> 
                          
                          @php
                            $arr_sum = 0;
                            foreach($all_carts->where('product_id',$product->id) as $single_cart){
                                if($single_cart?->order?->status==4){
                                    $arr_sum +=1;
                                  }else{
                                    $arr_sum +=0;
    
                                  }
                            }
                            echo '<span class="tx-16 font-weight-bold text-red">'.$arr_sum .'</span>';

                            @endphp</td>
                            <td class="text-center"> 
                          
                         @php
                            $arr_sum = 0;
                            foreach($all_carts->where('product_id',$product->id) as $single_cart){
                                if($single_cart?->order?->status==12){
                                    $arr_sum +=1;
                                  }else{
                                    $arr_sum +=0;
    
                                  }
                            }
                            echo '<span class="tx-16 font-weight-bold text-primary">'.$arr_sum .'</span>';

                            @endphp
                            
                            </td>
                          <td class="text-center">
                              
                              @php
                            $arr_sum = 0;
                            foreach($all_carts->where('product_id',$product->id) as $single_cart){
                                if($single_cart?->order?->status==5){
                                    $arr_sum +=1;
                                  }else{
                                    $arr_sum +=0;
    
                                  }
                            }
                            echo '<span class="tx-16 font-weight-bold text-success">'.$arr_sum .'</span>';

                            @endphp
                            
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
