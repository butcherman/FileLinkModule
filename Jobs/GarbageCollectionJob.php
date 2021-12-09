<?php

namespace Modules\FileLinkModule\Jobs;

use Carbon\Carbon;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\User;
use Modules\FileLinkModule\Entities\FileLink;

class GarbageCollectionJob implements ShouldQueue
{
    use Queueable;
    use Dispatchable;
    use SerializesModels;
    use InteractsWithQueue;

    /**
     * Execute the job.
     */
    public function handle()
    {
        Log::notice('Running Garbage Collection Job for FileLinkModule');

        //  Get the list of users that want their links cleaned up
        $userList = User::whereHas('UserSetting', function($q)
        {
            $q->whereHas('UserSettingType', function($r)
            {
                $r->where('name', 'like', 'Auto Delete Expired Links%');
            })->where('value', 1);
        })->get();

        //  Cycle through the list and get any any links that are more than 30 days old
        foreach($userList as $user)
        {
            $links = FileLink::where('user_id', $user->user_id)->where('expire', '<', Carbon::now()->subDays(30)->format('Y-m-d'))->get();
            $count = $links->count();

            if($count > 0)
            {
                foreach($links as $link)
                {
                    $link->delete();
                }

                Log::info($count.' file links for '.$user->User->full_name.' deleted for being expired more than 30 days');
            }
        }

        Log::notice('Finished Garbage Collection Job for FileLinkModule');
    }
}
