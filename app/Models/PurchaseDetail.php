<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
