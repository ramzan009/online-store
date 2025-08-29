<x-app-layout>

    @include('admin.banners._nav')

    <div class="show-users-block">

        <form method="POST" action="">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name" class="col-form-label">Name</label>
                <input id="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name', $banner->name) }}" required>
                @if ($errors->has('name'))
                    <span class="invalid-feedback"><strong>{{ $errors->first('name') }}</strong></span>
                @endif
            </div>

            <div class="form-group">
                <label for="slug" class="col-form-label">Limit</label>
                <input id="slug" type="text" class="form-control{{ $errors->has('limit') ? ' is-invalid' : '' }}" name="limit" value="{{ old('name', $banner->limit) }}" required>
                @if ($errors->has('limit'))
                    <span class="invalid-feedback"><strong>{{ $errors->first('limit') }}</strong></span>
                @endif
            </div>

            <div class="form-group">
                <label for="slug" class="col-form-label">Url</label>
                <input id="slug" type="text" class="form-control{{ $errors->has('url') ? ' is-invalid' : '' }}" name="url" value="{{ old('name', $banner->url) }}" required>
                @if ($errors->has('url'))
                    <span class="invalid-feedback"><strong>{{ $errors->first('url') }}</strong></span>
                @endif
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>

    </div>

</x-app-layout>
