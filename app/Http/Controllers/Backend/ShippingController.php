<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Models\Shipping;
use Illuminate\Http\Request;

class shippingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $settings = Settings::first();
        $shippings = Shipping::orderby('id', 'desc')->get();

        return view('backend.pages.shipping.manage', compact('shippings', 'settings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.pages.shipping.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $shipping = new Shipping;
        $shipping->type = $request->type;
        $shipping->text = $request->text;
        $shipping->amount = $request->amount;
        $shipping->status = $request->status;
        $shipping->save();

        $notification = [
            'message' => 'shipping information Added successfully',
            'alert-type' => 'info',
        ];

        return to_route('shipping.manage')->with($notification);
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
        $shipping = Shipping::find($id);
        if (! is_null($shipping)) {
            return view('backend.pages.shipping.edit', compact('shipping'));
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
        $shipping = Shipping::find($id);
        $shipping->type = $request->type;
        $shipping->text = $request->text;
        $shipping->amount = $request->amount;
        $shipping->status = $request->status;
        $shipping->save();
        $notification = [
            'message' => 'shipping information updated successfully',
            'alert-type' => 'info',
        ];

        return to_route('shipping.manage')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $shipping = shipping::find($id);
        if (! is_null($shipping)) {

            $shipping->delete();
            $notification = [
                'message' => 'shipping Deleted',
                'alert-type' => 'error',
            ];

            return to_route('shipping.manage')->with($notification);
        }
    }
}
