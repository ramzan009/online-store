<x-app-layout>
    <div class="block-banners">
        @include('cabinet.banners._nav')

        <p>Choose category:</p>

        @include('cabinet.banners.create._categories', ['categories' => $categories])
    </div>
</x-app-layout>
