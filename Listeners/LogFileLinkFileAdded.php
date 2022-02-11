<?php

namespace Modules\FileLinkModule\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LogFileLinkFileAdded
{
    /**
     * Handle the event.
     */
    public function handle($event)
    {
        Log::info('A file has been added to File Link ID '.$event->fileLink->link_id.' by '.Auth::user()->username, array_merge($event->fileLink->toArray(), $event->fileLinkFile->toArray()));
    }
}
