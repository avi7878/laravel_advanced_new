<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class IpInfoController extends Controller
{
    public function getIpData($ip = '')
    {
        if ($ip == '') {
            $ip = request()->ip();
        }
        $key = 'ip_info:' . $ip;
        $data = Cache::get($key);
        if ($data) {
            return $data;
        }
        $result = @file_get_contents('https://api.tribital.com/ipinfo/index.php?ip=' . $ip);
        return $result;
    }

    public function index(Request $request)
    {
        $ip = $request->query('ip', '');
        $ipData = null;

        if ($ip) {
            $rawData = $this->getIpData($ip);
            $ipData = json_decode($rawData, true);
        }
        return view('admin.ipinfo.index', compact('ipData', 'ip'));
    }
}
