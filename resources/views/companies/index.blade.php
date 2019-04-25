@extends('layouts.app')

@section('content')
@can('create', \App\Company::class)
    <div class="mb-3">
        <a href="{{ route('companies.create') }}" class="btn btn-primary">Add New Company</a>
    </div>
@endcan
@include('companies.partials.table')
{{ $companies->links() }}
@endsection
