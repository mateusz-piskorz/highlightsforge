<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ClipRequest;
use App\Mail\SendSomething;
use Illuminate\Support\Facades\Mail;

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

    public function sendSomething()
    {
        Mail::to("owcaofficial@yahoo.com")
            ->queue(new SendSomething());

        return response()->json([
            'success' => true,
            'message' => 'sent successfully'
        ], 200);
    }

}
