<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Helpers\Pagination;
use Illuminate\Support\Facades\Validator;

/**
 * Class BlogCategory
 *
 * @package App\Models
 * @property int $id
 * @property string $category
 * @property bool $status
 */
class BlogCategory extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blog_category';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category',
        'status'
    ];

    /**
     * List the blog categories for admin with pagination and action buttons.
     *
     * @param array $postData The data for the list, including search and pagination parameters.
     * @return array The paginated list with action buttons and status badges.
     */
    public function listAdmin(array $postData)
    {
        $category = new BlogCategory();
        $query = DB::table('blog_category')->select('*');
        $searchText = isset($postData['search']['value']) ? $postData['search']['value'] : '';

        // Apply search filter if search text length is greater than 2
        if (strlen($searchText) > 2) {
            $searchText = '%' . $searchText . '%';
            $query->where(function ($query) use ($searchText) {
                $query->orWhere("category", 'like', $searchText)
            ->orWhere("status",'like',$searchText);
        });
    }

        // Get the paginated result
        $result = (new Pagination())->getDataTable($query, $postData);
        $sessionUser = auth()->user();

        // Loop through the result data and add action buttons
        foreach ($result['data'] as $key => $row) {
            $result['data'][$key]->action = $this->getActionButtons($row, $sessionUser);
            $result['data'][$key]->status = $category->getStatusBadge($row->status);
        }

        return $result;
    }

    /**
     * Get the action buttons for each category based on user permissions.
     *
     * @param object $row The current category row data.
     * @param object $sessionUser The currently authenticated user.
     * @return string The HTML string for action buttons.
     */
    protected function getActionButtons($row, $sessionUser)
    {
        $actionButtons = '';

        if ($sessionUser->type == 0 && $sessionUser->role == 1) {
            // If the user is admin, show both Update and Delete buttons
            $actionButtons = '<div class="act-btns">
                <a href="admin/blog_category/update?id=' . $row->id . '" class="text-body router pjax" title="Update"><i class="bx bxs-edit icon-base"></i></a>
                <button style="border:none; background:none;" onclick="app.confirmAction(this);" data-action="admin/blog_category/delete" data-id="' . $row->id . '" class="text-body pjax" title="Delete"><i class="bx bxs-trash icon-base"></i></button>
            </div>';
        } else {
            // dd($sessionUser->hasPermission('admin/blog_category/update'));
            // Non-admin users see action buttons based on permissions
            if ($sessionUser->hasPermission('admin/blog_category/update')) {
                $actionButtons .= '<a href="admin/blog_category/update?id=' . $row->id . '" class="text-body act-btns router pjax" title="Update"><i class="bx bxs-edit icon-base"></i></a>';
            }
            if ($sessionUser->hasPermission('admin/blog_category/delete')) {
                $actionButtons .= '<button style="border:none; background:none;" onclick="app.confirmAction(this);" data-action="admin/blog_category/delete" data-id="' . $row->id . '" class="text-body act-btns pjax" title="Delete"><i class="bx bxs-trash icon-base"></i></button>';
            }
        }

        return $actionButtons;
    }

    /**
     * Store or update a blog category.
     *
     * @param array $postData The data to store or update.
     * @return array The status and message indicating success or failure.
     */
    public function store(array $postData)
    {
        $validator = Validator::make($postData, [
            'category' => 'required',
            'status' => 'nullable|boolean',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return [
                'status' => 0,
                'message' => $validator->errors()->first(),
            ];
        }

        $id = $postData['id'] ?? null;
        $model = $id ? self::find($id) : new self();

        // Return error if the category is not found during update
        if ($id && !$model) {
            return [
                'status' => 0,
                'message' => 'Blog Category not found.',
            ];
        }

        // Assign values to model
        $model->category = $postData['category'];
        $model->status = $postData['status'] ?? 1;
        $model->save();

        // Return success message
        return [
            'status' => 1,
            'message' => $id ? 'Blog Category Updated Successfully' : 'Blog Category Created Successfully',
            'next' => 'load',
            'url' => 'admin/blog_category/index',
        ];
    }

    /**
     * Get the status badge HTML for the category.
     *
     * @param bool $status The status of the category (1 for active, 0 for inactive).
     * @return string The HTML for the status badge.
     */
    public function getStatusBadge($status)
    {
        return $status == 1 
            ? '<span class="badge rounded-pill bg-label-success">Active</span>' 
            : '<span class="badge rounded-pill bg-label-danger">Inactive</span>';
    }
}
