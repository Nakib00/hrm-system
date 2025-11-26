@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Employees</h1>

    @if(session('success'))
    <div class="alert alert-success mt-2">
        {{ session('success') }}
    </div>
    @endif

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <a href="{{ route('employees.create') }}" class="btn btn-primary">Create Employee</a>

        <div class="d-flex align-items-center">
            <label class="me-2 mb-0">Filter by Department:</label>
            <select id="departmentFilter" class="form-select">
                <option value="">All</option>
                @foreach($departments as $department)
                <option value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <table class="table table-bordered" id="employeesTable">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>Skills</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="employeesTbody">
            @foreach($employees as $employee)
            <tr>
                <td>{{ $employee->full_name }}</td>
                <td>{{ $employee->email }}</td>
                <td>{{ $employee->department->name }}</td>
                <td>
                    @foreach($employee->skills as $skill)
                    <span class="badge bg-secondary">{{ $skill->name }}</span>
                    @endforeach
                </td>
                <td>
                    <a href="{{ route('employees.show', $employee) }}" class="btn btn-sm btn-info">View</a>
                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('Delete this employee?')" class="btn btn-sm btn-danger">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(function() {
        $('#departmentFilter').on('change', function() {
            const departmentId = $(this).val();
            $.ajax({
                url: "{{ route('employees.filter') }}",
                data: {
                    department_id: departmentId
                },
                success: function(employees) {
                    const $tbody = $('#employeesTbody');
                    $tbody.empty();

                    employees.forEach(function(employee) {
                        const skillsHtml = employee.skills.map(function(skill) {
                            return '<span class="badge bg-secondary me-1">' + skill.name + '</span>';
                        }).join('');

                        $tbody.append(`
                        <tr>
                            <td>${employee.first_name} ${employee.last_name}</td>
                            <td>${employee.email}</td>
                            <td>${employee.department ? employee.department.name : ''}</td>
                            <td>${skillsHtml}</td>
                            <td>
                                <a href="/employees/${employee.id}" class="btn btn-sm btn-info">View</a>
                                <a href="/employees/${employee.id}/edit" class="btn btn-sm btn-warning">Edit</a>
                                <form action="/employees/${employee.id}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Delete this employee?')" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    `);
                    });
                }
            });
        });
    });
</script>
@endpush