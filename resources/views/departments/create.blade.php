@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Department</h1>

    <form action="{{ route('departments.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <button class="btn btn-success">Create</button>
    </form>
</div>
@endsection