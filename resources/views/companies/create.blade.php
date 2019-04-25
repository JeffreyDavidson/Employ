@extends('layouts.app')

@section('content')
<form method="post" action="{{ route('companies.store') }}" enctype="multipart/form-data">
    @csrf
    @include('companies.partials.form')
</form>
@endsection
