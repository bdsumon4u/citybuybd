<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use File;
use Image;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $districts = District::orderBy('district_name','asc')->get();
        return view('backend.pages.district.manage',compact('districts'));
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $divisions =Division::orderBy('priority','asc')->get();
        return view('backend.pages.district.create',compact('divisions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $district=new District();
        $district->district_name  = $request->district_name;
        $district->division_id    = $request->division_id;
        $district->status         = $request->status;
        $district->save();
        return redirect()->route('district.manage');
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
        $district = District::find($id);
        if (!is_null($district)) {
        $divisions =Division::orderBy('priority','asc')->get();
         return view('backend.pages.district.edit',compact('district','divisions'));

        }else{
            return redirect()->route('district.manage');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
        $district = District::find($id);
        $district->district_name  = $request->district_name;
        $district->division_id    = $request->division_id;
        $district->status         = $request->status;
        $district->save();
        return redirect()->route('district.manage');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $district = District::find($id);
        if (!is_null($district)) {
         $district->delete();
         return redirect()->route('district.manage');

        }else{
            return redirect()->route('district.manage');
        }
    }
}
