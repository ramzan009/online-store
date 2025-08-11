<x-app-layout>
    <div class="about-users-block">

        <div class="d-flex flex-row mb-3">
            <a href="{{ route('admin.regions.edit', $region) }}" class="btn btn-primary mr-1">Изменить</a>
            <form method="POST" action="{{ route('admin.regions.update', $region) }}" class="mr-1">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Delete</button>
            </form>
        </div>

        <table class="table table-bordered table-striped">
            <tbody>
            <tr>
                <th>ID</th><td>{{ $region->id }}</td>
            </tr>
            <tr>
                <th>Name</th><td>{{ $region->name }}</td>
            </tr>
            <tr>
                <th>Slug</th><td>{{ $region->slug }}</td>
            </tr>
            </tbody>
        </table>
        @include('admin.regions._list', ['regions' => $regions])
    </div>
</x-app-layout>
