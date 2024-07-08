<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'shorthand',
        'division_id'
    ];

    /**
     * Function: Return division
     */
    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }
}
