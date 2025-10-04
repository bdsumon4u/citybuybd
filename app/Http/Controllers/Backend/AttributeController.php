<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductAttribute;
use App\Models\Atr_item;

class AttributeController extends Controller
{
    
    public function index()
    {
        $attributes = ProductAttribute::all();
        return view('backend.pages.attribute', compact('attributes'));
    }

   
    public function store(Request $request)
    {
        $attribute = new ProductAttribute;
        $attribute->name = $request->name;
        $attribute->status = $request->status;
        $attribute->save();
        return redirect()->back();

    }

   
    public function update(Request $request, $id)
    {
        $attribute = ProductAttribute::find($id);
        $attribute->name = $request->name;
        $attribute->status = $request->status;
        $attribute->save();
        return redirect()->back();
    }

   
    public function destroy($id)
    {
        $attribute = ProductAttribute::find($id);
        
        $attribute->delete();
        return redirect()->back();
    }

    public function item_store(Request $request)
    {
        $attribute = new Atr_item;
        $attribute->name = $request->name;
        $attribute->atr_id = $request->atr_id;
        $attribute->save();
        return redirect()->back();

    }

   
    public function item_update(Request $request, $id)
    {
        $attribute = Atr_item::find($id);
        $attribute->name = $request->name;
        $attribute->atr_id = $request->atr_id;
        $attribute->save();
        return redirect()->back();
    }

   
    public function item_destroy($id)
    {
        $attribute = Atr_item::find($id);
        
        $attribute->delete();
        return redirect()->back();
    }
    
}
