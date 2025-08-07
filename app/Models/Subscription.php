<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Pagination;
use App\Helpers\General;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Subscription extends Model
{
      /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subscription';

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
        'stripe_subscription_id',
        'status',
        'created_at',
        'updated_at'
    ];
}