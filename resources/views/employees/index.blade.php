@extends('layouts.app')

@section('content')
<a href="{{ route('companies.employees.create', $company) }}" class="btn btn-primary mb-3">Add New Employee</a>
@include('employees.partials.table')
{{ $employees->links() }}
@endsection
