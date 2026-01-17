<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Courier;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $citys = City::all();

        return view('manager.pages.city.manage', compact('citys'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $couriers = Courier::all();

        return view('manager.pages.city.create', compact('couriers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $city = new City;
        $city->courier_id = $request->courier_id;
        $city->city = $request->city;
        $city->status = $request->status;
        $city->save();

        return to_route('manager.city.manage');

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
        $city = City::find($id);
        if (! is_null($city)) {
            $couriers = Courier::all();

            return view('manager.pages.city.edit', compact('couriers', 'city'));
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
        $city = City::find($id);
        $city->courier_id = $request->courier_id;
        $city->city = $request->city;
        $city->status = $request->status;
        $city->save();

        return to_route('manager.city.manage');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $city = City::find($id);
        if (! is_null($city)) {
            $city->delete();
        }

        return to_route('manager.city.manage');
    }
}
