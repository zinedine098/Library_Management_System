<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('books.index') }}" class="text-slate-600 hover:text-slate-900">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ $book->title }}</h1>
                <p class="text-slate-600 mt-1">Book Details</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Book Cover & Info -->
        <div class="md:col-span-1">
            <x-card>
                <div class="aspect-[3/4] bg-slate-100 rounded-md overflow-hidden mb-4">
                    @if($book->cover_image)
                    <img src="{{ asset('storage/' . $book->cover_image) }}" 
                         alt="{{ $book->title }}"
                         class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    @endif
                </div>
                <div class="space-y-2">
                    <div>
                        <span class="text-sm text-slate-500">Category</span>
                        <p class="font-medium">{{ $book->category->name }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-slate-500">Stock</span>
                        <p>
                            <x-badge :variant="$book->stock_available > 0 ? 'success' : 'danger'">
                                {{ $book->stock_available }} / {{ $book->stock_total }}
                            </x-badge>
                        </p>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Book Details -->
        <div class="md:col-span-2">
            <x-card title="Book Information">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm text-slate-500">Title</dt>
                        <dd class="font-medium text-slate-900 mt-1">{{ $book->title }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">Author</dt>
                        <dd class="font-medium text-slate-900 mt-1">{{ $book->author }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">Publisher</dt>
                        <dd class="font-medium text-slate-900 mt-1">{{ $book->publisher }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">ISBN</dt>
                        <dd class="font-medium text-slate-900 mt-1">{{ $book->isbn ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">Published Year</dt>
                        <dd class="font-medium text-slate-900 mt-1">{{ $book->published_year ?? 'N/A' }}</dd>
                    </div>
                </dl>

                <div class="flex items-center gap-4 mt-6 pt-6 border-t border-slate-200">
                    <x-button href="{{ route('books.edit', $book) }}">Edit Book</x-button>
                    <form method="POST" action="{{ route('books.destroy', $book) }}" 
                          onsubmit="return confirm('Are you sure you want to delete this book?');">
                        @csrf
                        @method('DELETE')
                        <x-button variant="danger" type="submit">Delete</x-button>
                    </form>
                </div>
            </x-card>
        </div>
    </div>
</x-layouts.app>
