<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Department;
use App\Models\Member;
use Barryvdh\DomPDF\Facade\Pdf; // Make sure barryvdh/laravel-dompdf is installed

class ReportController extends Controller
{
    // Show main reports page
    public function index()
    {
        $departments = Department::all();
        $members = Member::all();
        $items = Item::with('department')->get();

        return view('reports.index', compact('departments', 'members', 'items'));
    }

    // Generate filtered report (Items)
    public function itemsReport(Request $request)
    {
        $query = Item::query()->with('department');

        if ($request->department_id) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->from_date && $request->to_date) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }

        $items = $query->get();
        $departments = Department::all();

        return view('reports.items', compact('items', 'departments'));
    }

    // Download PDF for Items
    public function downloadItemsPDF(Request $request)
    {
        $query = Item::query()->with('department');

        if ($request->department_id) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->from_date && $request->to_date) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }

        $items = $query->get();
        $pdf = Pdf::loadView('reports.items_pdf', compact('items'));

        return $pdf->download('items_report.pdf');
    }

    // Similarly, you can add departmentReport and usersReport methods
     public function departmentsReport()
    {
        $departments = Department::withCount('members', 'items')->paginate(8);
        return view('reports.departments', compact('departments'));
    }

    // Download departments report as PDF
    public function downloadDepartmentsPDF()
{
    $departments = Department::withCount('members', 'items')->get();

    $pdf = Pdf::loadView('reports.departments_pdf', compact('departments'));
    return $pdf->download('departments_report.pdf');
}

    // Show members report
    public function membersReport()
    {
        $members = Member::with('department')->paginate(10); // load department relationship
        $departments = Department::all(); // <--- Add this
        return view('reports.members', compact('members', 'departments'));
    }

    // Download members report as PDF
    public function downloadMembersPDF()
    {
        $members = Member::with('department')->get();
        $pdf = PDF::loadView('reports.members_pdf', compact('members'));

        return $pdf->download('members_report.pdf');
    }
}
