<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Department;
use Illuminate\Http\Request;

class MemberController extends Controller
{

public function show($id)
{
    $member = Member::with('department')->findOrFail($id);
    return response()->json($member);
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|unique:members,email',
            'department_id' => 'required|exists:departments,id',
        ]);

        Member::create($validated);

        return redirect()->back()->with('success', 'Member added successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $member = Member::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'department_id' => 'required|exists:departments,id',
        ]);

        $member->update($validated);

        return redirect()->back()->with('success', 'Member updated successfully.');
    }

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $member = Member::findOrFail($id);
        $member->delete();

        return redirect()->back()->with('success', 'Member deleted successfully.');
    }
}