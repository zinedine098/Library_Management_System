<x-layouts.app>
    <div class="max-w-4xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Profile Settings</h1>
            <p class="text-slate-600 mt-1">Manage your account information</p>
        </div>

        <!-- Success Messages -->
        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md">
            {{ session('success') }}
        </div>
        @endif

        @if(session('password-success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md">
            {{ session('password-success') }}
        </div>
        @endif

        <!-- Profile Tabs -->
        <div x-data="{ activeTab: 'profile' }" class="space-y-6 mb-5">
            <!-- Tabs Navigation -->
            <div class="border-b border-slate-200">
                <nav class="-mb-px flex space-x-8">
                    <button 
                        @click="activeTab = 'profile'"
                        :class="activeTab === 'profile' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        Profile Information
                    </button>
                    <button 
                        @click="activeTab = 'password'"
                        :class="activeTab === 'password' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        Change Password
                    </button>
                </nav>
            </div>

            <!-- Profile Information Tab -->
            <div x-show="activeTab === 'profile'" x-cloak>
                <x-card>
                    <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <div class="flex items-center gap-4 mb-4">
                                    <div class="w-20 h-20 bg-slate-900 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-slate-900">{{ Auth::user()->name }}</h3>
                                        <p class="text-sm text-slate-500">{{ Auth::user()->email }}</p>
                                    </div>
                                </div>
                            </div>

                            <x-input label="Name" 
                                     name="name" 
                                     required 
                                     value="{{ old('name', Auth::user()->name) }}"
                                     placeholder="Your name" />

                            <x-input label="Email" 
                                     name="email" 
                                     type="email"
                                     required 
                                     value="{{ old('email', Auth::user()->email) }}"
                                     placeholder="your@email.com" />
                        </div>

                        <div class="flex items-center gap-4 pt-4">
                            <x-button type="submit">Save Changes</x-button>
                        </div>
                    </form>
                </x-card>
            </div>

            <!-- Change Password Tab -->
            <div x-show="activeTab === 'password'" x-cloak>
                <x-card>
                    <form method="POST" action="{{ route('profile.password') }}" class="space-y-4">
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
                            <x-button type="submit" variant="primary">Update Password</x-button>
                        </div>
                    </form>
                </x-card>
            </div>
        </div>

        <!-- Delete Account Section (Optional - for future) -->
        {{-- <x-card class="mt-6" title="Danger Zone">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="font-medium text-slate-900">Delete Account</h4>
                    <p class="text-sm text-slate-500 mt-1">Permanently delete your account and all data.</p>
                </div>
                <x-button variant="danger" type="button" disabled>
                    Delete Account
                </x-button>
            </div>
        </x-card> --}}
    </div>
</x-layouts.app>
