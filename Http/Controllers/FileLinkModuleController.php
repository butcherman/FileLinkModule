<?php

namespace Modules\FileLinkModule\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Modules\FileLinkModule\Entities\FileLink;
use Modules\FileLinkModule\Entities\FileLinkFile;
use Modules\FileLinkModule\Traits\FileLinkTrait;
use Modules\FileLinkModule\Events\FileLinkCreatedEvent;
use Modules\FileLinkModule\Events\FileLinkDeletedEvent;
use Modules\FileLinkModule\Events\FileLinkUpdatedEvent;
use Modules\FileLinkModule\Http\Requests\FileLinkRequest;

class FileLinkModuleController extends Controller
{
    use FileLinkTrait;

    /**
     * Show the file links for the logged in user
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', FileLink::class);

        return Inertia::render('FileLinkModule::Index', [
            'link_list' => FileLink::where('user_id', $request->user()->user_id)->orderBy('expire', 'DESC')->get(),
        ]);
    }

    /**
     * Show the form for creating a new File Link.
     */
    public function create()
    {
        $this->authorize('create', FileLink::class);

        return Inertia::render('FileLinkModule::Create', [
            'expire' => Carbon::now()->addDays(30),
        ]);
    }

    /**
     * Store a newly created File LInk
     */
    public function store(FileLinkRequest $request)
    {
        $newLink = FileLink::create([
            'user_id'      => $request->user()->user_id,
            'link_hash'    => Str::uuid(),
            'link_name'    => $request->link_name,
            'expire'       => date('Y-m-d', strtotime($request->expire)),
            'instructions' => $request->instructions,
            'allow_upload' => filter_var($request->allow_upload, FILTER_VALIDATE_BOOL),
        ]);

        $this->processNewFiles($newLink, true, $request->user());

        event(new FileLinkCreatedEvent($newLink));
        return redirect(route('FileLinkModule.show', $newLink->link_id));
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $link = FileLink::with('FileLinkFile.FileUploads')->with('FileLinkFile.User')->findOrFail($id);
        $this->authorize('view', $link);

        return Inertia::render('FileLinkModule::Show', [
            'details' => $link,
        ]);
    }

    /**
     * Show the form for editing the file link details
     */
    public function edit($id)
    {
        $link = FileLink::findOrFail($id);
        $this->authorize('edit', $link);

        return Inertia::render('FileLinkModule::Edit', [
            'details' => $link,
        ]);
    }

    /**
     * Update the specified File Link
     */
    public function update(FileLinkRequest $request, $id)
    {
        $link = FileLink::findOrFail($id);

        $link->update([
            'link_name'    => $request->link_name,
            'expire'       => date('Y-m-d', strtotime($request->expire)),
            'instructions' => $request->instructions,
            'allow_upload' => filter_var($request->allow_upload, FILTER_VALIDATE_BOOL),
        ]);

        event(new FileLinkUpdatedEvent($link));
        return redirect(route('FileLinkModule.show', $id))->with([
            'message' => 'Link Updated',
            'type'    => 'success'
        ]);
    }

    /**
     * Remove the specified file link and all attached files
     */
    public function destroy($id)
    {
        $link = FileLink::findOrFail($id);
        $this->authorize('delete', $link);

        $fileList = FileLinkFile::where('link_id', $link->link_id);
        $link->delete();

        foreach($fileList as $file)
        {
            $this->deleteFile($file->file_id);
        }

        event(new FileLinkDeletedEvent($link));

        //  If this came from a show link, then go to the Route Index page
        $routeName = app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName();
        if($routeName === 'FileLinkModule.show')
        {
            return redirect(route('FileLinkModule.index'))->with([
                'message' => 'File Link Deleted',
                'type'    => 'success',
            ]);
        }

        return back()->with([
            'message' => 'File Link Deleted',
            'type'    => 'success',
        ]);
    }
}
