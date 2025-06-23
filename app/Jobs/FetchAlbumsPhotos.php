<?php

namespace App\Jobs;

use App\Models\Album;
use App\Models\Photo;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class FetchAlbumsPhotos implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $albumsResponse = Http::get('https://jsonplaceholder.typicode.com/albums');

        if ($albumsResponse->failed()) {
            return;
        }

        $albums = $albumsResponse->json();

        foreach ($albums as $album) {
            $new_album = Album::create([
                'title' => $album['title']
            ]);

            $photosResponse = Http::get("https://jsonplaceholder.typicode.com/albums/{$album['id']}/photos");

            if ($photosResponse->failed()) {
                continue;
            }

            $photos = $photosResponse->json();

            foreach ($photos as $photo) {
                Photo::create([
                    'album_id' => $new_album->id,
                    'title' => $photo['title'],
                    'url' => $photo['url'],
                    'thumbnail_url' => $photo['thumbnailUrl'],
                ]);
            }
        }


    }
}
