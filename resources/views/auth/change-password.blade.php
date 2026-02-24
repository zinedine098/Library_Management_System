<x-layouts.app>
    <div class="max-w-2xl">
        <x-card title="Change Password">
            <form method="POST" action="{{ route('change-password.update') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <x-input label="Current Password" 
                         name="current_password" 
                         type="password" 
                         required 
                         autocomplete="current-password" />

                <x-input label="New Password" 
                         name="password" 
                         type="password" 
                         required 
                         autocomplete="new-password" />

                <x-input label="Confirm New Password" 
                         name="password_confirmation" 
                         type="password" 
                         required 
                         autocomplete="new-password" />

                <div class="flex items-center gap-4 pt-4">
                    <x-button type="submit">Update Password</x-button>
                    <a href="{{ route('dashboard') }}" class="text-slate-600 hover:text-slate-900">Cancel</a>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
