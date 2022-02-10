<?php

namespace Modules\FileLinkModule\Events;

use Illuminate\Queue\SerializesModels;
use Modules\FileLinkModule\Entities\FileLink;
use Modules\FileLinkModule\Entities\FileLinkFile;

class FileLinkFileDeletedEvent
{
    use SerializesModels;

    public $fileLink;
    public $fileLinkFile;

    /**
     * Create a new event instance.
     */
    public function __construct(FileLink $fileLink, FileLinkFile $fileLinkFile)
    {
        $this->fileLink     = $fileLink;
        $this->fileLinkFile = $fileLinkFile;
    }
}
