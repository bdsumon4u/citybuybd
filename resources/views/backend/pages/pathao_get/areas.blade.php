
 
@if (!blank($areas))
@foreach ($areas as $area)
    <option  value="{{ $area->area_id }}">{{ $area->area_name }}</option>
@endforeach
@endif
