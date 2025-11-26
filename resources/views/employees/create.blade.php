@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Employee</h1>

    <form action="{{ route('employees.store') }}" method="POST">
        @include('employees._form', ['submitLabel' => 'Create'])
    </form>
</div>
@endsection