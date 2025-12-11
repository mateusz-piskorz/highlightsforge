<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\UploadedFile;

class ProcessClipOld implements ShouldQueue
{
    use Queueable;

    public function __construct(private UploadedFile $clip) {}

    public function handle(): void
    {
        $path = $this->clip->store('clips', 'public');
    }
}
