<?php

namespace App\Helpers;

/**
 * Class General
 * This class contains helper functions commonly used across the application.
 */
class General
{
    /**
     * Returns a CSS class if the current route matches the given route.
     *
     * @param array|string $r Route or array of routes to check against.
     * @param string $class CSS class to return if the route matches.
     * @return string
     */
    public function routeMatchClass($r, $class = 'active')
    {
        return $this->checkRoute($r) ? $class : '';
    }

    /**
     * Checks if the current route matches a given route or array of routes.
     *
     * @param array|string $r Route or array of routes to check.
     * @return bool
     */

    public function checkRoute($r)
    {
        $currentRoute = \Illuminate\Support\Facades\Route::currentRouteName();
        if (is_array($r)) {
            return in_array($currentRoute, $r);
        } else {
            return $currentRoute == $r;
        }
    }

    /**
     * Returns the auth redirect URL from the session if it exists, otherwise returns the default URL.
     *
     * @param string $redirectUrl Default URL to return if no redirect URL is set in session.
     * @return string
     */

    public function authRedirectUrl($redirectUrl)
    {
        $session_auth_redirect_url = session('auth_redirect_url');
        if ($session_auth_redirect_url) {
            $redirectUrl = $session_auth_redirect_url;
            session()->forget('auth_redirect_url');
        }
        return $redirectUrl;
    }

    /**
     * Returns the alert message view if a message exists in the session.
     *
     * @return \Illuminate\View\View|null
     */
    public function alertMessage()
    {
        if (session()->exists(['success', 'error', 'warning', 'info', 'errors'])) {
            return view('common.message_alert');
        }
        return null;
    }

    /**
     * Returns the password policy string.
     *
     * @param string $type Default URL to return if no redirect URL is set in session.
     * @return string
     */

    public function passwordType()
    {
        $type = config('setting.password_type');
        $policy = 'min:6';
        if ($type) {
            return \Illuminate\Validation\Rules\Password::min(6)->letters()->mixedCase()->numbers()->symbols()->uncompromised(3);
        } else {
            return \Illuminate\Validation\Rules\Password::min(6);
        }
        return $policy;
    }

    /**
     * Retrieves SEO meta tags from the cache or database.
     *
     * @return array|null
     */
    public function getMetaData()
    {
        return (new \App\Models\SeoMeta())->getMetaData();
    }

    /**
     * Retrieves all settings from the cache or database.
     *
     * @return array
     */
    public function getAllSettings()
    {
        return (new \App\Models\Setting())->getAllSettings();
    }

    /**
     * Retrieves the settings from the cache or database and updates the application configuration.
     *
     * @return void
     */
    public function configSettings()
    {
        config($this->getAllSettings());
    }

    /**
     * Checks if the rate limit for a given key has been exceeded.
     *
     * @param string $key Unique rate limiting key.
     * @param bool $addIpInKey Whether to append the client's IP address to the rate limit key.
     * @param int $limit Maximum number of attempts allowed.
     * @return bool
     */

    public function rateLimit($key, $limit = 10)
    {
        return !\Illuminate\Support\Facades\RateLimiter::attempt($key, $limit, function () {});
    }


    /**
     * Checks if Google reCAPTCHA verification fails.
     *
     * @return bool
     */
    public function recaptchaFails()
    {
        if (!config('setting.google_recaptcha')) {
            return true;
        }
        try {
            $recaptcha = request('g-recaptcha-response');
            $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . config('setting.google_recaptcha_secret_key') . '&response=' . $recaptcha;
            $response = @file_get_contents($url);;
            $response = @json_decode($response);;
            return !$response->success;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Retrieves the client's IP address.
     *
     * @return string
     */

    public function getClientIp()
    {
        return request()->ip();
    }

    /**
     * Fetches information about an IP address using a third-party API and caches the result.
     *
     * @param string $ip The IP address to look up. Defaults to the client's IP if empty.
     * @param int $decode Whether to decode the response (1: JSON, 2: location string).
     * @return mixed
     */

    public function getIpData($ip = '')
    {
        if ($ip == '') {
            $ip = $this->getClientIp();
        }
        $key = 'ip_info:' . $ip;
        $data = \Illuminate\Support\Facades\Cache::get($key);
        if ($data) {
            return $data;
        }
        $result = @file_get_contents('https://api.tribital.com/ipinfo/index.php?ip=' . $ip);
        \Illuminate\Support\Facades\Cache::add($key, $result, 86400);
        return $result; // Return raw data without decoding
    }

    public function getIpLocation()
    {
        $ipData = $this->getIpInfo(); // Get raw data
        if ($ipData) {
            return $ipData->city . ', ' . $ipData->region . ', ' . $ipData->country;
        } else {
            return '';
        }
    }

    public function getIpInfo($ip = '')
    {
        $ipInfo = $this->getIpData($ip); // Get raw data
        if ($ipInfo) {
            $ipInfo = @json_decode($ipInfo); // Decode the raw data here
            if (is_object($ipInfo)) {
                $ipData = new \stdClass();
                $ipData->city = $ipInfo->city ?? null;
                $ipData->region = $ipInfo->region ?? null;
                $ipData->country = $ipInfo->country_name ?? null;
                $ipData->latitude = $ipInfo->latitude ?? null;
                $ipData->longitude = $ipInfo->longitude ?? null;
                return $ipData;
            }
        }
        return false;
    }

    /**
     * Fetches only the country information for a given IP address.
     *
     * @param string $ip The IP address to look up. Defaults to the client's IP if empty.
     * @return string
     */

    public function getIpInfoCountry($ip = '')
    {
        if (empty($ip)) {
            $ip = $this->getClientIp();
        }
        $key = 'ip_info_country:' . $ip;
        $data = \Illuminate\Support\Facades\Cache::get($key);
        if ($data) {
            return $data;
        }
        $result = @file_get_contents('https://api.tribital.com/ipinfo_country/index.php?ip=' . $ip);
        \Illuminate\Support\Facades\Cache::add($key, $result, 86400);
        return $result;
    }

    /**
     * Retrieves the device name and operating system from the user agent string.
     *
     * @param string $userAgent User agent string to parse.
     * @return string
     */
    public function deviceName($device_name)
    {
        $result = (new \WhichBrowser\Parser($device_name));
        if ($result && isset($result->browser->name)) {
            return @$result->browser->name . ' on ' . @$result->os->name;
        } else {
            return '';
        }
    }


    public function slugify($title)
    {
        $slug = strtolower($title);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
        return $slug;
    }



    /**
     * Returns the file url for a given type.
     *
     * @param string $type The type of file (profile, setting, blog, etc.).
     * @return string
     */
    public function fileRules($type = 'image', $size = 1024)
    {
        $rule = 'file|mimetypes:image/*|max:' . $size;
        switch ($type) {
            case 'image':
                $rule = 'file|mimes:jpeg,jpg,png,gif,webp,bmp,svg,ico|max:' . $size;
                break;
            case 'pdf':
                $rule = 'file|mimes:pdf|max:1024';
                break;
            case 'doc':
                $rule = 'file|mimes:pdf,xlsx,doc,docx|max:1024';
                break;
            case 'all':
                $rule = 'file|mimes:pdf,xlsx,doc,docx,jpeg,jpg,png,gif,webp,bmp,svg,ico|max:1024';
                break;
        }
        return $rule;
    }

    /**
     * Returns the file path for a given type.
     *
     * @param string $type The type of file (profile, setting, blog, etc.).
     * @return string
     */
    public function getfilePath($type = 'profile')
    {
        return match ($type) {
            'profile' => 'profile/',
            'email' => 'email/',
            'logo' => 'logo/',
            default => 'temp/',
        };
    }
    /**
     * Retrieves the URL of a stored file, or returns the default "no file" URL if it doesn't exist.
     *
     * @param string $type The type of file (profile, setting, etc.).
     * @return string
     */
    public function getNoFile($type = 'setting')
    {
        return \Illuminate\Support\Facades\Storage::url('no-image.jpg');
    }

    /**
     * Retrieves the URL of a stored file, or returns the default "no file" URL if it doesn't exist.
     *
     * @param string|null $file The file name.
     * @param string $type The type of file (profile, setting, etc.).
     * @param string|null $subDir Subdirectory under the type's path.
     * @return string
     */
    public function getFileUrl($file, $type = 'profile')
    {
        if ($file) {
            $path = $this->getfilePath($type);
            $storage = new \Illuminate\Support\Facades\Storage();
            if ($storage::has($path . $file)) {
                return $storage::url($path . $file);
            }
        }
        return $this->getNoFile($type);
    }

    public function getError($validator)
    {
        $errors = $validator->messages()->all();
        return isset($errors[0]) ? $errors[0] : 'Something went wrong';
    }
    /** 
     * Deletes a file from the storage.
     *
     * @param string|null $file The file name.
     * @param string $type The type of file (profile, setting, etc.).
     * @param string|null $subDir Subdirectory under the type's path.
     * @return void
     */

    public function deleteFile($file, $type = 'profile')
    {
        if ($file) {
            $path = $this->getfilePath($type);
            $storage = new \Illuminate\Support\Facades\Storage();
            if ($storage::has($path . $file)) {
                $storage::delete($path . $file);
            }
        }
    }


    /**
     * Uploads a file to storage and returns its path.
     *
     * @param \Illuminate\Http\UploadedFile $file The file to upload.
     * @param int $type The file type identifier.
     * @param string|null $subDir Optional subdirectory under the type's path.
     * @param string $name Optional name for the uploaded file.
     * @return array
     */

    public function uploadFile($file, $type = 'profile', $subDir = '', $name = '')
    {
        $fileDir = $this->getfilePath($type);
        if ($subDir != '') {
            if ($subDir == 'date') {
                $subDir = date('Y/m');
            }
            \Illuminate\Support\Facades\Storage::makeDirectory($fileDir . $subDir);
        }
        try {
            if ($name == '') {
                $name = \Illuminate\Support\Str::random(32) . '.' . $file->getClientOriginalExtension();
            } else if ($name == 'same') {
                $name = $file->getClientOriginalName();
            }
            $fileResult = \Illuminate\Support\Facades\Storage::putFileAs($fileDir . $subDir, $file, $name);
            if ($fileResult) {
                return ['status' => 1, 'message' => 'File uploaded successfully', 'file_name' => trim(str_replace($fileDir, '', $fileResult), '/'), 'file_type' => $file->getClientMimeType(), 'size' => $file->getSize(), 'name' => $file->getClientOriginalName(), 'extension' => $file->getClientOriginalExtension()];
            } else {
                return ['status' => 0, 'message' => 'File upload failed'];
            }
        } catch (\Exception $e) {
            return ['status' => 0, 'message' => $e->getMessage()];
        }
    }

    /**
     * Sends an email using the default mailer.
     *
     * @param string $to The recipient's email address.
     * @param string $subject The email subject.
     * @param string $body The email body content.
     * @return array
     */
    public function sendEmail(string $to, string $template, array $data)
    {

        $templateData = (new \App\Models\EmailTemplate())->getEmailTemplate($template, $data);
        if (function_exists("proc_open")) {
            // Dispatch email job (queue must be running)
            \App\Jobs\SendEmail::dispatchAfterResponse($to, $templateData['subject'], $templateData['body']);
            return ['status' => 1, 'message' => 'Email dispatched to queue'];
        } else {
            // return $this->sendEmailSMTP($to, $templateData['subject'], $templateData['body']);
            return $this->sendMailApi($to, $templateData['subject'], $templateData['body']);
        }
    }

    public function sendEmailSMTP(string $to, string $subject, string $body){
        try {
            // Log email data for debugging
            \Log::info("Sending Email to: " . $to . ' : ' . $subject);
            // Send email immediately
            // \Mail::send('email/layouts/container', compact('body'), function ($message) use ($to, $subject) {
            //     $message->from(config('mail.from.address'), config('mail.from.name'))
            //         ->to($to)
            //         ->subject($subject);
            // });

            $transport = new \Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport(
                config('mail.mailers.smtp.host'),
                config('mail.mailers.smtp.port'),
                config('mail.mailers.smtp.encryption') === 'ssl'
            );
            $transport->setUsername(config('mail.mailers.smtp.username'));
            $transport->setPassword(config('mail.mailers.smtp.password'));
            // Now pass the transport directly to the Mailer
            $laravelMailer = new \Illuminate\Mail\Mailer(
                config('mail.default'),
                app('view'),
                $transport,
                app('events')
            );
            $laravelMailer->send('email/layouts/container', ['body' => $body], function ($message) use ($to, $subject) {
                $message->from(config('mail.from.address'), config('mail.from.name'))
                    ->to($to)
                    ->subject($subject);
            });

            \Log::info('Email Sent : ' . $to . ' : ' . $subject);
            return ['status' => 1, 'message' => 'Email sent successfully'];
        } catch (\Exception $e) {
            \Log::error("Email Failed : " . $to . " : " . $subject . " : " . $e->getMessage());
            return ['status' => 0, 'message' => $e->getMessage()];
        }
    }


    /**
     * Sends an email using the Tribital Mailer API.
     *
     * @param string $to The recipient's email address.
     * @param string $subject The email subject.
     * @param string $body The email body content.
     * @return array
     */
    public function sendMailApi(string $to, string $subject, string $body)
    {
        // Log email data for debugging
        \Log::info("Sending Email to: " . $to . ' : ' . $subject);

        $data = [
            'to' => $to,
            'subject' => $subject,
            'body' => $body,
        
            'api_key' => 'LhBuEz7wGEwv3AxmnBSX3QUwVsyjqr8qKj6jPjV7NuHkAFKnJR8',
            'smtp_host' => config('mail.mailers.smtp.host'),
            'smtp_port' => config('mail.mailers.smtp.port'),
            'smtp_encryption' => config('mail.mailers.smtp.encryption'),
            'smtp_username' => config('mail.mailers.smtp.username'),
            'smtp_password' => config('mail.mailers.smtp.password'),
            'from' => config('mail.from.address'),
            'from_name' => config('mail.from.name'),
            'to_name' => '',
        ];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.tribital.com/mailer/send.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $result=@json_decode($response, true);
        if($result['status']){
            \Log::info('Email Sent : ' . $to . ' : ' . $subject);
            return ['status' => 1, 'message' => 'Email sent successfully'];
        }else{
            \Log::error("Email Failed : " . $to . " : " . $subject . " : " . $result['message']);
            return ['status' => 0, 'message' => $result['message']];
        }
    }

    public function verifyEmail($email)
    {
        $result = ['status' => 1, 'message' => 'Email is valid'];
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://verify.maileroo.net/check',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                "api_key":"375df02c16af6b78a9131cf6ba190d9444423a101843232680ba3434e0c4d9c1",
                "email_address":"' . $email . '"
            }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            //echo $response;
            $response = json_decode($response, true);
            if ($response['success']) {
                if (!$response['success']['data']['format_valid']) {
                    $result = ['status' => 0, 'message' => 'Email is format is not valid'];
                }
                if (!$response['success']['data']['mx_found']) {
                    $result = ['status' => 0, 'message' => 'Email is is not valid'];
                }
                if (!$response['success']['data']['disposable']) {
                    $result = ['status' => 0, 'message' => 'Email is not allowed'];
                }
            }
        } catch (\Exception $e) {
        }
        return $result;
    }

    public function getTimezooneList()
    {
        return json_decode('{"Pacific/Midway":"(UTC-11:00) Pacific/Midway","US/Samoa":"(UTC-11:00) US/Samoa","US/Hawaii":"(UTC-10:00) US/Hawaii","US/Alaska":"(UTC-09:00) US/Alaska","US/Pacific":"(UTC-08:00) US/Pacific","America/Tijuana":"(UTC-08:00) America/Tijuana","US/Arizona":"(UTC-07:00) US/Arizona","US/Mountain":"(UTC-07:00) US/Mountain","America/Chihuahua":"(UTC-07:00) America/Chihuahua","America/Mazatlan":"(UTC-07:00) America/Mazatlan","America/Mexico_City":"(UTC-06:00) America/Mexico_City","America/Monterrey":"(UTC-06:00) America/Monterrey","Canada/Saskatchewan":"(UTC-06:00) Canada/Saskatchewan","US/Central":"(UTC-06:00) US/Central","US/Eastern":"(UTC-05:00) US/Eastern","US/East-Indiana":"(UTC-05:00) US/East-Indiana","America/Bogota":"(UTC-05:00) America/Bogota","America/Lima":"(UTC-05:00) America/Lima","America/Caracas":"(UTC-04:30) America/Caracas","Canada/Atlantic":"(UTC-04:00) Canada/Atlantic","America/La_Paz":"(UTC-04:00) America/La_Paz","America/Santiago":"(UTC-04:00) America/Santiago","Canada/Newfoundland":"(UTC-03:30) Canada/Newfoundland","America/Buenos_Aires":"(UTC-03:00) America/Buenos_Aires","Greenland":"(UTC-03:00) Greenland","Atlantic/Stanley":"(UTC-02:00) Atlantic/Stanley","Atlantic/Azores":"(UTC-01:00) Atlantic/Azores","Atlantic/Cape_Verde":"(UTC-01:00) Atlantic/Cape_Verde","Africa/Casablanca":"(UTC) Africa/Casablanca","Europe/Dublin":"(UTC) Europe/Dublin","Europe/Lisbon":"(UTC) Europe/Lisbon","Europe/London":"(UTC) Europe/London","Africa/Monrovia":"(UTC) Africa/Monrovia","Europe/Amsterdam":"(UTC+01:00) Europe/Amsterdam","Europe/Belgrade":"(UTC+01:00) Europe/Belgrade","Europe/Berlin":"(UTC+01:00) Europe/Berlin","Europe/Bratislava":"(UTC+01:00) Europe/Bratislava","Europe/Brussels":"(UTC+01:00) Europe/Brussels","Europe/Budapest":"(UTC+01:00) Europe/Budapest","Europe/Copenhagen":"(UTC+01:00) Europe/Copenhagen","Europe/Ljubljana":"(UTC+01:00) Europe/Ljubljana","Europe/Madrid":"(UTC+01:00) Europe/Madrid","Europe/Paris":"(UTC+01:00) Europe/Paris","Europe/Prague":"(UTC+01:00) Europe/Prague","Europe/Rome":"(UTC+01:00) Europe/Rome","Europe/Sarajevo":"(UTC+01:00) Europe/Sarajevo","Europe/Skopje":"(UTC+01:00) Europe/Skopje","Europe/Stockholm":"(UTC+01:00) Europe/Stockholm","Europe/Vienna":"(UTC+01:00) Europe/Vienna","Europe/Warsaw":"(UTC+01:00) Europe/Warsaw","Europe/Zagreb":"(UTC+01:00) Europe/Zagreb","Europe/Athens":"(UTC+02:00) Europe/Athens","Europe/Bucharest":"(UTC+02:00) Europe/Bucharest","Africa/Cairo":"(UTC+02:00) Africa/Cairo","Africa/Harare":"(UTC+02:00) Africa/Harare","Europe/Helsinki":"(UTC+02:00) Europe/Helsinki","Europe/Istanbul":"(UTC+02:00) Europe/Istanbul","Asia/Jerusalem":"(UTC+02:00) Asia/Jerusalem","Europe/Kiev":"(UTC+02:00) Europe/Kiev","Europe/Minsk":"(UTC+02:00) Europe/Minsk","Europe/Riga":"(UTC+02:00) Europe/Riga","Europe/Sofia":"(UTC+02:00) Europe/Sofia","Europe/Tallinn":"(UTC+02:00) Europe/Tallinn","Europe/Vilnius":"(UTC+02:00) Europe/Vilnius","Asia/Baghdad":"(UTC+03:00) Asia/Baghdad","Asia/Kuwait":"(UTC+03:00) Asia/Kuwait","Africa/Nairobi":"(UTC+03:00) Africa/Nairobi","Asia/Riyadh":"(UTC+03:00) Asia/Riyadh","Europe/Moscow":"(UTC+03:00) Europe/Moscow","Asia/Tehran":"(UTC+03:30) Asia/Tehran","Asia/Baku":"(UTC+04:00) Asia/Baku","Europe/Volgograd":"(UTC+04:00) Europe/Volgograd","Asia/Muscat":"(UTC+04:00) Asia/Muscat","Asia/Tbilisi":"(UTC+04:00) Asia/Tbilisi","Asia/Yerevan":"(UTC+04:00) Asia/Yerevan","Asia/Kabul":"(UTC+04:30) Asia/Kabul","Asia/Karachi":"(UTC+05:00) Asia/Karachi","Asia/Tashkent":"(UTC+05:00) Asia/Tashkent","Asia/Kolkata":"(UTC+05:30) Asia/Kolkata","Asia/Kathmandu":"(UTC+05:45) Asia/Kathmandu","Asia/Yekaterinburg":"(UTC+06:00) Asia/Yekaterinburg","Asia/Almaty":"(UTC+06:00) Asia/Almaty","Asia/Dhaka":"(UTC+06:00) Asia/Dhaka","Asia/Novosibirsk":"(UTC+07:00) Asia/Novosibirsk","Asia/Bangkok":"(UTC+07:00) Asia/Bangkok","Asia/Jakarta":"(UTC+07:00) Asia/Jakarta","Asia/Krasnoyarsk":"(UTC+08:00) Asia/Krasnoyarsk","Asia/Chongqing":"(UTC+08:00) Asia/Chongqing","Asia/Hong_Kong":"(UTC+08:00) Asia/Hong_Kong","Asia/Kuala_Lumpur":"(UTC+08:00) Asia/Kuala_Lumpur","Australia/Perth":"(UTC+08:00) Australia/Perth","Asia/Singapore":"(UTC+08:00) Asia/Singapore","Asia/Taipei":"(UTC+08:00) Asia/Taipei","Asia/Ulaanbaatar":"(UTC+08:00) Asia/Ulaanbaatar","Asia/Urumqi":"(UTC+08:00) Asia/Urumqi","Asia/Irkutsk":"(UTC+09:00) Asia/Irkutsk","Asia/Seoul":"(UTC+09:00) Asia/Seoul","Asia/Tokyo":"(UTC+09:00) Asia/Tokyo","Australia/Adelaide":"(UTC+09:30) Australia/Adelaide","Australia/Darwin":"(UTC+09:30) Australia/Darwin","Asia/Yakutsk":"(UTC+10:00) Asia/Yakutsk","Australia/Brisbane":"(UTC+10:00) Australia/Brisbane","Australia/Canberra":"(UTC+10:00) Australia/Canberra","Pacific/Guam":"(UTC+10:00) Pacific/Guam","Australia/Hobart":"(UTC+10:00) Australia/Hobart","Australia/Melbourne":"(UTC+10:00) Australia/Melbourne","Pacific/Port_Moresby":"(UTC+10:00) Pacific/Port_Moresby","Australia/Sydney":"(UTC+10:00) Australia/Sydney","Asia/Vladivostok":"(UTC+11:00) Asia/Vladivostok","Asia/Magadan":"(UTC+12:00) Asia/Magadan","Pacific/Auckland":"(UTC+12:00) Pacific/Auckland","Pacific/Fiji":"(UTC+12:00) Pacific/Fiji"}', true);
    }
}
