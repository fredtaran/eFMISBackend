<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator',
        'is_pr',
        'allocation_id',
        'date',
        'obr_no',
        'obr_amount',
        'obr_timestamp',
        'obr_month',
        'obr_year',
        'creditor',
        'dv_no',
        'dv_timestamp',
        'dv_amount',
        'dv_month',
        'dv_year',
        'dv_gross',
        'dv_tax',
        'dv_retention',
        'dv_penalty',
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
    
    /**
     * Function: Add timestamps on update
     */
    public static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            if ($model->isDirty('obr_no')) {
                $model->obr_timestamp = now();

                // Add activity log here
                $authUser = Auth::user()->firstname . " " . Auth::user()->lastname;

                Log::create([
                    'is_transaction'    => true,
                    'transaction_id'    => $model->id,
                    'from'              => Auth::user()->id,
                    'activity'          => "$authUser updated registry details and added an OBR No.: $model->obr_no to the transaction."
                ]);
            }

            if ($model->isDirty('dv_no')) {
                $model->dv_timestamp = now();

                // Add activity log here
                $authUser = Auth::user()->firstname . " " . Auth::user()->lastname;

                Log::create([
                    'is_transaction'    => true,
                    'transaction_id'    => $model->id,
                    'from'              => Auth::user()->id,
                    'activity'          => "$authUser received the goods and generated the Disbursement No.: $model->dv_no to the transaction."
                ]);
            }

            if ($model->isDirty('ada_no')) {
                $model->ada_timestamp = now();

                // Add activity log here
                $authUser = Auth::user()->firstname . " " . Auth::user()->lastname;

                Log::create([
                    'is_transaction'    => true,
                    'transaction_id'    => $model->id,
                    'from'              => Auth::user()->id,
                    'activity'          => "$authUser updated ADA/Check details of the transaction."
                ]);
            }

            if ($model->isDirty('dv_amount')) {
                // Add activity log here
                $authUser = Auth::user()->firstname . " " . Auth::user()->lastname;

                Log::create([
                    'is_transaction'    => true,
                    'transaction_id'    => $model->id,
                    'from'              => Auth::user()->id,
                    'activity'          => "$authUser updated the disbursement details of the transaction."
                ]);
            }
        });
    }
}
