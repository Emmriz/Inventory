<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku', 
        'department_id',
        'quantity',
        'status'
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];
    
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'in_use' => 'In Use',
            'not_in_use' => 'Not-In use',
            'damaged' => 'Damaged',
            default => 'Unknown'
        };
    }
}
