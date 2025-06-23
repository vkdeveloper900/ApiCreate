<?php

namespace App\Http\Controllers\ThirdPartyApi;

use App\Http\Controllers\Controller;
use App\Jobs\FetchAlbumsPhotos;
use App\Models\Album;
use App\Models\Photo;
use Illuminate\Support\Facades\Http;

class ApiDataController extends Controller
{
    public function fetchAndSave() {
//        dd('');
        FetchAlbumsPhotos::dispatch();

        return response()->json([
            'success' => true,
            'msg' => 'Albums sync started in background.'
        ]);
    }




}
