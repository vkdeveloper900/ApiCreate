<?php

namespace App\Jobs;

use App\Models\Album;
use App\Models\JobStatus;
use App\Models\Photo;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Throwable;

class FetchAlbumsPhotos implements ShouldQueue
{
    use Queueable;

    public $jobId;

    /**
     * Create a new job instance.
     */
    public function __construct(string $jobId)
    {
        $this->jobId = $jobId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $albumsResponse = Http::get('https://jsonplaceholder.typicode.com/albums');

        if ($albumsResponse->failed()) {
            JobStatus::where('job_id', $this->jobId)->update([
                'status' => JobStatus::STATUS_FAILED,
                'message' => 'Album fetch failed',
            ]);
            return;
        }

        $albums = $albumsResponse->json();

        foreach ($albums as $album) {
            $new_album = Album::updateOrCreate(
                ['id' => $album['id']], // Match condition (assuming same external ID)
                ['title' => $album['title']]
            );


            $photosResponse = Http::get("https://jsonplaceholder.typicode.com/albums/{$album['id']}/photos");

            if ($photosResponse->failed()) {
                continue;
            }

            $photos = $photosResponse->json();

            foreach ($photos as $photo) {
                Photo::updateOrCreate(
                    ['id' => $photo['id']], // Match by external photo ID
                    [
                        'album_id' => $new_album->id,
                        'title' => $photo['title'],
                        'url' => $photo['url'],
                        'thumbnail_url' => $photo['thumbnailUrl'],
                    ]
                );
            }


        }
        JobStatus::where('job_id', $this->jobId)->update([
            'status' => JobStatus::STATUS_COMPLETED,
            'message' => 'Albums & photos synced successfully',
        ]);
    }


    public function failed(Throwable $exception): void
    {
        JobStatus::where('job_id', $this->jobId)->update([
            'status' => JobStatus::STATUS_FAILED,
            'message' => $exception->getMessage(),
        ]);
    }
}
