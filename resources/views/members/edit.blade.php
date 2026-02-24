<x-layouts.app>
    <div class="max-w-2xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Edit Member</h1>
            <p class="text-slate-600 mt-1">Update member information</p>
        </div>

        <x-card>
            <form method="POST" action="{{ route('members.update', $member) }}">
                @csrf
                @method('PUT')

                <x-input label="Membership Number (NIM/NIK)" 
                         name="membership_number" 
                         required 
                         value="{{ old('membership_number', $member->membership_number) }}" />

                <x-input label="Name" 
                         name="name" 
                         required 
                         value="{{ old('name', $member->name) }}" />

                <x-input label="Email" 
                         name="email" 
                         type="email"
                         required 
                         value="{{ old('email', $member->email) }}" />

                <x-input label="Phone Number" 
                         name="phone" 
                         value="{{ old('phone', $member->phone) }}" />

                <x-select label="Status" 
                          name="status" 
                          :options="[
                              'active' => 'Active',
                              'blocked' => 'Blocked'
                          ]"
                          value="{{ old('status', $member->status) }}"
                          required />

                <div class="flex items-center gap-4 mt-6">
                    <x-button type="submit">Update Member</x-button>
                    <a href="{{ route('members.index') }}" class="text-slate-600 hover:text-slate-900">Cancel</a>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
