@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Skills</h1>

    @if(session('success'))
    <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif

    <a href="{{ route('skills.create') }}" class="btn btn-primary mb-3">Create Skill</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
            </tr>
        </thead>
        <tbody>
            @foreach($skills as $skill)
            <tr>
                <td>{{ $skill->name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection