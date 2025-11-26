<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Skill;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $departments = Department::orderBy('name')->get();
        $employees = Employee::with('department', 'skills')->orderBy('last_name')->get();

        return view('employees.index', compact('employees', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $departments = Department::orderBy('name')->get();
        $skills = Skill::orderBy('name')->get();

        return view('employees.create', compact('departments', 'skills'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'email'         => 'required|email|max:255|unique:employees,email',
            'department_id' => 'required|exists:departments,id',
            'skills'        => 'nullable|array',
            'skills.*'      => 'exists:skills,id',
        ]);

        $employee = Employee::create($data);

        $employee->skills()->sync($request->input('skills', []));

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        //
        $employee->load('department', 'skills');

        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        //
        $departments = Department::orderBy('name')->get();
        $skills = Skill::orderBy('name')->get();
        $employee->load('skills');

        return view('employees.edit', compact('employee', 'departments', 'skills'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        //
        $data = $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'email'         => [
                'required',
                'email',
                'max:255',
                Rule::unique('employees', 'email')->ignore($employee->id),
            ],
            'department_id' => 'required|exists:departments,id',
            'skills'        => 'nullable|array',
            'skills.*'      => 'exists:skills,id',
        ]);

        $employee->update($data);
        $employee->skills()->sync($request->input('skills', []));

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        //
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }

    public function filter(Request $request)
    {
        $departmentId = $request->input('department_id');

        $employees = Employee::with('department', 'skills')
            ->when($departmentId, function ($query) use ($departmentId) {
                $query->where('department_id', $departmentId);
            })
            ->orderBy('last_name')
            ->get();

        return response()->json($employees);
    }

    public function checkEmail(Request $request)
    {
        $email = $request->query('email');
        $employeeId = $request->query('employee_id');

        $query = Employee::where('email', $email);
        if ($employeeId) {
            $query->where('id', '!=', $employeeId);
        }

        $exists = $query->exists();

        return response()->json([
            'exists' => $exists,
        ]);
    }
}
