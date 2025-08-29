<x-app-layout>

    @include('cabinet.banners._nav')
    <div class="block-banners">
        @if ($region)
            <p>
                <a href="{{ route('cabinet.banners.create.banner', [$category, $region]) }}" class="btn btn-success">Add
                    Advert for {{ $region->name }}</a>
            </p>
        @else
            <p>
                <a href="{{ route('cabinet.banners.create.banner', [$category]) }}" class="btn btn-success">Add Advert
                    for all regions</a>
            </p>
        @endif

        <p>Or choose nested region:</p>

        <ul>
            @foreach ($regions as $current)
                <li>
                    <a href="{{ route('cabinet.banners.create.region', [$category, $current]) }}">{{ $current->name }}</a>
                </li>
            @endforeach
        </ul>
    </div>
</x-app-layout>
