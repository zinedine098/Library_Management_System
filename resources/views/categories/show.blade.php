<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('categories.index') }}" class="text-slate-600 hover:text-slate-900">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ $category->name }}</h1>
                <p class="text-slate-600 mt-1">Category Details</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <x-card>
            <div class="text-sm text-slate-600">Slug</div>
            <div class="text-lg font-semibold text-slate-900 mt-1">{{ $category->slug }}</div>
        </x-card>
        <x-card>
            <div class="text-sm text-slate-600">Total Books</div>
            <div class="text-lg font-semibold text-slate-900 mt-1">{{ $books->total() }}</div>
        </x-card>
        <x-card>
            <div class="text-sm text-slate-600">Created</div>
            <div class="text-lg font-semibold text-slate-900 mt-1">{{ $category->created_at->format('M d, Y') }}</div>
        </x-card>
    </div>

    <x-card title="Books in this Category">
        <x-table>
            <x-slot:header>
                <x-table-cell scope="header">Title</x-table-cell>
                <x-table-cell scope="header">Author</x-table-cell>
                <x-table-cell scope="header">ISBN</x-table-cell>
                <x-table-cell scope="header">Stock</x-table-cell>
                <x-table-cell scope="header">Actions</x-table-cell>
            </x-slot:header>
            @forelse($books as $book)
            <tr class="hover:bg-slate-50">
                <x-table-cell class="font-medium">{{ $book->title }}</x-table-cell>
                <x-table-cell>{{ $book->author }}</x-table-cell>
                <x-table-cell>{{ $book->isbn ?? 'N/A' }}</x-table-cell>
                <x-table-cell>
                    <x-badge :variant="$book->stock_available > 0 ? 'success' : 'danger'">
                        {{ $book->stock_available }} / {{ $book->stock_total }}
                    </x-badge>
                </x-table-cell>
                <x-table-cell>
                    <a href="{{ route('books.show', $book) }}" class="text-blue-600 hover:text-blue-900 text-sm">View</a>
                </x-table-cell>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                    No books in this category
                </td>
            </tr>
            @endforelse
        </x-table>

        @if($books->hasPages())
        <div class="mt-4">
            {{ $books->links() }}
        </div>
        @endif
    </x-card>
</x-layouts.app>
