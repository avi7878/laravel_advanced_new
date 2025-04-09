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
    public function getMetaTags()
    {
        return (new \App\Models\SeoMeta())->getMetaTags();
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

    public function getIpLocation(){
        $ipData = $this->getIpInfo(); // Get raw data
        if($ipData){
            return $ipData->city . ', ' . $ipData->region . ', ' . $ipData->country;
        }else{
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
        $key = 'ip_info:' . $ip;
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
                $rule = 'file|mimes:jpeg,jpg,png,gif,webp,bmp,svg|max:' . $size;
                break;
            case 'pdf':
                $rule = 'file|mimes:pdf|max:1024';
                break;
            case 'doc':
                $rule = 'file|mimes:pdf,xlsx,doc,docx|max:1024';
                break;
            case 'all':
                $rule = 'file|mimes:pdf,xlsx,doc,docx,jpeg,jpg,png,gif,webp,bmp,svg|max:1024';
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
    public function getfilePath($type = 'setting')
    {
        return match ($type) {
            'profile' => 'profile/',
            'email' => 'email/',
            'setting' => 'setting/',
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
    public function getFileUrl($file, $type = 'setting')
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

    public function deleteFile($file, $type = 'setting')
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
     * @return array
     */

    public function uploadFile($file, $type = 'setting')
    {
        $fileDir = $this->getfilePath($type);
        $dateDir = date('Y/m');
        \Illuminate\Support\Facades\Storage::makeDirectory($fileDir . $dateDir);
        try {
            $fileResult = \Illuminate\Support\Facades\Storage::put($fileDir . $dateDir, $file);
            if ($fileResult) {
                return ['status' => 1, 'file_name' => str_replace($fileDir, '', $fileResult), 'name' => $file->getClientOriginalName()];
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
        return $this->sendMail($to, $templateData['subject'], $templateData['body']);
    }

    /**
     * Sends a confirmation email for a new registration.
     *
     * @param string $email User's email address.
     * @param string $subject The email subject.
     * @param string $view The view for the email body.
     * @param array $data Data to be passed to the view.
     * @return array
     */

    public function sendMail(string $to, string $subject, string $body)
    {
        if (function_exists("proc_open")) {
            // Dispatch email job (queue must be running)
            \App\Jobs\SendEmail::dispatchAfterResponse($to, $subject, $body);
            return ['status' => 1, 'message' => 'Email dispatched to queue'];
        } else {
            try {
                // Log email data for debugging
                \Log::info("Sending Email to: " . $to);
                \Log::info("Subject: " . $subject);
                \Log::info("Body: " . $body);
                // Send email immediately
                \Mail::send('email/layouts/container', compact('body'), function ($message) use ($to, $subject) {
                    $message->from(config('mail.from.address'), config('mail.from.name'))
                        ->to($to)
                        ->subject($subject);
                });

                return ['status' => 1, 'message' => 'Email sent successfully'];
            } catch (\Exception $e) {
                \Log::error("Email Failed: " . $to . " | " . $subject . " | " . $e->getMessage());
                return ['status' => 0, 'message' => $e->getMessage()];
            }
        }
    }


    /**
     * Sends an email using the external mail API.
     *
     * This method sends an email by making a POST request to the specified mailer API endpoint.
     * It merges the provided data with additional SMTP configuration settings and an API key.
     *
     * @param array $data An associative array containing the email details:
     *                    - 'from' (string): The sender's email address.
     *                    - 'from_name' (string): The sender's name.
     *                    - 'to' (string): The recipient's email address.
     *                    - 'to_name' (string): The recipient's name.
     *                    - 'subject' (string): The subject of the email.
     *                    - 'body' (string): The body content of the email.
     *
     * @return array|null The response from the mailer API, decoded from JSON to an associative array,
     *                    or null if the response could not be decoded.
     */
    public function sendMailApi($data)
    {
        // self::sendMailApi([
        //     'from' => config('setting.mail_from_address'),
        //     'from_name' => config('setting.mail_from_name'),
        //     'to' => $to, // Changed from $email to $to
        //     'to_name' => '',
        //     'subject' => $subject,
        //     'body' => $body, // Corrected from $view and $data
        // ]);

        $data = array_merge($data, [
            'api_key' => 'LhBuEz7wGEwv3AxmnBSX3QUwVsyjqr8qKj6jPjV7NuHkAFKnJR8',
            'smtp_host' => config('mail.mailers.smtp.host'),
            'smtp_port' => config('mail.mailers.smtp.port'),
            'smtp_encryption' => config('mail.mailers.smtp.encryption'),
            'smtp_username' => config('mail.mailers.smtp.username'),
            'smtp_password' => config('mail.mailers.smtp.password'),
        ]);
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
        return @json_decode($response, true);
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
        return json_decode('{"Pacific\/Midway":"(GMT-11:00) Midway Island","US\/Samoa":"(GMT-11:00) Samoa","US\/Hawaii":"(GMT-10:00) Hawaii","US\/Alaska":"(GMT-09:00) Alaska","US\/Pacific":"(GMT-08:00) Pacific Time (US & Canada)","America\/Tijuana":"(GMT-08:00) Tijuana","US\/Arizona":"(GMT-07:00) Arizona","US\/Mountain":"(GMT-07:00) Mountain Time (US & Canada)","America\/Chihuahua":"(GMT-07:00) Chihuahua","America\/Mazatlan":"(GMT-07:00) Mazatlan","America\/Mexico_City":"(GMT-06:00) Mexico City","America\/Monterrey":"(GMT-06:00) Monterrey","Canada\/Saskatchewan":"(GMT-06:00) Saskatchewan","US\/Central":"(GMT-06:00) Central Time (US & Canada)","US\/Eastern":"(GMT-05:00) Eastern Time (US & Canada)","US\/East-Indiana":"(GMT-05:00) Indiana (East)","America\/Bogota":"(GMT-05:00) Bogota","America\/Lima":"(GMT-05:00) Lima","America\/Caracas":"(GMT-04:30) Caracas","Canada\/Atlantic":"(GMT-04:00) Atlantic Time (Canada)","America\/La_Paz":"(GMT-04:00) La Paz","America\/Santiago":"(GMT-04:00) Santiago","Canada\/Newfoundland":"(GMT-03:30) Newfoundland","America\/Buenos_Aires":"(GMT-03:00) Buenos Aires","Greenland":"(GMT-03:00) Greenland","Atlantic\/Stanley":"(GMT-02:00) Stanley","Atlantic\/Azores":"(GMT-01:00) Azores","Atlantic\/Cape_Verde":"(GMT-01:00) Cape Verde Is.","Africa\/Casablanca":"(GMT) Casablanca","Europe\/Dublin":"(GMT) Dublin","Europe\/Lisbon":"(GMT) Lisbon","Europe\/London":"(GMT) London","Africa\/Monrovia":"(GMT) Monrovia","Europe\/Amsterdam":"(GMT+01:00) Amsterdam","Europe\/Belgrade":"(GMT+01:00) Belgrade","Europe\/Berlin":"(GMT+01:00) Berlin","Europe\/Bratislava":"(GMT+01:00) Bratislava","Europe\/Brussels":"(GMT+01:00) Brussels","Europe\/Budapest":"(GMT+01:00) Budapest","Europe\/Copenhagen":"(GMT+01:00) Copenhagen","Europe\/Ljubljana":"(GMT+01:00) Ljubljana","Europe\/Madrid":"(GMT+01:00) Madrid","Europe\/Paris":"(GMT+01:00) Paris","Europe\/Prague":"(GMT+01:00) Prague","Europe\/Rome":"(GMT+01:00) Rome","Europe\/Sarajevo":"(GMT+01:00) Sarajevo","Europe\/Skopje":"(GMT+01:00) Skopje","Europe\/Stockholm":"(GMT+01:00) Stockholm","Europe\/Vienna":"(GMT+01:00) Vienna","Europe\/Warsaw":"(GMT+01:00) Warsaw","Europe\/Zagreb":"(GMT+01:00) Zagreb","Europe\/Athens":"(GMT+02:00) Athens","Europe\/Bucharest":"(GMT+02:00) Bucharest","Africa\/Cairo":"(GMT+02:00) Cairo","Africa\/Harare":"(GMT+02:00) Harare","Europe\/Helsinki":"(GMT+02:00) Helsinki","Europe\/Istanbul":"(GMT+02:00) Istanbul","Asia\/Jerusalem":"(GMT+02:00) Jerusalem","Europe\/Kiev":"(GMT+02:00) Kyiv","Europe\/Minsk":"(GMT+02:00) Minsk","Europe\/Riga":"(GMT+02:00) Riga","Europe\/Sofia":"(GMT+02:00) Sofia","Europe\/Tallinn":"(GMT+02:00) Tallinn","Europe\/Vilnius":"(GMT+02:00) Vilnius","Asia\/Baghdad":"(GMT+03:00) Baghdad","Asia\/Kuwait":"(GMT+03:00) Kuwait","Africa\/Nairobi":"(GMT+03:00) Nairobi","Asia\/Riyadh":"(GMT+03:00) Riyadh","Europe\/Moscow":"(GMT+03:00) Moscow","Asia\/Tehran":"(GMT+03:30) Tehran","Asia\/Baku":"(GMT+04:00) Baku","Europe\/Volgograd":"(GMT+04:00) Volgograd","Asia\/Muscat":"(GMT+04:00) Muscat","Asia\/Tbilisi":"(GMT+04:00) Tbilisi","Asia\/Yerevan":"(GMT+04:00) Yerevan","Asia\/Kabul":"(GMT+04:30) Kabul","Asia\/Karachi":"(GMT+05:00) Karachi","Asia\/Tashkent":"(GMT+05:00) Tashkent","Asia\/Kolkata":"(GMT+05:30) Kolkata","Asia\/Kathmandu":"(GMT+05:45) Kathmandu","Asia\/Yekaterinburg":"(GMT+06:00) Ekaterinburg","Asia\/Almaty":"(GMT+06:00) Almaty","Asia\/Dhaka":"(GMT+06:00) Dhaka","Asia\/Novosibirsk":"(GMT+07:00) Novosibirsk","Asia\/Bangkok":"(GMT+07:00) Bangkok","Asia\/Jakarta":"(GMT+07:00) Jakarta","Asia\/Krasnoyarsk":"(GMT+08:00) Krasnoyarsk","Asia\/Chongqing":"(GMT+08:00) Chongqing","Asia\/Hong_Kong":"(GMT+08:00) Hong Kong","Asia\/Kuala_Lumpur":"(GMT+08:00) Kuala Lumpur","Australia\/Perth":"(GMT+08:00) Perth","Asia\/Singapore":"(GMT+08:00) Singapore","Asia\/Taipei":"(GMT+08:00) Taipei","Asia\/Ulaanbaatar":"(GMT+08:00) Ulaan Bataar","Asia\/Urumqi":"(GMT+08:00) Urumqi","Asia\/Irkutsk":"(GMT+09:00) Irkutsk","Asia\/Seoul":"(GMT+09:00) Seoul","Asia\/Tokyo":"(GMT+09:00) Tokyo","Australia\/Adelaide":"(GMT+09:30) Adelaide","Australia\/Darwin":"(GMT+09:30) Darwin","Asia\/Yakutsk":"(GMT+10:00) Yakutsk","Australia\/Brisbane":"(GMT+10:00) Brisbane","Australia\/Canberra":"(GMT+10:00) Canberra","Pacific\/Guam":"(GMT+10:00) Guam","Australia\/Hobart":"(GMT+10:00) Hobart","Australia\/Melbourne":"(GMT+10:00) Melbourne","Pacific\/Port_Moresby":"(GMT+10:00) Port Moresby","Australia\/Sydney":"(GMT+10:00) Sydney","Asia\/Vladivostok":"(GMT+11:00) Vladivostok","Asia\/Magadan":"(GMT+12:00) Magadan","Pacific\/Auckland":"(GMT+12:00) Auckland","Pacific\/Fiji":"(GMT+12:00) Fiji"}', true);
    }
}
