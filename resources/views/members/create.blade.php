<x-layouts.app>
    <div class="max-w-2xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Create Member</h1>
            <p class="text-slate-600 mt-1">Add a new library member</p>
        </div>

        <x-card>
            <form method="POST" action="{{ route('members.store') }}">
                @csrf

                <x-input label="Membership Number (NIM/NIK)" 
                         name="membership_number" 
                         required 
                         value="{{ old('membership_number') }}"
                         placeholder="e.g., 2024001" />

                <x-input label="Name" 
                         name="name" 
                         required 
                         value="{{ old('name') }}"
                         placeholder="Full name" />

                <x-input label="Email" 
                         name="email" 
                         type="email"
                         required 
                         value="{{ old('email') }}"
                         placeholder="email@example.com" />

                <x-input label="Phone Number" 
                         name="phone" 
                         value="{{ old('phone') }}"
                         placeholder="e.g., 08123456789" />

                <div class="flex items-center gap-4 mt-6">
                    <x-button type="submit">Create Member</x-button>
                    <a href="{{ route('members.index') }}" class="text-slate-600 hover:text-slate-900">Cancel</a>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
