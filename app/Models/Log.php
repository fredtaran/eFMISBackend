<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_transaction',
        'transaction_id',
        'from',
        'to',
        'activity',
        'additional_notes'
    ];

    /**
     * Function: Return sender
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'from');
    }

    /**
     * Function: Return receiver
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'to');
    }

    /**
     * Function: Return transaction
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
