@extends('layout.layout')

@section('content')

    <div class="d-flex justify-content-center align-items-center vh-50 bg-light">
        <div class="text-center">
            <h1 class="display-4">Welcome, {{ Auth::user()->name ?? 'User' }}!</h1>
            <p class="lead">You're now logged in.</p>
            <a href="{{ route('admin.albums') }}" class="btn btn-info">View Albums</a>

        </div>
    </div>

    <div class="container mt-4">
        <div class="row">
            @forelse($photos as $photo)
                <div class="col-md-3 mb-4">
                    <div class="card shadow-sm h-100">
                        <img src="{{ $photo->thumbnailUrl }}" class="card-img-top" alt="{{ $photo->title }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ \Illuminate\Support\Str::limit($photo->title, 40) }}</h5>
                            <a href="{{ $photo->url }}" target="_blank" class="btn btn-sm btn-outline-primary">View Full</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-center">No photos found.</p>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $photos->appends(request()->all())->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
