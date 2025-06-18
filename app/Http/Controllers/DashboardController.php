<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use App\Models\Department;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            $data = [
                'totalItems' => Item::count(),
                'activeUsers' => User::where('role', 'user')->count(),
                'departments' => Department::count(),
                'lowStockItems' => Item::where('status', 'low_stock')->count(),
                'recentTransactionsList' => InventoryTransaction::with('item')
                    ->latest()->take(4)->get(),
                'lowStockItemsList' => Item::where('status', 'low_stock')->take(3)->get(),
            ];
        } else {
            $data = [
                'departmentItems' => Item::where('department_id', $user->department_id)->count(),
                'lowStockAlerts' => Item::where('department_id', $user->department_id)
                    ->where('status', 'low_stock')->count(),
                'recentTransactions' => InventoryTransaction::whereHas('item', function($q) use ($user) {
                    $q->where('department_id', $user->department_id);
                })->count(),
                'recentTransactionsList' => InventoryTransaction::with('item')
                    ->whereHas('item', function($q) use ($user) {
                        $q->where('department_id', $user->department_id);
                    })->latest()->take(4)->get(),
                'lowStockItemsList' => Item::where('department_id', $user->department_id)
                    ->where('status', 'low_stock')->take(3)->get(),
            ];
        }
        
        return view('dashboard', $data);
    }
}