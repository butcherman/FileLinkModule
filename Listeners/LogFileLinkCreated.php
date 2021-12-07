<?php

namespace Modules\FileLinkModule\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
