<x-app-layout>

    <div class="about-users-block">

        <div class="d-flex flex-row mb-3">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary mr-1">Изменить</a>

            {{--        @if ($user->isWait())--}}
            {{--            <form method="POST" action="{{ route('admin.users.verify', $user) }}" class="mr-1">--}}
            {{--                @csrf--}}
            {{--                <button class="btn btn-success">Verify</button>--}}
            {{--            </form>--}}
            {{--        @endif--}}

            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="mr-1">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Удалить</button>
            </form>
        </div>
        <table class="table table-bordered table-striped">
            <tbody>
            <tr>
                <th>ID</th>
                <td>{{ $user->id }}</td>
            </tr>
            <tr>
                <th>Name</th>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    {{--                    @if($user->status === \App\Models\User::STATUS_WAIT)--}}
                    {{--                        <span style="color: #1f2937;" class="badge badge-secondary">Waiting</span>--}}
                    {{--                    @endif--}}
                    {{--                    @if($user->status === \App\Models\User::STATUS_ACTIVE)--}}
                    {{--                        <span style="color: #1f2937;" class="badge badge-primary">Active</span>--}}
                    {{--                    @endif--}}

                    @if ($user->isWait())
                        <span style="color: #1f2937;" class="badge badge-secondary">Waiting</span>
                    @endif
                    @if ($user->isActive())
                        <span style="color: #1f2937;" class="badge badge-primary">Active</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Role</th>
                <td>
                    @if ($user->isAdmin())
                        <span style="color: #1f2937;" class="badge badge-danger">Admin</span>
                    @endif
                    @if($user->isUser())
                        <span style="color: #1f2937;" class="badge badge-secondary">User</span>
                    @endif
                    @if($user->isModerator())
                        <span style="color: #1f2937;" class="badge badge-secondary">Moderator</span>
                    @endif
            </tr>
            <tbody>
            </tbody>
        </table>
    </div>

</x-app-layout>
