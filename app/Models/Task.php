<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date',
        'user_id',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'due_date' => 'date',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Query Scopes (for filtering)
     */

    // Filter by status
    public function scopeStatus($query, $status)
    {
        if ($status && in_array($status, ['pending', 'in-progress', 'completed'])) {
            return $query->where('status', $status);
        }
        return $query;
    }

    // Filter by due date
    public function scopeDueDate($query, $date)
    {
        if ($date) {
            return $query->whereDate('due_date', $date);
        }
        return $query;
    }

    // Keyword search
    public function scopeSearch($query, $keyword)
    {
        if ($keyword) {
            return $query->where('title', 'like', "%{$keyword}%");
        }
        return $query;
    }
}
