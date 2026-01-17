<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Courier;
use App\Models\Zone;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $zones = Zone::all();

        return view('backend.pages.zone.manage', compact('zones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $couriers = Courier::all();
        $citys = City::all();

        return view('backend.pages.zone.create', compact('couriers', 'citys'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $zone = new Zone;
        $zone->couriar = $request->courier;
        $zone->city = $request->city;
        $zone->zone = $request->zone;
        $zone->status = $request->status;
        $zone->save();

        return to_route('zone.manage');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $zone = Zone::find($id);
        if (! is_null($zone)) {
            $couriers = Courier::all();
            $citys = City::all();

            return view('backend.pages.zone.edit', compact('couriers', 'citys', 'zone'));
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $zone = Zone::find($id);
        $zone->couriar = $request->courier;
        $zone->city = $request->city;
        $zone->zone = $request->zone;
        $zone->status = $request->status;
        $zone->save();

        return to_route('zone.manage');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $zone = Zone::find($id);
        if (! is_null($zone)) {
            $zone->delete();
        }

        return to_route('zone.manage');
    }
}
