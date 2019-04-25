<table class="table table-bordered table-stripped">
    <thead>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Telephone</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @forelse($employees as $employee)
            <tr>
                <td>{{ $employee->first_name }}</td>
                <td>{{ $employee->last_name }}</td>
                <td>{{ $employee->email }}</td>
                <td>{{ $employee->formatted_telephone }}</td>
                <td>
                    @can('update', $employee)
                        <a href="{{ route('companies.employees.edit', [$company, $employee]) }}" class="btn">Edit</a>
                    @endcan
                    @can('delete', $employee)
                        <form action="{{ route('companies.employees.destroy', [$company, $employee] ) }}" method="post" class="d-inline-block">
                            @csrf
                            @method('DELETE')
                            <button class="btn">Delete</button>
                        </form>
                    @endcan
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No Employees For Company</td>
            </tr>
        @endforelse
    </tbody>
</table>
