<?php return  [
  'barryvdh/laravel-debugbar' => 
   [
    'aliases' => 
     [
      'Debugbar' => \Barryvdh\Debugbar\Facades\Debugbar::class,
    ],
    'providers' => 
     [
      0 => \Barryvdh\Debugbar\ServiceProvider::class,
    ],
  ],
  'hardevine/shoppingcart' => 
   [
    'aliases' => 
     [
      'Cart' => \Gloudemans\Shoppingcart\Facades\Cart::class,
    ],
    'providers' => 
     [
      0 => \Gloudemans\Shoppingcart\ShoppingcartServiceProvider::class,
    ],
  ],
  'iazaran/smart-cache' => 
   [
    'aliases' => 
     [
      'SmartCache' => \SmartCache\Facades\SmartCache::class,
    ],
    'providers' => 
     [
      0 => \SmartCache\Providers\SmartCacheServiceProvider::class,
    ],
  ],
  'intervention/image' => 
   [
    'providers' => 
     [
      0 => \Intervention\Image\ImageServiceProvider::class,
    ],
    'aliases' => 
     [
      'Image' => \Intervention\Image\Facades\Image::class,
    ],
  ],
  'laravel-notification-channels/webpush' => 
   [
    'providers' => 
     [
      0 => \NotificationChannels\WebPush\WebPushServiceProvider::class,
    ],
  ],
  'laravel/breeze' => 
   [
    'providers' => 
     [
      0 => \Laravel\Breeze\BreezeServiceProvider::class,
    ],
  ],
  'laravel/sail' => 
   [
    'providers' => 
     [
      0 => \Laravel\Sail\SailServiceProvider::class,
    ],
  ],
  'laravel/sanctum' => 
   [
    'providers' => 
     [
      0 => \Laravel\Sanctum\SanctumServiceProvider::class,
    ],
  ],
  'laravel/tinker' => 
   [
    'providers' => 
     [
      0 => \Laravel\Tinker\TinkerServiceProvider::class,
    ],
  ],
  'livewire/livewire' => 
   [
    'aliases' => 
     [
      'Livewire' => \Livewire\Livewire::class,
    ],
    'providers' => 
     [
      0 => \Livewire\LivewireServiceProvider::class,
    ],
  ],
  'maatwebsite/excel' => 
   [
    'aliases' => 
     [
      'Excel' => \Maatwebsite\Excel\Facades\Excel::class,
    ],
    'providers' => 
     [
      0 => \Maatwebsite\Excel\ExcelServiceProvider::class,
    ],
  ],
  'nesbot/carbon' => 
   [
    'providers' => 
     [
      0 => \Carbon\Laravel\ServiceProvider::class,
    ],
  ],
  'netflie/laravel-notification-whatsapp' => 
   [
    'providers' => 
     [
      0 => \NotificationChannels\WhatsApp\WhatsAppServiceProvider::class,
    ],
  ],
  'nunomaduro/collision' => 
   [
    'providers' => 
     [
      0 => \NunoMaduro\Collision\Adapters\Laravel\CollisionServiceProvider::class,
    ],
  ],
  'nunomaduro/termwind' => 
   [
    'providers' => 
     [
      0 => \Termwind\Laravel\TermwindServiceProvider::class,
    ],
  ],
  'spatie/laravel-ignition' => 
   [
    'aliases' => 
     [
      'Flare' => \Spatie\LaravelIgnition\Facades\Flare::class,
    ],
    'providers' => 
     [
      0 => \Spatie\LaravelIgnition\IgnitionServiceProvider::class,
    ],
  ],
  'spatie/laravel-responsecache' => 
   [
    'aliases' => 
     [
      'ResponseCache' => \Spatie\ResponseCache\Facades\ResponseCache::class,
    ],
    'providers' => 
     [
      0 => \Spatie\ResponseCache\ResponseCacheServiceProvider::class,
    ],
  ],
];