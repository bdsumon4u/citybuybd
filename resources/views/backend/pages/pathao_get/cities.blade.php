
<option  value="" selected> Select a City</option>
@if (!blank($cities))
@foreach ($cities as $city)
    <option  value="{{ $city->city_id }}"> {{ $city->city_name }}</option>
@endforeach
@endif
