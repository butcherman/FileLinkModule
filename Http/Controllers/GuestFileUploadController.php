<?php

namespace Modules\FileLinkModule\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Modules\FileLinkModule\Traits\FileLinkTrait;
use Modules\FileLinkModule\Entities\FileLink;
use Modules\FileLinkModule\Entities\FileLinkFile;
use Modules\FileLinkModule\Events\GuestFileUploadedEvent;
use Modules\FileLinkModule\Http\Requests\GuestFileUploadRequest;

class GuestFileUploadController extends Controller
{
    use FileLinkTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('FileLinkModule::Welcome');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('FileLinkModule::Welcome');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $link = FileLink::where('link_hash', $id)->firstOrFail()->makeHidden(['expire', 'expire_formatted', 'file_count', 'is_expired', 'link_id', 'link_name']);
        $files = FileLinkFile::where('link_id', $link->link_id)->where('upload', false)->with('FileUploads')->get()->makeHidden(['added_by', 'link_file_id', 'note', 'updated_at', 'upload', 'user_id']);

        return Inertia::render('FileLinkModule::Guest', [
            'details' => $link,
            'files'   => $files,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return Inertia::render('FileLinkModule::Welcome');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GuestFileUploadRequest $request, $id)
    {
        $link = FileLink::where('link_hash', $id)->firstOrFail();

        $this->processGuestFile($link, $request);

        event(new GuestFileUploadedEvent($request, $link));
        return back()->with([
            'message' => 'File(s) uploaded',
            'type'    => 'success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
