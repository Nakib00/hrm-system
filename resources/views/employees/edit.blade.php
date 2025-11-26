@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Employee</h1>

    <form action="{{ route('employees.update', $employee) }}" method="POST">
        @method('PUT')
        @include('employees._form', ['submitLabel' => 'Update'])
    </form>
</div>
@endsection