<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Book;
use App\Models\Member;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    private const FINE_PER_DAY = 1000; // Fine per day in the local currency

    /**
     * Display a listing of borrowings with search and filtering.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $filters = $request->only(['status', 'member_id', 'fine_status', 'date_from', 'date_to']);

        $borrowings = Borrowing::with(['member', 'book', 'admin'])
            ->search($search)
            ->filter($filters)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $members = Member::select('id', 'name')->orderBy('name')->get();

        return view('borrowings.index', compact('borrowings', 'members', 'search', 'filters'));
    }

    /**
     * Show the form for creating a new borrowing.
     */
    public function create()
    {
        $members = Member::where('status', 'active')->pluck('name', 'id');
        $books = Book::where('stock_available', '>', 0)->pluck('title', 'id');

        return view('borrowings.create', compact('members', 'books'));
    }

    /**
     * Store a newly created borrowing in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => ['required', 'exists:members,id'],
            'book_id' => ['required', 'exists:books,id'],
            'borrow_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after:borrow_date'],
        ]);

        // Check if member can borrow
        $member = Member::findOrFail($validated['member_id']);
        if (!$member->canBorrow()) {
            return back()->withErrors(['member_id' => 'Member is blocked or has unpaid fines.'])
                ->withInput();
        }

        // Check book availability
        $book = Book::findOrFail($validated['book_id']);
        if (!$book->isAvailable()) {
            return back()->withErrors(['book_id' => 'Book is not available.'])
                ->withInput();
        }

        // Create borrowing
        $borrowing = Borrowing::create([
            'member_id' => $validated['member_id'],
            'book_id' => $validated['book_id'],
            'admin_id' => auth()->id(),
            'borrow_date' => $validated['borrow_date'],
            'due_date' => $validated['due_date'],
            'status' => 'borrowed',
        ]);

        // Decrement book stock
        $book->decrementStock();

        return redirect()->route('borrowings.show', $borrowing)
            ->with('success', 'Borrowing created successfully.');
    }

    /**
     * Display the specified borrowing.
     */
    public function show(Borrowing $borrowing)
    {
        $borrowing->load(['member', 'book', 'admin']);
        
        return view('borrowings.show', compact('borrowing'));
    }

    /**
     * Show the form for processing a return.
     */
    public function returnForm(Borrowing $borrowing)
    {
        if ($borrowing->status === 'returned') {
            return redirect()->route('borrowings.show', $borrowing)
                ->with('error', 'This book has already been returned.');
        }

        $daysOverdue = $borrowing->daysOverdue();
        $fineAmount = $borrowing->calculateFine(self::FINE_PER_DAY);

        return view('borrowings.return', compact('borrowing', 'daysOverdue', 'fineAmount'));
    }

    /**
     * Process the return of a borrowed book.
     */
    public function processReturn(Request $request, Borrowing $borrowing)
    {
        if ($borrowing->status === 'returned') {
            return back()->with('error', 'This book has already been returned.');
        }

        $validated = $request->validate([
            'fine_payment' => ['required', 'in:paid,unpaid'],
        ]);

        // Calculate fine
        $fineAmount = $borrowing->calculateFine(self::FINE_PER_DAY);
        
        // Return the book
        $borrowing->returnBook($fineAmount);

        // Update fine status based on payment
        if ($validated['fine_payment'] === 'paid' && $fineAmount > 0) {
            $borrowing->update(['fine_status' => 'paid']);
        }

        // Check if member should be blocked due to unpaid fines
        if ($fineAmount > 0 && $validated['fine_payment'] === 'unpaid') {
            $borrowing->member->update(['status' => 'blocked']);
        }

        return redirect()->route('borrowings.show', $borrowing)
            ->with('success', 'Book returned successfully.');
    }

    /**
     * Export borrowings to CSV
     */
    public function export(Request $request)
    {
        $filters = $request->only(['status', 'member_id', 'date_from', 'date_to']);

        $borrowings = Borrowing::with(['member', 'book', 'admin'])
            ->filter($filters)
            ->latest()
            ->get();

        $filename = 'borrowings_' . now()->format('Y-m-d') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Member', 'Book', 'Admin', 'Borrow Date', 'Due Date', 'Return Date', 'Fine Amount', 'Fine Status', 'Status']);

        foreach ($borrowings as $borrowing) {
            fputcsv($output, [
                $borrowing->id,
                $borrowing->member->name,
                $borrowing->book->title,
                $borrowing->admin->name,
                $borrowing->borrow_date->format('Y-m-d'),
                $borrowing->due_date->format('Y-m-d'),
                $borrowing->return_date?->format('Y-m-d') ?? 'N/A',
                $borrowing->fine_amount,
                $borrowing->fine_status,
                $borrowing->status,
            ]);
        }

        fclose($output);
        exit;
    }
}
