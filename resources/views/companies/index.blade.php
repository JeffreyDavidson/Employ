@extends('layouts.app')

@section('content')
<table class="table table-bordered table-stripped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($companies as $company)
            <tr>
                <td>{{ $company->name }}</td>
                <td>
                    {{-- @can('update', $company)
                        <a href="{{ route('companies.edit') }}">Edit</a>
                    @endcan
                    @can('delete', $company)
                        <form method="post" action="{{ route('companies.destroy') }}"
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    @endcan --}}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $companies->links() }}
@endsection
