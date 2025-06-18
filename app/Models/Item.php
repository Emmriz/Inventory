<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'sku', 'quantity', 
        'min_quantity', 'price', 'department_id', 'status'
    ];
    
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    
    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }
    
    // Auto-update status based on quantity
    protected static function booted()
    {
        static::saving(function ($item) {
            if ($item->quantity <= $item->min_quantity) {
                $item->status = 'low_stock';
            } elseif ($item->status === 'low_stock' && $item->quantity > $item->min_quantity) {
                $item->status = 'active';
            }
        });
    }
}