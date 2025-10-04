<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\RedXApi\RedXApiInterface;
use Illuminate\Http\Request;

class RedXController extends Controller
{
    protected $redX;
    public function __construct(RedXApiInterface $redX)
    {
        $this->redX   = $redX;
    }
    public function getAreas(Request $request){

        $areas = $this->redX->getAreas();
        return view('backend.pages.redx.areas',compact('areas'));
    }

    public function redxStatusUpdate(Request $request){
   
        if($this->redX->statusUpdate($request)):
            return response()->json([
                'success'   => true,
                'message'   => 'Status updated successfully'
            ],200);
        else:
            return response()->json([
                'success'   => false,
                'message'   => 'Status update Failed.'
            ],400);
        endif;
    }

}
