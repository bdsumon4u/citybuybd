<?php
namespace  App\Enums;
interface OrderStatus
{
    const Processing        = 1;
    const Pending_Delivery  = 2;
    const On_Hold           = 3;
    const Cancel            = 4;
    const Completed         = 5;
    const Pending_Payment   = 6;
    const On_Delivery       = 7;

    const no_response1      = 8;
    const no_response2      = 9;
    const courier_hold      = 11;
    const return            = 12;
    const Partial_Delivery  = 13;
    const Paid_Return       = 14;
    const Stock_Out         = 15;
    const Total_Delivery    = 16;

}
