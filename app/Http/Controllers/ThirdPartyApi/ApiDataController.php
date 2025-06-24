<?php

namespace App\Http\Controllers\ThirdPartyApi;

use App\Http\Controllers\Controller;
use App\Jobs\FetchAlbumsPhotos;
use App\Models\JobStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ApiDataController extends Controller
{
    public function fetchAndSave()
    {
        $user = auth()->user();

        $lastJob = JobStatus::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subMinutes(5))
            ->latest('id')
            ->first();

        if ($lastJob) {
            return errorResponse('You can sync only once every 5 minutes. Try again later.');
        }

        $jobId = (string)Str::uuid();

        JobStatus::create([
            'user_id' => $user->id,
            'job_id' => $jobId,
            'status' => 'pending',
        ]);

        FetchAlbumsPhotos::dispatch($jobId);

//        FetchAlbumsPhotos::dispatch($jobId)->delay(now()->addMinutes(15));


        return successResponse('Albums sync started in background.', $jobId);
    }


    public function JobCheckStatus(Request $request)
    {
        $jobId = $request->input('jobId');

        $job = JobStatus::where('job_id', $jobId)->first();


        Log::channel('job_status')->info(json_encode([
            'Task' => 'JobCheckStatus',
            'data' => $job ? $job->toArray() : null
        ]));


        if (!$job) {
            return response()->json([
                'status' => 'not_found',
                'message' => 'Job not found'
            ], 404);
        }


        return response()->json([
            'status' => $job->status,
            'message' => $job->message,
        ]);
    }


}
