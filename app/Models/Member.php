<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'membership_number',
        'name',
        'email',
        'phone',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Get all borrowings for this member
     */
    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    /**
     * Get active borrowings
     */
    public function activeBorrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class)->where('status', 'borrowed');
    }

    /**
     * Scope for searching members
     */
    public function scopeSearch($query, ?string $search)
    {
        return $query->when($search, function ($q) use ($search) {
            $q->where(function ($innerQuery) use ($search) {
                $innerQuery->where('name', 'like', "%{$search}%")
                    ->orWhere('membership_number', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        });
    }

    /**
     * Scope for filtering members
     */
    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['status'] ?? null, function ($q, $status) {
            $q->where('status', $status);
        });
    }

    /**
     * Check if member is blocked
     */
    public function isBlocked(): bool
    {
        return $this->status === 'blocked';
    }

    /**
     * Check if member can borrow (not blocked and has no unpaid fines)
     */
    public function canBorrow(): bool
    {
        if ($this->isBlocked()) {
            return false;
        }

        // Check for unpaid fines
        $hasUnpaidFines = $this->borrowings()
            ->where('fine_status', 'unpaid')
            ->where('fine_amount', '>', 0)
            ->exists();

        return !$hasUnpaidFines;
    }
}
