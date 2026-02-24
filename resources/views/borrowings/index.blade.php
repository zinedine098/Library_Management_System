<x-layouts.app>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Borrowings</h1>
            <p class="text-slate-600 mt-1">Manage book circulation</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('borrowings.export', request()->query()) }}" 
               class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-md font-medium hover:bg-green-600 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export CSV
            </a>
            <x-button href="{{ route('borrowings.create') }}">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                New Borrowing
            </x-button>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-md">
        {{ session('error') }}
    </div>
    @endif

    
    <div class="space-y-6">
        <!-- Search and Filter -->
        <x-card class="mb-6">
            <form method="GET" action="{{ route('borrowings.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div class="lg:col-span-2">
                        <x-input name="search" 
                                 value="{{ $search }}" 
                                 placeholder="Search by member, book, or ID..." 
                                 label="" />
                    </div>
                    <div>
                        <x-select name="status" 
                                  :options="[
                                      '' => 'All Status',
                                      'borrowed' => 'Borrowed',
                                      'returned' => 'Returned'
                                  ]" 
                                  value="{{ $filters['status'] ?? '' }}"
                                  label="" />
                    </div>
                    <div>
                        <x-select name="fine_status" 
                                  :options="[
                                      '' => 'All Fines',
                                      'paid' => 'Paid',
                                      'unpaid' => 'Unpaid'
                                  ]" 
                                  value="{{ $filters['fine_status'] ?? '' }}"
                                  label="" />
                    </div>
                    <div>
                        <x-select name="member_id" 
                                  :options="['' => 'All Members'] + $members->pluck('name', 'id')->toArray()" 
                                  value="{{ $filters['member_id'] ?? '' }}"
                                  label="" />
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <x-input name="date_from" 
                                 type="date"
                                 value="{{ $filters['date_from'] ?? '' }}"
                                 label="From Date" />
                    </div>
                    <div>
                        <x-input name="date_to" 
                                 type="date"
                                 value="{{ $filters['date_to'] ?? '' }}"
                                 label="To Date" />
                    </div>
                    <div class="flex items-end">
                        <x-button type="submit" class="w-full">Filter</x-button>
                    </div>
                </div>
                @if($search || $filters)
                <div class="pt-2">
                    <a href="{{ route('borrowings.index') }}" class="text-sm text-slate-600 hover:text-slate-900">
                        Clear all filters
                    </a>
                </div>
                @endif
            </form>
        </x-card>
    
        <!-- Borrowings Table -->
        <x-card>
            <x-table>
                <x-slot:header>
                    <x-table-cell scope="header">ID</x-table-cell>
                    <x-table-cell scope="header">Book</x-table-cell>
                    <x-table-cell scope="header">Member</x-table-cell>
                    <x-table-cell scope="header">Borrow Date</x-table-cell>
                    <x-table-cell scope="header">Due Date</x-table-cell>
                    <x-table-cell scope="header">Status</x-table-cell>
                    <x-table-cell scope="header">Actions</x-table-cell>
                </x-slot:header>
                @forelse($borrowings as $borrowing)
                <tr class="hover:bg-slate-50">
                    <x-table-cell class="font-medium">#{{ $borrowing->id }}</x-table-cell>
                    <x-table-cell>{{ Str::limit($borrowing->book->title, 30) }}</x-table-cell>
                    <x-table-cell>{{ $borrowing->member->name }}</x-table-cell>
                    <x-table-cell>{{ $borrowing->borrow_date->format('M d, Y') }}</x-table-cell>
                    <x-table-cell>
                        @if($borrowing->isOverdue())
                        <span class="text-red-600 font-medium">{{ $borrowing->due_date->format('M d, Y') }}</span>
                        @else
                        {{ $borrowing->due_date->format('M d, Y') }}
                        @endif
                    </x-table-cell>
                    <x-table-cell>
                        <div class="flex items-center gap-2">
                            <x-badge :variant="$borrowing->status === 'returned' ? 'success' : 'warning'">
                                {{ ucfirst($borrowing->status) }}
                            </x-badge>
                            @if($borrowing->fine_amount > 0)
                            <x-badge :variant="$borrowing->fine_status === 'paid' ? 'info' : 'danger'">
                                Fine: {{ ucfirst($borrowing->fine_status) }}
                            </x-badge>
                            @endif
                        </div>
                    </x-table-cell>
                    <x-table-cell>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('borrowings.show', $borrowing) }}" 
                               class="text-blue-600 hover:text-blue-900 text-sm">View</a>
                            @if($borrowing->status === 'borrowed')
                            <a href="{{ route('borrowings.return-form', $borrowing) }}" 
                               class="text-green-600 hover:text-green-900 text-sm">Return</a>
                            @endif
                        </div>
                    </x-table-cell>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                        No borrowings found
                    </td>
                </tr>
                @endforelse
            </x-table>
    
            <!-- Pagination -->
            @if($borrowings->hasPages())
            <div class="mt-4">
                {{ $borrowings->links() }}
            </div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
