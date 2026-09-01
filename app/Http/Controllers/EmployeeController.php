<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $shop = $request->user()->shop;

        $employees = $shop->employees()
            ->with('user')
            ->latest()
            ->get();

        return view('dashboard.employees.index', compact('employees'));
    }

    public function store(Request $request)
    {
        $shop = $request->user()->shop;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'role' => ['required', 'in:manager,operator,viewer'],
        ]);

        $validated['shop_id'] = $shop->id;
        $validated['is_active'] = true;

        $shop->employees()->create($validated);

        return back()->with('success', 'تم إضافة الموظف بنجاح.');
    }

    public function update(Request $request, $id)
    {
        $shop = $request->user()->shop;

        $employee = $shop->employees()->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'role' => ['required', 'in:manager,operator,viewer'],
        ]);

        $employee->update($validated);

        return back()->with('success', 'تم تحديث بيانات الموظف بنجاح.');
    }

    public function destroy(Request $request, $id)
    {
        $shop = $request->user()->shop;

        $employee = $shop->employees()->findOrFail($id);
        $employee->delete();

        return back()->with('success', 'تم حذف الموظف بنجاح.');
    }

    public function toggleActive(Request $request, $id)
    {
        $shop = $request->user()->shop;

        $employee = $shop->employees()->findOrFail($id);
        $employee->update(['is_active' => !$employee->is_active]);

        return back()->with('success', 'تم تحديث حالة الموظف بنجاح.');
    }
}
