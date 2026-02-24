<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('borrowings.index') }}" class="text-slate-600 hover:text-slate-900">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Borrowing #{{ $borrowing->id }}</h1>
                <p class="text-slate-600 mt-1">Transaction Details</p>
            </div>
        </div>
    </div>

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

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Transaction Info -->
        <x-card title="Transaction Information">
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm text-slate-500">Transaction ID</dt>
                    <dd class="font-medium text-slate-900 mt-1">#{{ $borrowing->id }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-slate-500">Processed By</dt>
                    <dd class="font-medium text-slate-900 mt-1">{{ $borrowing->admin->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-slate-500">Borrow Date</dt>
                    <dd class="font-medium text-slate-900 mt-1">{{ $borrowing->borrow_date->format('M d, Y') }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-slate-500">Due Date</dt>
                    <dd class="font-medium {{ $borrowing->isOverdue() ? 'text-red-600' : 'text-slate-900' }} mt-1">
                        {{ $borrowing->due_date->format('M d, Y') }}
                        @if($borrowing->isOverdue())
                        <span class="text-sm">({{ $borrowing->daysOverdue() }} days overdue)</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm text-slate-500">Return Date</dt>
                    <dd class="font-medium text-slate-900 mt-1">{{ $borrowing->return_date?->format('M d, Y') ?? 'Not returned yet' }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-slate-500">Status</dt>
                    <dd class="mt-1">
                        <x-badge :variant="$borrowing->status === 'returned' ? 'success' : 'warning'">
                            {{ ucfirst($borrowing->status) }}
                        </x-badge>
                    </dd>
                </div>
            </dl>
        </x-card>

        <!-- Book & Member Info -->
        <div class="space-y-6">
            <x-card title="Book Information">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-24 bg-slate-100 rounded-md flex items-center justify-center flex-shrink-0">
                        @if($borrowing->book->cover_image)
                        <img src="{{ asset('storage/' . $borrowing->book->cover_image) }}" 
                             alt="{{ $borrowing->book->title }}"
                             class="w-full h-full object-cover rounded-md">
                        @else
                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        @endif
                    </div>
                    <div>
                        <p class="font-semibold text-slate-900">{{ $borrowing->book->title }}</p>
                        <p class="text-sm text-slate-600 mt-1">{{ $borrowing->book->author }}</p>
                        <p class="text-sm text-slate-500 mt-1">ISBN: {{ $borrowing->book->isbn ?? 'N/A' }}</p>
                        <a href="{{ route('books.show', $borrowing->book) }}" class="text-blue-600 hover:text-blue-900 text-sm mt-2 inline-block">
                            View Book Details →
                        </a>
                    </div>
                </div>
            </x-card>

            <x-card title="Member Information">
                <div class="space-y-2">
                    <p class="font-semibold text-slate-900">{{ $borrowing->member->name }}</p>
                    <p class="text-sm text-slate-600">{{ $borrowing->member->membership_number }}</p>
                    <p class="text-sm text-slate-500">{{ $borrowing->member->email }}</p>
                    <div class="flex items-center gap-2 mt-2">
                        <x-badge :variant="$borrowing->member->status === 'active' ? 'success' : 'danger'">
                            {{ ucfirst($borrowing->member->status) }}
                        </x-badge>
                    </div>
                    <a href="{{ route('members.show', $borrowing->member) }}" class="text-blue-600 hover:text-blue-900 text-sm mt-2 inline-block">
                        View Member Details →
                    </a>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Fine Information -->
    @if($borrowing->status === 'returned' && $borrowing->fine_amount > 0)
    <x-card title="Fine Information" class="mt-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-600">Fine Amount</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">Rp {{ number_format($borrowing->fine_amount, 0, ',', '.') }}</p>
            </div>
            <div>
                <x-badge :variant="$borrowing->fine_status === 'paid' ? 'success' : 'danger'">
                    {{ ucfirst($borrowing->fine_status) }}
                </x-badge>
            </div>
        </div>
    </x-card>
    @endif

    <!-- Actions -->
    <div class="mt-6 flex items-center gap-4">
        @if($borrowing->status === 'borrowed')
        <x-button href="{{ route('borrowings.return-form', $borrowing) }}" variant="success">
            Process Return
        </x-button>
        @endif
        <a href="{{ route('borrowings.index') }}" class="text-slate-600 hover:text-slate-900">
            Back to List
        </a>
    </div>
</x-layouts.app>
