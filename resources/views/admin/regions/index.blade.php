<x-app-layout>
    <div class="create-view-users-block">
        @include('admin.regions._nav')
        @include('admin.regions._list', ['regions' => $regions])
        <p><a href="{{ route('admin.regions.create') }}" class="btn btn-success">Добавить Регион</a></p>
    </div>
</x-app-layout>
