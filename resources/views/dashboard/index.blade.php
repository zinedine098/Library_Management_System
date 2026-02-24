<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Dashboard</h1>
        <p class="text-slate-600 mt-1">Overview of library statistics and activities</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Total Books</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ number_format($totalBooks) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Total Members</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ number_format($totalMembers) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Borrowed Books</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ number_format($borrowedBooks) }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Overdue Books</p>
                    <p class="text-3xl font-bold text-red-600 mt-2">{{ number_format($overdueBooks) }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Borrowings -->
        <x-card title="Recent Borrowings">
            <x-table>
                <x-slot:header>
                    <x-table-cell scope="header">Book</x-table-cell>
                    <x-table-cell scope="header">Member</x-table-cell>
                    <x-table-cell scope="header">Due Date</x-table-cell>
                    <x-table-cell scope="header">Status</x-table-cell>
                </x-slot:header>
                @forelse($recentBorrowings as $borrowing)
                <tr class="hover:bg-slate-50">
                    <x-table-cell>{{ Str::limit($borrowing->book->title, 30) }}</x-table-cell>
                    <x-table-cell>{{ $borrowing->member->name }}</x-table-cell>
                    <x-table-cell>{{ $borrowing->due_date->format('M d, Y') }}</x-table-cell>
                    <x-table-cell>
                        <x-badge :variant="$borrowing->status === 'returned' ? 'success' : 'warning'">
                            {{ ucfirst($borrowing->status) }}
                        </x-badge>
                    </x-table-cell>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                        No recent borrowings
                    </td>
                </tr>
                @endforelse
            </x-table>
            <div class="mt-4">
                <a href="{{ route('borrowings.index') }}" class="text-sm font-medium text-slate-900 hover:text-slate-700">
                    View all borrowings →
                </a>
            </div>
        </x-card>

        <!-- Overdue Borrowings -->
        <x-card title="Overdue Books">
            <x-table>
                <x-slot:header>
                    <x-table-cell scope="header">Book</x-table-cell>
                    <x-table-cell scope="header">Member</x-table-cell>
                    <x-table-cell scope="header">Due Date</x-table-cell>
                    <x-table-cell scope="header">Days</x-table-cell>
                </x-slot:header>
                @forelse($overdueBorrowings as $borrowing)
                <tr class="hover:bg-slate-50">
                    <x-table-cell>{{ Str::limit($borrowing->book->title, 30) }}</x-table-cell>
                    <x-table-cell>{{ $borrowing->member->name }}</x-table-cell>
                    <x-table-cell class="text-red-600">{{ $borrowing->due_date->format('M d, Y') }}</x-table-cell>
                    <x-table-cell>
                        <x-badge variant="danger">{{ $borrowing->daysOverdue() }} days</x-badge>
                    </x-table-cell>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                        No overdue books
                    </td>
                </tr>
                @endforelse
            </x-table>
            <div class="mt-4">
                <a href="{{ route('borrowings.index', ['status' => 'borrowed']) }}" class="text-sm font-medium text-slate-900 hover:text-slate-700">
                    View all active borrowings →
                </a>
            </div>
        </x-card>
    </div>
</x-layouts.app>
