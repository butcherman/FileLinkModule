<?php

namespace Modules\FileLinkModule\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
