<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'line_id'
    ];

    /**
     * Function: Return line item
     */
    public function lineItem()
    {
        return $this->belongsTo(LineItem::class, 'line_id');
    }
}
