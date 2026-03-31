<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Final Order Override Secret
    |--------------------------------------------------------------------------
    |
    | This secret key is required when an admin changes the status of an order
    | that is already in a delivered/returned final state.
    |
    */
    'final_status_override_secret' => env('ORDER_STATUS_OVERRIDE_SECRET_KEY', 'Hotash'),
];
