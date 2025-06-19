<?php

namespace App\Models;

use App\Helpers\General;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Pagination;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use App\Services\PermissionService;
use App\Services\AuthService;

class User extends Authenticatable
{

    protected $table = 'user';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $dateFormat = 'U';
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'password_reset_token',
        'email_verified',
        'role',
        'status',
        'country',
        'timezone',
        'registered_ip',
        'image',
        'status',
        'created_at',
        'updated_at',
        'permission'
    ];

    protected $hidden = [
        'password',
        'password_reset_token',
        'email_verified',
        'otp',
    ];


    public $userRole = [4];
    public $superAdminRole = 1;
    public $adminRole = [1,2,3];

    public function isUser()
    {
        return in_array($this->role, $this->userRole) ? true : false;
    }

    public function isAdmin()
    {
        return in_array($this->role, $this->adminRole) ? true : false;
    }

    public function isSuperAdmin()
    {
        return $this->role == $this->superAdminRole ? true : false;
    }
    public function getPermissionListData(): array
    {
        return (new PermissionService())->getPermissionListData();
    }

    public function hasPermission($permission = '')
    {
        if ($this->isSuperAdmin()) {
            return true;
        }
        return (new PermissionService())->hasPermission($permission, $this->permission);
    }

    public function getStatusBadge($status)
    {
        return $status == 1 ? '<span class="badge rounded-pill bg-label-success">Active</span>' : '<span class="badge rounded-pill bg-label-danger">Inactive</span>';
    }

    public function listAdmin($postData)
    {
        $query = DB::table('user')->select('*')->where('role', 1);
        $searchText = isset($postData['search']['value']) ? $postData['search']['value'] : '';
        if (strlen($searchText) > 2) {
            $searchText = '%' . $searchText . '%';
            $query->where(function ($query) use ($searchText) {
                $query->whereRaw("concat(user.first_name,' ' ,user.last_name) like ?", $searchText)
                    ->orWhere("email", 'like', $searchText);
            });
        }
        /**/
        $userObj = new User();
        $result = (new Pagination())->getDataTable($query, $postData);
        // Get current authenticated user for permission checks
        $sessionUser = auth()->user();
        // Process each user record
        foreach ($result['data'] as $key => $row) {
            // Get profile image URL if available
            $imageUrl = (new General())->getFileUrl($row->image, 'profile');
            if ($row->image) {
                $result['data'][$key]->image = '<a href="upload/profile/' . $row->image . '" data-toggle="lightbox" data-title="Image" class = "noroute pjax" target = "_blank">
                <img style="width:30px;height:30px" src="' . $imageUrl . '" class="h-auto rounded-circle" alt="blog image"></a>';
            }
            // Concatenate first and last names
            $result['data'][$key]->first_name = $row->first_name . ' ' . $row->last_name;
            $result['data'][$key]->permission = $row->permission;
            // Set user status badge
            $result['data'][$key]->status = $userObj->getStatusBadge($row->status);
            // Format the creation date based on the application's date format setting
            $result['data'][$key]->created_at = date(config('setting.date_format'), $row->created_at);
            
            // Assign action buttons based on the role and permissions
            if (auth()->user()->role == 0) {
                $result['data'][$key]->action = '<div class="act-btns">
            <a href="admin/admin/view?id=' . $row->id . '" class="text-body pjax" title="View"><i class="bx bxs-show icon-base"></i></a>&nbsp;
            <a href="admin/admin/update?id=' . $row->id . '" class="text-body pjax" title="Update"><i class="bx bxs-edit icon-base"></i></a>
            <button style=" border:none; background:none;" onclick="app.confirmAction(this);" data-action="admin/admin/delete" data-id="' . $row->id . '" class="text-body pjax" title="Delete"><i class="bx bxs-trash icon-base"></i></button></div>';
            } else {
                $result['data'][$key]->action = '';
                if ($sessionUser->hasPermission('admin/admin/view')) {
                    $result['data'][$key]->action .= '
                    <a href="admin/admin/view?id=' . $row->id . '" class="text-body  pjax" title="View"><i class="bx bxs-show icon-base"></i></a>&nbsp;';
                }
                if ($sessionUser->hasPermission('admin/admin/update')) {
                    $result['data'][$key]->action .= '
                    <a href="admin/admin/update?id=' . $row->id . '" class="text-body pjax" title="Update"><i class="bx bxs-edit icon-base"></i></a>';
                }
                if ($sessionUser->hasPermission('admin/admin/delete')) {
                    $result['data'][$key]->action .= '
                    <button style=" border:none; background:none;" onclick="app.confirmAction(this);" data-action="admin/admin/delete" data-id="' . $row->id . '" class="text-body" title="Delete"><i class="bx bxs-trash icon-base"></i></button>';
                }
                 if ($sessionUser->hasPermission('admin/user/autologin')) {
                $result['data'][$key]->action .= '
                    <button style="border:none; background:none;" onclick="app.confirmAction(this);" data-action="' . $autologinUrl . '" class="text-body" title="Autologin">
                        <i class="bx bx-log-out icon-base"></i>
                    </button>';
            }
            }
        }
        return $result;
    }

    public function list($postData)
    {
        $query = DB::table('user')->select('*')->where('role', 2);
        /**/
        $searchText = isset($postData['search']['value']) ? $postData['search']['value'] : '';
        if (strlen($searchText) > 2) {
            $searchText = '%' . $searchText . '%';
            $query->where(function ($query) use ($searchText) {
                $query->whereRaw("concat(first_name,' ' ,last_name) like ?", $searchText)->orWhere("email", 'like', $searchText)->orWhere(DB::raw("FROM_UNIXTIME(created_at, '%d-%m-%Y')"), 'LIKE', '%' . $searchText . '%')->orWhere(function ($query) use ($searchText) {
                    if (stripos($searchText, '%Act%') !== false) {
                        $query->where('status', '=', 1);
                    } elseif (stripos($searchText, '%Inac%') !== false) {
                        $query->where('status', '=', 0);
                    }
                });
            });
        }
        /**/
        $userObj = new User();
        $result = (new Pagination())->getDataTable($query, $postData);
        $sessionUser = auth()->user();
        foreach ($result['data'] as $key => $row) {
            $imageUrl = (new General())->getFileUrl($row->image, 'profile');
            if ($row->image) {
                $result['data'][$key]->image = '<a href="upload/profile/' . $row->image . '" data-toggle="lightbox" data-title="Image" class = "noroute pjax" target = "_blank">
                <img style="width:30px;height:30px" src="' . $imageUrl . '" class="h-auto rounded-circle" alt="blog image"></a>';
            }
            $result['data'][$key]->first_name = $row->first_name . ' ' . $row->last_name;
            $result['data'][$key]->email = $row->email;
            $result['data'][$key]->phone = $row->phone;
            $result['data'][$key]->status = $userObj->getStatusBadge($row->status);
            $result['data'][$key]->created_at = date(config('setting.date_format'), $row->created_at);


            $result['data'][$key]->updated_at = date(config('setting.date_format'), $row->updated_at);
                    $autologinUrl = url('admin/user/autologin?id=' . $row->id);

            
            if (auth()->user()->role == 0) {
                $result['data'][$key]->action = '
            <div class="act-btns"><a href="admin/user/view?id=' . $row->id . '" class="text-body pjax" title="View"><i class="bx bxs-show icon-base"></i></a>;
            <a href="admin/user/update?id=' . $row->id . '" class="text-body pjax" title="Update"><i class="bx bxs-edit icon-base"></i></a>
            <button style=" border:none; background:none;" onclick="app.confirmAction(this);" data-action="admin/user/delete" data-id="' . $row->id . '" class="text-body" title="Delete"><i class="bx bxs-trash icon-base"></i></button></div>';
            } else {
                $result['data'][$key]->action = '';
                if ($sessionUser->hasPermission('admin/user/view')) {
                    $result['data'][$key]->action .= '
                    <a href="admin/user/view?id=' . $row->id . '" class="text-body pjax" title="View"><i class="bx bxs-show icon-base"></i></a>&nbsp;</div>';
                }
                if ($sessionUser->hasPermission('admin/user/update')) {
                    $result['data'][$key]->action .= '
                    <a href="admin/user/update?id=' . $row->id . '" class="text-body pjax" title="Update"><i class="bx bxs-edit icon-base"></i></a>';
                }
                if ($sessionUser->hasPermission('admin/user/delete')) {
                    $result['data'][$key]->action .= '
                    <button style=" border:none; background:none;" onclick="app.confirmAction(this);" data-action="admin/user/delete" data-id="' . $row->id . '" class="text-body" title="Delete"><i class="bx bxs-trash icon-base"></i></button>';
                }
                 if ($sessionUser->hasPermission('admin/user/autologin')) {
                $result['data'][$key]->action .= '
                    <button style="border:none; background:none;" onclick="app.confirmAction(this);" data-action="' . $autologinUrl . '" class="text-body" title="Autologin">
                        <i class="bx bx-log-out icon-base"></i>
                    </button>';
            }
                
            }
        }
        return $result;
    }

    public function storeAdmin(array $postData): array
    {

        $general = new General();
        $id = $postData['id'] ?? null;
        $existingPassword = $postData['pass'] ?? null;

        // Define validation rules
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:user,email,' . $id,
            'phone' => 'required|digits:10|numeric',
            'status' => 'required|boolean',
            'permission' => 'required|array',
            'role' => 'required|string|max:255',
        ];

        // Add specific rules for new admin creation
        if (!$id) {
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
        } else {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        // Validate the data
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {
            return [
                'status' => 0,
                'message' => $validator->errors()->first()
            ];
        }

        // Find or create a new user instance
        $model = $id ? User::find($id) : new User();

        // Handle profile image upload
        if (isset($postData['image']) && $postData['image']->isValid()) {
            $uploadResult = $general->uploadFile($postData['image'], 'profile');
            if (!$uploadResult['status']) {
                return $uploadResult;
            }
            $image = $uploadResult['file_name'];
            if ($image) {
                if ($model->image) {
                    $general->deleteFile($model->image, 'profile');
                }
                $model->image = $image;
            }
        }
        // Set model attributes
        $model->first_name = $postData['first_name'];
        $model->last_name = $postData['last_name'];
        $model->email = $postData['email'];
        $model->phone = $postData['phone'];
        $model->status = (bool) $postData['status'];
        $model->permission = implode(',', $postData['permission']);
        $model->role = $postData['role'];
        $model->registered_ip = $general->getClientIp();
        // Encrypt and set password
        $service = new AuthService();
        $model->password = !empty($postData['password'])
            ? $service->encryptPassword($postData['password'])
            : $existingPassword;
        // Save model
        $model->save();
        // Set response message
        $message = $id ? 'Admin updated successfully.' : 'Admin created successfully.';
        return [
            'status' => 1,
            'message' => $message,
            'next' => 'load',
            'url' => 'admin/admin'
        ];
    }
    /**
     * Store or update a user in the database.
     *
     * This method handles both creating a new user and updating an existing user. It validates the input data,
     * handles image uploads, and encrypts the password if provided. The user’s IP address and country information
     * are also recorded. After saving the user, a response message is returned.
     *
     * @param array $postData The input data for the user (can be for new user creation or update).
     * @return array The response array containing status, message, and other related data.
     */
    public function store(array $postData): array
    {
        $general = new General();
        $id = $postData['id'] ?? null;
        $pass = $postData['pass'] ?? null;
        // Define validation rules
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|digits:10|numeric',
            'status' => 'required|boolean',
        ];
        // Additional rule for new users
        if (!$id) {
            $rules['email'] .= '|unique:user';
            $rules['image'] = 'image|mimes:jpeg,png,jpg,gif|max:2048';
        } else {
            $rules['image'] = 'image|mimes:jpeg,png,jpg,gif|max:2048';
        }
        // Validate input data
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {
            return [
                'status' => 0,
                'message' => $validator->errors()->first(),
            ];
        }
        // Retrieve or create user model
        $model = $id ? User::find($id) : new User();
        // Handle image upload if provided
        if (isset($postData['image']) && $postData['image']->isValid()) {
            $uploadResult = $general->uploadFile($postData['image'], 'profile');
            if (!$uploadResult['status']) {
                return $uploadResult;
            }
            $image = $uploadResult['file_name'];
            if ($image) {
                if ($model->image) {
                    $general->deleteFile($model->image, 'profile');
                }
                $model->image = $image;
            }
        }
        // Assign IP and other properties
        $model->first_name = $postData['first_name'];
        $model->last_name = $postData['last_name'];
        $model->email = $postData['email'];
        $model->phone = $postData['phone'];
        $model->country = $postData['country'] ?? null;
        $model->status = $postData['status'];
        $model->role = $postData['role'];
        $model->registered_ip = $general->getClientIp();
        // Encrypt password if provided
        if (!empty($postData['password'])) {
            $model->password = (new AuthService())->encryptPassword($postData['password']);
        } else {
            $model->password = $pass;
        }
        // Save the model
        $model->save();
        // Return response message
        return [
            'status' => 1,
            'message' => $id ? 'User updated successfully.' : 'User created successfully.',
            'next' => 'load',
            'url' => 'admin/users',
        ];
    }
}
