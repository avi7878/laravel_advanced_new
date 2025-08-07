<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Pagination;
use App\Helpers\General;
use Stripe\Stripe;
use Stripe\Product;
use Stripe\Price;
use Illuminate\Support\Facades\DB;

class Plan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'plan';

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
        'stripe_price_id',
        'title',
        'description',
        'amount',
        'duration',
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
    $plan = new Plan();

    // Get paginated result
    $pagination = new Pagination();
    $general = new general();
    $result = $pagination->getDataTable($query, $postData);
     $sessionUser = auth()->user();

    // Add action buttons for each row
    foreach ($result['data'] as $key => $row) {
        $result['data'][$key]->status = $plan->getStatusBadge($row->status);
        $result['data'][$key]->created_at = $general->dateFormat($row->created_at);
        $result['data'][$key]->action = '';
            if ($sessionUser->role == 0) {
                $result['data'][$key]->action = '<div class="act-btns col-sm-2" > 
                    <a href="admin/plan/view?id=' . $row->id . '" class="text-body router pjax" title="View">
                        <i class="bx bxs-show icon-base "></i>
                    </a>&nbsp;
                    <a href="admin/plan/update?id=' . $row->id . '" class="text-body pjax" title="Update">
                        <i class="bx bxs-edit icon-base"></i>
                    </a>
                    <button style="border:none; background:none;" onclick="app.confirmAction(this);" data-action="admin/plan/delete" data-id="' . $row->id . '" class="text-body pjax" title="Delete">
                        <i class="bx bxs-trash icon-base"></i>
                    </button>
                </div>';
            } else {
                // Check permissions for non-admin roles
                if ($sessionUser->hasPermission('admin/plan/view')) {
                    $result['data'][$key]->action .= '
                        <a href="admin/plan/view?id=' . $row->id . '" class="text-body pjax" title="View">
                            <i class="bx bxs-show icon-base"></i>
                        </a>&nbsp;';
                }
                if ($sessionUser->hasPermission('admin/plan/update')) {
                    $result['data'][$key]->action .= '
                        <a href="admin/plan/update?id=' . $row->id . '" class="text-body pjax" title="Update">
                            <i class="bx bxs-edit icon-base"></i>
                        </a>';
                }
                if ($sessionUser->hasPermission('admin/plan/delete')) {
                    $result['data'][$key]->action .= ' 
                        <button style="border:none; background:none;" onclick="app.confirmAction(this);" data-action="admin/plan/delete" data-id="' . $row->id . '" class="text-body pjax" title="Delete">
                            <i class="bx bxs-trash icon-base"></i>
                        </button>';
                }
            }
     }        
    return $result;
}

    public function store(array $postData)
    {
        $general = new General();
        $id =$postData['id'] ?? null;
       
        $plan = $id ? Plan::find($id) :new Plan();
      
       $plan->title = $postData['title'];
       $plan ->description = $postData['description'];
       $plan-> amount = $postData['amount'];
       $plan ->duration =$postData['duration'];
       $plan ->status = $postData['status'];

        $stripeSecretKey = env('STRIPE_SECRET_KEY');
        Stripe::setApiKey($stripeSecretKey);
        
        if(!$id || !$plan->stripe_price_id){
            
            $validDuration = [
                'day' => 'day',
                'month' => 'month',
                'year' => 'year',
            ];
            
            if (!array_key_exists($postData['duration'], $validDuration)) {
                 return ['status'=>0, 'message' => 'Invalid duration provided. Valid options are: day, month, or year.'];
            }    
            
            $interval = $validDuration[$postData['duration']];
            if(!$plan->stripe_price_id){
                $product = Product::create([
                    'name' => $postData['title'],
                    'description'=> $postData['description'],
                ]);
                    
                $price = Price::create([
                    'unit_amount' => $postData['amount'] * 100,
                    'currency' => 'usd',
                    'recurring' => ['interval' => $interval],
                    'product' => $product->id,
                ]);
                $plan->stripe_price_id = $price->id;
            }
        }
        
        $plan->save();

        return [
            'status' => 1,
            'message' => isset($postData['id']) ? 'Plan updated successfully.' : 'Plan created successfully.',
            'next' => 'load',
            'url' => 'admin/plan'
        ];
    }
    
    public function getStatusBadge($status)
    {
        return $status == 1 
            ? '<span class="badge rounded-pill bg-label-success">Active</span>' 
            : '<span class="badge rounded-pill bg-label-danger">Inactive</span>';
    }

    

}
