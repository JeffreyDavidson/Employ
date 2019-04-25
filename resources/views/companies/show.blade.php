@extends('layouts.app')

@section('content')
<h1>{{ $company->name }}</h1>

<h2>Managers</h2>
<ul>
@foreach($managers as $manager)
    <li>{{ $manager->name }}</li>
@endforeach
</ul>
@endsection
