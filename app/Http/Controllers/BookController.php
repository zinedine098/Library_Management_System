<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of books with search and filtering.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $filters = $request->only(['category', 'availability', 'sort', 'direction']);

        $books = Book::with('category')
            ->search($search)
            ->filter($filters)
            ->paginate(15)
            ->withQueryString();

        $categories = Category::pluck('name', 'id');

        return view('books.index', compact('books', 'categories', 'search', 'filters'));
    }

    /**
     * Show the form for creating a new book.
     */
    public function create()
    {
        $categories = Category::pluck('name', 'id');
        
        return view('books.create', compact('categories'));
    }

    /**
     * Store a newly created book in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'publisher' => ['required', 'string', 'max:255'],
            'isbn' => ['nullable', 'string', 'max:50'],
            'published_year' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'stock_total' => ['required', 'integer', 'min:1'],
            'cover_image' => ['nullable', 'image', 'max:2048'],
        ]);

        $validated['stock_available'] = $validated['stock_total'];

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        Book::create($validated);

        return redirect()->route('books.index')
            ->with('success', 'Book created successfully.');
    }

    /**
     * Display the specified book.
     */
    public function show(Book $book)
    {
        $book->load('category', 'borrowings.member');
        
        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified book.
     */
    public function edit(Book $book)
    {
        $categories = Category::pluck('name', 'id');
        
        return view('books.edit', compact('book', 'categories'));
    }

    /**
     * Update the specified book in storage.
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'publisher' => ['required', 'string', 'max:255'],
            'isbn' => ['nullable', 'string', 'max:50'],
            'published_year' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'stock_total' => ['required', 'integer', 'min:1'],
            'cover_image' => ['nullable', 'image', 'max:2048'],
        ]);

        // Update stock available if total changed
        $stockDiff = $validated['stock_total'] - $book->stock_total;
        $validated['stock_available'] = $book->stock_available + $stockDiff;

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            // Delete old image
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $book->update($validated);

        return redirect()->route('books.index')
            ->with('success', 'Book updated successfully.');
    }

    /**
     * Remove the specified book from storage.
     */
    public function destroy(Book $book)
    {
        if ($book->borrowings()->where('status', 'borrowed')->count() > 0) {
            return back()->with('error', 'Cannot delete book with active borrowings.');
        }

        // Delete cover image
        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('books.index')
            ->with('success', 'Book deleted successfully.');
    }
}
