
<!DOCTYPE html>
<html lang="en">
  <head>
   @include('backend.includes.header')
   @include('backend.includes.css')
    
  </head>

  <body>

   
     @include('backend.includes.leftmenu')
   
    @include('backend.includes.topbar')
    
   
    <div class="br-mainpanel">
<div class="br-pagetitle">
   <div class="br-pagebody">
      <form action="{{route('cart_atr_edit',$cart->id)}}" class="cart_form" method="POST">
          @csrf
           @if($cart->product->atr_item !=NULL)

                                            @foreach(App\Models\ProductAttribute::whereIn('id',explode('"',$cart->product->atr))->get() as $b)
                                            <div class="row mb-2 justify-content-center">
                                        <div class="col-md-6 col-12">
                                            <label for="">{{$b->name}}  </label>
                                            <input type="hidden" name="attribute_id[]" value="">
                                            <select name="attribute[]" id="" class="form-control attribute_item_id">
                                                @foreach(App\Models\Atr_item::whereIn('id',explode('"',$cart->product->atr_item))->where('atr_id',$b->id)->get() as $c)
                                              <option value="{{$c->id}}">{{$c->name}}</option>

                                              @endforeach
                                            </select>
                                        </div>
                                    </div>

                                            <p>
                                                
                                            </p>
                                            


                                            @endforeach
                                            @endif
         
          
          
          <input type="button" class="btn btn-success my-2 cart_button" value="Update">
        </form>
   </div>
</div>
</div>
</body>
</html>