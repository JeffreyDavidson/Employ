<table class="table table-bordered table-stripped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($companies as $company)
            <tr>
                <td>{{ $company->name }}</td>
                <td>{{ $company->email }}</td>
                <td>
                    @can('update', $company)
                        <a href="{{ route('companies.edit', $company) }}" class="btn">Edit</a>
                    @endcan
                    @can('delete', $company)
                        <form class="d-inline-block" method="post" action="{{ route('companies.destroy', $company) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn" type="submit">Delete</button>
                        </form>
                    @endcan
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3">No Companies</td>
            </tr>
        @endforelse
    </tbody>
</table>
