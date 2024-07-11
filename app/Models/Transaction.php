<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator',
        'is_pr',
        'allocation_id',
        'date',
        'obr_no',
        'obr_timestamp',
        'obr_month',
        'obr_year',
        'creditor',
        'dv_no',
        'dv_timestamp',
        'dv_amount',
        'dv_year',
        'obr_unpaid',
        'ada_no',
        'ada_timestamp',
        'activity_title',
        'saa_title',
        'remarks',
        'from',
        'to',
        'received',
        'reference_no'
    ];

    /**
     * Function: Get the purchase details
     */
    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    /**
     * Function: Get the allocation details
     */
    public function allocation()
    {
        return $this->belongsTo(Allocation::class, 'allocation_id');
    }

    /**
     * Function: Get the accounts
     */
    public function accounts()
    {
        return $this->hasMany(UacsTransaction::class);
    }
}
