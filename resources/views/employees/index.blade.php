@extends('layouts.app')

@section('content')
<div class="mb-3">
    <a href="{{ route('companies.employees.create', $company) }}" class="btn btn-primary">Add New Employee</a>
    <a href="{{ route('companies.index') }}" class="btn btn-secondary">Back To Companies</a>
</div>
@include('employees.partials.table')
{{ $employees->links() }}
@endsection
