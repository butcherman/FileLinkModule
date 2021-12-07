<?php

namespace Modules\FileLinkModule\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\FileLinkModule\Events\FileLinkCreatedEvent;
use Modules\FileLinkModule\Events\FileLinkDeletedEvent;
use Modules\FileLinkModule\Events\FileLinkFileAddedEvent;
use Modules\FileLinkModule\Events\FileLinkFileDeletedEvent;
use Modules\FileLinkModule\Events\FileLinkUpdatedEvent;
use Modules\FileLinkModule\Events\GuestFileUploadedEvent;
use Modules\FileLinkModule\Listeners\LogFileLinkCreated;
use Modules\FileLinkModule\Listeners\LogFileLinkDeleted;
use Modules\FileLinkModule\Listeners\LogFileLinkFileAdded;
use Modules\FileLinkModule\Listeners\LogFileLinkFileDeleted;
use Modules\FileLinkModule\Listeners\LogFileLinkUpdated;
use Modules\FileLinkModule\Listeners\LogGuestFileUploaded;
use Modules\FileLinkModule\Listeners\NotifyGuestFileUploaded;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        FileLinkCreatedEvent::class => [
            LogFileLinkCreated::class,
        ],
        FileLinkUpdatedEvent::class => [
            LogFileLinkUpdated::class,
        ],
        FileLinkDeletedEvent::class => [
            LogFileLinkDeleted::class,
        ],
        FileLinkFileAddedEvent::class => [
            LogFileLinkFileAdded::class,
        ],
        FileLinkFileDeletedEvent::class => [
            LogFileLinkFileDeleted::class,
        ],
        GuestFileUploadedEvent::class => [
            LogGuestFileUploaded::class,
            NotifyGuestFileUploaded::class,
        ],
    ];
}
