@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Departments</h1>

    @if(session('success'))
    <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif

    <a href="{{ route('departments.create') }}" class="btn btn-primary mb-3">Create Department</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
            </tr>
        </thead>
        <tbody>
            @foreach($departments as $department)
            <tr>
                <td>{{ $department->name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection