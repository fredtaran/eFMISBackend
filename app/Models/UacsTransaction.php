<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UacsTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'uacs_id',
        'amount'
    ];

    /**
     * Function: Get UACS
     */
    public function uacs()
    {
        return $this->belongsTo(Uacs::class, 'uacs_id');
    }
}
