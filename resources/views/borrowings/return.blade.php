<x-layouts.app>
    <div class="max-w-2xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Process Return</h1>
            <p class="text-slate-600 mt-1">Return borrowing #{{ $borrowing->id }}</p>
        </div>

        <x-card>
            <!-- Book & Member Info Summary -->
            <div class="mb-6 p-4 bg-slate-50 rounded-md">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-slate-500">Book</p>
                        <p class="font-medium text-slate-900">{{ Str::limit($borrowing->book->title, 40) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Member</p>
                        <p class="font-medium text-slate-900">{{ $borrowing->member->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Due Date</p>
                        <p class="font-medium {{ $daysOverdue > 0 ? 'text-red-600' : 'text-slate-900' }}">
                            {{ $borrowing->due_date->format('M d, Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Days Overdue</p>
                        <p class="font-medium {{ $daysOverdue > 0 ? 'text-red-600' : 'text-green-600' }}">
                            {{ $daysOverdue }} days
                        </p>
                    </div>
                </div>
            </div>

            <!-- Fine Calculation -->
            @if($fineAmount > 0)
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-md">
                <h3 class="font-semibold text-red-900 mb-2">Fine Calculation</h3>
                <div class="space-y-1 text-sm text-red-800">
                    <p>Days Overdue: {{ $daysOverdue }} days</p>
                    <p>Fine per Day: Rp 1,000</p>
                    <p class="font-semibold text-lg mt-2 pt-2 border-t border-red-200">
                        Total Fine: Rp {{ number_format($fineAmount, 0, ',', '.') }}
                    </p>
                </div>
            </div>
            @else
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-md">
                <p class="text-green-800">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Book returned on time. No fine applicable.
                </p>
            </div>
            @endif

            <form method="POST" action="{{ route('borrowings.process-return', $borrowing) }}">
                @csrf

                @if($fineAmount > 0)
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Fine Payment Status
                    </label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" 
                                   name="fine_payment" 
                                   value="paid" 
                                   checked
                                   class="text-slate-900 focus:ring-slate-900">
                            <span class="ml-2 text-slate-700">Paid - Member has paid the fine</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" 
                                   name="fine_payment" 
                                   value="unpaid"
                                   class="text-slate-900 focus:ring-slate-900">
                            <span class="ml-2 text-slate-700">Unpaid - Member will pay later (will be blocked)</span>
                        </label>
                    </div>
                    @error('fine_payment')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                @else
                <input type="hidden" name="fine_payment" value="paid">
                @endif

                <div class="flex items-center gap-4">
                    <x-button type="submit" variant="success">Confirm Return</x-button>
                    <a href="{{ route('borrowings.show', $borrowing) }}" class="text-slate-600 hover:text-slate-900">Cancel</a>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
