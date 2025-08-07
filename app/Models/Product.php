<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Pagination;
use App\Helpers\General;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product';

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
        'title',
        'image',
        'description',
        'amount',
        'status',
        'created_at',
        'updated_at'
    ];

    public function list($postData)
    {

        $query = DB::table($this->table);
      
        // Apply search filter if search text is provided and is more than 2 characters long
        $searchText = $postData['search']['value'] ?? '';
        if (strlen($searchText) > 2) {
            $query->where('title', 'like', '%' . $searchText . '%');
        }
        $product = new Product();
        
        // Get paginated result
        $pagination = new Pagination();
        $general = new General();
        $result = $pagination->getDataTable($query, $postData);
        $sessionUser = auth()->user();

        // Add action buttons for each row
        foreach ($result['data'] as $key => $row) {
            $result['data'][$key]->status = $product->getStatusBadge($row->status);
            $result['data'][$key]->created_at = $general->dateFormat($row->created_at);
            $result['data'][$key]->image = '<img width="30px" height="30px" src="' . (new General())->getFileUrl($row->image, '2025/05') . '">';
            $result['data'][$key]->action = '';
             if ($sessionUser->role == 0) {
                $result['data'][$key]->action = '<div class="act-btns">
                    <a href="admin/product/view?id=' . $row->id . '" class="text-body pjax" title="View">
                        <i class="bx bxs-show icon-base "></i>
                    </a>&nbsp;
                    <a href="admin/product/update?id=' . $row->id . '" class="text-body pjax" title="Update">
                        <i class="bx bxs-edit icon-base"></i>
                    </a>
                    <button style="border:none; background:none;" onclick="app.confirmAction(this);" data-action="admin/product/delete" data-id="' . $row->id . '" class="text-body pjax" title="Delete">
                        <i class="bx bxs-trash icon-base"></i>
                    </button>
                </div>';
            } else {
                // Check permissions for non-admin roles
                if ($sessionUser->hasPermission('admin/product/view')) {
                    $result['data'][$key]->action .= '
                        <a href="admin/product/view?id=' . $row->id . '" class="text-body pjax" title="View">
                            <i class="bx bxs-show icon-base"></i>
                        </a>&nbsp;';
                }
                if ($sessionUser->hasPermission('admin/product/update')) {
                    $result['data'][$key]->action .= '
                        <a href="admin/product/update?id=' . $row->id . '" class="text-body pjax" title="Update">
                            <i class="bx bxs-edit icon-base"></i>
                        </a>';
                }
                if ($sessionUser->hasPermission('admin/product/delete')) {
                    $result['data'][$key]->action .= '
                        <button style="border:none; background:none;" onclick="app.confirmAction(this);" data-action="admin/product/delete" data-id="' . $row->id . '" class="text-body pjax" title="Delete">
                            <i class="bx bxs-trash icon-base"></i>
                        </button>';
                }
               $result['data'][$key]->action .= '
                 <a href="admin/product/export?id=' . $row->id . '" class="text-body btn">CSV</a>';
            }
        }

        return $result;
    }
    public function store(array $postData)
    {
        $general = new General();
        $product = Product::find($postData['id'] ?? null) ?? new product();
        
        if (isset($postData['image'])) {
            $uploadedImage = $general->uploadFile($postData['image'], 'product');
            if ($uploadedImage) {
                // Delete old image if exists
                if ($product->image) {
                    $general->deleteFile($product->image, 'product');
                }
                $product->image = $uploadedImage['file_name'];
            }
        }

        $product->fill([
            'title' => $postData['title'],
            'description' => $postData['description'],
            'amount' => $postData['amount'],
            'status' => $postData['status'],
        ]);

        if (isset($postData['image']) && $postData['image']->isValid()) {
            $uploadResult = $general->uploadFile($postData['image'], '2025/05');
            if (!$uploadResult['status']) {
                return $uploadResult;
            }
            $image = $uploadResult['file_name'];
            if ($image) {
                if ($product->image) {
                    $general->deleteFile($product->image, '2025/05');
                }
                $product->image = $image;
            }
        }
        // Update gallery field
            $product->updated_at = date('Y-m-d H:i:s');
        $product->save();

        return [
            'status' => 1,
            'message' => isset($postData['id']) ? 'Product updated successfully.' : 'Product created successfully.',
            'next' => 'load',
            'url' => 'admin/product'
        ];
    }
    public function getStatusBadge($status)
    {
        return $status == 1
            ? '<span class="badge rounded-pill bg-label-success">Active</span>'
            : '<span class="badge rounded-pill bg-label-danger">Inactive</span>';
    }
}
