<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Item;
use App\Models\Department;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BorrowingController extends Controller
{
    public function index()
    {
        $borrowings = Borrowing::with(['item', 'department'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $departments = Department::all();
        
        // Update overdue status for all borrowed items
        Borrowing::where('status', 'borrowed')
            ->where('return_date', '<', Carbon::today())
            ->update(['status' => 'overdue']);
        
        return view('borrowings.index', compact('borrowings', 'departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'department_id' => 'required|exists:departments,id',
            'borrower_name' => 'required|string|max:255',
            'date_taken' => 'required|date',
            'return_date' => 'required|date|after_or_equal:date_taken',
            'notes' => 'nullable|string'
        ]);

        // Check if item is already borrowed
        $existingBorrowing = Borrowing::where('item_id', $validated['item_id'])
            ->whereIn('status', ['borrowed', 'overdue'])
            ->first();

        if ($existingBorrowing) {
            return redirect()->back()->with('error', 'This item is already borrowed out.');
        }

        // Set status to overdue if return date is in the past
        $status = Carbon::parse($validated['return_date'])->isPast() ? 'overdue' : 'borrowed';
        $validated['status'] = $status;

        Borrowing::create($validated);

        return redirect()->route('borrowings.index')->with('success', 'Item borrowed successfully.');
    }

    public function returnItem(Request $request, $id)
    {
        $borrowing = Borrowing::findOrFail($id);

        if ($borrowing->status === 'returned') {
            return redirect()->back()->with('error', 'This item has already been returned.');
        }

        $borrowing->update([
            'actual_return_date' => Carbon::today(),
            'status' => 'returned'
        ]);

        return redirect()->back()->with('success', 'Item returned successfully.');
    }

    public function getItemsByDepartment($departmentId)
    {
        // Get all items for the department first (for debugging)
        $allItems = Item::where('department_id', $departmentId)->get();
        Log::info('All items in department ' . $departmentId . ': ' . $allItems->count());
        Log::info('Items status breakdown: ' . $allItems->groupBy('status')->map->count());
        
        $items = Item::where('department_id', $departmentId)
            ->whereNotIn('id', function($query) {
                $query->select('item_id')
                    ->from('borrowings')
                    ->whereIn('status', ['borrowed', 'overdue']);
            })
            ->get(['id', 'name', 'sku']);

        Log::info('Available items found: ' . $items->count());
        return response()->json($items);
    }

    

    public function destroy($id)
    {
        $borrowing = Borrowing::findOrFail($id);
        $borrowing->delete();

        return redirect()->back()->with('success', 'Borrowing record deleted successfully.');
    }
}