@extends('layouts.app')

@section('content')
<form method="post" action="{{ route('companies.employees.store', $company) }}">
    @csrf
    @include('employees.partials.form')
</form>
@endsection
