<?php

namespace Modules\FileLinkModule\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
