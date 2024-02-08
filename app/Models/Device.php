<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'color',
        'price',
        'stock',
        'pre_ordered_count'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $dateFormat = 'Y-m-d H:i:s';

    public function getAvailableStockAttribute()
    {
        return $this->stock - $this->pre_ordered_count;
    }

    public function getHaveBeenPreorderedAttribute()
    {
        return $this->pre_ordered_count > 0;
    }

    public function preorders()
    {
        return $this->hasMany(Preorder::class);
    }
}
