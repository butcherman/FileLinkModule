<?php

namespace Modules\FileLinkModule\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LogFileLinkDeleted
{
    /**
     * Handle the event.
     */
    public function handle($event)
    {
        Log::info('File Link has been deleted by '.Auth::user()->username, $event->fileLink->toArray());
    }
}
