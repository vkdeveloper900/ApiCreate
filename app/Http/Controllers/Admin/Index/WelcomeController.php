<?php

namespace App\Http\Controllers\Admin\Index;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\User;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function welcome(Request $request)
    {
        $query = User::query();

        //  Search
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->input('sortBy', 'id'); // default column
        $order = $request->input('order', 'asc');  // asc or desc
        $query->orderBy($sortBy, $order);

        // Pagination
        $perPage = $request->input('perPage', 10);
        $users = $query->paginate($perPage)->appends($request->all());

        return view('admin.index.welcome', compact('users', 'perPage'));
    }


    public function albums(Request $request)
    {

        $query = Album::query();

        // Search by title
        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        // Sort
        $sortBy = $request->input('sortBy', 'id');
        $order = $request->input('order', 'asc');
        $query->orderBy($sortBy, $order);

        // Pagination
        $perPage = $request->input('perPage', 10);
        $albums = $query->paginate($perPage)->appends($request->all());

        return view('admin.album.album', compact('albums', 'perPage'));
    }

    public function photos(Request $request)
    {
        $album = Album::findOrFail($request->id);

        $photos = $album->photos()->latest()->paginate(12)->appends($request->all());

        return view('admin.album.photo', compact('photos', 'album'));
    }

}
