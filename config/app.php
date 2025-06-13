<?php
function getClientTimezone($defaultTimezone){
    if(!isset($_COOKIE[env('APP_UID').'_tz'])){
        return $defaultTimezone;
    }
    $tz=$_COOKIE[env('APP_UID').'_tz'];
    if(in_array($tz, timezone_identifiers_list())) {
        return $tz;
    }
    $tzMap = [
        'Asia/Calcutta' => 'Asia/Kolkata',
        'Asia/Katmandu' => 'Asia/Kathmandu', 
        'Asia/Rangoon' => 'Asia/Yangon',
        'Asia/Saigon' => 'Asia/Ho_Chi_Minh',
        'America/Argentina/ComodRivadavia' => 'America/Argentina/Buenos_Aires',
        'America/Atka' => 'America/Adak',
        'America/Buenos_Aires' => 'America/Argentina/Buenos_Aires',
        'America/Ensenada' => 'America/Tijuana',
        'America/Fort_Wayne' => 'America/Indiana/Indianapolis',
        'America/Indianapolis' => 'America/Indiana/Indianapolis',
        'America/Knox_IN' => 'America/Indiana/Knox',
        'America/Louisville' => 'America/Kentucky/Louisville',
        'America/Montreal' => 'America/Toronto',
        'America/Porto_Acre' => 'America/Rio_Branco',
        'America/Rosario' => 'America/Argentina/Buenos_Aires',
        'America/Virgin' => 'America/Puerto_Rico',
        'Antarctica/South_Pole' => 'Pacific/Auckland',
        'Asia/Istanbul' => 'Europe/Istanbul',
        'Asia/Phnom_Penh' => 'Asia/Bangkok',
        'Asia/Tel_Aviv' => 'Asia/Jerusalem',
        'Atlantic/Faeroe' => 'Atlantic/Faroe',
        'Atlantic/Jan_Mayen' => 'Europe/Oslo',
        'Australia/ACT' => 'Australia/Sydney',
        'Australia/Canberra' => 'Australia/Sydney',
        'Australia/LHI' => 'Australia/Lord_Howe',
        'Australia/NSW' => 'Australia/Sydney',
        'Australia/North' => 'Australia/Darwin',
        'Australia/Queensland' => 'Australia/Brisbane',
        'Australia/South' => 'Australia/Adelaide',
        'Australia/Tasmania' => 'Australia/Hobart',
        'Australia/Victoria' => 'Australia/Melbourne',
        'Australia/West' => 'Australia/Perth',
        'Australia/Yancowinna' => 'Australia/Broken_Hill',
        'Brazil/Acre' => 'America/Rio_Branco',
        'Brazil/DeNoronha' => 'America/Noronha',
        'Brazil/East' => 'America/Sao_Paulo',
        'Brazil/West' => 'America/Manaus',
        'Canada/Atlantic' => 'America/Halifax',
        'Canada/Central' => 'America/Winnipeg',
        'Canada/Eastern' => 'America/Toronto',
        'Canada/Mountain' => 'America/Edmonton',
        'Canada/Newfoundland' => 'America/St_Johns',
        'Canada/Pacific' => 'America/Vancouver',
        'Canada/Saskatchewan' => 'America/Regina',
        'Canada/Yukon' => 'America/Whitehorse',
        'Chile/Continental' => 'America/Santiago',
        'Chile/EasterIsland' => 'Pacific/Easter',
        'Cuba' => 'America/Havana',
        'Egypt' => 'Africa/Cairo',
        'Eire' => 'Europe/Dublin',
        'Europe/Belfast' => 'Europe/London',
        'Europe/Tiraspol' => 'Europe/Chisinau',
        'GB' => 'Europe/London',
        'GB-Eire' => 'Europe/London',
        'Greenwich' => 'Etc/GMT',
        'Hongkong' => 'Asia/Hong_Kong',
        'Iceland' => 'Atlantic/Reykjavik',
        'Iran' => 'Asia/Tehran',
        'Israel' => 'Asia/Jerusalem',
        'Jamaica' => 'America/Jamaica',
        'Japan' => 'Asia/Tokyo',
        'Kwajalein' => 'Pacific/Kwajalein',
        'Libya' => 'Africa/Tripoli',
        'Mexico/BajaNorte' => 'America/Tijuana',
        'Mexico/BajaSur' => 'America/Mazatlan',
        'Mexico/General' => 'America/Mexico_City',
        'NZ' => 'Pacific/Auckland',
        'NZ-CHAT' => 'Pacific/Chatham',
        'Navajo' => 'America/Denver',
        'PRC' => 'Asia/Shanghai',
        'Pacific/Johnston' => 'Pacific/Honolulu',
        'Pacific/Ponape' => 'Pacific/Pohnpei',
        'Pacific/Samoa' => 'Pacific/Pago_Pago',
        'Pacific/Truk' => 'Pacific/Chuuk',
        'Pacific/Yap' => 'Pacific/Chuuk',
        'Poland' => 'Europe/Warsaw',
        'Portugal' => 'Europe/Lisbon',
        'ROC' => 'Asia/Taipei',
        'ROK' => 'Asia/Seoul',
        'Singapore' => 'Asia/Singapore',
        'Turkey' => 'Europe/Istanbul',
        'UCT' => 'Etc/UTC',
        'US/Alaska' => 'America/Anchorage',
        'US/Aleutian' => 'America/Adak',
        'US/Arizona' => 'America/Phoenix',
        'US/Central' => 'America/Chicago',
        'US/East-Indiana' => 'America/Indiana/Indianapolis',
        'US/Eastern' => 'America/New_York',
        'US/Hawaii' => 'Pacific/Honolulu',
        'US/Indiana-Starke' => 'America/Indiana/Knox',
        'US/Michigan' => 'America/Detroit',
        'US/Mountain' => 'America/Denver',
        'US/Pacific' => 'America/Los_Angeles',
        'US/Samoa' => 'Pacific/Pago_Pago',
        'Universal' => 'Etc/UTC',
        'W-SU' => 'Europe/Moscow',
        'Zulu' => 'Etc/UTC'
    ];
    if(isset($tzMap[$tz])){
        return $tzMap[$tz];
    }
    return $defaultTimezone;
}
return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application, which will be used when the
    | framework needs to place the application's name in a notification or
    | other UI elements where an application name needs to be displayed.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | the application so that it's available within Artisan commands.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. The timezone
    | is set to "UTC" by default as it is suitable for most use cases.
    |
    */

    'timezone' => getClientTimezone(env('APP_TIMEZONE', 'en')),

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by Laravel's translation / localization methods. This option can be
    | set to any locale for which you plan to have translation strings.
    |
    */

    'locale' => env('APP_LOCALE', 'en'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is utilized by Laravel's encryption services and should be set
    | to a random, 32 character string to ensure that all encrypted values
    | are secure. You should do this prior to deploying the application.
    |
    */

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | These configuration options determine the driver used to determine and
    | manage Laravel's "maintenance mode" status. The "cache" driver will
    | allow maintenance mode to be controlled across multiple machines.
    |
    | Supported drivers: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

];
