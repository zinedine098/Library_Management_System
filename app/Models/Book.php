<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'author',
        'publisher',
        'isbn',
        'published_year',
        'stock_total',
        'stock_available',
        'cover_image',
    ];

    protected $casts = [
        'published_year' => 'integer',
        'stock_total' => 'integer',
        'stock_available' => 'integer',
    ];

    /**
     * Get the category that owns the book
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all borrowings for this book
     */
    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    /**
     * Scope for searching books
     */
    public function scopeSearch($query, ?string $search)
    {
        return $query->when($search, function ($q) use ($search) {
            $q->where(function ($innerQuery) use ($search) {
                $innerQuery->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%")
                    ->orWhere('publisher', 'like', "%{$search}%");
            });
        });
    }

    /**
     * Scope for filtering books
     */
    public function scopeFilter($query, array $filters)
    {
        return $query
            ->when($filters['category'] ?? null, function ($q, $category) {
                $q->where('category_id', $category);
            })
            ->when($filters['availability'] ?? null, function ($q, $avail) {
                if ($avail === 'available') {
                    return $q->where('stock_available', '>', 0);
                }
                if ($avail === 'out') {
                    return $q->where('stock_available', 0);
                }
            })
            ->when($filters['sort'] ?? 'title', function ($q, $sort) {
                $direction = $filters['direction'] ?? 'asc';
                return $q->orderBy($sort, $direction);
            });
    }

    /**
     * Check if book is available
     */
    public function isAvailable(): bool
    {
        return $this->stock_available > 0;
    }

    /**
     * Decrement stock
     */
    public function decrementStock(): void
    {
        $this->decrement('stock_available');
    }

    /**
     * Increment stock
     */
    public function incrementStock(): void
    {
        $this->increment('stock_available');
    }
}
