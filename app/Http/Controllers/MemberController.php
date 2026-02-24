<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display a listing of members with search and filtering.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $filters = $request->only(['status']);

        $members = Member::query()
            ->search($search)
            ->filter($filters)
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('members.index', compact('members', 'search', 'filters'));
    }

    /**
     * Show the form for creating a new member.
     */
    public function create()
    {
        return view('members.create');
    }

    /**
     * Store a newly created member in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'membership_number' => ['required', 'string', 'max:50', 'unique:members,membership_number'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:members,email'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $validated['status'] = 'active';

        Member::create($validated);

        return redirect()->route('members.index')
            ->with('success', 'Member created successfully.');
    }

    /**
     * Display the specified member.
     */
    public function show(Member $member)
    {
        $member->load(['borrowings.book', 'activeBorrowings']);
        
        $activeBorrowings = $member->activeBorrowings;
        $history = $member->borrowings()->with('book')->latest()->paginate(15);

        return view('members.show', compact('member', 'activeBorrowings', 'history'));
    }

    /**
     * Show the form for editing the specified member.
     */
    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    /**
     * Update the specified member in storage.
     */
    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'membership_number' => ['required', 'string', 'max:50', 'unique:members,membership_number,' . $member->id],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:members,email,' . $member->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'in:active,blocked'],
        ]);

        $member->update($validated);

        return redirect()->route('members.index')
            ->with('success', 'Member updated successfully.');
    }

    /**
     * Remove the specified member from storage.
     */
    public function destroy(Member $member)
    {
        if ($member->borrowings()->where('status', 'borrowed')->count() > 0) {
            return back()->with('error', 'Cannot delete member with active borrowings.');
        }

        $member->delete();

        return redirect()->route('members.index')
            ->with('success', 'Member deleted successfully.');
    }

    /**
     * Toggle member block status
     */
    public function toggleStatus(Member $member)
    {
        $newStatus = $member->status === 'active' ? 'blocked' : 'active';
        $member->update(['status' => $newStatus]);

        return back()->with('success', 'Member status updated to ' . $newStatus);
    }
}
