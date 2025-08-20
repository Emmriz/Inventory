<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Department;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::with('department')->orderBy('created_at', 'desc')->paginate(10);
        $departments = Department::all();
        return view('items.index', compact('items', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:items',
            'department_id' => 'required|exists:departments,id',
            'quantity' => 'required|integer|min:0',
            'status' => 'required|in:in_use,not_in_use,damaged',
        ]);

        Item::create($validated);

        return redirect()->route('items.index')->with('success', 'Item added successfully.');
    }

    /**
     * Display the specified resource for editing.
     */
    public function show(string $id)
    {
        $item = Item::with('department')->findOrFail($id);
        
        return response()->json([
            'id' => $item->id,
            'name' => $item->name,
            'sku' => $item->sku,
            'department_id' => $item->department_id,
            'quantity' => $item->quantity,
            'status' => $item->status,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = Item::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:items,sku,' . $item->id,
            'department_id' => 'required|exists:departments,id',
            'quantity' => 'required|integer|min:0',
            'status' => 'required|in:in_use,not_in_use,damaged',
        ]);

        $item->update($validated);

        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Item::find($id);

        if (!$item) {
            return redirect()->route('items.index')->with('error', 'Item not found.');
        }

        $item->delete();

        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }
}
