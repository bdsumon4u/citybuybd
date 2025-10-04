

@if (!blank($areas))
    @foreach ($areas->areas as $area)
        <option  value="{{ $area->id }}" data-area="{{$area->name}}">{{ $area->name }}</option>
    @endforeach
@endif
