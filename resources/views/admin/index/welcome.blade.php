@extends('layout.layout')

@php
    $currentSort = request('sortBy', 'id');
    $currentOrder = request('order', 'asc');

    function sortUrl($column, $order) {
        return request()->fullUrlWithQuery(['sortBy' => $column, 'order' => $order]);
    }

    function sortArrow($column, $dir, $currentSort, $currentOrder) {
        $symbol = $dir === 'asc' ? '▲' : '▼';
        $isActive = $column === $currentSort && $currentOrder === $dir;
        return $isActive ? "<strong>$symbol</strong>" : "<span style='color:#ccc;'>$symbol</span>";
    }
@endphp

@section('content')

    <div class="d-flex justify-content-center align-items-center vh-50 bg-light">
        <div class="text-center">
            <h1 class="display-4">Welcome, {{ Auth::user()->name ?? 'User' }}!</h1>
            <p class="lead">You're now logged in.</p>
            <a href="{{ route('admin.logOut') }}" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <div class="container mt-4">
        <form method="GET" class="mb-3 d-flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." class="form-control w-25">

            <select name="perPage" class="form-select w-auto" onchange="this.form.submit()">
                @foreach([10, 25, 50, 100] as $size)
                    <option value="{{ $size }}" {{ request('perPage', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary">Apply</button>

            <a href="{{ route('admin.welcome') }}" class="btn btn-secondary">Reset</a>
        </form>


        <form method="POST" action="" id="bulk-delete-form">
            @csrf
            @method('DELETE')

            <div class="d-flex justify-content-between mb-2">
                <div>
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete selected?')">
                        Delete Selected
                    </button>
                </div>
            </div>

            <table class="table table-bordered">
                <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>
                        ID
                        <a href="{{ sortUrl('id', 'asc') }}">{!! sortArrow('id', 'asc', $currentSort, $currentOrder) !!}</a>
                        <a href="{{ sortUrl('id', 'desc') }}">{!! sortArrow('id', 'desc', $currentSort, $currentOrder) !!}</a>
                    </th>
                    <th>
                        Name
                        <a href="{{ sortUrl('name', 'asc') }}">{!! sortArrow('name', 'asc', $currentSort, $currentOrder) !!}</a>
                        <a href="{{ sortUrl('name', 'desc') }}">{!! sortArrow('name', 'desc', $currentSort, $currentOrder) !!}</a>
                    </th>
                    <th>Email</th>
                    <th>Registered</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>
                            <input type="checkbox" name="selected_users[]" value="{{ $user->id }}">
                        </td>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at->format('d-m-Y') }}</td>
                        <td>
                            <form method="POST" action="">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $user->status ? 'btn-success' : 'btn-secondary' }}">
                                    {{ $user->status ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>

                        <td>
                            <a href="" class="btn btn-sm btn-warning">✏️</a>
                            <form method="POST" action="" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?')">🗑️</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </form>


        <div class="d-flex justify-content-center mt-3">
            {{ $users->appends(request()->all())->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <script>
        document.getElementById('select-all').addEventListener('change', function () {
            let checkboxes = document.querySelectorAll('input[name="selected_users[]"]');
            for (let checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        });
    </script>


@endsection
