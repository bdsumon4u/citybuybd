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
    'contact_phone_plus',
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
    'whatsapp_from_phone_number_id',
    'whatsapp_token',
    'whatsapp_notification_enabled_processing',
    'whatsapp_template_name_processing',
    'whatsapp_notification_enabled_pending_delivery',
    'whatsapp_template_name_pending_delivery',
    'whatsapp_notification_enabled_on_hold',
    'whatsapp_template_name_on_hold',
    'whatsapp_notification_enabled_cancel',
    'whatsapp_template_name_cancel',
    'whatsapp_notification_enabled_completed',
    'whatsapp_template_name_completed',
    'whatsapp_notification_enabled_pending_payment',
    'whatsapp_template_name_pending_payment',
    'whatsapp_notification_enabled_on_delivery',
    'whatsapp_template_name_on_delivery',
    'whatsapp_notification_enabled_no_response1',
    'whatsapp_template_name_no_response1',
    'whatsapp_notification_enabled_no_response2',
    'whatsapp_template_name_no_response2',
    'whatsapp_notification_enabled_courier_hold',
    'whatsapp_template_name_courier_hold',
    'whatsapp_notification_enabled_order_return',
    'whatsapp_template_name_order_return',
    ];

}
