<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'program',
        'code',
        'amount',
        'year',
        'line_id',
        'fs_id',
        'section_id'
    ];

    /**
     * Function: Returns line item
     */
    public function lineItem()
    {
        return $this->belongsTo(LineItem::class, 'line_id');
    }

    /**
     * Function: Returns fund source
     */
    public function fundSource()
    {
        return $this->belongsTo(FundSource::class, 'fs_id');
    }

    /**
     * Function: Returns section
     */
    public function section()
    {
        return $this->belongsTo(Section::class, 'section');
    }
}
