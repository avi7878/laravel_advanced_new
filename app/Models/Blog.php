<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Pagination;
use App\Helpers\General;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Blog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blog';

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
    public $timestamps = true;

    /**
     * The format for the date columns.
     *
     * @var string
     */
    // protected $dateFormat = 'U';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'blog_category_id',
        'slug',
        'title',
        'image',
        'tags',
        'description',
        'status',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the status badge for the blog post.
     *
     * @param int $status
     * @return string
     */
    public function getStatus($status)
    {
        // Return a status badge based on the blog status
        if ($status == 0) {
            return '<span class="badge badge-warning">Draft</span>';
        }

        return '<span class="badge badge-success">Published</span>';
    }

    /**
     * Get the list of blogs for the admin panel with pagination and filtering.
     *
     * @param array $postData
     * @return array
     */
    public function listAdmin($postData)
    {
        // Build the query to fetch blogs with category name
        $query = DB::table($this->table)
            ->select('blog.*', 'blog_category.category as blog_category_name')
            ->leftJoin('blog_category', 'blog.blog_category_id', '=', 'blog_category.id');

        // Handle search filter if provided
        $searchText = isset($postData['search']['value']) ? $postData['search']['value'] : '';

        if (strlen($searchText) > 2) {
            $searchText = '%' . $searchText . '%';
            $query->where(function ($query) use ($searchText) {
                $query->where("blog.title", 'like', $searchText)
                    ->orWhere("blog_category.category", 'like', $searchText)
                    ->orWhere(DB::raw("FROM_UNIXTIME(blog.created_at, '%d-%m-%Y')"), 'LIKE', $searchText);
            });
        }

        // Fetch paginated data using the Pagination helper
        $result = (new Pagination())->getDataTable($query, $postData);
        $general = new General();
        // Get the currently authenticated user
        $sessionUser = auth()->user();
        // Modify the result with additional information like formatted date and action buttons
        foreach ($result['data'] as $key => $row) {
             $result['data'][$key]->created_at = $general->dateFormat($row->created_at);
            $result['data'][$key]->title = $row->title;
            $result['data'][$key]->blog_category_id = $row->blog_category_name ?: 'Category Not Found';
            $result['data'][$key]->image = '<img width="30px" height="30px" src="' . (new General())->getFileUrl($row->image, 'blog') . '">';

            // Handle action buttons (view, update, delete)
            $result['data'][$key]->action = '';

            if ($sessionUser->role == 0) {
                $result['data'][$key]->action = '<div class="act-btns">
                    <a href="admin/blog/view?id=' . $row->id . '" class="text-body router pjax" title="View">
                        <i class="bx bxs-show icon-base "></i>
                    </a>&nbsp;
                    <a href="admin/blog/update?id=' . $row->id . '" class="text-body router pjax" title="Update">
                        <i class="bx bxs-edit icon-base"></i>
                    </a>
                    <button style="border:none; background:none;" onclick="app.confirmAction(this);" data-action="admin/blog/delete" data-id="' . $row->id . '" class="text-body pjax" title="Delete">
                        <i class="bx bxs-trash icon-base"></i>
                    </button>
                </div>';
            } else {
                // Check permissions for non-admin roles
                if ($sessionUser->hasPermission('admin/blog/view')) {
                    $result['data'][$key]->action .= '
                        <a href="admin/blog/view?id=' . $row->id . '" class="text-body router pjax" title="View">
                            <i class="bx bxs-show icon-base"></i>
                        </a>&nbsp;';
                }
                if ($sessionUser->hasPermission('admin/blog/update')) {
                    $result['data'][$key]->action .= '
                        <a href="admin/blog/update?id=' . $row->id . '" class="text-body router pjax" title="Update">
                            <i class="bx bxs-edit icon-base"></i>
                        </a>';
                }
                if ($sessionUser->hasPermission('admin/blog/delete')) {
                    $result['data'][$key]->action .= '
                        <button style="border:none; background:none;" onclick="app.confirmAction(this);" data-action="admin/blog/delete" data-id="' . $row->id . '" class="text-body pjax " title="Delete">
                            <i class="bx bxs-trash icon-base"></i>
                        </button>';
                }
            }
        }

        return $result;
    }


    /**
     * Get the list of blogs with filtering options for the frontend.
     *
     * @param array $postData
     * @return array
     */
    public function list($postData)
    {
        // Initialize variables
        $searchText = isset($postData['search']) ? $postData['search'] : '';
        $postData['limit'] = 2;

        // Build query to fetch blog posts
        $query = DB::table($this->table)->select('*');

        // Apply search filter if search text is provided
        if (strlen($searchText) > 2) {
            $query->where(function ($query) use ($searchText) {
                $query->where('title', 'like', '%' . $searchText . '%');
            });
        }

        // Handle the filter for category and tag
        $filter = [];
        if (isset($postData['filter'])) {

            $filter = is_string($postData['filter']) ? json_decode(urldecode($postData['filter']), true) : $postData['filter'];
            if (json_last_error() !== JSON_ERROR_NONE) {
                $filter = [];
            }
        }

        if ($filter) {
            // Apply category filter if present
            $query->when(isset($filter['category']), function ($query) use ($filter) {
                $query->where('blog_category_id', $filter['category']);
            });

            // Apply tag filter if present
            $query->when(isset($filter['tag']), function ($query) use ($filter) {
                $tags = explode(',', $filter['tag']);
                $query->where(function ($query) use ($tags) {
                    foreach ($tags as $tag) {
                        $query->where('tags', 'like', '%' . trim($tag) . '%');
                    }
                });
            });
        }

        // Return paginated results
        return (new Pagination())->getData($query, $postData);
    }

    /**
     * Store or update a blog post along with its associated tags, image, and gallery.
     *
     * @param array $postData The data to store or update the blog post, including title, description, category, image, gallery, etc.
     * 
     * @return array The status of the operation, message, and redirection URL.
     */
    public function store(array $postData)
    {
        // Initialize necessary objects
        $general = new General();
        $blog = Blog::find($postData['id'] ?? null) ?? new Blog();

        // Validation rules for creating or updating a blog post
        $rules = [
            'title' => 'required',
            'description' => 'required',
            'category' => 'required',
            'gallery.*' => 'mimes:jpeg,jpg,png,gif,webp|max:10000',
        ];
        // If no image is provided, only validate the existing fields
        if (empty($postData['id']) || request()->hasFile('image')) {
            $rules['image'] = 'required|mimes:jpeg,jpg,png,gif,webp|max:10000';
        }

        // Validate the data
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {
            return [
                'status' => 0,
                'message' => (new General())->getError($validator),
            ];
        }
        // Handle tags (extract values)
        // $tags = isset($postData['tags']) ? json_decode($postData['tags'], true) : [];
        $tags = is_array($tags = json_decode($postData['tags'] ?? '[]', true)) ? $tags : [];
        $newTags = array_column($tags, 'value');
        $existingTags = explode(',', (new Setting())->getOne('blog_tag'));

        // Slugify and fill basic properties for the blog post
        $slug = $general->slugify($postData['title']);
        $blog->fill([
            'title' => $postData['title'],
            'slug' => isset($postData['id']) ? "{$slug}-{$postData['id']}" : $slug,
            'description' => $postData['description'],
            'tags' => implode(',', $newTags),
            'blog_category_id' => $postData['category'],
        ]);
        // Handle image upload (only if a new image is uploaded)
        // dd($postData);
        if (isset($postData['image'])) {
            $uploadedImage = $general->uploadFile($postData['image'], 'blog');
            // dd($uploadedImage);
            if ($uploadedImage) {
                // Delete old image if exists
                if ($blog->image) {
                    $general->deleteFile($blog->image, 'blog');
                }
                $blog->image = $uploadedImage['file_name'];
            }
        }

        // Handle gallery images
        $newFiles = request()->file('gallery') ?? [];
        $oldFiles = explode(',', $blog->gallery);
        $fileOld = $postData['file_old'] ?? [];
        $newFileArray = [];

        foreach ($newFiles as $image) {
            $uploadedImage = $general->uploadFile($image, 'blog');
            if ($uploadedImage) {
                $newFileArray[] = $uploadedImage['file_name'];
            }
        }

        // Remove old gallery files if needed
        if ($oldFiles) {
            if ($fileOld) {
                // Merge old files with new ones and delete removed images
                $newFileArray = array_merge($fileOld, $newFileArray);
                $removedImages = array_diff($oldFiles, $fileOld);
                foreach ($removedImages as $img) {
                    $general->deleteFile($img, 'blog');
                }
            } else {
                // If no old files are passed, delete all existing gallery files
                foreach ($oldFiles as $img) {
                    $general->deleteFile($img, 'blog');
                }
            }
        }

        // Update gallery field
        $blog->gallery = implode(',', $newFileArray); 
        $blog->updated_at = time();
        $blog->save();

        // Update tags in settings
        $updatedTags = array_unique(array_merge($existingTags, $newTags));
        (new Setting())->setOne('blog_tag', implode(',', $updatedTags));

        return [
            'status' => 1,
            'message' => isset($postData['id']) ? 'Blog updated successfully.' : 'Blog created successfully.',
            'next' => 'load',
            'url' => 'admin/blog/index',
        ];
    }
}
