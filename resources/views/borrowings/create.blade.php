<x-layouts.app>
    <div class="max-w-2xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">New Borrowing</h1>
            <p class="text-slate-600 mt-1">Create a new book borrowing transaction</p>
        </div>

        <x-card>
            <form method="POST" action="{{ route('borrowings.store') }}">
                @csrf

                <x-select label="Member" 
                          name="member_id" 
                          :options="$members"
                          required 
                          value="{{ old('member_id') }}"
                          placeholder="Select a member" />
                @error('member_id')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror

                <x-select label="Book" 
                          name="book_id" 
                          :options="$books"
                          required 
                          value="{{ old('book_id') }}"
                          placeholder="Select a book" />
                @error('book_id')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror

                <x-input label="Borrow Date" 
                         name="borrow_date" 
                         type="date"
                         required 
                         value="{{ old('borrow_date', today()->format('Y-m-d')) }}" />

                <x-input label="Due Date" 
                         name="due_date" 
                         type="date"
                         required 
                         value="{{ old('due_date', today()->addDays(7)->format('Y-m-d')) }}" />

                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-md">
                    <p class="text-sm text-blue-800">
                        <strong>Note:</strong> The default loan period is 7 days. Books returned late will incur a fine of Rp 1,000 per day.
                    </p>
                </div>

                <div class="flex items-center gap-4 mt-6">
                    <x-button type="submit">Create Borrowing</x-button>
                    <a href="{{ route('borrowings.index') }}" class="text-slate-600 hover:text-slate-900">Cancel</a>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
