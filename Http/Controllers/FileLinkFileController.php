<?php

namespace Modules\FileLinkModule\Http\Controllers;

use App\Http\Controllers\Controller;

use Modules\FileLinkModule\Traits\FileLinkTrait;

use Modules\FileLinkModule\Entities\FileLink;
use Modules\FileLinkModule\Entities\FileLinkFile;

use Modules\FileLinkModule\Events\FileLinkFileDeletedEvent;

use Modules\FileLinkModule\Http\Requests\FileLinkFileRequest;


class FileLinkFileController extends Controller
{
    use FileLinkTrait;

    /**
     * Update the specified resource in storage.
     */
    public function update(FileLinkFileRequest $request, $id)
    {
        $link    = FileLink::findOrFail($id);
        $this->processNewFiles($link, false, $request->user());

        return back()->with([
            'message' => 'File(s) Added',
            'type'    => 'success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $file = FileLinkFile::findOrFail($id);
        $link = FileLink::find($file->link_id);
        $this->authorize('viewAny', FileLink::class);

        $file->delete();
        event(new FileLinkFileDeletedEvent($link, $file));
        return back()->with([
            'message' => 'File Deleted',
            'type'    => 'success',
        ]);
    }
}
