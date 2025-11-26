@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Employee Details</h1>

    <div class="card">
        <div class="card-body">
            <h3>{{ $employee->full_name }}</h3>
            <p><strong>Email:</strong> {{ $employee->email }}</p>
            <p><strong>Department:</strong> {{ $employee->department->name }}</p>
            <p><strong>Skills:</strong>
                @forelse($employee->skills as $skill)
                <span class="badge bg-secondary">{{ $skill->name }}</span>
                @empty
                <span class="text-muted">No skills assigned</span>
                @endforelse
            </p>
        </div>
    </div>

    <a href="{{ route('employees.index') }}" class="btn btn-link mt-3">Back to list</a>
</div>
@endsection