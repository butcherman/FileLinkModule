<?php

namespace Modules\FileLinkModule\Events;

use Illuminate\Queue\SerializesModels;
use Modules\FileLinkModule\Entities\FileLink;
use Modules\FileLinkModule\Http\Requests\GuestFileUploadRequest;

class GuestFileUploadedEvent
{
    use SerializesModels;

    public $details;
    public $link;

    /**
     * Create a new event instance.
     */
    public function __construct(GuestFileUploadRequest $details, FileLink $link)
    {
        $this->details = $details;
        $this->link    = $link;
    }
}
