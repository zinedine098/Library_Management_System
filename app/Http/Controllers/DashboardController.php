<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Member;
use App\Models\Borrowing;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $totalBooks = Book::count();
        $totalMembers = Member::count();
        $totalCategories = Category::count();
        $borrowedBooks = Borrowing::where('status', 'borrowed')->count();
        $overdueBooks = Borrowing::overdue()->count();
        
        // Calculate fines for current month
        $currentMonthFines = Borrowing::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('fine_amount');

        // Recent borrowings
        $recentBorrowings = Borrowing::with(['member', 'book', 'admin'])
            ->latest()
            ->take(10)
            ->get();

        // Overdue borrowings
        $overdueBorrowings = Borrowing::with(['member', 'book'])
            ->overdue()
            ->latest('due_date')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalBooks',
            'totalMembers',
            'totalCategories',
            'borrowedBooks',
            'overdueBooks',
            'currentMonthFines',
            'recentBorrowings',
            'overdueBorrowings'
        ));
    }
}
