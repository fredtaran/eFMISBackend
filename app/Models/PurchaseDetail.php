<?php

namespace App\Models;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'budget_no',
        'bno_timestamp',
        'pr_no',
        'pr_timestamp',
        'po_no',
        'po_timestamp',
        'iar',
        'iar_timestamp',
        'transaction_id'
    ];

    /**
     * Function: Return transaction
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }



    /**
     * Function: Add timestamps on update
     */
    public static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            if ($model->isDirty('budget_no')) {
                $model->bno_timestamp = now();

                // Add acitivity log here

                $authUser = Auth::user()->firstname . " " . Auth::user()->lastname;

                Log::create([
                    'is_transaction'    => true,
                    'transaction_id'    => $model->transaction_id,
                    'from'              => Auth::user()->id,
                    'activity'          => "$authUser assigned a Budget No.: $model->budget_no to the transaction."
                ]);
            }

            if ($model->isDirty('pr_no')) {
                $model->pr_timestamp = now();

                // Add acitivity log here
                $authUser = Auth::user()->firstname . " " . Auth::user()->lastname;

                Log::create([
                    'is_transaction'    => true,
                    'transaction_id'    => $model->transaction_id,
                    'from'              => Auth::user()->id,
                    'activity'          => "$authUser assigned a Purchase No.: $model->pr_no to the transaction."
                ]);
            }

            if ($model->isDirty('po_no')) {
                $model->po_timestamp = now();

                // Add acitivity log here
                $authUser = Auth::user()->firstname . " " . Auth::user()->lastname;

                Log::create([
                    'is_transaction'    => true,
                    'transaction_id'    => $model->transaction_id,
                    'from'              => Auth::user()->id,
                    'activity'          => "$authUser assigned a Purchase Orde No.: $model->po_no to the transaction."
                ]);
            }

            if ($model->iar != $model->getOriginal('iar')) {

                $model->iar_timestamp = now();

                // Add acitivity log here
            }
        });
    }
}
