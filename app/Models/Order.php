<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Pagination;
use App\Helpers\General;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orders';

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
    protected $dateFormat = 'U';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'transaction_id',
        'created_at',
        'updated_at'
    ];



    public function list($postData)
    {
        $query = DB::table('orders')
            ->select(
                'orders.*',
                'user.first_name',
                'user.last_name',
                'orders.transaction_id',
                'product.title as title'
            )
            ->leftJoin('transaction', 'orders.transaction_id', '=', 'transaction.id')
            ->leftJoin('user', 'transaction.user_id', '=', 'user.id')
            ->leftJoin('product', 'orders.product_id', '=', 'product.id');

        // Apply search filter if search text is provided and is more than 2 characters long
         $searchText = isset($postData['search']['value']) ? $postData['search']['value'] : '';
        if (strlen($searchText) > 2) {
            $searchText = '%' . $searchText . '%';
            $query->where(function ($query) use ($searchText) {
                $query->whereRaw("concat(user.first_name,' ' ,user.last_name) like ?", $searchText)
                    ->orWhere("email", 'like', $searchText);
            });
        }
        // Get paginated result
        $pagination = new Pagination();
        $general = new General();
        $result = $pagination->getDataTable($query, $postData);
        $sessionUser = auth()->user();

        // Add action buttons for each row
        foreach ($result['data'] as $key => $row) {
            $result['data'][$key]->created_at = $general->dateFormat($row->created_at);
            $result['data'][$key]->user_name = $row->first_name . ' ' . $row->last_name;
            $result['data'][$key]->product_title = $row->title;
            $result['data'][$key]->action = '';

            if ($sessionUser->role == 0) {
                $result['data'][$key]->action = '<div class="act-btns">
                <a href="admin/order/view?id=' . $row->id . '" class="text-body pjax" title="View">
                    <i class="bx bxs-show icon-base "></i>
                </a>&nbsp;
            </div>';
            } else {
                if ($sessionUser->hasPermission('admin/order/view')) {
                    $result['data'][$key]->action .= '
                    <a href="admin/order/view?id=' . $row->id . '" class="text-body pjax" title="View">
                        <i class="bx bxs-show icon-base"></i>
                    </a>&nbsp;';
                }
            }
        }

        return $result;
    }


    public function store(array $postData)
    {
        $general = new General();
        $id = $postData['id'] ?? null;

        $order = $id ? Order::find($id) : new Order();

        $order->data = $postData['data'];
        $order->status = $postData['status'];
        $order->save();

        return [
            'status' => 1,
            'message' => isset($postData['id']) ? 'Order updated successfully.' : 'Order created successfully.',
            'next' => 'load',
            'url' => 'admin/order'
        ];
    }
}
