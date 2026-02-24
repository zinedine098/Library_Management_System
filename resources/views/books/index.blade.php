<x-layouts.app>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Books</h1>
            <p class="text-slate-600 mt-1">Manage book catalog</p>
        </div>
        <x-button href="{{ route('books.create') }}">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Book
        </x-button>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-md">
            {{ session('error') }}
        </div>
    @endif

    <!-- Search and Filter -->
    <x-card class="mb-6">
        <form method="GET" action="{{ route('books.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <x-input name="search" value="{{ $search }}" placeholder="Search by title, author, ISBN..."
                        label="" />
                </div>
                <div>
                    <x-select name="category" :options="['' => 'All Categories'] + $categories->toArray()" :value="$filters['category'] ?? ''" label="" />
                </div>
                <div>
                    <x-select name="availability" :options="[
                        '' => 'All Availability',
                        'available' => 'Available',
                        'out' => 'Out of Stock',
                    ]" value="{{ $filters['availability'] ?? '' }}"
                        label="" />
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div>
                    <x-select name="sort" :options="[
                        'title' => 'Title A-Z',
                        'title' => 'Title Z-A',
                        'created_at' => 'Newest First',
                        'created_at' => 'Oldest First',
                    ]" value="{{ $filters['sort'] ?? 'title' }}"
                        label="" />
                </div>
                <x-button type="submit">Filter</x-button>
                @if ($search || $filters)
                    <a href="{{ route('books.index') }}"
                        class="inline-flex items-center px-4 py-2 text-slate-600 hover:text-slate-900">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </x-card>

    <!-- Books Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mt-5">
        @forelse($books as $book)
            <div
                class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-shadow">
                <div class="aspect-[3/4] bg-slate-100 flex items-center justify-center overflow-hidden">
                    @if ($book->cover_image)
                        <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}"
                            class="w-full h-full object-cover">
                    @else
                        <svg class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-slate-900 line-clamp-2">{{ $book->title }}</h3>
                    <p class="text-sm text-slate-600 mt-1">{{ $book->author }}</p>
                    <div class="flex items-center justify-between mt-3">
                        <x-badge :variant="$book->stock_available > 0 ? 'success' : 'danger'">
                            {{ $book->stock_available }} available
                        </x-badge>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('books.show', $book) }}"
                                class="text-blue-600 hover:text-blue-900 text-sm">View</a>
                            <a href="{{ route('books.edit', $book) }}"
                                class="text-slate-600 hover:text-slate-900 text-sm">Edit</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <x-card>
                    <div class="py-8 text-center text-slate-500">
                        No books found
                    </div>
                </x-card>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($books->hasPages())
        <div class="mt-6">
            {{ $books->links() }}
        </div>
    @endif
</x-layouts.app>
