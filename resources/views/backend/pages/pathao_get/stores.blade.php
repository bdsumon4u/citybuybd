
 <option  value="" selected> Select a store</option>
 @if (!blank($stores))
    @foreach ($stores as $store)
    <option value="{{ $store->store_id }}"  data-contact-name="{{$store->store_name}}"  > {{ $store->store_name }}</option>
    @endforeach
 @endif
