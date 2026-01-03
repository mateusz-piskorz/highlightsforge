<?php
namespace App\Jobs;

use App\Models\Post;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class ProcessPost implements ShouldQueue
{
    use Queueable;

    public function __construct(public Post $post)
    {}

    public function handle(): void
    {
        $this->post->update(['status' => 'pending']);

        if ($this->post->file_type === 'video/mp4') {
            $optimizedPath = 'posts/opt_' . basename($this->post->file_path);

            FFMpeg::fromDisk('public')
                ->open($this->post->file_path)
                ->export()
                ->toDisk('public')
                ->inFormat($this->getCopyFormat())
                ->save($optimizedPath);

            Storage::disk('public')->delete($this->post->file_path);
            $this->post->file_path = $optimizedPath;
        }

        $this->post->update(['status' => 'draft']);
    }

    private function getCopyFormat(): \FFMpeg\Format\Video\DefaultVideo
    {
        return (new class extends \FFMpeg\Format\Video\DefaultVideo
        {
            public function getAvailableVideoCodecs()
            {return ['copy'];}
            public function getAvailableAudioCodecs()
            {return ['copy'];}
            public function supportBFrames()
            {return false;}
            public function getExtraParameters()
            {return [];}
            public function getModulus()
            {return 2;}
        })->setVideoCodec('copy')
            ->setAudioCodec('copy')
            ->setAdditionalParameters(['-map', '0', '-movflags', '+faststart']);
    }
}
