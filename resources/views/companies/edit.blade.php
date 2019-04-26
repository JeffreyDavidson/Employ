@extends('layouts.app')

@section('content')
<form method="post" action="{{ route('companies.update', $company) }}" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    @include('companies.partials.form')
</form>
@endsection
