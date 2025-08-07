<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Pagination;
use App\Helpers\General;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transaction';

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
        'stripe_transaction_id',
        'data',
        'status',
        'created_at',
        'updated_at'
    ];


    public function list($postData)
    {
        $query = DB::table('transaction')
            ->select('transaction.*', 'user.first_name', 'user.last_name')
            ->leftJoin('user', 'transaction.user_id', '=', 'user.id');

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
        $general = new General ();
        $result = $pagination->getDataTable($query, $postData);
        $sessionUser = auth()->user();

        // Add action buttons for each row
        foreach ($result['data'] as $key => $row) {
            $result['data'][$key]->created_at = $general->dateFormat($row->created_at);
            $result['data'][$key]->user_name = $row->first_name . ' ' . $row->last_name;
            $result['data'][$key]->action = '';

            if ($sessionUser->role == 0) {
                $result['data'][$key]->action = '<div class="act-btns">
                <a href="admin/transaction/view?id=' . $row->id . '" class="text-body pjax" title="View">
                    <i class="bx bxs-show icon-base "></i>
                </a>&nbsp;
            </div>';
            } else {
                if ($sessionUser->hasPermission('admin/transaction/view')) {
                    $result['data'][$key]->action .= '
                    <a href="admin/transaction/view?id=' . $row->id . '" class="text-body pjax" title="View">
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

        $transaction = $id ? Transaction::find($id) : new Transaction();

        $transaction->data = $postData['data'];
        $transaction->status = $postData['status'];
        $transaction->save();

        return [
            'status' => 1,
            'message' => isset($postData['id']) ? 'Transaction updated successfully.' : 'Transaction created successfully.',
            'next' => 'load',
            'url' => 'admin/transaction'
        ];
    }
}
