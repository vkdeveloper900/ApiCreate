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
            <button onclick="onSyncAlbum()" class="btn btn-success">Fetch And Save Albums</button>
            <a href="{{ route('admin.welcome') }}" class="btn btn-info">View Users</a>
        </div>
    </div>

    <div class="container mt-4">
        <form method="GET" class="mb-3 d-flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
                   class="form-control w-25">

            <select name="perPage" class="form-select w-auto" onchange="this.form.submit()">
                @foreach([10, 25, 50, 100] as $size)
                    <option
                        value="{{ $size }}" {{ request('perPage', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary">Apply</button>

            <a href="{{ route('admin.welcome') }}" class="btn btn-secondary">Reset</a>
        </form>


        <form method="POST" action="" id="bulk-delete-form">
            @csrf
            @method('DELETE')

            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>
                        ID
                        <a href="{{ sortUrl('id', 'asc') }}">{!! sortArrow('id', 'asc', $currentSort, $currentOrder) !!}</a>
                        <a href="{{ sortUrl('id', 'desc') }}">{!! sortArrow('id', 'desc', $currentSort, $currentOrder) !!}</a>
                    </th>
                    <th>
                        Title
                        <a href="{{ sortUrl('title', 'asc') }}">{!! sortArrow('title', 'asc', $currentSort, $currentOrder) !!}</a>
                        <a href="{{ sortUrl('title', 'desc') }}">{!! sortArrow('title', 'desc', $currentSort, $currentOrder) !!}</a>
                    </th>
                    <th>Created</th>
                    <th>Photos</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($albums as $album)
                    <tr>
                        <td>{{ $album->id }}</td>
                        <td>{{ $album->title }}</td>
                        <td>{{ $album->created_at->format('d-m-Y') }}</td>
                        <td>
                            <a href="{{ route('admin.album.photos', ['id'=>$album->id]) }}"
                               class="btn btn-sm btn-primary">View</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </form>


        <div class="d-flex justify-content-center mt-3">
            {{ $albums->appends(request()->all())->links('pagination::bootstrap-5') }}
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const jobId = localStorage.getItem('sync_job_id');
            if (jobId) {
                checkStatus(jobId);
            }
        });


        function onSyncAlbum() {
            Swal.fire({
                title: 'Are you sure?',
                text: "Sync Albums",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Sync Now!',
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-outline-danger ml-1'
                },
                buttonsStyling: false
            }).then(function (result) {
                if (result.isConfirmed) {
                    $.get("{{ route('apiFetchAndSave') }}", function (respo) {

                        if (respo.success) {
                            localStorage.setItem('sync_job_id', respo.data);
                            Swal.fire({
                                icon: 'info',
                                title: 'Syncing Started...',
                                text: respo.msg,
                                showConfirmButton: false,
                                timer: 2000
                            });
                            checkStatus(respo.data);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed!',
                                text: respo.msg,
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        }
                    });
                }
            });
        }

        let a = 0;
        function checkStatus(jobId) {
            let interval = setInterval(() => {

                console.log('checkStatus Trigger' ,++a)

                $.get("{{ route('job.chekStatus') }}", {
                    jobId: jobId
                }, function (data) {

                    if (data.status === '{{ \App\Models\JobStatus::STATUS_COMPLETED }}') {
                        clearInterval(interval);
                        localStorage.removeItem('sync_job_id');
                        toastr.success(data.message, 'Syncing Completed ');
                    }

                    if (data.status === '{{ \App\Models\JobStatus::STATUS_FAILED }}') {
                        clearInterval(interval);
                        localStorage.removeItem('sync_job_id');
                        toastr.error(data.message, 'Syncing Failed');
                    }
                });
            }, 5000);
        }

    </script>

@endsection

