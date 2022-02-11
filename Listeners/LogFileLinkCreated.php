<?php

namespace Modules\FileLinkModule\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LogFileLinkCreated
{
    /**
     * Handle the event.
     */
    public function handle($event)
    {
        Log::info('New File Link has been created by '.Auth::user()->username, $event->fileLink->toArray());
    }
}
