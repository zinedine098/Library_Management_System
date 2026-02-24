<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'book_id',
        'admin_id',
        'borrow_date',
        'due_date',
        'return_date',
        'fine_amount',
        'fine_status',
        'status',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
        'fine_amount' => 'decimal:2',
        'fine_status' => 'string',
        'status' => 'string',
    ];

    /**
     * Get the member who borrowed
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the book that was borrowed
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Get the admin who processed this transaction
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Scope for searching borrowings
     */
    public function scopeSearch($query, ?string $search)
    {
        return $query->when($search, function ($q) use ($search) {
            $q->where(function ($innerQuery) use ($search) {
                $innerQuery->whereHas('member', function ($qm) use ($search) {
                    $qm->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('book', function ($qb) use ($search) {
                    $qb->where('title', 'like', "%{$search}%");
                })
                ->orWhere('id', 'like', "%{$search}%");
            });
        });
    }

    /**
     * Scope for filtering by status
     */
    public function scopeStatus($query, ?string $status)
    {
        return $query->when($status, function ($q, $s) {
            $q->where('status', $s);
        });
    }

    /**
     * Scope for overdue borrowings
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'borrowed')
            ->where('due_date', '<', now()->toDateString());
    }

    /**
     * Scope for filtering borrowings
     */
    public function scopeFilter($query, array $filters)
    {
        return $query
            ->when($filters['status'] ?? null, fn($q, $s) => $q->status($s))
            ->when($filters['member_id'] ?? null, fn($q, $m) => $q->where('member_id', $m))
            ->when($filters['fine_status'] ?? null, fn($q, $fs) => $q->where('fine_status', $fs))
            ->when($filters['date_from'] ?? null, fn($q, $d) => $q->whereDate('borrow_date', '>=', $d))
            ->when($filters['date_to'] ?? null, fn($q, $d) => $q->whereDate('borrow_date', '<=', $d));
    }

    /**
     * Check if borrowing is overdue
     */
    public function isOverdue(): bool
    {
        return $this->status === 'borrowed' && $this->due_date->isPast();
    }

    /**
     * Calculate days overdue
     */
    public function daysOverdue(): int
    {
        if ($this->status === 'returned' || !$this->due_date->isPast()) {
            return 0;
        }

        return now()->diffInDays($this->due_date, false);
    }

    /**
     * Calculate fine based on days overdue
     */
    public function calculateFine(float $finePerDay = 1000): float
    {
        $days = $this->daysOverdue();
        return $days * $finePerDay;
    }

    /**
     * Return the book
     */
    public function returnBook(?float $fineAmount = null): void
    {
        $this->update([
            'return_date' => now()->toDateString(),
            'status' => 'returned',
            'fine_amount' => $fineAmount ?? 0,
            'fine_status' => ($fineAmount ?? 0) > 0 ? 'unpaid' : 'paid',
        ]);

        // Increment book stock
        $this->book->incrementStock();
    }
}
