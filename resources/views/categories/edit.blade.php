<x-layouts.app>
    <div class="max-w-2xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Edit Category</h1>
            <p class="text-slate-600 mt-1">Update category information</p>
        </div>

        <x-card>
            <form method="POST" action="{{ route('categories.update', $category) }}">
                @csrf
                @method('PUT')

                <x-input label="Category Name" 
                         name="name" 
                         required 
                         value="{{ old('name', $category->name) }}" />

                <div class="flex items-center gap-4 mt-6">
                    <x-button type="submit">Update Category</x-button>
                    <a href="{{ route('categories.index') }}" class="text-slate-600 hover:text-slate-900">Cancel</a>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
