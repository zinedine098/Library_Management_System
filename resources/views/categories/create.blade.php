<x-layouts.app>
    <div class="max-w-2xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Create Category</h1>
            <p class="text-slate-600 mt-1">Add a new book category</p>
        </div>

        <x-card>
            <form method="POST" action="{{ route('categories.store') }}">
                @csrf

                <x-input label="Category Name" 
                         name="name" 
                         required 
                         placeholder="e.g., Fiction, Science, History"
                         value="{{ old('name') }}" />

                <div class="flex items-center gap-4 mt-6">
                    <x-button type="submit">Create Category</x-button>
                    <a href="{{ route('categories.index') }}" class="text-slate-600 hover:text-slate-900">Cancel</a>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
