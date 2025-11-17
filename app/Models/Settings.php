<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;
     protected $fillable = [
    'address',
    'phone',
    'phone_two',
    'phone_three',
    'dial_up',
    'whatsapp_number',
    'messenger_username',
    'imo_number',
    'email',
    'email_two',
    'marque_text',
    'marque_status',
    'sms_status',
    'fb_link',
    'twitter_link',
    'yt_link',
    'insta_link',
    'copyright',
    'logo',
    'favicon',
    'currency',
    'bkash',
    'fb_pixel',
    'about_us',
    'delivery_policy',
    'return_policy',
    'google_sheet',
    'orders_per_hour_limit',
    'orders_per_day_limit',
    ];

}
