<?php

namespace Modules\FileLinkModule\Listeners;

use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Modules\FileLinkModule\Notifications\GuestFileNotification;

class NotifyGuestFileUploaded
{
    /**
     * Handle the event.
     */
    public function handle($event)
    {
        $owner = User::find($event->link->user_id);

        Notification::send($owner, new GuestFileNotification($event->link, $event->details->name));
    }
}
