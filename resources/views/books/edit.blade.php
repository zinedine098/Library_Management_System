<x-layouts.app>
    <div class="max-w-2xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Edit Book</h1>
            <p class="text-slate-600 mt-1">Update book information</p>
        </div>

        <x-card>
            <form method="POST" action="{{ route('books.update', $book) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <x-select label="Category" 
                          name="category_id" 
                          :options="$categories"
                          required 
                          value="{{ old('category_id', $book->category_id) }}" />

                <x-input label="Title" 
                         name="title" 
                         required 
                         value="{{ old('title', $book->title) }}" />

                <x-input label="Author" 
                         name="author" 
                         required 
                         value="{{ old('author', $book->author) }}" />

                <x-input label="Publisher" 
                         name="publisher" 
                         required 
                         value="{{ old('publisher', $book->publisher) }}" />

                <x-input label="ISBN" 
                         name="isbn" 
                         value="{{ old('isbn', $book->isbn) }}" />

                <x-input label="Published Year" 
                         name="published_year" 
                         type="number"
                         value="{{ old('published_year', $book->published_year) }}" />

                <x-input label="Stock Total" 
                         name="stock_total" 
                         type="number"
                         required 
                         value="{{ old('stock_total', $book->stock_total) }}"
                         min="1" />

                <div class="mt-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Current Cover
                    </label>
                    @if($book->cover_image)
                    <img src="{{ asset('storage/' . $book->cover_image) }}" 
                         alt="{{ $book->title }}"
                         class="w-32 h-48 object-cover rounded-md mb-2">
                    @else
                    <p class="text-sm text-slate-500 mb-2">No cover image</p>
                    @endif
                </div>

                <div class="mt-4">
                    <label for="cover_image" class="block text-sm font-medium text-slate-700 mb-1">
                        Upload New Cover
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
                    <x-button type="submit">Update Book</x-button>
                    <a href="{{ route('books.index') }}" class="text-slate-600 hover:text-slate-900">Cancel</a>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
