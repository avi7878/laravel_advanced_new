<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Helpers\Pagination;
use App\Helpers\General;

class Device extends Model
{
    use HasUlids;


    /**
     * The table associated with the model.
     *
     * @var string
     */

    protected $table = 'device';

    /**
     * The primary key for the model.
     *
     * @var string
     */

    protected $primaryKey = 'id';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */

    protected $keyType = 'string';

    /**
     * Indicates if the IDs are incrementing.
     *
     * @var bool
     */

    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'user_id',
        'device_uid',
        'session_id',
        'type',
        'remember_token',
        'remember_expire_at',
        'client',
        'ip',
        'created_at',
        'updated_at'
    ];
    /**
     * Generates a random authentication token.
     *
     * @return string
     */


    public function generateAuthtoken()
    {
        return \Illuminate\Support\Str::random(64);
    }

    /**
     * Handles the login process for a user on a device.
     *
     * @param int $userId The ID of the user logging in.
     * @param int $remember Whether to remember the login session.
     * @return Device|false
     */

    public function login($userId, $remember = 1)
    {
        $deviceUid = @$_COOKIE[config("setting.app_uid") . '_token'];

        if (!$deviceUid) {
            return false;
        }
        $client = @$_SERVER['HTTP_USER_AGENT'];
        $time = time();
        $device = self::where(['device_uid' => $deviceUid])->first();
        if (!$device) {
            $device = new Device();
            $device->device_uid = $deviceUid;
        }
        $device->user_id = $userId;

        if ($remember) {
            $device->remember_token = $this->generateAuthtoken();
            $device->remember_expire_at = $time + (config('setting.device_expire_days') * 24 * 60 * 60);
            \Illuminate\Support\Facades\Cookie::queue(config('setting.app_uid') . '_user_token', $device->remember_token, config('setting.device_expire_days') * 24 * 60);
        } else {
            $device->remember_expire_at = 0;
        }

        $general = new General();
        $ip = $general->getClientIp();
        $device->ip = $ip;
        $device->client = $client;
        $device->session_id = session()->getId();
        $device->save();
        return $device;
    }

    /**
     * Logs out the current user from the device.
     *
     * @return bool
     */

    public function logout()
    {
        $deviceUid = @$_COOKIE[config("setting.app_uid") . '_token'];
        if (!$deviceUid) {
            return false;
        }
        $device = self::where(['device_uid' => $deviceUid])->first();
        if ($device) {
            $device->user_id = 0;
            $device->remember_token = '';
            $device->remember_expire_at = 0;
            $device->session_id = '';
            $device->save();
        }
    }

    /**
     * Forces a logout for a specific device by ID.
     *
     * @param string $id The ID of the device.
     * @return void
     */

    public function forceLogout($id)
    {
        $device = self::where('id', $id)->first();
        if ($device) {
            $sessionDriver = config('session.driver');
            if ($sessionDriver == 'file') {
                if (session()->getId() == $device->session_id) {
                    auth()->logout();
                } else {
                    try {
                        Session::getHandler()->destroy($device->session_id);
                        unlink(config('session.files') . '/' . $device->session_id);
                    } catch (\Exception $e) {
                    }
                }
            } elseif ($sessionDriver == 'database') {
                DB::table('sessions')->where('id', $device->session_id)->delete();
            }
            $device->user_id = 0;
            $device->remember_token = '';
            $device->remember_expire_at = 0;
            $device->session_id = '';
            $device->save();
        }
    }


    /**
     * Retrieves a paginated list of devices for a user.
     *
     * @param array $postData The data from the request.
     * @param int $userId The ID of the user.
     * @return array
     */


    public function list($postData, $userId)
    {
        $query = DB::table('device')->select(['device.*', 'sessions.last_activity'])
            ->where('device.user_id', $userId)
            ->where(function ($query) {
                $query->where('device.remember_expire_at', '>', time())
                    ->orWhere('sessions.last_activity', '>', time() - (config('session.lifetime') * 60));
            })
            ->leftJoin('sessions', 'sessions.id', 'device.session_id');

        $searchText = isset($postData['search']['value']) ? $postData['search']['value'] : '';
        if (strlen($searchText) > 2) {
            $searchText = '%' . $searchText . '%';
            $query->where(function ($query) use ($searchText) {
                $query->orwhere("client", 'like', $searchText);
                $query->orwhere("ip", 'like', $searchText);
                // $query->orwhere("location", 'like', $searchText);
                $query->orWhere(DB::raw("FROM_UNIXTIME(last_activity, '%d-%m-%Y')"), 'LIKE', '%' . $searchText . '%');
            });
        }
        $general = new General();
        $result = (new Pagination())->getDataTable($query, $postData);

        foreach ($result['data'] as $key => $row) {
            $result['data'][$key]->location = $general->getIpLocation($row->ip);
            $result['data'][$key]->client = (new General())->deviceName($row->client) . ' ' . ($row->device_uid == @$_COOKIE[config("setting.app_uid") . '_token'] ? ' (This Device)' : '');
            $result['data'][$key]->last_activity = date(config('setting.date_format'), @$row->last_activity ? $row->last_activity : $row->updated_at);
            $result['data'][$key]->action = '<button style="border: none; background: none;"  onclick="app.confirmAction(this);" data-action="account/device-logout?id=' . $row->id . '"  class="text-body pjax" title="logout">                    <svg class="dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width:22px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
</button>';
        }
        return $result;
    }

    /**
     * Retrieves a paginated list of devices for admin view.
     *
     * @param array $postData The data from the request.
     * @return array
     */

    public function listAdmin($postData)
    {

        $query = DB::table('device')->select(['device.updated_at as updated_at', 'device.id', 'device.ip', 'device.device_uid', 'device.client', 'device.id as deviceId', 'user.first_name', 'user.last_name', 'user.email'])
            ->join('user', 'user.id', '=', 'device.user_id')
            ->leftJoin('sessions', 'sessions.id', 'device.session_id')
            ->where(function ($query) {
                $query->where('device.remember_expire_at', '>', time())
                    ->orWhere('sessions.last_activity', '>', time() - (config('session.lifetime') * 60));
            });

        $searchText = isset($postData['search']['value']) ? $postData['search']['value'] : '';
        if (strlen($searchText) > 2) {
            $searchText = '%' . $searchText . '%';
            $query->where(function ($query) use ($searchText) {
                $query->where(function ($query) use ($searchText) {
                    $query->where(DB::raw('CONCAT(user.first_name, " ",user.last_name)  '), 'like', $searchText);
                    $query->orwhere("device.client", 'like', $searchText);
                    $query->orWhere(DB::raw("FROM_UNIXTIME(last_activity, '%d-%m-%Y')"), 'LIKE', '%' . $searchText . '%');
                });
            });
        }
        $result = (new Pagination())->getDataTable($query, $postData);
        $general = new General();
        $sessionUser = auth()->user();
        foreach ($result['data'] as $key => $row) {
            $result['data'][$key]->first_name = $row->first_name . ' ' . $row->last_name;
            $result['data'][$key]->location = $general->getIpLocation($row->ip);
            $result['data'][$key]->client = (new General())->deviceName($row->client) . ' ' . ($row->device_uid == @$_COOKIE[config("setting.app_uid") . '_token'] ? ' (This Device)' : '');
            $result['data'][$key]->last_activity = date(config('setting.date_time_format'), @$row->last_activity ? $row->last_activity : $row->updated_at);

            $result['data'][$key]->action = '';
            if ($sessionUser->hasPermission('admin/device/logout')) {
                $result['data'][$key]->action .= '
                <button style="border: none; background: none;" onclick="app.confirmAction(this);" data-action="admin/device/logout?id=' . $row->deviceId . '" class="text-body pjax" title="logout">                    <svg class="dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width:22px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
</button>';
            }
        }
        return $result;
    }

    /**
     * Returns the device type as a string.
     *
     * @param int $type The device type (0 for User, 1 for Admin).
     * @return string
     */

    public function deviceType($type)
    {
        if ($type == 0) {
            $type = '<span class="">User</span>';
        } else if ($type == 1) {
            $type = '<span class="">Admin</span>';
        }
        return $type;
    }
}
