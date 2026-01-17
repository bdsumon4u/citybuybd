<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use File;
use Illuminate\Http\Request;
use Image;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $sliders = Slider::all();

        return view('backend.pages.slider.manage', compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.pages.slider.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $slider = new Slider;
        if ($request->name) {
            $image = $request->file('name');
            $img = random_int(0, mt_getrandmax()).'.'.$image->getClientOriginalExtension();
            $location = 'backend/img/sliders/'.$img;
            Image::make($image)->save($location);
            $slider->name = $img;

        }
        $slider->status = $request->status;
        $slider->save();

        return to_route('slider.manage');
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
        $slider = Slider::find($id);
        if (! is_null($slider)) {
            return view('backend.pages.slider.edit', compact('slider'));
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
        $slider = Slider::find($id);
        if ($request->name) {
            if (File::exists('Backend/img/sliders/'.$slider->name)) {
                File::delete('Backend/img/sliders/'.$slider->name);

            }
            $image = $request->file('name');
            $img = random_int(0, mt_getrandmax()).'.'.$image->getClientOriginalExtension();
            $location = 'backend/img/sliders/'.$img;
            Image::make($image)->save($location);
            $slider->name = $img;

        }
        $slider->status = $request->status;
        $slider->save();

        return to_route('slider.manage');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $slider = Slider::find($id);
        if (! is_null($slider)) {
            $slider->delete();

            return to_route('slider.manage');
        }
    }
}
