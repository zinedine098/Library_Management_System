<x-layouts.app>
    <div class="max-w-2xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Create Book</h1>
            <p class="text-slate-600 mt-1">Add a new book to the catalog</p>
        </div>

        <x-card>
            <form method="POST" action="{{ route('books.store') }}" enctype="multipart/form-data">
                @csrf

                <x-select label="Category" 
                          name="category_id" 
                          :options="$categories"
                          required 
                          value="{{ old('category_id') }}" />

                <x-input label="Title" 
                         name="title" 
                         required 
                         value="{{ old('title') }}"
                         placeholder="Book title" />

                <x-input label="Author" 
                         name="author" 
                         required 
                         value="{{ old('author') }}"
                         placeholder="Author name" />

                <x-input label="Publisher" 
                         name="publisher" 
                         required 
                         value="{{ old('publisher') }}"
                         placeholder="Publisher name" />

                <x-input label="ISBN" 
                         name="isbn" 
                         value="{{ old('isbn') }}"
                         placeholder="ISBN number (optional)" />

                <x-input label="Published Year" 
                         name="published_year" 
                         type="number"
                         value="{{ old('published_year') }}"
                         placeholder="e.g., 2024" />

                <x-input label="Stock Total" 
                         name="stock_total" 
                         type="number"
                         required 
                         value="{{ old('stock_total', 1) }}"
                         min="1" />

                <div class="mt-4">
                    <label for="cover_image" class="block text-sm font-medium text-slate-700 mb-1">
                        Cover Image
                    </label>
                    <input type="file" 
                           id="cover_image" 
                           name="cover_image" 
                           accept="image/*"
                           class="w-full px-3 py-2 border border-slate-300 rounded-md text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900">
                    @error('cover_image')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-4 mt-6">
                    <x-button type="submit">Create Book</x-button>
                    <a href="{{ route('books.index') }}" class="text-slate-600 hover:text-slate-900">Cancel</a>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
