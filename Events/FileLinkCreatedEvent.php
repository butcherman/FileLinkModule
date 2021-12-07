<?php

namespace Modules\FileLinkModule\Events;

use Illuminate\Queue\SerializesModels;
use Modules\FileLinkModule\Entities\FileLink;

class FileLinkCreatedEvent
{
    use SerializesModels;

    public $fileLink;

    /**
     * Create a new event instance.
     */
    public function __construct(FileLink $fileLink)
    {
        $this->fileLink = $fileLink;
    }
}
