<x-app-layout>

    <div class="create-view-users-block">

        @include('cabinet.adverts._nav')

        <p>Choose category:</p>

        @include('cabinet.adverts.create._categories', ['categories' => $categories])

    </div>

</x-app-layout>
