<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $divisions = Division::orderby('priority', 'asc')->get();

        return view('backend.pages.division.manage', compact('divisions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('backend.pages.division.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $division = new Division;
        $division->name = $request->name;
        $division->priority = $request->priority;
        $division->status = $request->status;
        $division->save();

        return to_route('division.manage');

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
        $division = Division::find($id);
        if (! is_null($division)) {
            $divisions = Division::orderBy('priority', 'asc')->get();

            return view('backend.pages.division.edit', compact('divisions', 'district'));
        } else {
            return to_route('division.manage');
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
        $division = Division::find($id);
        $division->name = $request->name;
        $division->priority = $request->priority;
        $division->status = $request->status;
        $division->save();

        return to_route('division.manage');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $division = Division::find($id);
        if (! is_null($division)) {
            $division->delete();

            return to_route('division.manage');
        }
    }
}
