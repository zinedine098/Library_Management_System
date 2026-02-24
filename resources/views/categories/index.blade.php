<x-layouts.app>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Categories</h1>
            <p class="text-slate-600 mt-1">Manage book categories</p>
        </div>
        <x-button href="{{ route('categories.create') }}">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Category
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


    <div class="space-y-6">
        <!-- Search -->
        <x-card class="mt-6">
            <form method="GET" action="{{ route('categories.index') }}" class="flex gap-4">
                <div class="flex-1">
                    <x-input name="search" value="{{ $search }}" placeholder="Search categories..."
                        label="" />
                </div>
                <x-button type="submit">Search</x-button>
                @if ($search)
                    <a href="{{ route('categories.index') }}"
                        class="inline-flex items-center px-4 py-2 text-slate-600 hover:text-slate-900">
                        Clear
                    </a>
                @endif
            </form>
        </x-card>

        <!-- Categories Table -->
        <x-card class="mt-6">
            <x-table>
                <x-slot:header>
                    <x-table-cell scope="header">Name</x-table-cell>
                    <x-table-cell scope="header">Slug</x-table-cell>
                    <x-table-cell scope="header">Books</x-table-cell>
                    <x-table-cell scope="header">Created</x-table-cell>
                    <x-table-cell scope="header">Actions</x-table-cell>
                </x-slot:header>
                @forelse($categories as $category)
                    <tr class="hover:bg-slate-50">
                        <x-table-cell class="font-medium">{{ $category->name }}</x-table-cell>
                        <x-table-cell class="text-slate-500">{{ $category->slug }}</x-table-cell>
                        <x-table-cell>
                            <x-badge variant="info">{{ $category->books->count() }} books</x-badge>
                        </x-table-cell>
                        <x-table-cell>{{ $category->created_at->format('M d, Y') }}</x-table-cell>
                        <x-table-cell>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('categories.show', $category) }}"
                                    class="text-slate-600 hover:text-slate-900 text-sm">View</a>
                                <a href="{{ route('categories.edit', $category) }}"
                                    class="text-blue-600 hover:text-blue-900 text-sm">Edit</a>
                                <form method="POST" action="{{ route('categories.destroy', $category) }}"
                                    onsubmit="return confirm('Are you sure you want to delete this category?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-600 hover:text-red-900 text-sm">Delete</button>
                                </form>
                            </div>
                        </x-table-cell>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                            No categories found
                        </td>
                    </tr>
                @endforelse
            </x-table>

            <!-- Pagination -->
            @if ($categories->hasPages())
                <div class="mt-4">
                    {{ $categories->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
