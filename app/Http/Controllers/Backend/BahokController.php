<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\BahokCourierApi\BahokCourierApiInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BahokController extends Controller
{
    public function __construct(
        protected BahokCourierApiInterface $bahok
    ) {}

    public function bahokStatusUpdate(Request $request): JsonResponse
    {
        if ($this->bahok->statusUpdate($request)) {
            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Status update failed or order not found.',
        ], 400);
    }
}
