<?php

namespace Modules\FileLinkModule\Traits;

use App\Models\User;
use App\Traits\FileTrait;
use Modules\FileLinkModule\Entities\FileLink;
use Modules\FileLinkModule\Entities\FileLinkFile;
use Modules\FileLinkModule\Events\FileLinkFileAddedEvent;
use Modules\FileLinkModule\Http\Requests\GuestFileUploadRequest;

trait FileLinkTrait
{
    use FileTrait;

    /**
     * Add files stored in the session to a file link
     */
    protected function processNewFiles(FileLink $link, $move = false, User $user)
    {
        $fileData = session()->pull('new-file-upload');
        if($fileData)
        {
            foreach($fileData as $file)
            {
                if($move)
                {
                    $this->moveStoredFile($file->file_id, $link->link_id);
                }

                $fileData = FileLinkFile::create([
                    'link_id' => $link->link_id,
                    'file_id' => $file->file_id,
                    'user_id' => $user->user_id,
                    'upload'  => false,
                ]);

                event(new FileLinkFileAddedEvent($link, $fileData));
            }
        }
    }

    /**
     * Process a file uploaded by a guest
     */
    protected function processGuestFile(FileLInk $link, GuestFileUploadRequest $details)
    {
        $fileData = session()->pull('new-file-upload');
        if($fileData)
        {
            foreach($fileData as $file)
            {
                $this->moveStoredFile($file->file_id, $link->link_id);

                $fileData = FileLinkFile::create([
                    'link_id' => $link->link_id,
                    'file_id' => $file->file_id,
                    'added_by' => $details->name,
                    'upload' => true,
                    'note' => $details->notes,
                ]);
            }
        }
    }
}
