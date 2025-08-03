<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'department_id',
        'borrower_name',
        'date_taken',
        'return_date',
        'actual_return_date',
        'status',
        'notes'
    ];

    protected $casts = [
        'date_taken' => 'date',
        'return_date' => 'date',
        'actual_return_date' => 'date',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'borrowed' => 'Borrowed',
            'returned' => 'Returned',
            'overdue' => 'Overdue',
            default => 'Unknown'
        };
    }

    public function getIsOverdueAttribute()
    {
        return $this->status === 'borrowed' && Carbon::parse($this->return_date)->isPast();
    }

    // Automatically update status to overdue if past return date
    public function updateStatusIfOverdue()
    {
        if ($this->status === 'borrowed' && Carbon::parse($this->return_date)->isPast()) {
            $this->update(['status' => 'overdue']);
        }
    }
}