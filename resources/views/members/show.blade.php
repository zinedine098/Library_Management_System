<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('members.index') }}" class="text-slate-600 hover:text-slate-900">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ $member->name }}</h1>
                <p class="text-slate-600 mt-1">Member Details</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <x-card>
            <div class="text-sm text-slate-500">Membership Number</div>
            <div class="text-lg font-semibold text-slate-900 mt-1">{{ $member->membership_number }}</div>
        </x-card>
        <x-card>
            <div class="text-sm text-slate-500">Status</div>
            <div class="mt-1">
                <x-badge :variant="$member->status === 'active' ? 'success' : 'danger'">
                    {{ ucfirst($member->status) }}
                </x-badge>
            </div>
        </x-card>
        <x-card>
            <div class="text-sm text-slate-500">Active Borrowings</div>
            <div class="text-lg font-semibold text-slate-900 mt-1">{{ $activeBorrowings->count() }}</div>
        </x-card>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Member Info -->
        <x-card title="Contact Information">
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm text-slate-500">Email</dt>
                    <dd class="font-medium text-slate-900 mt-1">{{ $member->email }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-slate-500">Phone</dt>
                    <dd class="font-medium text-slate-900 mt-1">{{ $member->phone ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-slate-500">Member Since</dt>
                    <dd class="font-medium text-slate-900 mt-1">{{ $member->created_at->format('M d, Y') }}</dd>
                </div>
            </dl>

            <div class="flex items-center gap-4 mt-6 pt-6 border-t border-slate-200">
                <x-button href="{{ route('members.edit', $member) }}">Edit Member</x-button>
                @if($member->status === 'active')
                <form method="POST" action="{{ route('members.toggle-status', $member) }}">
                    @csrf
                    <x-button variant="warning" type="submit">Block Member</x-button>
                </form>
                @else
                <form method="POST" action="{{ route('members.toggle-status', $member) }}">
                    @csrf
                    <x-button variant="success" type="submit">Activate Member</x-button>
                </form>
                @endif
            </div>
        </x-card>

        <!-- Active Borrowings -->
        <x-card title="Currently Borrowed Books">
            @if($activeBorrowings->count() > 0)
            <div class="space-y-3">
                @foreach($activeBorrowings as $borrowing)
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-md">
                    <div>
                        <p class="font-medium text-slate-900">{{ Str::limit($borrowing->book->title, 40) }}</p>
                        <p class="text-sm text-slate-500">Due: {{ $borrowing->due_date->format('M d, Y') }}</p>
                    </div>
                    @if($borrowing->isOverdue())
                    <x-badge variant="danger">{{ $borrowing->daysOverdue() }} days late</x-badge>
                    @else
                    <x-badge variant="success">On time</x-badge>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <p class="text-slate-500 py-4">No active borrowings</p>
            @endif
        </x-card>
    </div>

    <!-- Borrowing History -->
    <x-card title="Borrowing History" class="mt-6">
        <x-table>
            <x-slot:header>
                <x-table-cell scope="header">Book</x-table-cell>
                <x-table-cell scope="header">Borrow Date</x-table-cell>
                <x-table-cell scope="header">Due Date</x-table-cell>
                <x-table-cell scope="header">Return Date</x-table-cell>
                <x-table-cell scope="header">Fine</x-table-cell>
                <x-table-cell scope="header">Status</x-table-cell>
            </x-slot:header>
            @forelse($history as $record)
            <tr class="hover:bg-slate-50">
                <x-table-cell class="font-medium">{{ Str::limit($record->book->title, 30) }}</x-table-cell>
                <x-table-cell>{{ $record->borrow_date->format('M d, Y') }}</x-table-cell>
                <x-table-cell>{{ $record->due_date->format('M d, Y') }}</x-table-cell>
                <x-table-cell>{{ $record->return_date?->format('M d, Y') ?? '-' }}</x-table-cell>
                <x-table-cell>
                    @if($record->fine_amount > 0)
                    <span class="{{ $record->fine_status === 'paid' ? 'text-green-600' : 'text-red-600' }}">
                        Rp {{ number_format($record->fine_amount, 0, ',', '.') }}
                    </span>
                    @else
                    -
                    @endif
                </x-table-cell>
                <x-table-cell>
                    <x-badge :variant="$record->status === 'returned' ? 'success' : 'warning'">
                        {{ ucfirst($record->status) }}
                    </x-badge>
                </x-table-cell>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                    No borrowing history
                </td>
            </tr>
            @endforelse
        </x-table>

        @if($history->hasPages())
        <div class="mt-4">
            {{ $history->links() }}
        </div>
        @endif
    </x-card>
</x-layouts.app>
