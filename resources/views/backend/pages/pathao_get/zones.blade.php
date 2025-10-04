
 @if (!blank($zones))
    @foreach ($zones as $zone)
        <option  value="{{ $zone->zone_id }}">{{ $zone->zone_name }}</option>
    @endforeach
 @endif
