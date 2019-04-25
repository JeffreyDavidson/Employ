@extends('layouts.app')

@section('content')
<form method="post" action="{{ route('companies.employees.update', [$company, $employee]) }}">
    @csrf
    @method('PATCH')
    @include('employees.partials.form')
</form>
@endsection
