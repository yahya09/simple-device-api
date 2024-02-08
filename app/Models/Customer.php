<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_number',
        'name',
        'phone_number'
    ];

    public function preorders()
    {
        return $this->hasMany(Preorder::class);
    }
}
