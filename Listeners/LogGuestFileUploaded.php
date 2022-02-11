<?php

namespace Modules\FileLinkModule\Listeners;

use Illuminate\Support\Facades\Log;

class LogGuestFileUploaded
{
    /**
     * Handle the event.
     */
    public function handle($event)
    {
        Log::info('A file has been uploaded to File Link ID '.$event->link->link_id, array_merge($event->details->toArray(), $event->link->toArray()));
    }
}
