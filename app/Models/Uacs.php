<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uacs extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'code'
    ];


    public function uacs_transactions()
    {
        return $this->hasMany(UacsTransaction::class);
    }
}
