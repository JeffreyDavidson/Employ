@extends('layouts.app')

@section('content')
<h1>{{ $company->name }}</h1>

<h2>Managers</h2>
<ul>
@forelse($managers as $manager)
    <li>{{ $manager->name }}</li>
@empty
    <li>No Managers Assigned</li>
@endforelse
</ul>

@endsection
