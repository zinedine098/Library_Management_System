<x-layouts.app>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Members</h1>
            <p class="text-slate-600 mt-1">Manage library members</p>
        </div>
        <x-button href="{{ route('members.create') }}">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Member
        </x-button>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-md">
        {{ session('error') }}
    </div>
    @endif

    <!-- Search and Filter -->
    <x-card class="mb-6">
        <form method="GET" action="{{ route('members.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <x-input name="search" 
                             value="{{ $search }}" 
                             placeholder="Search by name, membership number, email..." 
                             label="" />
                </div>
                <div>
                    <x-select name="status" 
                              :options="[
                                  '' => 'All Status',
                                  'active' => 'Active',
                                  'blocked' => 'Blocked'
                              ]" 
                              value="{{ $filters['status'] ?? '' }}"
                              label="" />
                </div>
            </div>
            <div class="flex items-center gap-4">
                <x-button type="submit">Filter</x-button>
                @if($search || $filters)
                <a href="{{ route('members.index') }}" class="inline-flex items-center px-4 py-2 text-slate-600 hover:text-slate-900">
                    Clear
                </a>
                @endif
            </div>
        </form>
    </x-card>

    <!-- Members Table -->
    <x-card>
        <x-table>
            <x-slot:header>
                <x-table-cell scope="header">Membership #</x-table-cell>
                <x-table-cell scope="header">Name</x-table-cell>
                <x-table-cell scope="header">Email</x-table-cell>
                <x-table-cell scope="header">Phone</x-table-cell>
                <x-table-cell scope="header">Status</x-table-cell>
                <x-table-cell scope="header">Actions</x-table-cell>
            </x-slot:header>
            @forelse($members as $member)
            <tr class="hover:bg-slate-50">
                <x-table-cell class="font-medium">{{ $member->membership_number }}</x-table-cell>
                <x-table-cell>{{ $member->name }}</x-table-cell>
                <x-table-cell>{{ $member->email }}</x-table-cell>
                <x-table-cell>{{ $member->phone ?? 'N/A' }}</x-table-cell>
                <x-table-cell>
                    <x-badge :variant="$member->status === 'active' ? 'success' : 'danger'">
                        {{ ucfirst($member->status) }}
                    </x-badge>
                </x-table-cell>
                <x-table-cell>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('members.show', $member) }}" 
                           class="text-blue-600 hover:text-blue-900 text-sm">View</a>
                        <a href="{{ route('members.edit', $member) }}" 
                           class="text-slate-600 hover:text-slate-900 text-sm">Edit</a>
                        <form method="POST" action="{{ route('members.destroy', $member) }}" 
                              onsubmit="return confirm('Are you sure you want to delete this member?');"
                              class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Delete</button>
                        </form>
                    </div>
                </x-table-cell>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                    No members found
                </td>
            </tr>
            @endforelse
        </x-table>

        <!-- Pagination -->
        @if($members->hasPages())
        <div class="mt-4">
            {{ $members->links() }}
        </div>
        @endif
    </x-card>
</x-layouts.app>
