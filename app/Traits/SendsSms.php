<?php

declare(strict_types=1);

namespace App\Traits;

use App\Channels\SmsChannel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait SendsSms
{
    /**
     * Send custom SMS to a customer phone number.
     */
    public function sendCustomSms(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string|max:1000',
        ]);

        $phone = (string) $request->phone;
        $message = trim((string) $request->message);

        $smsChannel = new SmsChannel();
        $result = $smsChannel->sendSingleSms($phone, $message);

        if (! empty($result['success'])) {
            return response()->json([
                'success' => true,
                'message' => $result['message'] ?? 'SMS sent successfully!',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'] ?? 'Failed to send SMS.',
        ], 422);
    }
}
