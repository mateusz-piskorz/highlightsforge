<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ClipRequest;

class ClipController
{

    public function index()
    {
        return response()->json([
            'something' => true
        ]);

    }

    public function store(ClipRequest $request)
    {

        $file = $request->file('clip');
        $path = $file->store('clips', 'public');

        return response()->json([
            'path'    => $path,
            'success' => true,
            'message' => 'clip stored successfully'
        ], 201);
    }
}
