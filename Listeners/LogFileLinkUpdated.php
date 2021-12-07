<?php

namespace Modules\FileLinkModule\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogFileLinkUpdated
{
    /**
     * Handle the event.
     */
    public function handle($event)
    {
        Log::info('File Link has been updated by '.Auth::user()->username, $event->fileLink->toArray());
    }
}
